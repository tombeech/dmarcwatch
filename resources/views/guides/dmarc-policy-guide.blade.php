<x-marketing-layout title="DMARC Policy Guide - DMARCWatch" meta-description="The path from p=none to p=reject. Learn when and how to tighten your DMARC policy safely.">
<article class="py-20">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 prose prose-lg">
        <a href="/guides" class="text-lime-600 hover:text-lime-700 no-underline">&larr; Back to Guides</a>

        <h1 class="mt-8">DMARC Policy Guide</h1>
        <p class="lead text-gray-600">The path from p=none to p=reject. Learn when and how to tighten your DMARC policy safely.</p>

        <h2>The Three DMARC Policies</h2>
        <p>The <code>p=</code> tag in your DMARC record is the most important setting. It tells receiving mail servers what to do with messages that fail both SPF and DKIM alignment. There are three possible values:</p>

        <h3><code>p=none</code> &mdash; Monitor Only</h3>
        <p>No enforcement. Messages that fail DMARC are delivered normally. Aggregate reports are still generated and sent to the address in your <code>rua=</code> tag, giving you full visibility into authentication results without any risk of blocking legitimate email.</p>
        <p>This is the correct starting policy for every domain. It allows you to discover all sources sending as your domain and fix authentication issues before enforcement begins.</p>

        <h3><code>p=quarantine</code> &mdash; Soft Enforcement</h3>
        <p>Messages that fail DMARC are treated as suspicious. In practice, most receiving servers will deliver these messages to the spam or junk folder rather than the inbox. This policy begins protecting your domain while giving you a safety net &mdash; if a legitimate sender was missed during the monitoring phase, their messages will still reach recipients (albeit in spam) rather than being discarded entirely.</p>

        <h3><code>p=reject</code> &mdash; Full Enforcement</h3>
        <p>Messages that fail DMARC are rejected outright by the receiving server. They are not delivered to any folder. This is the strongest protection against spoofing and is the ultimate goal of any DMARC deployment. Major mailbox providers like Google and Yahoo require bulk senders to have a DMARC policy, and <code>p=reject</code> provides the maximum benefit.</p>

        <h2>The Recommended Progression Path</h2>
        <p>Moving directly from no DMARC to <code>p=reject</code> is risky because it can block legitimate email from services you did not know were sending as your domain. The safe approach is a gradual progression:</p>

        <h3>Phase 1: Monitor (2-4 weeks minimum)</h3>
        <pre><code class="bg-forest-900 text-lime-400 block rounded-lg p-4 text-sm overflow-x-auto">v=DMARC1; p=none; rua=mailto:your-token@agg.dmarcwatch.io; fo=1;</code></pre>
        <p>Publish this record and monitor aggregate reports in DMARCWatch. Identify every legitimate source and ensure they pass SPF or DKIM with proper alignment. Common sources to check include your primary email provider, marketing platforms, CRM systems, helpdesk tools, and transactional email services.</p>

        <h3>Phase 2: Quarantine with Percentage (2-4 weeks)</h3>
        <pre><code class="bg-forest-900 text-lime-400 block rounded-lg p-4 text-sm overflow-x-auto">v=DMARC1; p=quarantine; pct=25; rua=mailto:your-token@agg.dmarcwatch.io; fo=1;</code></pre>
        <p>The <code>pct=25</code> tag tells receivers to apply the quarantine policy to only 25% of failing messages. The remaining 75% are treated as if the policy were <code>none</code>. This limits the blast radius if a legitimate sender is still misconfigured. Gradually increase <code>pct</code> to 50, then 75, then 100 as you confirm there are no issues.</p>

        <h3>Phase 3: Full Quarantine (2-4 weeks)</h3>
        <pre><code class="bg-forest-900 text-lime-400 block rounded-lg p-4 text-sm overflow-x-auto">v=DMARC1; p=quarantine; rua=mailto:your-token@agg.dmarcwatch.io; fo=1;</code></pre>
        <p>With <code>pct</code> omitted (or set to 100), all failing messages are quarantined. Monitor your reports to confirm no legitimate mail is ending up in spam.</p>

        <h3>Phase 4: Reject with Percentage (2-4 weeks)</h3>
        <pre><code class="bg-forest-900 text-lime-400 block rounded-lg p-4 text-sm overflow-x-auto">v=DMARC1; p=reject; pct=25; rua=mailto:your-token@agg.dmarcwatch.io; fo=1;</code></pre>
        <p>Begin rejecting a portion of failing messages. Ramp up the percentage over time.</p>

        <h3>Phase 5: Full Reject</h3>
        <pre><code class="bg-forest-900 text-lime-400 block rounded-lg p-4 text-sm overflow-x-auto">v=DMARC1; p=reject; rua=mailto:your-token@agg.dmarcwatch.io; fo=1;</code></pre>
        <p>Your domain is now fully protected. All messages that fail DMARC alignment will be rejected. Continue monitoring reports in DMARCWatch to catch any new services added to your email infrastructure.</p>

        <h2>DMARC Record Tags Reference</h2>
        <p>Here is a complete reference of all DMARC tags:</p>
        <ul>
            <li><code>v=DMARC1</code> &mdash; <strong>Required.</strong> Version identifier. Must be the first tag in the record.</li>
            <li><code>p=</code> &mdash; <strong>Required.</strong> Policy for the domain. Values: <code>none</code>, <code>quarantine</code>, <code>reject</code>.</li>
            <li><code>sp=</code> &mdash; Subdomain policy. Applies to all subdomains that do not have their own DMARC record. If omitted, the <code>p=</code> policy applies to subdomains as well.</li>
            <li><code>rua=</code> &mdash; Aggregate report destination. A comma-separated list of <code>mailto:</code> URIs where daily aggregate reports should be sent. Example: <code>rua=mailto:dmarc@yourdomain.com</code>.</li>
            <li><code>ruf=</code> &mdash; Forensic (failure) report destination. Receives detailed reports for individual failing messages. Many providers do not send forensic reports due to privacy concerns, but the tag is still useful where supported.</li>
            <li><code>pct=</code> &mdash; Percentage of failing messages to which the policy applies. Value from 1 to 100. Default is 100. Use this to gradually roll out enforcement.</li>
            <li><code>adkim=</code> &mdash; DKIM alignment mode. <code>r</code> for relaxed (default), <code>s</code> for strict. Relaxed allows subdomains to align with the organisational domain.</li>
            <li><code>aspf=</code> &mdash; SPF alignment mode. <code>r</code> for relaxed (default), <code>s</code> for strict.</li>
            <li><code>fo=</code> &mdash; Failure reporting options. <code>0</code> = report if both SPF and DKIM fail (default). <code>1</code> = report if either fails. <code>d</code> = report DKIM failure. <code>s</code> = report SPF failure.</li>
            <li><code>ri=</code> &mdash; Reporting interval in seconds. Default is 86400 (24 hours). Most providers ignore this and send reports on their own schedule.</li>
        </ul>

        <h2>Subdomain Policy</h2>
        <p>The <code>sp=</code> tag deserves special attention. Many organisations focus on protecting their primary domain but forget that subdomains can also be spoofed. If you do not send email from subdomains, you can set a strict subdomain policy immediately:</p>
        <pre><code class="bg-forest-900 text-lime-400 block rounded-lg p-4 text-sm overflow-x-auto">v=DMARC1; p=none; sp=reject; rua=mailto:your-token@agg.dmarcwatch.io;</code></pre>
        <p>This record monitors the main domain while rejecting all email from subdomains that do not have their own DMARC record. This is a quick win &mdash; attackers frequently spoof subdomains like <code>billing.yourcompany.com</code> or <code>support.yourcompany.com</code> because they are often unprotected.</p>
        <p>If specific subdomains do send email, publish individual DMARC records for them (e.g., <code>_dmarc.marketing.yourcompany.com</code>) with appropriate policies.</p>

        <h2>Common Pitfalls</h2>
        <ul>
            <li><strong>Moving too fast.</strong> Jumping to <code>p=reject</code> before auditing all senders will block legitimate email. Always start with <code>p=none</code> and use DMARCWatch to identify every source.</li>
            <li><strong>Ignoring the <code>pct</code> tag.</strong> The percentage rollout is your safety valve. Use it during every policy transition.</li>
            <li><strong>Forgetting to update after adding services.</strong> Whenever you add a new email-sending service (a new CRM, ticketing system, or newsletter tool), update your SPF and DKIM configuration before the service goes live.</li>
            <li><strong>Not monitoring after reaching reject.</strong> DMARC is not a set-and-forget configuration. Infrastructure changes, new services, and provider updates can break authentication. Continuous monitoring with DMARCWatch ensures you catch issues early.</li>
        </ul>

        <div class="mt-12 pt-8 border-t border-gray-200">
            <a href="/guides" class="text-lime-600 hover:text-lime-700 no-underline">&larr; Back to Guides</a>
        </div>
    </div>
</article>
</x-marketing-layout>
