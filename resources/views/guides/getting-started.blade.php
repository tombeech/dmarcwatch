<x-marketing-layout title="Getting Started with DMARC - DMARCWatch" meta-description="A beginner-friendly introduction to DMARC and how to set up monitoring with DMARCWatch.">
<article class="py-20">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 prose prose-lg">
        <a href="/guides" class="text-lime-600 hover:text-lime-700 no-underline">&larr; Back to Guides</a>

        <h1 class="mt-8">Getting Started with DMARC</h1>
        <p class="lead text-gray-600">A beginner-friendly introduction to DMARC and how to set up monitoring with DMARCWatch.</p>

        <h2>What Is DMARC?</h2>
        <p>DMARC stands for <strong>Domain-based Message Authentication, Reporting, and Conformance</strong>. It is an email authentication protocol that builds on two existing mechanisms &mdash; SPF (Sender Policy Framework) and DKIM (DomainKeys Identified Mail) &mdash; to give domain owners control over what happens when an email fails authentication checks.</p>
        <p>In practical terms, DMARC lets you publish a DNS record that tells receiving mail servers: "Here is how to verify that messages claiming to come from my domain are legitimate, and here is what to do if they are not."</p>

        <h2>Why DMARC Matters</h2>
        <p>Without DMARC, anyone can send email that appears to come from your domain. This is called <strong>email spoofing</strong>, and it is the foundation of most phishing attacks. DMARC protects your organisation in three important ways:</p>
        <ul>
            <li><strong>Prevents brand abuse.</strong> Attackers cannot impersonate your domain to trick your customers, partners, or employees.</li>
            <li><strong>Improves deliverability.</strong> Mailbox providers like Google and Microsoft give preferential treatment to domains with properly configured authentication. A strong DMARC policy signals that your domain is trustworthy.</li>
            <li><strong>Provides visibility.</strong> DMARC aggregate reports show you every IP address that is sending email on behalf of your domain, so you can identify unauthorised senders and misconfigured services.</li>
        </ul>

        <h2>How DMARCWatch Helps</h2>
        <p>DMARC aggregate reports arrive as machine-readable XML files, often compressed, and they can be difficult to interpret on their own. DMARCWatch receives these reports, parses them, and presents the data in a clear dashboard so you can:</p>
        <ul>
            <li>See which IP addresses are sending mail as your domain.</li>
            <li>Monitor SPF and DKIM pass rates over time.</li>
            <li>Identify unauthorised senders quickly.</li>
            <li>Gain the confidence you need to move from a monitoring policy to a strict enforcement policy.</li>
        </ul>

        <h2>Step-by-Step: Setting Up Your First Domain</h2>

        <h3>Step 1: Create Your DMARCWatch Account</h3>
        <p>Sign up at DMARCWatch and add your first domain from the dashboard. DMARCWatch will provide you with a unique reporting address in the format <code>your-token@agg.dmarcwatch.io</code>. This is the address that will receive your DMARC aggregate reports.</p>

        <h3>Step 2: Publish a DMARC DNS Record</h3>
        <p>Log into the DNS management console for your domain (your registrar or hosting provider) and create a new TXT record with the following details:</p>
        <ul>
            <li><strong>Host / Name:</strong> <code>_dmarc</code></li>
            <li><strong>Type:</strong> TXT</li>
            <li><strong>Value:</strong> Your DMARC policy string (see below)</li>
        </ul>
        <p>Here is a recommended starting record that enables monitoring without affecting mail delivery:</p>
        <pre><code class="bg-forest-900 text-lime-400 block rounded-lg p-4 text-sm overflow-x-auto">v=DMARC1; p=none; rua=mailto:your-token@agg.dmarcwatch.io; fo=1;</code></pre>
        <p>Let's break down each tag:</p>
        <ul>
            <li><code>v=DMARC1</code> &mdash; Identifies this as a DMARC record. This tag is required and must be first.</li>
            <li><code>p=none</code> &mdash; The policy. <code>none</code> means "take no action on failing messages; just send me reports." This is the correct starting point.</li>
            <li><code>rua=mailto:your-token@agg.dmarcwatch.io</code> &mdash; The address where aggregate reports should be sent. Replace this with the address DMARCWatch assigns to your domain.</li>
            <li><code>fo=1</code> &mdash; Failure reporting option. The value <code>1</code> requests reports when either SPF or DKIM fails, giving you maximum visibility.</li>
        </ul>

        <h3>Step 3: Verify DNS Propagation</h3>
        <p>DNS changes can take anywhere from a few minutes to 48 hours to propagate. You can verify your record is live by running the following command in a terminal:</p>
        <pre><code class="bg-forest-900 text-lime-400 block rounded-lg p-4 text-sm overflow-x-auto">dig TXT _dmarc.yourdomain.com +short</code></pre>
        <p>You should see your DMARC policy string in the output. DMARCWatch also provides a DNS verification check in the dashboard to confirm your record is correctly configured.</p>

        <h3>Step 4: Wait for Reports</h3>
        <p>Aggregate reports are generated by receiving mail servers and are typically sent once every 24 hours. After publishing your DMARC record, you should start seeing reports in DMARCWatch within 24 to 72 hours, depending on your email volume and which providers your recipients use. Google and Microsoft are usually the fastest reporters.</p>

        <h3>Step 5: Analyse and Take Action</h3>
        <p>Once reports begin arriving, review them in your DMARCWatch dashboard. Look for:</p>
        <ul>
            <li><strong>Legitimate senders failing authentication.</strong> These might be third-party services (marketing platforms, CRM tools, transactional email providers) that send on your behalf but are not yet configured with proper SPF or DKIM. Update their DNS records before tightening your policy.</li>
            <li><strong>Unknown sources.</strong> IP addresses you do not recognise sending as your domain are likely unauthorised. DMARC enforcement will block these.</li>
        </ul>
        <p>When you are confident that all legitimate senders pass authentication, you can begin moving your policy from <code>p=none</code> to <code>p=quarantine</code> and eventually to <code>p=reject</code>. See our <a href="/guides/dmarc-policy-guide">DMARC Policy Guide</a> for a detailed progression strategy.</p>

        <h2>What Comes Next</h2>
        <p>Setting up DMARC is the first step toward a fully protected domain. To deepen your understanding, we recommend reading these companion guides:</p>
        <ul>
            <li><a href="/guides/email-authentication-101">Email Authentication 101</a> &mdash; How SPF, DKIM, and DMARC work together.</li>
            <li><a href="/guides/understanding-spf">Understanding SPF Records</a> &mdash; How to build and optimise your SPF record.</li>
            <li><a href="/guides/dkim-explained">DKIM Explained</a> &mdash; How DKIM signing and verification work.</li>
            <li><a href="/guides/reading-aggregate-reports">Reading Aggregate Reports</a> &mdash; How to interpret the data DMARCWatch shows you.</li>
        </ul>

        <div class="mt-12 pt-8 border-t border-gray-200">
            <a href="/guides" class="text-lime-600 hover:text-lime-700 no-underline">&larr; Back to Guides</a>
        </div>
    </div>
</article>
</x-marketing-layout>
