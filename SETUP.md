# DMARCWatch Setup Guide

## System Requirements

- **PHP** 8.2+ with extensions: PDO, OpenSSL, Ctype, JSON, Mbstring, Tokenizer, XML, cURL
- **Node.js** 18+ with npm
- **Composer** (PHP dependency manager)
- **Database**: SQLite (default), MySQL 5.7+, or PostgreSQL 10+
- **Redis** (optional but recommended for queues/cache)

---

## Quick Start (Local Development)

```bash
git clone https://github.com/tombeech/dmarcwatch.git
cd dmarcwatch

# Install dependencies
composer install
npm install

# Configure environment
cp .env.example .env
php artisan key:generate

# Database setup
php artisan migrate
php artisan db:seed       # Seeds known sending sources (Google, Microsoft, etc.)

# Build frontend assets
npm run build

# Start all services
composer dev
```

`composer dev` starts all of these concurrently:
- Laravel server on `http://localhost:8688`
- Laravel Horizon (queue worker)
- Scheduler (`php artisan schedule:work`)
- Log viewer (`php artisan pail`)
- Vite dev server on port 5174

---

## Environment Variables

Copy `.env.example` to `.env` and configure the following sections.

### Core Application

| Variable | Default | Description |
|----------|---------|-------------|
| `APP_NAME` | DMARCWatch | Application name |
| `APP_ENV` | local | Environment: `local`, `staging`, `production` |
| `APP_KEY` | — | Generated via `php artisan key:generate` |
| `APP_DEBUG` | true | Set to `false` in production |
| `APP_URL` | http://localhost:8688 | Base application URL |

### Database

| Variable | Default | Description |
|----------|---------|-------------|
| `DB_CONNECTION` | sqlite | Driver: `sqlite`, `mysql`, `pgsql` |
| `DB_HOST` | 127.0.0.1 | Database host (MySQL/PostgreSQL) |
| `DB_PORT` | 3306 | Database port |
| `DB_DATABASE` | dmarcwatch | Database name (or path for SQLite) |
| `DB_USERNAME` | root | Database username |
| `DB_PASSWORD` | — | Database password |

For SQLite, create the file: `touch database/database.sqlite`

### Queue, Cache & Sessions

| Variable | Default | Description |
|----------|---------|-------------|
| `QUEUE_CONNECTION` | database | `database`, `redis`, or `sync` |
| `CACHE_STORE` | database | `database`, `redis`, or `file` |
| `SESSION_DRIVER` | database | `database`, `redis`, or `file` |
| `SESSION_LIFETIME` | 120 | Session timeout in minutes |

### Redis (if using)

| Variable | Default | Description |
|----------|---------|-------------|
| `REDIS_HOST` | 127.0.0.1 | Redis server host |
| `REDIS_PORT` | 6379 | Redis server port |
| `REDIS_PASSWORD` | null | Redis password |

Install Redis on macOS:
```bash
brew install redis
brew services start redis
pecl install redis     # PHP extension
```

### Outbound Mail

| Variable | Default | Description |
|----------|---------|-------------|
| `MAIL_MAILER` | log | `log` (dev), `smtp`, `mailgun`, `postmark`, `ses` |
| `MAIL_HOST` | 127.0.0.1 | SMTP host |
| `MAIL_PORT` | 2525 | SMTP port |
| `MAIL_USERNAME` | null | SMTP username |
| `MAIL_PASSWORD` | null | SMTP password |
| `MAIL_FROM_ADDRESS` | hello@dmarcwatch.app | Sender email |
| `MAIL_FROM_NAME` | DMARCWatch | Sender name |

Used for: welcome emails, subscription notifications, weekly digests, and email-based alerts.

---

## Third-Party Services

### Stripe (Billing)

Required for subscription management and billing.

#### 1. Create Stripe Account
Sign up at https://dashboard.stripe.com

#### 2. Create Products & Prices

Create 5 products in Stripe. Each product has a single price with **multi-currency** pricing (USD, GBP, EUR configured on the same price):

| Product | USD | GBP | EUR |
|---------|-----|-----|-----|
| Pro Monthly | $24/mo | £19/mo | €22/mo |
| Pro Yearly | $240/yr | £190/yr | €220/yr |
| Enterprise Monthly | $79/mo | £59/mo | €69/mo |
| Enterprise Yearly | $790/yr | £590/yr | €690/yr |
| Domain Add-on | $20/mo | £15/mo | €18/mo |

Each product = 1 price ID (Stripe handles currency conversion automatically).

#### 3. Set Environment Variables

```env
STRIPE_KEY=pk_test_xxxxx              # Publishable key
STRIPE_SECRET=sk_test_xxxxx           # Secret key
STRIPE_WEBHOOK_SECRET=whsec_xxxxx     # Webhook signing secret

# Stripe Price IDs (each price has multi-currency built in)
STRIPE_PRO_MONTHLY_PRICE=price_xxxxx
STRIPE_ENTERPRISE_MONTHLY_PRICE=price_xxxxx
STRIPE_DOMAIN_ADDON_PRICE=price_xxxxx
```

