<x-mail::message>
# Weekly DMARC Digest for {{ $team->name }}

Here's a summary of your DMARC activity for the past week.

## Summary

<x-mail::table>
| Metric | Value |
|:-------|------:|
@isset($stats['total_reports'])
| Total Reports | {{ number_format($stats['total_reports']) }} |
@endisset
@isset($stats['total_messages'])
| Total Messages | {{ number_format($stats['total_messages']) }} |
@endisset
@isset($stats['pass_rate'])
| DMARC Pass Rate | {{ $stats['pass_rate'] }}% |
@endisset
@isset($stats['dkim_pass_rate'])
| DKIM Pass Rate | {{ $stats['dkim_pass_rate'] }}% |
@endisset
@isset($stats['spf_pass_rate'])
| SPF Pass Rate | {{ $stats['spf_pass_rate'] }}% |
@endisset
@isset($stats['domains_monitored'])
| Domains Monitored | {{ $stats['domains_monitored'] }} |
@endisset
@isset($stats['alerts_triggered'])
| Alerts Triggered | {{ $stats['alerts_triggered'] }} |
@endisset
</x-mail::table>

<x-mail::button :url="url('/dashboard')">
View Full Report
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
