Act as a Senior Laravel Architect, Senior Multi-Vendor Event Marketplace Architect, Senior Laravel Refactoring Expert, Senior QA Engineer, Senior SaaS Product Designer, Senior UI/UX Architect, Senior Project Manager, DevOps-Aware Laravel Engineer, and Full-Stack Developer.

I am a complete beginner in Laravel.

I am uploading my latest Laravel Event Marketplace project after applying Advanced Step A9.

IMPORTANT CONTEXT:
- Steps 1–30 are already completed.
- Advanced Steps A1–A8 are already completed.
- Advanced Step A9 added separate organizer authentication and a unified admin/organizer Event Control Panel.

CRITICAL LEGACY RULE — NEVER REINTRODUCE OLD BROKEN ARCHITECTURE:
Never reintroduce or reference Booking, TemporaryBooking, TemporaryBookingSeat, EventSeat, EventSeatType, BookingController, EventSeatController, EventSeatTypeController, old temporary-booking PaymentController flow, or ClearExpiredTemporaryBookings.

NEXT TASK:
Audit Advanced Step A9 after I apply it locally. Inspect the latest uploaded codebase first and fix only real issues found in:
1. `/organizer/login`
2. `/organizer/register`
3. `/organizer/status`
4. `/organizer/events/{event}/control`
5. `/admin/events/{event}/control`
6. Organizer event create/edit fields and media uploads
7. Organizer venue create/edit fields and image upload
8. Seating plan form/designer links
9. Ticket management links from event control panel
10. Route safety and tests

REQUIREMENTS:
- Generate a real downloadable patch ZIP containing ONLY new and modified files.
- Do NOT rebuild the project from scratch.
- Do NOT add unrelated modules.
- Do NOT add migrations unless absolutely required.
- Preserve admin auth, customer auth, A1 login redirect behavior, A2/A3/A4/A7 seat-map logic, A8 dashboard/QR work, checkout, payment, refunds, payouts, and support workflows.
- Include tests and update SNAPSHOT.md and NEXT_STEP_PROMPT.md.

COMMANDS THAT MUST WORK AFTER PATCH:
```bash
composer install
php artisan optimize:clear
composer dump-autoload
php artisan route:list
php artisan migrate
php artisan db:seed
php artisan test --filter=AdvancedOrganizerAuthEventPanelTest
php artisan test --filter=AdvancedLoginRedirectTest
php artisan test --filter=AdvancedDashboardQrAuditTest
php artisan test --filter=AdvancedSeatMapProductionHardeningTest
php artisan test --filter=AdvancedTicketSectionMatrixTest
php artisan test --filter=AdvancedUserPanelTest
php artisan test --filter=EventCancellationTest
php artisan test --filter=MarketplaceEndToEndRegressionTest
php artisan test --filter=RouteSafetyTest
php artisan test
```