#### 4. Configure Webhook
- URL: `https://yourdomain.com/stripe/webhook`
- Events: `customer.subscription.*`, `invoice.*`, `charge.*`
- Copy the signing secret to `STRIPE_WEBHOOK_SECRET`

#### Plan Limits

| Feature | Free | Pro | Enterprise |
|---------|------|-----|------------|
| Domains | 3 | 50 | 100 (+25 per add-on) |
| Reports/month | 100 | Unlimited | Unlimited |
| Retention | 30 days | 365 days | Unlimited |
| Alert channels | 1 | 5 | Unlimited |
| Team members | 1 | 5 | Unlimited |
| DNS check interval | 24 hours | 1 hour | 15 minutes |
| API access | No | Yes | Yes |
| Slack/Webhooks | No | Yes | Yes |
| Weekly digests | No | Yes | Yes |

Trial period: 14 days (configurable via `DMARCWATCH_TRIAL_DAYS`)

---

### Mailgun (Inbound DMARC Reports)

Required for receiving DMARC aggregate reports via email.

#### 1. Create Mailgun Account
Sign up at https://www.mailgun.com

#### 2. Add Inbound Domain
Register a subdomain for receiving reports, e.g. `reports.dmarcwatch.app`

#### 3. Configure DNS
Add MX records pointing to Mailgun (provided in their dashboard):
```
MX  reports.dmarcwatch.app  mxa.mailgun.org  10
MX  reports.dmarcwatch.app  mxb.mailgun.org  10
```

#### 4. Create Inbound Route
In Mailgun Dashboard > Receiving > Routes:
- **Expression**: `match_recipient(".*@reports.dmarcwatch.app")`
- **Action**: Forward to `https://yourdomain.com/webhooks/inbound-report`
- **Action**: Stop

#### 5. Set Environment Variables
```env
DMARCWATCH_INBOUND_DOMAIN=reports.dmarcwatch.app
MAILGUN_INBOUND_SIGNING_KEY=your-signing-key
```

Get the **Inbound Signing Key** from Mailgun Dashboard > Settings > API Security (this is NOT the API key).

---

## Background Jobs

The app uses 6 background jobs managed by Laravel Horizon:

| Job | Queue | Trigger |
|-----|-------|---------|
| `ScheduleDnsChecks` | default | Every minute (scheduled) |
| `CheckDomainDns` | dns-checking | Dispatched by scheduler |
| `ProcessInboundReport` | report-processing | Mailgun webhook |
| `SendAlertNotification` | alerts | Alert rule triggered |
| `SendWeeklyDigests` | default | Mondays at 09:00 UTC |
| `PruneOldReports` | default | Daily (scheduled) |

### Scheduled Tasks

| Task | Schedule |
|------|----------|
| `ScheduleDnsChecks` | Every minute (without overlapping) |
| `PruneOldReports` | Daily |
| `SendWeeklyDigests` | Mondays at 09:00 |
| `horizon:snapshot` | Every 5 minutes |

---

## Production Deployment

### 1. Server Setup

```bash
composer install --no-dev --optimize-autoloader
npm ci && npm run build
```

### 2. Environment

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://dmarcwatch.app
DB_CONNECTION=mysql
QUEUE_CONNECTION=redis
CACHE_STORE=redis
MAIL_MAILER=smtp     # Or mailgun, postmark, ses
```

### 3. Database

```bash
php artisan migrate --force
php artisan db:seed --force   # Only on first deploy
```

### 4. Cache Optimization

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan storage:link      # Symlink public storage
```

### 5. Queue Worker (Supervisor)

Create `/etc/supervisor/conf.d/horizon.conf`:

```ini
[program:horizon]
process_name=%(program_name)s
command=php /path/to/dmarcwatch/artisan horizon
autostart=true
autorestart=true
user=www-data
redirect_stderr=true
stdout_logfile=/var/log/horizon.log
stopwaitsecs=3600
```

Then:
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start horizon
```

### 6. Scheduler (Cron)

Add to crontab (`crontab -e`):
```
* * * * * cd /path/to/dmarcwatch && php artisan schedule:run >> /dev/null 2>&1
```

### 7. DNS Records

**Main domain** (e.g. dmarcwatch.app):
- A/CNAME record pointing to your server
- TLS/SSL certificate (Let's Encrypt recommended)

**Reports subdomain** (e.g. reports.dmarcwatch.app):
- MX records pointing to Mailgun

---

## Testing

```bash
./vendor/bin/pest                     # Run all tests
./vendor/bin/pest --filter=keyword    # Filter tests
./vendor/bin/phpstan analyse          # Static analysis
./vendor/bin/pint                     # Code formatting
```

---

## Default Ports

| Service | Port |
|---------|------|
| Laravel | 8688 |
| Vite | 5174 |
| MySQL | 3306 |
| PostgreSQL | 5432 |
| Redis | 6379 |

---

## Useful Commands

```bash
composer dev                    # Start all dev services
php artisan migrate             # Run migrations
php artisan db:seed             # Seed database
php artisan horizon             # Start queue worker
php artisan schedule:work       # Run scheduler (foreground)
php artisan config:clear        # Clear config cache
php artisan cache:clear         # Clear app cache
php artisan queue:failed        # List failed jobs
php artisan tinker              # Interactive REPL
```
