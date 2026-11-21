<?php

namespace App\Jobs;

use App\Models\NewsletterCampaign;
use App\Models\NewsletterSubscriber;
use App\Services\Notifier;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendNewsletterCampaign implements ShouldQueue
{
    use Queueable;

    public function __construct(public int $campaignId)
    {
    }

    public function handle(): void
    {
        $campaign = NewsletterCampaign::query()->find($this->campaignId);
        if (! $campaign || $campaign->status !== 'queued') {
            return;
        }

        if ($campaign->scheduled_at && $campaign->scheduled_at->isFuture()) {
            $this->release($campaign->scheduled_at->diffInSeconds(now()));

            return;
        }

        $sent = 0;
        $subscribers = NewsletterSubscriber::query()
            ->where('status', 1)
            ->where('consent', 1)
            ->get();

        foreach ($subscribers as $subscriber) {
            $body = $campaign->content.'<p><a href="'.e(route('newsletter.unsubscribe', $subscriber->token)).'">Unsubscribe</a></p>';
            if (app(Notifier::class)->html($subscriber->email, $campaign->subject, $body)) {
                $sent++;
            }
        }

        $campaign->update([
            'status' => 'sent',
            'sent_at' => now(),
            'recipient_count' => $sent,
        ]);
    }
}
