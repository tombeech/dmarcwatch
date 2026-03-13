@extends('layouts.marketing')
@section('title', 'Reading Aggregate Reports — DMARCWatch')

@section('content')
<article class="py-20">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 prose prose-lg">
        <a href="/guides" class="text-lime-600 hover:text-lime-700 no-underline">&larr; Back to Guides</a>

        <h1 class="mt-8">Reading DMARC Aggregate Reports</h1>
        <p class="lead text-gray-600">How to interpret DMARC aggregate report data &mdash; sources, pass rates, disposition, and alignment.</p>

        <h2>What Are Aggregate Reports?</h2>
        <p>When you publish a DMARC record with a <code>rua=</code> tag, receiving mail servers send you <strong>aggregate reports</strong> (also called RUA reports). These are XML files, typically compressed as ZIP or GZIP attachments, that summarise authentication results for all email they received claiming to be from your domain during a reporting period (usually 24 hours).</p>
        <p>Aggregate reports do not contain message content or subject lines. They contain metadata: which IP addresses sent email as your domain, how many messages each sent, and whether those messages passed or failed SPF, DKIM, and DMARC.</p>

        <h2>XML Structure Overview</h2>
        <p>A DMARC aggregate report has two main sections: metadata about the report itself, and one or more records describing mail sources. Here is a simplified example:</p>
        <pre><code class="bg-forest-900 text-lime-400 block rounded-lg p-4 text-sm overflow-x-auto">&lt;feedback&gt;
  &lt;report_metadata&gt;
    &lt;org_name&gt;google.com&lt;/org_name&gt;
    &lt;email&gt;noreply-dmarc-support@google.com&lt;/email&gt;
    &lt;report_id&gt;17293847561234567890&lt;/report_id&gt;
    &lt;date_range&gt;
      &lt;begin&gt;1709164800&lt;/begin&gt;
      &lt;end&gt;1709251199&lt;/end&gt;
    &lt;/date_range&gt;
  &lt;/report_metadata&gt;

  &lt;policy_published&gt;
    &lt;domain&gt;yourcompany.com&lt;/domain&gt;
    &lt;adkim&gt;r&lt;/adkim&gt;
    &lt;aspf&gt;r&lt;/aspf&gt;
    &lt;p&gt;none&lt;/p&gt;
    &lt;sp&gt;none&lt;/sp&gt;
    &lt;pct&gt;100&lt;/pct&gt;
  &lt;/policy_published&gt;

  &lt;record&gt;
    &lt;row&gt;
      &lt;source_ip&gt;209.85.220.41&lt;/source_ip&gt;
      &lt;count&gt;1524&lt;/count&gt;
      &lt;policy_evaluated&gt;
        &lt;disposition&gt;none&lt;/disposition&gt;
        &lt;dkim&gt;pass&lt;/dkim&gt;
        &lt;spf&gt;pass&lt;/spf&gt;
      &lt;/policy_evaluated&gt;
    &lt;/row&gt;
    &lt;identifiers&gt;
      &lt;header_from&gt;yourcompany.com&lt;/header_from&gt;
    &lt;/identifiers&gt;
    &lt;auth_results&gt;
      &lt;dkim&gt;
        &lt;domain&gt;yourcompany.com&lt;/domain&gt;
        &lt;result&gt;pass&lt;/result&gt;
        &lt;selector&gt;selector1&lt;/selector&gt;
      &lt;/dkim&gt;
      &lt;spf&gt;
        &lt;domain&gt;yourcompany.com&lt;/domain&gt;
        &lt;result&gt;pass&lt;/result&gt;
      &lt;/spf&gt;
    &lt;/auth_results&gt;
  &lt;/record&gt;
