@extends('layouts.marketing')
@section('title', 'Guides — DMARCWatch')

@section('content')
<section class="py-20">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-4xl font-bold text-forest-900 mb-4">Guides</h1>
        <p class="text-lg text-gray-600 mb-12">Learn about email authentication and how to protect your domain.</p>
        <div class="space-y-6">
            @foreach([
                ['url' => '/guides/getting-started', 'title' => 'Getting Started with DMARC', 'desc' => 'A beginner-friendly introduction to DMARC and how to set up monitoring with DMARCWatch.'],
                ['url' => '/guides/understanding-spf', 'title' => 'Understanding SPF Records', 'desc' => 'Learn how SPF works, how to configure it, and how to avoid common pitfalls like exceeding the DNS lookup limit.'],
                ['url' => '/guides/dkim-explained', 'title' => 'DKIM Explained', 'desc' => 'Understand DomainKeys Identified Mail, how to set up DKIM signing, and verify your configuration.'],
                ['url' => '/guides/dmarc-policy-guide', 'title' => 'DMARC Policy Guide', 'desc' => 'The path from p=none to p=reject. Learn when and how to tighten your DMARC policy.'],
                ['url' => '/guides/reading-aggregate-reports', 'title' => 'Reading Aggregate Reports', 'desc' => 'How to interpret DMARC aggregate report data — sources, pass rates, disposition, and alignment.'],
                ['url' => '/guides/email-authentication-101', 'title' => 'Email Authentication 101', 'desc' => 'A comprehensive overview of email authentication: SPF, DKIM, DMARC, and how they work together.'],
            ] as $guide)
                <a href="{{ $guide['url'] }}" class="block bg-white rounded-lg p-6 shadow-sm border border-gray-100 hover:border-lime-300 transition">
                    <h3 class="text-lg font-semibold text-forest-900">{{ $guide['title'] }}</h3>
                    <p class="text-gray-600 text-sm mt-1">{{ $guide['desc'] }}</p>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endsection