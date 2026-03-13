<?php

namespace App\Mail;

use App\Models\AlertRule;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DmarcAlertEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public AlertRule $rule,
        public array $eventData,
    ) {}

    public function envelope(): Envelope
    {
        $eventType = $this->eventData['event_type'] ?? 'Unknown Event';

        return new Envelope(
            subject: "DMARCWatch Alert: {$eventType}",
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.dmarc-alert',
            with: [
                'rule' => $this->rule,
                'eventData' => $this->eventData,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
