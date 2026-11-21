<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Throwable;

class Notifier
{
    public function mail(string $to, string $subject, string $body): bool
    {
        return $this->deliver($to, $subject, function () use ($to, $subject, $body) {
            Mail::raw($body, function ($message) use ($to, $subject) {
                $message->to($to)->subject($subject);
            });
        });
    }

    public function html(string $to, string $subject, string $html, ?string $replyTo = null, ?string $replyName = null): bool
    {
        return $this->deliver($to, $subject, function () use ($to, $subject, $html, $replyTo, $replyName) {
            Mail::html($html, function ($message) use ($to, $subject, $replyTo, $replyName) {
                $message->to($to)->subject($subject);
                if (filter_var($replyTo, FILTER_VALIDATE_EMAIL)) {
                    $message->replyTo($replyTo, $replyName ?: null);
                }
            });
        });
    }

    public function contact(array $data): array
    {
        $support = config('mail.contact_notify') ?: config('mail.from.address');

        return [
            'support' => $this->html(
                (string) $support,
                'New Cranzo contact message',
                view('mail.contact_received', $data)->render(),
                $data['email'] ?? null,
                $data['name'] ?? null
            ),
            'thanks' => $this->html(
                (string) ($data['email'] ?? ''),
                'We received your message',
                view('mail.contact_thanks', $data)->render()
            ),
        ];
    }

    public function userCreated(User $user): bool
    {
        $name = trim($user->first_name.' '.$user->last_name) ?: 'there';
        $url = URL::temporarySignedRoute('verification.verify', now()->addHours(48), [
            'id' => $user->getKey(),
            'hash' => sha1($user->getEmailForVerification()),
        ]);

        return $this->html(
            $user->email,
            'Confirm your Cranzo account',
            view('mail.account_created', [
                'name' => $name,
                'email' => $user->email,
                'verifyUrl' => $url,
            ])->render()
        );
    }

    private function deliver(string $to, string $subject, callable $send): bool
    {
        if (! filter_var($to, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        try {
            $send();

            return true;
        } catch (Throwable $exception) {
            Log::warning('Email was not sent.', [
                'to' => $to,
                'subject' => $subject,
                'error' => $exception->getMessage(),
            ]);

            return false;
        }
    }
}
