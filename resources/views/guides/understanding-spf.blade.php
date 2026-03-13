@extends('layouts.marketing')
@section('title', 'Understanding SPF Records — DMARCWatch')

@section('content')
<article class="py-20">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 prose prose-lg">
        <a href="/guides" class="text-lime-600 hover:text-lime-700 no-underline">&larr; Back to Guides</a>

        <h1 class="mt-8">Understanding SPF Records</h1>
        <p class="lead text-gray-600">Learn how SPF works, how to configure it, and how to avoid common pitfalls like exceeding the DNS lookup limit.</p>

        <h2>What Is SPF?</h2>
        <p>Sender Policy Framework (SPF) is a DNS-based email authentication protocol defined in <strong>RFC 7208</strong>. It allows a domain owner to specify which IP addresses and mail servers are authorised to send email on behalf of their domain. Receiving mail servers check the SPF record during the SMTP transaction, before the message body is even transferred, making it one of the earliest checks in the delivery pipeline.</p>
        <p>SPF validates the <strong>envelope sender</strong> (also known as the Return-Path or MAIL FROM address), not the <code>From:</code> header that users see. This is an important distinction &mdash; it is DMARC's alignment check that connects SPF results back to the visible sender.</p>

        <h2>SPF Record Syntax</h2>
        <p>An SPF record is a TXT record published at the root of your domain. Every SPF record begins with a version tag and ends with a default mechanism:</p>
        <pre><code class="bg-forest-900 text-lime-400 block rounded-lg p-4 text-sm overflow-x-auto">v=spf1 [mechanisms] [default]</code></pre>
        <p>Here is a real-world example:</p>
        <pre><code class="bg-forest-900 text-lime-400 block rounded-lg p-4 text-sm overflow-x-auto">v=spf1 ip4:203.0.113.0/24 ip6:2001:db8::/32 include:_spf.google.com include:sendgrid.net mx -all</code></pre>

        <h2>Mechanisms</h2>
        <p>Mechanisms are the rules that define authorised senders. They are evaluated from left to right, and the first match determines the result.</p>

        <h3><code>ip4</code> and <code>ip6</code></h3>
        <p>Authorise a specific IPv4 or IPv6 address or range. This is the most explicit way to authorise a sender.</p>
        <pre><code class="bg-forest-900 text-lime-400 block rounded-lg p-4 text-sm overflow-x-auto">v=spf1 ip4:198.51.100.10 ip4:203.0.113.0/24 ip6:2001:db8::1 -all</code></pre>

        <h3><code>include</code></h3>
        <p>Delegates authorisation to another domain's SPF record. This is how you authorise third-party services like Google Workspace, Microsoft 365, Mailchimp, or SendGrid. When the receiving server encounters an <code>include</code>, it looks up the referenced domain's SPF record and evaluates it.</p>
        <pre><code class="bg-forest-900 text-lime-400 block rounded-lg p-4 text-sm overflow-x-auto">v=spf1 include:_spf.google.com include:spf.protection.outlook.com -all</code></pre>

        <h3><code>a</code></h3>
        <p>Authorises the IP addresses that your domain's A (or AAAA) records resolve to. Useful if your web server also sends email.</p>
        <pre><code class="bg-forest-900 text-lime-400 block rounded-lg p-4 text-sm overflow-x-auto">v=spf1 a -all</code></pre>

        <h3><code>mx</code></h3>
        <p>Authorises the IP addresses of your domain's MX (mail exchange) servers. If your inbound mail servers also handle outbound delivery, this is a convenient shorthand.</p>
        <pre><code class="bg-forest-900 text-lime-400 block rounded-lg p-4 text-sm overflow-x-auto">v=spf1 mx -all</code></pre>

        <h3><code>all</code></h3>
        <p>The catch-all mechanism that matches everything. It should always appear at the end of your record and defines the default action for senders that did not match any previous mechanism.</p>

        <h2>Qualifiers</h2>
        <p>Each mechanism can be prefixed with a qualifier that determines the result when it matches:</p>
        <ul>
            <li><code>+</code> (Pass) &mdash; The sender is authorised. This is the default if no qualifier is specified.</li>
            <li><code>-</code> (Fail) &mdash; The sender is explicitly <strong>not</strong> authorised. Use <code>-all</code> at the end of your record to hard-fail everything that does not match.</li>
            <li><code>~</code> (SoftFail) &mdash; The sender is probably not authorised. Messages are accepted but may be flagged. <code>~all</code> is commonly used during initial deployment, but <code>-all</code> is recommended once you are confident in your record.</li>
            <li><code>?</code> (Neutral) &mdash; No assertion is made about the sender. Rarely used in practice.</li>
        </ul>

        <h2>The 10 DNS Lookup Limit</h2>
        <p>This is the most common source of SPF failures. RFC 7208 specifies that an SPF evaluation must not require more than <strong>10 DNS lookups</strong>. The following mechanisms each consume one lookup: <code>include</code>, <code>a</code>, <code>mx</code>, <code>redirect</code>, and <code>exists</code>. Notably, <code>ip4</code> and <code>ip6</code> do <strong>not</strong> require a DNS lookup.</p>
        <p>Each <code>include</code> can itself contain further <code>include</code> statements, and these nested lookups count toward the same limit. For example, <code>include:_spf.google.com</code> alone can consume 3-4 lookups because Google's SPF record chains to other records.</p>
        <p>If your SPF evaluation exceeds 10 lookups, the result is a <strong>permerror</strong> and SPF fails entirely &mdash; even for legitimate senders.</p>

        <h3>How to Stay Under the Limit</h3>
        <ul>
            <li><strong>Replace <code>include</code> with <code>ip4</code>/<code>ip6</code></strong> where possible. If a service sends from a known, stable set of IP addresses, list them directly.</li>
            <li><strong>Remove unused services.</strong> Audit your SPF record regularly and remove includes for services you no longer use.</li>
            <li><strong>Use SPF flattening.</strong> Tools can resolve all includes to their underlying IP addresses and produce a flattened record. Be aware that flattened records must be updated when the service provider changes their IP ranges.</li>
            <li><strong>Avoid the <code>a</code> and <code>mx</code> mechanisms</strong> unless necessary, as each one consumes a lookup.</li>
        </ul>

        <h2>Common Mistakes</h2>
        <ul>
            <li><strong>Multiple SPF records.</strong> A domain must have only one SPF TXT record. If you publish two, SPF evaluation returns a permerror. To add a new service, edit your existing record rather than creating a second one.</li>
            <li><strong>Using <code>+all</code>.</strong> This authorises the entire internet to send as your domain. Never use it.</li>
            <li><strong>Forgetting third-party senders.</strong> If you use services like Mailchimp, HubSpot, Zendesk, or a transactional email provider, they must be included in your SPF record or they will fail authentication.</li>
            <li><strong>Overly broad records.</strong> Including every service "just in case" wastes lookups and expands your attack surface. Only include services that actively send email as your domain.</li>
        </ul>

        <h2>Building Your SPF Record</h2>
        <p>Follow this process to create an effective SPF record:</p>
        <ol>
            <li><strong>Inventory your senders.</strong> List every system, service, and server that sends email using your domain. Check with marketing, support, IT, and engineering teams.</li>
            <li><strong>Gather their SPF requirements.</strong> Each service will document their recommended <code>include</code> or IP ranges.</li>
            <li><strong>Count your lookups.</strong> Add up the DNS lookups and ensure you stay under 10.</li>
            <li><strong>Publish and test.</strong> Publish your record and use DMARCWatch to monitor whether legitimate mail passes SPF.</li>
        </ol>
        <p>A well-constructed SPF record for a typical organisation might look like this:</p>
        <pre><code class="bg-forest-900 text-lime-400 block rounded-lg p-4 text-sm overflow-x-auto">v=spf1 include:_spf.google.com include:sendgrid.net ip4:203.0.113.10 -all</code></pre>
        <p>This record authorises Google Workspace, SendGrid, and a single server IP, then hard-fails everything else. It uses approximately 5 DNS lookups, leaving room for future additions.</p>

        <div class="mt-12 pt-8 border-t border-gray-200">
            <a href="/guides" class="text-lime-600 hover:text-lime-700 no-underline">&larr; Back to Guides</a>
        </div>
    </div>
</article>
@endsection