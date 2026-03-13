<?php

namespace App\Jobs;

use App\Enums\AlertStatus;
use App\Enums\DmarcEventType;
use App\Models\AlertLog;
use App\Models\AlertRule;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendAlertNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $queue = 'alerts';

    public function __construct(
        public readonly AlertRule $rule,
        public readonly DmarcEventType $eventType,
        public readonly array $eventData,
    ) {}

    public function handle(): void
    {
        $channel = $this->rule->alertChannel;

        if (! $channel || ! $channel->is_active) {
            return;
        }

        $status = AlertStatus::SENT;
        $response = null;

        try {
            $response = match ($channel->type->value) {
                'email' => $this->sendEmail($channel->config),
                'slack' => $this->sendSlack($channel->config),
                'webhook' => $this->sendWebhook($channel->config),
                'pushover' => $this->sendPushover($channel->config),
                default => 'Unknown channel type',
            };
        } catch (\Throwable $e) {
            $status = AlertStatus::FAILED;
            $response = $e->getMessage();
            Log::error('[SendAlertNotification] Failed', [
                'rule_id' => $this->rule->id,
                'error' => $e->getMessage(),
            ]);
        }

        AlertLog::create([
            'alert_rule_id' => $this->rule->id,
            'alert_channel_id' => $channel->id,
            'event_type' => $this->eventType->value,
            'event_data' => $this->eventData,
            'status' => $status,
            'response' => $response,
            'sent_at' => now(),
        ]);
    }

    protected function sendEmail(array $config): string
    {
        $to = $config['email'] ?? null;
        if (! $to) {
            return 'No email address configured';
        }

        $subject = '[DMARCWatch] ' . $this->eventType->value . ' alert';
        $body = "Event: {$this->eventType->value}\n\n" . json_encode($this->eventData, JSON_PRETTY_PRINT);

        Mail::raw($body, function ($message) use ($to, $subject) {
            $message->to($to)->subject($subject);
        });

        return 'Email sent to ' . $to;
    }

    protected function sendSlack(array $config): string
    {
        $webhookUrl = $config['webhook_url'] ?? null;
        if (! $webhookUrl) {
            return 'No Slack webhook configured';
        }

        $response = Http::post($webhookUrl, [
            'text' => "[DMARCWatch] {$this->eventType->value}: " . json_encode($this->eventData),
        ]);

        return 'Slack response: ' . $response->status();
    }

    protected function sendWebhook(array $config): string
    {
        $url = $config['url'] ?? null;
        if (! $url) {
            return 'No webhook URL configured';
        }

        $payload = [
            'event_type' => $this->eventType->value,
            'data' => $this->eventData,
            'timestamp' => now()->toIso8601String(),
        ];

        $headers = [];
        if (! empty($config['secret'])) {
            $headers['X-Webhook-Signature'] = hash_hmac('sha256', json_encode($payload), $config['secret']);
        }

        $response = Http::withHeaders($headers)->post($url, $payload);

        return 'Webhook response: ' . $response->status();
    }

    protected function sendPushover(array $config): string
    {
        $response = Http::post('https://api.pushover.net/1/messages.json', [
            'token' => $config['app_token'] ?? '',
            'user' => $config['user_key'] ?? '',
            'title' => '[DMARCWatch] Alert',
            'message' => "{$this->eventType->value}: " . ($this->eventData['domain_name'] ?? 'Unknown domain'),
        ]);

        return 'Pushover response: ' . $response->status();
    }
}
