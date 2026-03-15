<x-marketing-layout title="DKIM Explained - DMARCWatch" meta-description="Understand DomainKeys Identified Mail, how to set up DKIM signing, and verify your configuration.">
<article class="py-20">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 prose prose-lg">
        <a href="/guides" class="text-lime-600 hover:text-lime-700 no-underline">&larr; Back to Guides</a>

        <h1 class="mt-8">DKIM Explained</h1>
        <p class="lead text-gray-600">Understand DomainKeys Identified Mail, how to set up DKIM signing, and verify your configuration.</p>

        <h2>What Is DKIM?</h2>
        <p>DomainKeys Identified Mail (DKIM), defined in <strong>RFC 6376</strong>, is an email authentication method that allows the sender to attach a cryptographic signature to outgoing messages. This signature proves two things: that the email was authorised by the owner of the signing domain, and that the message content has not been modified after it was signed.</p>
        <p>Unlike SPF, which validates the sending server's IP address, DKIM validates the <strong>message itself</strong>. This makes DKIM resilient to email forwarding &mdash; a forwarded message retains its original DKIM signature and can still be verified by the final recipient's mail server.</p>

        <h2>How DKIM Signing Works</h2>
        <p>DKIM uses public-key cryptography. The process involves two parties: the sending mail server (which holds the private key) and the receiving mail server (which retrieves the public key from DNS).</p>

        <h3>Signing (Outbound)</h3>
        <ol>
            <li>The sending server generates a hash of selected message headers and the body.</li>
            <li>The hash is encrypted using the domain's <strong>private key</strong> to produce a digital signature.</li>
            <li>The signature is added to the email as a <code>DKIM-Signature</code> header.</li>
        </ol>

        <h3>Verification (Inbound)</h3>
        <ol>
            <li>The receiving server reads the <code>DKIM-Signature</code> header and extracts the signing domain (<code>d=</code>) and selector (<code>s=</code>).</li>
            <li>It constructs a DNS query for <code>selector._domainkey.signingdomain.com</code> to retrieve the <strong>public key</strong>.</li>
            <li>It uses the public key to decrypt the signature and compares the result to a freshly computed hash of the message.</li>
            <li>If they match, DKIM passes. If they differ, the message was altered or the signature is invalid.</li>
        </ol>

        <h2>The DKIM Signature Header</h2>
        <p>Every DKIM-signed email contains a header that looks like this:</p>
        <pre><code class="bg-forest-900 text-lime-400 block rounded-lg p-4 text-sm overflow-x-auto">DKIM-Signature: v=1; a=rsa-sha256; d=yourcompany.com;
  s=selector1; c=relaxed/relaxed;
  h=from:to:subject:date:message-id;
  bh=2jUSOH9NhtVGCQWNr9BrIAPreKQjO6Sn7XIkfJVOzv8=;
  b=AuUoFEfDxTDkHlLXSZEpZj79LICEps6eda7W3deTVFOk...</code></pre>
        <p>The key tags are:</p>
        <ul>
            <li><code>v=1</code> &mdash; DKIM version.</li>
            <li><code>a=rsa-sha256</code> &mdash; The signing algorithm. RSA-SHA256 is the most common. Ed25519 (<code>a=ed25519-sha256</code>) is a newer, faster alternative.</li>
            <li><code>d=yourcompany.com</code> &mdash; The signing domain. For DMARC alignment, this must match (or be a subdomain of) the From header domain.</li>
            <li><code>s=selector1</code> &mdash; The selector, used to locate the correct public key in DNS.</li>
            <li><code>c=relaxed/relaxed</code> &mdash; The canonicalization method for headers and body. "Relaxed" tolerates minor whitespace changes; "simple" requires an exact match.</li>
            <li><code>h=</code> &mdash; The list of headers included in the signature. The <code>From</code> header is always required.</li>
            <li><code>bh=</code> &mdash; The hash of the message body.</li>
            <li><code>b=</code> &mdash; The signature itself (base64-encoded).</li>
        </ul>

        <h2>DKIM DNS Records and Selectors</h2>
        <p>The public key is published as a TXT record at a specific location in DNS. The location is determined by the <strong>selector</strong>:</p>
        <pre><code class="bg-forest-900 text-lime-400 block rounded-lg p-4 text-sm overflow-x-auto">selector1._domainkey.yourcompany.com  TXT  "v=DKIM1; k=rsa; p=MIGfMA0GCSq..."</code></pre>
        <p>The record contains:</p>
        <ul>
            <li><code>v=DKIM1</code> &mdash; Identifies this as a DKIM key record.</li>
            <li><code>k=rsa</code> &mdash; The key type (RSA is standard; Ed25519 is also supported).</li>
            <li><code>p=</code> &mdash; The public key, base64-encoded. If this field is empty, the key has been revoked.</li>
        </ul>

        <h3>Why Selectors Exist</h3>
        <p>Selectors allow a single domain to have multiple active DKIM keys simultaneously. This is essential for several reasons:</p>
        <ul>
            <li><strong>Multiple mail sources.</strong> Your primary mail server, your marketing platform, and your transactional email service can each have their own selector and key pair.</li>
            <li><strong>Key rotation.</strong> You can publish a new key under a new selector, switch your signing infrastructure to use it, and then remove the old key &mdash; all without any gap in authentication.</li>
        </ul>
        <p>Common selector naming conventions include <code>selector1</code>, <code>s1</code>, <code>google</code>, <code>k1</code>, or date-based names like <code>202601</code>.</p>

        <h2>Key Rotation</h2>
        <p>DKIM private keys should be rotated periodically to limit the impact of a compromised key. A recommended rotation schedule is every 6 to 12 months. The process is:</p>
        <ol>
            <li>Generate a new key pair.</li>
            <li>Publish the new public key in DNS under a new selector.</li>
            <li>Wait for DNS propagation (allow 24-48 hours).</li>
            <li>Configure your mail server to sign with the new private key and selector.</li>
            <li>Keep the old public key in DNS for a transition period (at least 7 days) so that messages signed before the switch can still be verified.</li>
            <li>Remove the old public key from DNS.</li>
        </ol>
        <p>Use a key size of at least <strong>2048 bits</strong> for RSA keys. The older 1024-bit keys are considered weak and should be upgraded. Note that 2048-bit keys may need to be split across multiple DNS strings if your provider has a 255-character TXT record limit.</p>

        <h2>Common DKIM Issues</h2>
        <ul>
            <li><strong>Missing DNS record.</strong> If the public key is not published or is published at the wrong selector path, DKIM verification will fail with a "no key found" error.</li>
            <li><strong>Body modification by intermediaries.</strong> Mailing list software, forwarding services, or security gateways that alter the message body after signing will break the DKIM signature. Using <code>c=relaxed/relaxed</code> canonicalization helps tolerate minor changes, but significant modifications will still cause failure.</li>
            <li><strong>Misaligned signing domain.</strong> For DMARC to pass via DKIM, the <code>d=</code> domain in the signature must align with the From header domain. If your email service signs with their own domain (e.g., <code>d=sendgrid.net</code>) instead of yours, DKIM will pass but DMARC alignment will fail. Most providers support custom DKIM signing &mdash; configure it to sign as your domain.</li>
            <li><strong>Expired or revoked keys.</strong> If a public key record has an empty <code>p=</code> tag, the key has been revoked and all signatures using that selector will fail.</li>
            <li><strong>DNS record formatting errors.</strong> Extra spaces, missing quotes around concatenated strings, or incorrect escaping can cause the public key to be unparseable.</li>
        </ul>

        <h2>Verifying Your DKIM Setup</h2>
        <p>To confirm DKIM is working, send a test email from your domain and inspect the headers at the receiving end. Look for the <code>DKIM-Signature</code> header and the authentication results:</p>
        <pre><code class="bg-forest-900 text-lime-400 block rounded-lg p-4 text-sm overflow-x-auto">Authentication-Results: mx.google.com;
  dkim=pass header.d=yourcompany.com header.s=selector1;</code></pre>
        <p>You can also query your DKIM public key directly:</p>
        <pre><code class="bg-forest-900 text-lime-400 block rounded-lg p-4 text-sm overflow-x-auto">dig TXT selector1._domainkey.yourcompany.com +short</code></pre>
        <p>DMARCWatch will show you DKIM pass and fail rates for every source sending as your domain, making it straightforward to spot signing issues across all your mail streams.</p>

        <div class="mt-12 pt-8 border-t border-gray-200">
            <a href="/guides" class="text-lime-600 hover:text-lime-700 no-underline">&larr; Back to Guides</a>
        </div>
    </div>
</article>
</x-marketing-layout>