&lt;/feedback&gt;</code></pre>

        <h2>Understanding Source IPs</h2>
        <p>The <code>&lt;source_ip&gt;</code> field tells you the IP address of the server that delivered the message. This is the single most important field for identifying who is sending email as your domain. Each unique IP address appears as a separate record with a <code>&lt;count&gt;</code> of how many messages it sent during the reporting period.</p>
        <p>To interpret source IPs effectively:</p>
        <ul>
            <li><strong>Look up the IP owner.</strong> Use a reverse DNS lookup or WHOIS query to determine who owns the IP. DMARCWatch does this automatically and displays the organisation name alongside each IP.</li>
            <li><strong>Match IPs to known services.</strong> Google Workspace, Microsoft 365, Amazon SES, SendGrid, Mailchimp, and other major services send from well-known IP ranges. If you recognise the owner as a service you use, the traffic is likely legitimate.</li>
            <li><strong>Flag unknown senders.</strong> IPs that belong to hosting providers, VPS services, or unknown organisations &mdash; especially those failing authentication &mdash; are often spoofing attempts or misconfigured systems.</li>
        </ul>

        <h2>Interpreting SPF and DKIM Results</h2>
        <p>Each record contains two sets of authentication results that are important to understand separately:</p>

        <h3>Raw Authentication Results (<code>&lt;auth_results&gt;</code>)</h3>
        <p>These show whether SPF and DKIM passed on their own terms, regardless of alignment:</p>
        <ul>
            <li><code>&lt;spf&gt;&lt;result&gt;pass&lt;/result&gt;</code> &mdash; The sending IP was listed in the SPF record of the envelope sender domain.</li>
            <li><code>&lt;dkim&gt;&lt;result&gt;pass&lt;/result&gt;</code> &mdash; A valid DKIM signature was found for the specified domain and selector.</li>
        </ul>
        <p>Possible result values include <code>pass</code>, <code>fail</code>, <code>softfail</code>, <code>neutral</code>, <code>temperror</code> (temporary DNS error), <code>permerror</code> (permanent error, such as a malformed record), and <code>none</code> (no record found).</p>

        <h3>Policy Evaluation (<code>&lt;policy_evaluated&gt;</code>)</h3>
        <p>This section shows the DMARC-level result after alignment is considered:</p>
        <ul>
            <li><code>&lt;spf&gt;pass&lt;/spf&gt;</code> &mdash; SPF passed <strong>and</strong> the SPF domain aligns with the From header domain.</li>
            <li><code>&lt;dkim&gt;pass&lt;/dkim&gt;</code> &mdash; DKIM passed <strong>and</strong> the DKIM signing domain aligns with the From header domain.</li>
        </ul>
        <p>A message passes DMARC if either the SPF or DKIM policy evaluation shows <code>pass</code>. Both do not need to pass &mdash; one aligned pass is sufficient.</p>

        <h2>Disposition Values</h2>
        <p>The <code>&lt;disposition&gt;</code> field tells you what the receiving server actually did with the message based on your published DMARC policy:</p>
        <ul>
            <li><code>none</code> &mdash; The message was delivered normally. This is expected when your policy is <code>p=none</code>, or when the message passed DMARC.</li>
            <li><code>quarantine</code> &mdash; The message was placed in the recipient's spam or junk folder.</li>
            <li><code>reject</code> &mdash; The message was rejected and not delivered at all.</li>
        </ul>
        <p>Note that the disposition reflects what the receiving server chose to do, which may not always match your published policy exactly. Some receivers apply local overrides &mdash; for example, a message might show <code>disposition=none</code> even when your policy is <code>p=quarantine</code> if the receiver decided the message was legitimate based on other signals.</p>

        <h2>Identifying Legitimate vs. Unauthorised Senders</h2>
        <p>When reviewing your aggregate report data in DMARCWatch, apply this framework to each source IP:</p>

        <h3>Legitimate and Properly Configured</h3>
        <p>The source IP belongs to a known service, and both SPF and DKIM pass with alignment. No action needed &mdash; this is the ideal state.</p>

        <h3>Legitimate but Misconfigured</h3>
        <p>The source IP belongs to a service you use, but SPF or DKIM is failing. Common causes include:</p>
        <ul>
            <li>The service's sending IPs are not in your SPF record. Add the appropriate <code>include</code> mechanism.</li>
            <li>DKIM is not configured for the service, or it is signing with the service's own domain instead of yours. Set up custom DKIM signing in the service's settings.</li>
            <li>The service uses an envelope sender domain that does not align with your From domain. Check the service's documentation for custom return-path settings.</li>
        </ul>

        <h3>Unauthorised Senders</h3>
        <p>The source IP belongs to an unknown hosting provider, VPS, or residential ISP, and authentication is failing. These are likely spoofing attempts. Once your policy is set to <code>p=reject</code>, these messages will be blocked automatically.</p>

        <h3>Forwarded Mail</h3>
        <p>Mail forwarding (e.g., university alumni addresses, legacy domain redirects) often breaks SPF because the forwarding server's IP is not in the original sender's SPF record. DKIM typically survives forwarding if the message body is not modified. These sources will show SPF fail but DKIM pass. If DKIM alignment passes, DMARC still passes. If you see legitimate forwarded mail failing both, consider the impact before tightening your policy.</p>

        <h2>Using DMARCWatch to Simplify This</h2>
        <p>Reading raw XML reports is tedious and error-prone. DMARCWatch parses all incoming reports automatically and presents the data as clear, actionable dashboards. You can see at a glance which sources pass or fail, track your authentication rates over time, and identify the specific issues that need to be fixed before you can safely move to a stricter policy.</p>
        <p>For more on the policy progression, see our <a href="/guides/dmarc-policy-guide">DMARC Policy Guide</a>. For help setting up authentication for your sending sources, see <a href="/guides/understanding-spf">Understanding SPF Records</a> and <a href="/guides/dkim-explained">DKIM Explained</a>.</p>

        <div class="mt-12 pt-8 border-t border-gray-200">
            <a href="/guides" class="text-lime-600 hover:text-lime-700 no-underline">&larr; Back to Guides</a>
        </div>
    </div>
</article>
@endsection