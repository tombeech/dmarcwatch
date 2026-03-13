@extends('layouts.marketing')
@section('title', 'Contact — DMARCWatch')

@section('content')
<section class="py-20">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid md:grid-cols-2 gap-12">
            <div>
                <h1 class="text-4xl font-bold text-forest-900 mb-4">Get in touch</h1>
                <p class="text-gray-600 mb-8">Have a question about DMARCWatch? We'd love to hear from you.</p>
                <div class="space-y-4 text-sm text-gray-600">
                    <p><strong>Email:</strong> hello@dmarcwatch.app</p>
                    <p><strong>Response time:</strong> Within one business day</p>
                </div>
            </div>
            <div>
                @if (session('success'))
                    <div class="bg-lime-50 border border-lime-200 text-lime-800 px-4 py-3 rounded-lg mb-6">{{ session('success') }}</div>
                @endif
                <form method="POST" action="{{ route('contact.submit') }}" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">First name</label>
                            <input type="text" name="first_name" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Last name</label>
                            <input type="text" name="last_name" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Company</label>
                        <input type="text" name="company" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
                        <select name="subject" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500">
                            <option value="general">General Inquiry</option>
                            <option value="support">Support</option>
                            <option value="enterprise">Enterprise</option>
                            <option value="billing">Billing</option>
                            <option value="partnership">Partnership</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Message</label>
                        <textarea name="message" rows="4" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500"></textarea>
                    </div>
                    <button type="submit" class="bg-lime-400 text-forest-900 px-6 py-3 rounded-lg font-semibold hover:bg-lime-300 transition">Send Message</button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection