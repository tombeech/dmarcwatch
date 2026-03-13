@extends('layouts.marketing')
@section('title', 'About — DMARCWatch')

@section('content')
<section class="py-20">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-4xl font-bold text-forest-900 mb-8">About DMARCWatch</h1>
        <div class="prose prose-lg text-gray-600">
            <p>DMARCWatch was built to solve a simple problem: understanding who is sending email on behalf of your domain shouldn't require a PhD in email authentication.</p>
            <p>DMARC, SPF, and DKIM are essential protocols that protect your domain from email spoofing and phishing. But configuring them correctly and monitoring compliance is complex. Aggregate reports arrive as XML files buried in email attachments. Understanding them requires parsing, cross-referencing IP addresses, and tracking changes over time.</p>
            <p>We built DMARCWatch to automate all of this. Add your domain, point your DMARC reports to us, and we'll handle the rest — parsing reports, identifying senders, scoring compliance, and alerting you when something goes wrong.</p>
        </div>

        <div class="mt-16 grid md:grid-cols-3 gap-8">
            @foreach([
                ['label' => 'Protocols', 'value' => 'DMARC, SPF, DKIM'],
                ['label' => 'Processing', 'value' => '< 60 seconds'],
                ['label' => 'Alert Channels', 'value' => 'Email, Slack, Webhook, Pushover'],
            ] as $stat)
                <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-100 text-center">
                    <div class="text-2xl font-bold text-forest-900">{{ $stat['value'] }}</div>
                    <div class="text-sm text-gray-500 mt-1">{{ $stat['label'] }}</div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endsection