@extends('layouts.marketing')

@section('title', 'Pricing — DMARCWatch')

@section('content')
<section class="py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h1 class="text-4xl font-bold text-forest-900">Simple, transparent pricing</h1>
            <p class="mt-4 text-lg text-gray-600">Start free, upgrade when you need more.</p>
        </div>

        <div class="grid md:grid-cols-3 gap-8 max-w-5xl mx-auto">
            <!-- Free -->
            <div class="bg-white rounded-xl p-8 shadow-sm border border-gray-200">
                <h3 class="text-lg font-semibold text-forest-900">Free</h3>
                <div class="mt-4"><span class="text-4xl font-bold">$0</span><span class="text-gray-500">/month</span></div>
                <ul class="mt-8 space-y-3 text-sm text-gray-600">
                    <li class="flex items-center"><svg class="w-4 h-4 text-lime-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>3 domains</li>
                    <li class="flex items-center"><svg class="w-4 h-4 text-lime-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>100 reports/month</li>
                    <li class="flex items-center"><svg class="w-4 h-4 text-lime-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>30-day retention</li>
                    <li class="flex items-center"><svg class="w-4 h-4 text-lime-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>1 alert channel</li>
                    <li class="flex items-center"><svg class="w-4 h-4 text-lime-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Daily DNS checks</li>
                </ul>
                <a href="{{ route('register') }}" class="mt-8 block text-center bg-gray-100 text-forest-900 px-6 py-3 rounded-lg font-semibold hover:bg-gray-200 transition">Get Started</a>
            </div>

            <!-- Pro -->
            <div class="bg-white rounded-xl p-8 shadow-sm border-2 border-lime-400 relative">
                <div class="absolute -top-3 left-1/2 -translate-x-1/2 bg-lime-400 text-forest-900 text-xs font-bold px-3 py-1 rounded-full">POPULAR</div>
                <h3 class="text-lg font-semibold text-forest-900">Pro</h3>
                <div class="mt-4"><span class="text-4xl font-bold">$24</span><span class="text-gray-500">/month</span></div>
                <ul class="mt-8 space-y-3 text-sm text-gray-600">
                    <li class="flex items-center"><svg class="w-4 h-4 text-lime-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>50 domains</li>
                    <li class="flex items-center"><svg class="w-4 h-4 text-lime-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Unlimited reports</li>
                    <li class="flex items-center"><svg class="w-4 h-4 text-lime-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>365-day retention</li>
                    <li class="flex items-center"><svg class="w-4 h-4 text-lime-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>5 alert channels (all types)</li>
                    <li class="flex items-center"><svg class="w-4 h-4 text-lime-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Hourly DNS checks</li>
                    <li class="flex items-center"><svg class="w-4 h-4 text-lime-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>API access</li>
                    <li class="flex items-center"><svg class="w-4 h-4 text-lime-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Weekly digests</li>
                </ul>
                <a href="{{ route('register') }}" class="mt-8 block text-center bg-lime-400 text-forest-900 px-6 py-3 rounded-lg font-semibold hover:bg-lime-300 transition">Start Free Trial</a>
            </div>

            <!-- Enterprise -->
            <div class="bg-white rounded-xl p-8 shadow-sm border border-gray-200">
                <h3 class="text-lg font-semibold text-forest-900">Enterprise</h3>
                <div class="mt-4"><span class="text-4xl font-bold">$79</span><span class="text-gray-500">/month</span></div>
                <ul class="mt-8 space-y-3 text-sm text-gray-600">
                    <li class="flex items-center"><svg class="w-4 h-4 text-lime-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>100+ domains (expandable)</li>
                    <li class="flex items-center"><svg class="w-4 h-4 text-lime-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Unlimited everything</li>
                    <li class="flex items-center"><svg class="w-4 h-4 text-lime-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Unlimited retention</li>
                    <li class="flex items-center"><svg class="w-4 h-4 text-lime-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Unlimited channels &amp; team members</li>
                    <li class="flex items-center"><svg class="w-4 h-4 text-lime-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>15-minute DNS checks</li>
                    <li class="flex items-center"><svg class="w-4 h-4 text-lime-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>50,000 API requests/day</li>
                    <li class="flex items-center"><svg class="w-4 h-4 text-lime-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Domain addon bundles</li>
                </ul>
                <a href="{{ route('register') }}" class="mt-8 block text-center bg-gray-100 text-forest-900 px-6 py-3 rounded-lg font-semibold hover:bg-gray-200 transition">Start Free Trial</a>
            </div>
        </div>
    </div>
</section>
@endsection