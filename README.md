# DMARCWatch

DMARC management SaaS application — monitor email authentication (DMARC, SPF, DKIM), receive and parse aggregate reports, identify sending sources, score domain compliance, and alert on issues.

Built with Laravel 12, Livewire, Jetstream (Teams), Tailwind CSS, and Stripe billing.

## Requirements

- PHP 8.2+
- Composer
- Node.js 18+ & npm
- SQLite (default) or MySQL/PostgreSQL

## Local Development Setup

### 1. Clone and install dependencies

```bash
git clone https://github.com/tombeech/dmarcwatch.git
cd dmarcwatch
composer install
npm install
```

### 2. Environment configuration

```bash
cp .env.example .env
php artisan key:generate
```

### 3. Database

SQLite is the default. Create the database file and run migrations:

```bash
touch database/database.sqlite
php artisan migrate
php artisan db:seed   # Seeds known sending sources (Google, Microsoft, SendGrid, etc.)
```

### 4. Start the dev server

```bash
composer dev
```

This starts all services concurrently:
- Laravel server on `http://localhost:8688`
- Laravel Horizon (queue worker)
- Laravel Scheduler
- Laravel Pail (log viewer)
- Vite dev server on port 5174

Alternatively, run each individually:

```bash
php artisan serve --port=8688
php artisan horizon
npm run dev
```

### 5. Build frontend for production

```bash
npm run build
```

---

## External Services Setup

### Stripe (Billing)

DMARCWatch uses Laravel Cashier for subscription billing with three tiers (Free, Pro, Enterprise) in three currencies (USD, GBP, EUR).

