<x-mail::message>
# DMARC Alert: {{ $eventData['event_type'] ?? 'Unknown Event' }}

An alert has been triggered for rule **{{ $rule->name }}**.

## Event Details

<x-mail::table>
| Detail | Value |
|:-------|:------|
@foreach($eventData as $key => $value)
| {{ ucwords(str_replace('_', ' ', $key)) }} | {{ is_array($value) ? json_encode($value) : $value }} |
@endforeach
</x-mail::table>

<x-mail::button :url="url('/alerts')">
View Alerts
</x-mail::button>

You are receiving this because you have alert rules configured in DMARCWatch.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
