# Production Checklist and Smoke-Test Plan

Use this checklist before handing over or deploying the marketplace.

## Environment Checklist

- [ ] `APP_NAME` is set to the real marketplace name.
- [ ] `APP_ENV=production`.
- [ ] `APP_DEBUG=false`.
- [ ] `APP_URL` uses the final HTTPS domain.
- [ ] `APP_KEY` is generated and private.
- [ ] Database name/user/password are production values.
- [ ] `SESSION_SECURE_COOKIE=true` on HTTPS.
- [ ] `QUEUE_CONNECTION=database` or another production queue driver.
- [ ] Mail credentials are configured or intentionally set to `log` for staging.
- [ ] Stripe keys and webhook secret are configured.
- [ ] `FILESYSTEM_DISK=public` or the expected production disk.
- [ ] Storage symlink is created.
- [ ] Scheduler cron is configured.
- [ ] Queue worker is supervised and restartable.
- [ ] Backups are scheduled.

## Deployment Commands

```bash
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

## Public Smoke Tests

- [ ] `/` loads homepage marketplace sections.
- [ ] `/events` loads public event browse.
- [ ] `/event/demo-marketplace-summit` loads event detail in demo/staging.
- [ ] `/organizers/demo-marketplace-events` loads organizer profile in demo/staging.
- [ ] `/sitemap.xml` loads and contains only public URLs.
- [ ] `/robots.txt` loads and blocks private areas.
- [ ] Public pages contain canonical and Open Graph metadata.

## Customer Smoke Tests

- [ ] Register a customer or login as `demo.customer@example.com` in staging/demo.
- [ ] Save and unsave a published event.
- [ ] Open `/user/saved-events`.
- [ ] Open `/user/discover`.
- [ ] Add an event ticket to cart.
- [ ] Open cart and checkout.
- [ ] For paid orders, confirm Stripe test mode redirects correctly.
- [ ] For free/demo orders, confirm order and ticket pages load.
- [ ] Confirm printable ticket shows order number, ticket code, QR payload, event, venue, and seat details.
- [ ] Submit a refund request where allowed.

## Organizer Smoke Tests

- [ ] Login as organizer owner in staging/demo.
- [ ] Open `/organizer/dashboard`.
- [ ] Open organizer events, venues, seating plans, ticket types, reports, and payouts.
- [ ] Open `/organizer/check-in`.
- [ ] Validate a valid demo ticket and confirm duplicate check-in is blocked.
- [ ] Submit a support ticket.

## Admin Smoke Tests

- [ ] Login as admin.
- [ ] Open `/admin/marketplace-reports`.
- [ ] Open `/admin/support-tickets`.
- [ ] Open `/admin/audit-logs`.
- [ ] Open `/admin/reviews`.
- [ ] Open `/admin/payouts`.
- [ ] Review organizer payout methods.
- [ ] Review refunds and event cancellations.
- [ ] Confirm audit logs are created for sensitive actions.

## Mail and Notification Smoke Tests

- [ ] Mail is set to log/sandbox in staging or SMTP in production.
- [ ] Order confirmation notification is created.
- [ ] Ticket delivery notification is created.
- [ ] Event approval/rejection notification is created.
- [ ] Refund decision notification is created.
- [ ] Payout decision notification is created.

## Stripe Smoke Tests

- [ ] `STRIPE_KEY` and `STRIPE_SECRET` match the intended environment.
- [ ] `STRIPE_WEBHOOK_SECRET` matches the configured webhook endpoint.
- [ ] Test payment creates/updates a payment transaction.
- [ ] Stripe webhook route responds safely.

## Backup and Restore Checklist

- [ ] Database backup command is documented for the hosting environment.
- [ ] `storage/app/public` backup is included.
- [ ] Restore process is tested in staging.
- [ ] Backups are stored outside the application server when possible.

## Final Safety Checks

- [ ] `php artisan route:list` works.
- [ ] `php artisan test --filter=RouteSafetyTest` passes.
- [ ] No legacy `Booking`, `TemporaryBooking`, `EventSeat`, or old payment flow routes/classes are used.
- [ ] No `.env`, logs, cache, vendor, or node_modules are committed in patch ZIPs.
