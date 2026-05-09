# Troubleshooting Notes

## Homepage or Public Browse Shows Database Table Missing

Run migrations first:

```bash
php artisan migrate
php artisan optimize:clear
```

For a clean demo install:

```bash
php artisan migrate:fresh --seed
```

## Route or Class Not Found After Patch

```bash
php artisan optimize:clear
composer dump-autoload
php artisan route:list
```

## CSS/JS Not Updated

```bash
npm ci
npm run build
php artisan view:clear
```

## Uploaded Images Do Not Display

```bash
php artisan storage:link
```

Confirm the web server can read `public/storage` and write to `storage/app/public`.

## Queued Notifications Do Not Send

Run a queue worker:

```bash
php artisan queue:work --sleep=3 --tries=3 --timeout=90
```

Then restart after deployment:

```bash
php artisan queue:restart
```

## Stripe Payments Do Not Complete

Check:

- `STRIPE_KEY`
- `STRIPE_SECRET`
- `STRIPE_WEBHOOK_SECRET`
- webhook endpoint: `/stripe/webhook`
- webhook event delivery logs in Stripe dashboard

## Login or Session Issues on HTTPS

Confirm:

```text
APP_URL=https://your-domain.com
SESSION_DOMAIN=your-domain.com
SESSION_SECURE_COOKIE=true
```

Then run:

```bash
php artisan optimize:clear
```

## Admin/Organizer/Customer Pages Redirect Unexpectedly

Confirm you are logged in with the correct guard/user and that organizer profile status is approved. For organizer staff, confirm active team member role and current `OrganizerTeamAccessService` rules.
