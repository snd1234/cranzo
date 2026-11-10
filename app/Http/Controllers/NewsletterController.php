<?php

namespace App\Http\Controllers;

use App\Jobs\SendNewsletterCampaign;
use App\Models\NewsletterCampaign;
use App\Models\NewsletterSubscriber;
use App\Support\Seo;
use App\Support\HtmlCleaner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Throwable;

class NewsletterController extends BaseController
{
    public function subscribe(Request $request)
    {
        if ($request->filled('company_website')) {
            return response()->json(['ok' => true, 'message' => 'Thanks. You are subscribed.']);
        }

        $data = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'consent' => ['nullable', 'boolean'],
        ]);

        $email = Str::lower(trim($data['email']));
        $consent = $request->boolean('consent');
        $existing = NewsletterSubscriber::query()->where('email', $email)->first();

        if ($existing && (int) $existing->status === 1) {
            return response()->json([
                'ok' => false,
                'message' => 'This email address is already subscribed.',
            ], 422);
        }

        $subscriber = $existing ?: new NewsletterSubscriber();
        $subscriber->fill([
            'email' => $email,
            'status' => 1,
            'consent' => $consent ? 1 : 0,
            'token' => Str::random(48),
            'subscribed_at' => now(),
            'unsubscribed_at' => null,
        ])->save();

        $this->confirm($subscriber);

        return response()->json([
            'ok' => true,
            'message' => $consent
                ? 'Thanks. You are subscribed. A confirmation email is sent when mail is configured.'
                : 'Saved. Tick the consent box if you want offer emails.',
        ]);
    }

    public function unsubscribe(string $token)
    {
        $subscriber = NewsletterSubscriber::query()->where('token', $token)->firstOrFail();
        $this->unsubscribeSeo();

        return view('newsletter_unsubscribe', compact('subscriber'));
    }

    public function confirmUnsubscribe(Request $request, string $token)
    {
        $subscriber = NewsletterSubscriber::query()->where('token', $token)->firstOrFail();
        $subscriber->update([
            'status' => 0,
            'consent' => 0,
            'unsubscribed_at' => now(),
            'token' => Str::random(48),
        ]);
        $this->unsubscribeSeo();

        return view('newsletter_unsubscribe', ['subscriber' => $subscriber, 'done' => true]);
    }

    public function index(Request $request)
    {
        $subscribers = $this->filtered($request)->paginate(20)->withQueryString();

        return view('admin.newsletter.index', compact('subscribers'));
    }

    public function show(NewsletterSubscriber $subscriber)
    {
        return view('admin.newsletter.show', compact('subscriber'));
    }

    public function status(NewsletterSubscriber $subscriber)
    {
        $active = (int) $subscriber->status !== 1;
        $subscriber->update([
            'status' => $active ? 1 : 0,
            'consent' => $active ? $subscriber->consent : 0,
            'unsubscribed_at' => $active ? null : now(),
            'subscribed_at' => $active ? now() : $subscriber->subscribed_at,
        ]);

        return back()->with('success', $active ? 'Subscriber activated.' : 'Subscriber unsubscribed.');
    }

    public function export(Request $request)
    {
        $rows = $this->filtered($request)->get(['email', 'status', 'consent', 'subscribed_at', 'unsubscribed_at']);

        return response()->streamDownload(function () use ($rows) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['email', 'status', 'consent', 'subscribed_at', 'unsubscribed_at']);
            foreach ($rows as $row) {
                fputcsv($out, [
                    $row->email,
                    $row->status ? 'subscribed' : 'unsubscribed',
                    $row->consent ? 'yes' : 'no',
                    optional($row->subscribed_at)->toDateTimeString(),
                    optional($row->unsubscribed_at)->toDateTimeString(),
                ]);
            }
            fclose($out);
        }, 'newsletter-subscribers.csv', ['Content-Type' => 'text/csv']);
    }

    public function campaigns()
    {
        $campaigns = NewsletterCampaign::query()->orderByDesc('id')->paginate(15);

        return view('admin.newsletter.campaigns', compact('campaigns'));
    }

    public function storeCampaign(Request $request)
    {
        $data = $request->validate([
            'subject' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'scheduled_at' => ['nullable', 'date'],
        ]);
        $data['content'] = HtmlCleaner::clean($data['content']);
        if ($data['content'] === '') {
            return back()->withInput()->withErrors(['content' => 'Add the email content.']);
        }
        $data['status'] = 'draft';
        NewsletterCampaign::create($data);

        return back()->with('success', 'Campaign saved as a draft. It has not been sent.');
    }

    public function queueCampaign(NewsletterCampaign $campaign)
    {
        if ($campaign->status === 'sent') {
            return back()->with('success', 'This campaign was already sent.');
        }

        $campaign->update(['status' => 'queued']);
        $pending = SendNewsletterCampaign::dispatch($campaign->id);
        if ($campaign->scheduled_at && $campaign->scheduled_at->isFuture()) {
            $pending->delay($campaign->scheduled_at);
        }

        return back()->with('success', 'Campaign queued. It sends only to subscribed addresses that gave consent, and only when a queue worker is running.');
    }

    private function unsubscribeSeo(): void
    {
        Seo::share([
            'title' => 'Unsubscribe | Cranzo',
            'description' => 'Stop Cranzo offer emails.',
            'robots' => 'noindex,nofollow',
            'canonical' => url('/'),
        ]);
    }

    private function filtered(Request $request)
    {
        return NewsletterSubscriber::query()
            ->when($request->filled('q'), fn ($query) => $query->where('email', 'like', '%'.Str::lower($request->string('q')).'%'))
            ->when($request->filled('status'), fn ($query) => $query->where('status', (int) $request->input('status')))
            ->orderByDesc('id');
    }

    private function confirm(NewsletterSubscriber $subscriber): void
    {
        try {
            Mail::raw(
                "Your Cranzo newsletter request is saved.\n\nUnsubscribe: ".route('newsletter.unsubscribe', $subscriber->token),
                function ($message) use ($subscriber) {
                    $message->to($subscriber->email)->subject('Cranzo newsletter subscription');
                }
            );
        } catch (Throwable $exception) {
            report($exception);
        }

        $admin = config('mail.newsletter_notify');
        if (! $admin) {
            return;
        }

        try {
            Mail::raw('A visitor subscribed: '.$subscriber->email, function ($message) use ($admin) {
                $message->to($admin)->subject('New Cranzo newsletter subscriber');
            });
        } catch (Throwable $exception) {
            report($exception);
        }
    }
}
