<x-marketing-layout title="Email Authentication 101 - DMARCWatch" meta-description="A comprehensive overview of email authentication: SPF, DKIM, DMARC, and how they work together to protect your domain.">
<article class="py-20">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 prose prose-lg">
        <a href="/guides" class="text-lime-600 hover:text-lime-700 no-underline">&larr; Back to Guides</a>

        <h1 class="mt-8">Email Authentication 101</h1>
        <p class="lead text-gray-600">A comprehensive overview of email authentication: SPF, DKIM, DMARC, and how they work together to protect your domain.</p>

        <h2>The Problem: Email Spoofing</h2>
        <p>The Simple Mail Transfer Protocol (SMTP) was designed in the early 1980s, long before security was a primary concern. By default, SMTP does not verify the identity of the sender. Any mail server can claim to send email from any domain &mdash; there is nothing in the base protocol to prevent it.</p>
        <p>This means an attacker can craft a message with a <code>From:</code> header that reads <code>ceo@yourcompany.com</code> and deliver it to anyone. The recipient's mail client will display it as though it genuinely came from your CEO. This technique, known as <strong>email spoofing</strong>, is the backbone of phishing, business email compromise (BEC), and brand impersonation attacks.</p>
        <p>Email authentication protocols were developed to close this gap. There are three, and they work as layers that build on each other.</p>

        <h2>The Three Pillars of Email Authentication</h2>

        <h3>1. SPF (Sender Policy Framework)</h3>
        <p>SPF allows a domain owner to declare which mail servers are authorised to send email on behalf of their domain. This declaration is published as a DNS TXT record on the domain.</p>
        <p>When a receiving server gets an email, it checks the <strong>envelope sender</strong> (the Return-Path address used during the SMTP transaction) and looks up the SPF record for that domain. If the sending server's IP address is listed in the SPF record, SPF passes. If not, it fails.</p>
        <p>A simple SPF record looks like this:</p>
        <pre><code class="bg-forest-900 text-lime-400 block rounded-lg p-4 text-sm overflow-x-auto">v=spf1 include:_spf.google.com ip4:203.0.113.5 -all</code></pre>
        <p>SPF is effective but has a limitation: it validates the envelope sender, not the <code>From:</code> header that the user sees. An attacker can use their own domain in the envelope while spoofing yours in the visible header.</p>

        <h3>2. DKIM (DomainKeys Identified Mail)</h3>
        <p>DKIM adds a cryptographic signature to each outgoing email. The sending server signs parts of the message (typically the headers and body) with a private key, and the corresponding public key is published in a DNS TXT record. The receiving server retrieves the public key, verifies the signature, and confirms that the message has not been altered in transit.</p>
        <p>A DKIM signature header in an email looks like this:</p>
        <pre><code class="bg-forest-900 text-lime-400 block rounded-lg p-4 text-sm overflow-x-auto">DKIM-Signature: v=1; a=rsa-sha256; d=yourcompany.com;
  s=selector1; c=relaxed/relaxed;
  h=from:to:subject:date:message-id;
  bh=base64encodedBodyHash;
  b=base64encodedSignature</code></pre>
        <p>DKIM proves that an authorised system signed the message and that the content was not tampered with. However, like SPF, DKIM alone does not tell the receiving server what to do when verification fails.</p>

        <h3>3. DMARC (Domain-based Message Authentication, Reporting, and Conformance)</h3>
        <p>DMARC ties SPF and DKIM together and adds two critical capabilities: a <strong>policy</strong> that tells receivers how to handle authentication failures, and a <strong>reporting mechanism</strong> that gives domain owners visibility into who is sending email as their domain.</p>
        <p>A DMARC record is published as a TXT record at <code>_dmarc.yourdomain.com</code>:</p>
        <pre><code class="bg-forest-900 text-lime-400 block rounded-lg p-4 text-sm overflow-x-auto">v=DMARC1; p=reject; rua=mailto:reports@yourdomain.com; adkim=s; aspf=s;</code></pre>
        <p>When a message arrives, the receiving server evaluates SPF and DKIM results and then checks whether either result <strong>aligns</strong> with the domain in the <code>From:</code> header. If neither aligns, the DMARC policy determines what happens to the message.</p>

        <h2>How They Work Together</h2>
        <p>Think of the three protocols as layers of a security system:</p>
        <ol>
            <li><strong>SPF</strong> checks whether the sending IP is authorised.</li>
            <li><strong>DKIM</strong> checks whether the message is signed and unaltered.</li>
            <li><strong>DMARC</strong> checks whether either SPF or DKIM passes <em>and</em> aligns with the visible From domain, then enforces a policy on failures.</li>
        </ol>
        <p>A message passes DMARC if <strong>at least one</strong> of the following is true:</p>
        <ul>
            <li>SPF passes and the SPF-authenticated domain aligns with the From header domain.</li>
            <li>DKIM passes and the DKIM signing domain (the <code>d=</code> tag) aligns with the From header domain.</li>
        </ul>

        <h2>Understanding Alignment</h2>
        <p>Alignment is the concept that makes DMARC more powerful than SPF or DKIM alone. It ensures that the domain authenticated by SPF or DKIM actually matches the domain the user sees in the <code>From:</code> header.</p>
        <p>There are two alignment modes:</p>
        <ul>
            <li><strong>Relaxed alignment</strong> (the default) &mdash; The authenticated domain and the From domain must share the same organisational domain. For example, <code>mail.yourcompany.com</code> aligns with <code>yourcompany.com</code>.</li>
            <li><strong>Strict alignment</strong> &mdash; The authenticated domain must exactly match the From domain. <code>mail.yourcompany.com</code> would <em>not</em> align with <code>yourcompany.com</code> in strict mode.</li>
        </ul>
        <p>You control alignment mode in your DMARC record with the <code>adkim</code> (for DKIM) and <code>aspf</code> (for SPF) tags. Relaxed alignment is recommended for most organisations because it accommodates subdomains used by third-party services.</p>

        <h2>Why You Need All Three</h2>
        <p>Each protocol has limitations on its own:</p>
        <ul>
            <li><strong>SPF alone</strong> does not protect the visible From header and breaks when emails are forwarded.</li>
            <li><strong>DKIM alone</strong> does not tell receivers what to do on failure and does not prevent replay attacks.</li>
            <li><strong>DMARC alone</strong> is meaningless &mdash; it requires at least SPF or DKIM to exist in order to evaluate alignment.</li>
        </ul>
        <p>Together, they form a complete system: SPF and DKIM provide the authentication checks, and DMARC provides the policy and reporting layer that makes the system actionable.</p>

        <h2>Getting Started</h2>
        <p>If you are new to email authentication, the recommended path is:</p>
        <ol>
            <li>Ensure SPF is configured for your domain. See <a href="/guides/understanding-spf">Understanding SPF Records</a>.</li>
            <li>Set up DKIM signing for all mail sources. See <a href="/guides/dkim-explained">DKIM Explained</a>.</li>
            <li>Publish a DMARC record starting with <code>p=none</code> and a reporting address. See <a href="/guides/getting-started">Getting Started with DMARC</a>.</li>
            <li>Monitor your reports with DMARCWatch, fix issues, and gradually enforce stricter policies. See <a href="/guides/dmarc-policy-guide">DMARC Policy Guide</a>.</li>
        </ol>

        <div class="mt-12 pt-8 border-t border-gray-200">
            <a href="/guides" class="text-lime-600 hover:text-lime-700 no-underline">&larr; Back to Guides</a>
        </div>
    </div>
</article>
</x-marketing-layout>