1. Create a [Stripe account](https://dashboard.stripe.com/register)
2. Create products and prices in the Stripe dashboard:

| Product | Billing | You need a price ID for each currency (USD/GBP/EUR) |
|---------|---------|-----------------------------------------------------|
| Pro | Monthly | `STRIPE_PRO_MONTHLY_PRICE_{USD,GBP,EUR}` |
| Pro | Yearly | `STRIPE_PRO_YEARLY_PRICE_{USD,GBP,EUR}` |
| Enterprise | Monthly | `STRIPE_ENTERPRISE_MONTHLY_PRICE_{USD,GBP,EUR}` |
| Enterprise | Yearly | `STRIPE_ENTERPRISE_YEARLY_PRICE_{USD,GBP,EUR}` |
| Domain Add-on | Monthly | `STRIPE_DOMAIN_ADDON_PRICE_{USD,GBP,EUR}` |

3. Set these in `.env`:

```env
STRIPE_KEY=pk_test_...
STRIPE_SECRET=sk_test_...
CASHIER_CURRENCY=usd

# Repeat for each currency (USD, GBP, EUR)
STRIPE_PRO_MONTHLY_PRICE_USD=price_xxxxx
STRIPE_PRO_YEARLY_PRICE_USD=price_xxxxx
STRIPE_ENTERPRISE_MONTHLY_PRICE_USD=price_xxxxx
STRIPE_ENTERPRISE_YEARLY_PRICE_USD=price_xxxxx
STRIPE_DOMAIN_ADDON_PRICE_USD=price_xxxxx
# ... GBP and EUR variants
```

4. Set up the Stripe webhook in the Stripe dashboard to point at `https://yourdomain.com/stripe/webhook` and add the signing secret to `.env` as `STRIPE_WEBHOOK_SECRET`.

### Mailgun (Inbound DMARC Reports)

DMARC aggregate reports are received via email. Each monitored domain gets a unique RUA address like `abc12345@reports.dmarcwatch.app`. Mailgun handles inbound routing.

1. Create a [Mailgun account](https://www.mailgun.com/)
2. Add and verify your inbound domain (e.g., `reports.dmarcwatch.app`)
3. Set up MX records for the inbound domain pointing to Mailgun
4. Create an inbound route in Mailgun:
   - **Expression:** `match_recipient(".*@reports.dmarcwatch.app")`
   - **Action:** `forward("https://yourdomain.com/webhooks/inbound-report")` and `stop()`
5. Set environment variables:

```env
DMARCWATCH_INBOUND_DOMAIN=reports.dmarcwatch.app
MAILGUN_INBOUND_SIGNING_KEY=your-mailgun-inbound-signing-key
```

The signing key is found under **Mailgun Dashboard > Settings > API Security > Inbound Signing Key** (this is different from the API key).

### Mail (Outbound — Alerts, Digests, Welcome Emails)

Configure any mail driver. For production, use Mailgun, Postmark, SES, etc.:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailgun.org
MAIL_PORT=587
MAIL_USERNAME=postmaster@yourdomain.com
MAIL_PASSWORD=your-smtp-password
MAIL_FROM_ADDRESS=hello@dmarcwatch.app
MAIL_FROM_NAME=DMARCWatch
```

For local development, `MAIL_MAILER=log` writes emails to `storage/logs/`.

---

## Queue & Background Jobs

DMARCWatch uses Laravel Horizon to manage queues. The following jobs run automatically:

| Job | Queue | Schedule |
|-----|-------|----------|
| `ScheduleDnsChecks` | default | Every minute |
| `CheckDomainDns` | dns-checking | Dispatched by ScheduleDnsChecks |
| `ProcessInboundReport` | report-processing | Dispatched on inbound webhook |
| `SendAlertNotification` | alerts | Dispatched by AlertDispatcher |
| `SendWeeklyDigests` | default | Mondays at 09:00 |
| `PruneOldReports` | default | Daily |

### Horizon Setup

Publish the Horizon config if you need to customize queue workers:

```bash
php artisan vendor:publish --provider="Laravel\Horizon\HorizonServiceProvider"
```

Horizon dashboard is available at `/horizon` (restricted to local environment by default).

For production, run Horizon as a daemon process (use Supervisor or similar):

```bash
php artisan horizon
```

---

## DNS Configuration (Production)

For the production domain (`dmarcwatch.app`):

1. **A/CNAME record** — Point your domain to your server
2. **MX records** for `reports.dmarcwatch.app` — Point to Mailgun's MX servers
3. **SPF record** for outbound email — Include your mail provider
4. **DKIM** — Set up via your mail provider

---

## Plan Limits

| Feature | Free | Pro | Enterprise |
|---------|------|-----|------------|
| Domains | 1 | 10 | Unlimited |
| Reports/month | 100 | 10,000 | Unlimited |
| Retention | 7 days | 90 days | 365 days |
| DNS check interval | 1440 min (daily) | 60 min | 15 min |
| Alert channels | 1 | 5 | Unlimited |
| Weekly digests | No | Yes | Yes |
| API access | No | Yes | Yes |

---

## API

Authenticated API available at `/api/v1/` using Sanctum tokens. Public lookup endpoints (rate-limited 30/min):

```
GET  /api/v1/lookup/dmarc?domain=example.com
GET  /api/v1/lookup/spf?domain=example.com
GET  /api/v1/lookup/dkim?domain=example.com&selector=google
```

Authenticated endpoints (require API token + Pro/Enterprise plan):

```
GET/POST       /api/v1/domains
GET/PUT/DELETE /api/v1/domains/{id}
POST           /api/v1/domains/{id}/check-dns
GET            /api/v1/domains/{id}/reports
GET            /api/v1/reports
GET            /api/v1/reports/{id}
CRUD           /api/v1/alert-channels
CRUD           /api/v1/alert-rules
```

---

## Testing

```bash
# Run full test suite (111 tests)
./vendor/bin/pest

# Run static analysis
./vendor/bin/phpstan analyse

# Run code formatting
./vendor/bin/pint
```

---

## Project Structure

```
app/
├── Enums/           # SubscriptionPlan, DmarcPolicy, AlertChannelType, etc.
├── Http/
│   ├── Controllers/ # API v1 controllers + inbound webhook
│   ├── Middleware/   # Team scope, plan limits, API access, webhook verification
│   └── Resources/   # API JSON resources
├── Jobs/            # Report processing, DNS checks, alerts, digests, pruning
├── Livewire/        # Dashboard, Onboarding, Domains, Reports, Sources, Tools, Alerts, Billing
├── Mail/            # WelcomeEmail, DmarcAlertEmail, WeeklyDigest, SubscriptionChangeEmail
├── Models/          # Domain, DmarcReport, ReportRecord, SendingSource, AlertChannel, etc.
└── Services/        # DmarcAnalyzer, SpfAnalyzer, DkimVerifier, ReportParser, ComplianceScorer, etc.

config/dmarcwatch.php  # Stripe prices, trial days, DNS resolvers, inbound email config
routes/
├── web.php           # Marketing pages + authenticated app routes
├── api.php           # Public lookups + authenticated API v1
└── console.php       # Scheduled jobs
```

---

## Environment Variables Reference

| Variable | Required | Description |
|----------|----------|-------------|
| `APP_KEY` | Yes | Generated by `php artisan key:generate` |
| `APP_URL` | Yes | Your application URL |
| `DB_CONNECTION` | Yes | `sqlite`, `mysql`, or `pgsql` |
| `STRIPE_KEY` | For billing | Stripe publishable key |
| `STRIPE_SECRET` | For billing | Stripe secret key |
| `STRIPE_WEBHOOK_SECRET` | For billing | Stripe webhook signing secret |
| `STRIPE_*_PRICE_*` | For billing | 15 Stripe price IDs (5 products x 3 currencies) |
| `DMARCWATCH_INBOUND_DOMAIN` | For reports | Domain for receiving DMARC reports |
| `MAILGUN_INBOUND_SIGNING_KEY` | For reports | Mailgun webhook signature verification |
| `DMARCWATCH_TRIAL_DAYS` | No | Trial period in days (default: 14) |
| `MAIL_*` | For emails | Outbound mail configuration |
| `QUEUE_CONNECTION` | No | `database` (default), `redis`, or `sync` |
