# Event Marketplace — Production-Ready Multi-Vendor Event Platform

This Laravel application has been converted through 30 patch-based steps into a multi-vendor Eventbrite-style marketplace. It supports public event discovery, organizer onboarding, organizer-owned venues/seating/events, ticket sales, cart reservations, order/ticket issuance, Stripe payment, QR check-in, refunds, payouts, reporting, notifications, audit logs, support workflows, reviews, public organizer profiles, saved events, SEO metadata, demo data, and regression coverage.

## Core Roles

- **Admin:** manages organizers, event approvals, reports, payouts, refunds, support, moderation, reviews, audit logs, SEO-visible public data, and platform settings.
- **Organizer:** manages profile, venues, seating plans, events, ticket types, attendees, reports, check-in, team members, payout methods, payout requests, support, and notifications.
- **Customer:** browses events, saves events, follows organizers, checks out, pays online, views orders/tickets, prints tickets, requests refunds, reviews attended events, and opens support tickets.
- **Check-in staff:** validates issued tickets using the current `OrderTicket` and `TicketCheckIn` workflow.
- **Finance viewer:** views organizer reporting/payout areas without editing sensitive operations.

## Architecture Notes

The final marketplace uses the newer architecture only:

- OrganizerProfile, OrganizerTeamMember, OrganizerPayoutMethod
- Venue, SeatingPlan, SeatingSection, SeatingSeat, SeatLock
- Event, EventTicket, CartItem, Order, OrderItem, OrderTicket
- PaymentTransaction, TicketCheckIn
- RefundRequest, RefundTransaction, EventCancellationRequest
- PlatformCommissionSetting, OrganizerLedger, PlatformCommissionLedger, OrganizerPayout
- AuditLog, MarketplaceSupportTicket, MarketplaceSupportMessage, MarketplaceModerationFlag, MarketplaceDispute
- MarketplaceEventReview, MarketplaceOrganizerRating, OrganizerFollower, OrganizerTrustBadge
- CustomerSavedEvent, CustomerEventInterest

Legacy broken booking/temp-booking architecture must not be reintroduced.

## Local Installation

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan optimize:clear
php artisan migrate:fresh --seed
php artisan storage:link
npm run build
php artisan serve
```

## Useful Demo Accounts

All demo accounts created by the final seeders use password:

```text
password
```

Common demo users:

```text
Admin: admin@example.com
Customer: demo.customer@example.com
Organizer owner: demo.organizer@example.com
Organizer manager: organizer.manager@example.com
Organizer check-in staff: organizer.checkin@example.com
Organizer finance viewer: organizer.finance@example.com
```

## Main Test Commands

```bash
composer install
php artisan optimize:clear
composer dump-autoload
php artisan route:list
php artisan migrate:fresh --seed
php artisan db:seed --class=DemoMarketplaceSeeder
php artisan test --filter=ProductionReadinessDocumentationTest
php artisan test --filter=MarketplaceEndToEndRegressionTest
php artisan test --filter=MarketplaceUxSmokeTest
php artisan test --filter=PublicSeoMetadataTest
php artisan test --filter=RouteSafetyTest
php artisan test
```

## Public Smoke URLs

```text
/
/events
/event/demo-marketplace-summit
/event/demo-online-growth-workshop
/organizers/demo-marketplace-events
/sitemap.xml
/robots.txt
```

## Private Smoke URLs

```text
/user/dashboard
/user/saved-events
/user/discover
/user/orders
/user/tickets
/organizer/dashboard
/organizer/reports
/organizer/check-in
/admin/marketplace-reports
/admin/support-tickets
/admin/audit-logs
/admin/reviews
/admin/payouts
```

## Production Documents

Read these files before deployment or handover:

- `DEPLOYMENT.md`
- `RELEASE.md`
- `PRODUCTION_CHECKLIST.md`
- `.env.example`
- `SNAPSHOT.md`

## Deployment Principle

Deploy only after `php artisan migrate:fresh --seed` passes in a staging-like database and the targeted regression tests pass. Use real secrets only in `.env`; never commit `.env` or production credentials.
