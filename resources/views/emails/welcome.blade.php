<x-mail::message>
# Welcome to DMARCWatch, {{ $user->name }}!

Thanks for signing up. You're on your way to better email security and deliverability.

## Getting Started

1. **Add your first domain** — Head to your dashboard and register the domains you want to monitor.
2. **Publish your DMARC record** — We'll provide the DNS record you need to start receiving reports.
3. **Review your reports** — Once reports start arriving, you'll see detailed breakdowns of your email authentication status.
4. **Set up alerts** — Configure alert rules so you're notified when something needs attention.

<x-mail::button :url="url('/dashboard')">
Go to Dashboard
</x-mail::button>

If you have any questions, reply to this email and we'll be happy to help.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
