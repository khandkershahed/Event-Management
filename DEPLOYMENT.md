# Deployment Guide — Event Marketplace

This guide prepares the completed Laravel marketplace for staging or production deployment. It does not introduce new features or change marketplace business logic.

## 1. Server Requirements

Use a server compatible with the project dependencies:

- PHP 8.2 or newer
- Composer 2.x
- MySQL 8.x or MariaDB compatible with Laravel 12 migrations
- Node.js 20+ for asset builds
- Web server: Nginx, Apache, LiteSpeed, or cPanel-managed equivalent
- PHP extensions commonly required by Laravel: mbstring, openssl, pdo_mysql, tokenizer, xml, ctype, json, bcmath, fileinfo, curl
- SSL certificate for production domains

## 2. Safe Deployment Flow

```bash
git pull origin main
composer install --no-dev --optimize-autoloader
npm ci
npm run build
php artisan down
php artisan optimize:clear
php artisan migrate --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan queue:restart
php artisan up
```

If this is the first staging/demo install and you need demo data:

```bash
php artisan migrate:fresh --seed --force
php artisan db:seed --class=DemoMarketplaceSeeder --force
```

Do not run `migrate:fresh` on a real production database with live data.

## 3. File Permissions

Make sure the web server user can write to:

```text
storage/
bootstrap/cache/
```

Typical Linux example:

```bash
chmod -R ug+rw storage bootstrap/cache
```

Use ownership appropriate for your hosting environment.

## 4. Public Directory

The web server document root should point to:

```text
public/
```

Do not expose the project root directly to the web.

## 5. Storage Symlink

Run once per deployment target:

```bash
php artisan storage:link
```

Uploaded organizer/event assets should be served through the public storage symlink.

## 6. Queue Worker

The marketplace uses database notifications and may send/fake email notifications. In production, run a queue worker if `QUEUE_CONNECTION` is not `sync`.

Systemd/Supervisor-style command:

```bash
php artisan queue:work --sleep=3 --tries=3 --timeout=90
```

Restart workers after each deployment:

```bash
php artisan queue:restart
```

## 7. Scheduler

Add the Laravel scheduler to cron:

```cron
* * * * * cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1
```

Use the scheduler for Laravel-maintained recurring tasks and future cleanup jobs.

## 8. Stripe Webhook

Set the production Stripe webhook endpoint to:

```text
https://your-domain.com/stripe/webhook
```

Configure `STRIPE_KEY`, `STRIPE_SECRET`, and `STRIPE_WEBHOOK_SECRET` in `.env`. Test with Stripe test mode before going live.

## 9. Mail

For staging, use `MAIL_MAILER=log` or a sandbox SMTP provider.

For production, configure SMTP or a transactional email provider. Confirm:

- order confirmation notifications
- ticket delivery notifications
- event approval/rejection notifications
- refund notifications
- payout notifications

Database notifications remain available even when mail is not configured.

## 10. Backup and Restore

Back up before every deployment:

```bash
mysqldump -u DB_USER -p DB_NAME > backup_$(date +%Y%m%d_%H%M%S).sql
php artisan down
```

Restore example:

```bash
mysql -u DB_USER -p DB_NAME < backup_file.sql
php artisan optimize:clear
```

Also back up uploaded files:

```text
storage/app/public/
```

## 11. Post-Deployment Verification

Run:

```bash
php artisan optimize:clear
php artisan route:list
php artisan test --filter=ProductionReadinessDocumentationTest
php artisan test --filter=MarketplaceEndToEndRegressionTest
php artisan test --filter=RouteSafetyTest
```

Then complete the smoke-test checklist in `PRODUCTION_CHECKLIST.md`.
