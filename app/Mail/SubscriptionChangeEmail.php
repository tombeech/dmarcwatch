<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SubscriptionChangeEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public string $planName,
        public string $action,
    ) {}

    public function envelope(): Envelope
    {
        $actionLabel = ucfirst($this->action);

        return new Envelope(
            subject: "DMARCWatch — Subscription {$actionLabel}",
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.subscription-change',
            with: [
                'user' => $this->user,
                'planName' => $this->planName,
                'action' => $this->action,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
