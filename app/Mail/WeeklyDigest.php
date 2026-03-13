<?php

namespace App\Mail;

use App\Models\Team;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WeeklyDigest extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public Team $team,
        public array $stats,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Weekly DMARC Digest',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.weekly-digest',
            with: [
                'team' => $this->team,
                'stats' => $this->stats,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
