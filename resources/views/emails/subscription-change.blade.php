<x-mail::message>
# Subscription {{ ucfirst($action) }}

Hi {{ $user->name }},

@if($action === 'subscribed')
Welcome aboard! You've successfully subscribed to the **{{ $planName }}** plan.
@elseif($action === 'upgraded')
Great news! Your plan has been upgraded to **{{ $planName }}**. Your new features are available immediately.
@elseif($action === 'downgraded')
Your plan has been changed to **{{ $planName }}**. The change will take effect at the end of your current billing period.
@elseif($action === 'cancelled')
Your subscription has been cancelled. You'll continue to have access to your **{{ $planName }}** plan features until the end of your current billing period.
@endif

## Plan Details

<x-mail::table>
| | |
|:--|:--|
| Plan | {{ $planName }} |
| Action | {{ ucfirst($action) }} |
| Date | {{ now()->format('F j, Y') }} |
</x-mail::table>

<x-mail::button :url="url('/settings/billing')">
Manage Billing
</x-mail::button>

If you have any questions about your subscription, reply to this email.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
