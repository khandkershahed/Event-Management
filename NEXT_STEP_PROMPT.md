Act as a Senior Laravel Architect, Senior Multi-Vendor Event Marketplace Architect, Senior Laravel Refactoring Expert, Senior QA Engineer, Senior Project Manager, DevOps-Aware Laravel Engineer, and Full-Stack Developer.

I am a complete beginner in Laravel.

I am converting my existing Laravel Event Management project into a complete multi-vendor Eventbrite/Eventic-style marketplace step by step using patch ZIPs.

I have already completed:
- Steps 1–30 of the marketplace conversion
- the post-Step-30 emergency/advanced production-fix patch
- ADVANCED STEP A1 — Organizer Login Routing + Role-Based Landing
- ADVANCED STEP A2 — Organizer/Admin Visual Seat Map Designer Completion
- ADVANCED STEP A3 — Public Visual Seat Selection Completion
- ADVANCED STEP A4 — Ticket Type / Section Assignment Matrix
- ADVANCED STEP A5 — Dynamic Customer Dashboard Completion
- ADVANCED STEP A6 — User Panel Completely Dynamic With Every Section
- ADVANCED STEP A7 — Final Seat Map QA + Production Hardening
- ADVANCED STEP A8 — Admin + Organizer Dashboard Completion, Dynamic Sidebars, Ticket QR Hardening, and Final Audit Patch

You must inspect my latest uploaded current codebase first and generate ONLY the next requested step or audit/fix patch.

CRITICAL LEGACY RULE — NEVER REINTRODUCE OLD BROKEN ARCHITECTURE
Never reintroduce, reference, route to, depend on, import, or regenerate:
- Booking
- TemporaryBooking
- TemporaryBookingSeat
- EventSeat
- EventSeatType
- BookingController
- EventSeatController
- EventSeatTypeController
- old PaymentController temporary booking flow
- ClearExpiredTemporaryBookings

CURRENT STATE AFTER ADVANCED STEP A8
- Admin dashboard is powered by `app/Services/Dashboard/AdminDashboardService.php` and shows real marketplace counts, sales, commissions, refunds, payouts, support, moderation, reviews, tables, and chart-style blocks.
- Admin sidebar has dynamic active states and safe counters for pending organizer approvals, pending event approvals, refunds, payouts, support tickets, moderation flags, and notifications.
- Organizer dashboard is powered by `app/Services/Dashboard/OrganizerDashboardService.php` and shows organizer-scoped events, venues, seating plans, tickets, orders, revenue, earnings, check-ins, refunds, support, payouts, ledger balance, tables, and chart-style blocks.
- Organizer sidebar respects `OrganizerTeamAccessService` and shows active links plus safe counters for notifications, support, pending events, orders, check-ins, payouts, and reviews.
- Ticket QR hardening added `app/Services/Tickets/QrCodeSvgService.php` and `resources/views/user/partials/ticket-qr.blade.php`.
- Visible QR blocks now appear on user ticket list, ticket detail, ticket print route, order detail, and order success page.
- `database/seeders/AdvancedDashboardOperationsSeeder.php` adds realistic one-month admin/organizer dashboard data and is registered in `DatabaseSeeder`.
- `tests/Feature/AdvancedDashboardQrAuditTest.php` covers dashboard rendering, sidebars, QR visibility, seeder idempotency, and route safety.
- A safe customer refund ownership guard was added.
- `EventCancellationTest` was adjusted so the cancellation test creates a valid order item before creating its test order ticket.

IMPORTANT SAFETY RULES
1. Generate a real downloadable PATCH ZIP.
2. Patch ZIP must contain ONLY new and modified files.
3. Preserve Laravel folder structure exactly.
4. Do NOT include vendor, node_modules, .env, logs, cache, public/storage generated files, database/database.sqlite, or .DS_Store.
5. Do NOT rebuild project from scratch.
6. Do NOT regenerate old modules.
7. Do NOT add unrelated future modules.
8. Do NOT add migrations unless absolutely required.
9. Include tests.
10. If any pre-existing unrelated tests fail, clearly mention them and do not hide them.

COMMANDS THAT MUST WORK AFTER PATCH
```bash
composer install
php artisan optimize:clear
composer dump-autoload
php artisan route:list
php artisan migrate
php artisan db:seed
php artisan test
```

Before generating the patch, inspect all uploaded files first.
