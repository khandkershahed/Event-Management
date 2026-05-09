# EVENTBRITE-STYLE MULTI-VENDOR EVENT MANAGEMENT PLATFORM ROADMAP
# Existing Project Conversion Workflow
# Laravel Patch-Zip Development Plan for Beginner-Friendly ChatGPT Implementation

================================================================================
PROJECT GOAL
================================================================================

Transform the current Laravel event management project into a complete multi-vendor
event marketplace similar to Eventbrite.

Final system will allow:

1. Admin
   - Manage platform settings
   - Approve/reject organizers
   - Approve/reject events
   - Manage categories, event types, venues, users, reports
   - View platform sales, commissions, payouts, refunds
   - Handle disputes and moderation

2. Organizer / Event Manager / Vendor
   - Register as organizer
   - Submit organizer profile for approval
   - Create venues
   - Create custom seating layouts
   - Create events
   - Create ticket types
   - Sell tickets
   - View orders, attendees, check-ins, revenue, payouts
   - Request payout

3. Customer / Attendee
   - Register/login
   - Browse events
   - Select tickets/seats
   - Pay online
   - Receive ticket with QR code
   - View orders and tickets
   - Download/print ticket

4. Check-in Staff
   - Scan QR ticket
   - Mark attendee as checked in
   - Prevent duplicate entry

================================================================================
IMPORTANT DEVELOPMENT RULES FOR EVERY CHATGPT PATCH
================================================================================

Every implementation step must follow these rules:

1. ChatGPT must inspect the latest uploaded codebase first.
2. ChatGPT must generate ONLY the requested step.
3. ChatGPT must not rebuild the full project from scratch.
4. ChatGPT must preserve existing Laravel structure unless the step explicitly changes it.
5. ChatGPT must include ONLY new and modified files in the patch zip.
6. Patch zip must be extraction-ready into the Laravel project root.
7. Patch zip must not include:
   - vendor/
   - node_modules/
   - .env
   - storage/logs/
   - bootstrap/cache/*.php
   - public/storage generated files
   - database/database.sqlite unless explicitly required
   - .DS_Store
8. Each step must include:
   - migrations
   - models
   - controllers
   - requests
   - policies where needed
   - services/actions where needed
   - routes
   - Blade views
   - seeders
   - tests
   - menu/sidebar updates
   - SNAPSHOT.md
   - NEXT_STEP_PROMPT.md
9. Each step must include terminal testing commands.
10. Each step must include webpage testing instructions.
11. Each step must be small enough that a beginner can apply and test safely.

================================================================================
CURRENT PROJECT AUDIT SUMMARY
================================================================================

Current project has:

- Laravel 12 base
- Admin authentication
- User authentication
- Basic event management
- Partial seating plan system
- Partial ticket type system
- Partial order/ticket system
- Stripe package installed
- Spatie permission package installed

Current project problems:

1. Legacy ticketing system is still present but broken.
   Broken/legacy files reference missing models/tables:
   - Booking
   - TemporaryBooking
   - TemporaryBookingSeat
   - EventSeat
   - EventSeatType

2. Two conflicting ticket systems exist:
   OLD BROKEN SYSTEM:
   - BookingController
   - PaymentController
   - EventSeatController
   - EventSeatTypeController
   - temporary bookings
   - event seats

   NEW BETTER SYSTEM:
   - SeatingPlan
   - SeatingSection
   - SeatingSeat
   - SeatLock
   - EventTicket
   - Order
   - OrderItem
   - OrderTicket

3. Final system should keep the NEW system and remove/replace the OLD system.

4. Multi-vendor system is missing:
   - organizer registration
   - organizer profile
   - organizer approval
   - event ownership
   - venue ownership
   - payout system
   - commission system
   - organizer dashboard
   - ticket check-in workflow
   - complete payment finalization workflow

================================================================================
FINAL ARCHITECTURE DIRECTION
================================================================================

Use these main domains:

1. Admin Domain
   - Admin
   - Role/Permission
   - PlatformSetting
   - PlatformCommission
   - OrganizerApproval
   - EventApproval
   - Reports

2. Organizer Domain
   - OrganizerProfile
   - OrganizerTeamMember
   - OrganizerBankAccount
   - OrganizerPayoutMethod

3. Event Domain
   - Event
   - EventCategory / Category
   - EventType
   - EventImage
   - Venue
   - SeatingPlan
   - SeatingSection
   - SeatingSeat

4. Ticket Domain
   - EventTicket
   - TicketAvailabilityService
   - SeatLock
   - CartItem or SessionCart
   - Order
   - OrderItem
   - OrderTicket

5. Payment Domain
   - PaymentTransaction
   - RefundRequest
   - RefundTransaction
   - PlatformCommissionLedger
   - OrganizerLedger
   - OrganizerPayout

6. Check-in Domain
   - TicketQrCode
   - TicketCheckIn
   - CheckInStaff

7. Notification Domain
   - Email notification
   - Ticket email
   - Organizer approval notification
   - Order confirmation notification

================================================================================
STEP 0 — PROJECT CLEANUP AND BASELINE SAFETY
================================================================================

Objective:
Clean the project from clearly unnecessary and broken legacy files before starting
new development.

This step removes:
- .DS_Store files
- root vendor folder from copied/distributed project
- cache files
- logs
- broken legacy ticketing files
- copied/old Blade files
- broken legacy seeders

This step keeps:
- current Laravel app
- current admin UI
- current frontend UI
- current new seating system
- current event ticket/order/order ticket tables
- composer.json
- package.json
- public frontend/admin assets

Patch ZIP:
No patch zip required for this step if using terminal cleanup commands.
But after cleanup, ChatGPT Step 1 must create the clean route/controller patch.

Terminal test:
php artisan optimize:clear
composer dump-autoload
php artisan route:list

Webpage test:
- Open admin login
- Open homepage
- Confirm project still loads

Expected result:
- Legacy files removed
- Project still boots
- Some route errors may still remain until Step 1 patch replaces route files

================================================================================
STEP 1 — ROUTE SAFETY, LEGACY TICKETING REMOVAL, AND BASELINE TEST FIX
================================================================================

Objective:
Remove broken legacy route references and make the app boot cleanly using the new
ticketing architecture only.

Main work:
1. Remove all routes pointing to:
   - EventSeatController
   - EventSeatTypeController
   - BookingController
   - legacy PaymentController temporary booking flow

2. Keep or repair routes for:
   - EventController
   - VenueController
   - SeatingPlanController
   - SeatingPlanDesignerController
   - SeatingSectionController
   - SeatingSeatController
   - EventTicketTypeController
   - TicketOrderController
   - SeatMapController

3. Fix route/controller mismatch:
   - SeatingSectionController@store currently expects planId but route does not provide it.

4. Restore basic profile routes or adjust auth tests to project route names.

5. Create a project health test:
   - route list loads
   - homepage loads
   - admin login page loads
   - user login page loads

Files expected in patch:
- routes/web.php
- routes/frontend.php
- routes/admin.php
- routes/api.php
- routes/auth.php
- app/Http/Controllers/Admin/SeatingSectionController.php
- app/Http/Controllers/Frontend/TicketOrderController.php if needed
- tests/Feature/ProjectBootTest.php
- tests/Feature/AuthRouteSafetyTest.php
- SNAPSHOT.md
- NEXT_STEP_PROMPT.md

Seeder:
No new seeder required.

Terminal test:
php artisan optimize:clear
composer dump-autoload
php artisan route:list
php artisan test --filter=ProjectBootTest
php artisan test --filter=AuthRouteSafetyTest

Webpage test:
- / should load
- /login should load
- /register should load
- /admin/login should load
- Admin route list should not reference missing controllers

Expected result:
Project boots without missing legacy class errors.

================================================================================
STEP 2 — ORGANIZER ROLE, PROFILE, AND APPROVAL FOUNDATION
================================================================================

Objective:
Introduce organizer/vendor identity without breaking current users/admins.

Main work:
1. Add organizer profile table:
   - user_id
   - organization_name
   - slug
   - contact_person
   - phone
   - email
   - website
   - address
   - description
   - logo
   - banner
   - status: draft, pending, approved, rejected, suspended
   - submitted_at
   - approved_at
   - approved_by
   - rejection_reason

2. Add model:
   - OrganizerProfile

3. Add relationships:
   - User hasOne OrganizerProfile
   - OrganizerProfile belongsTo User
   - OrganizerProfile approvedBy Admin

4. Add organizer registration/onboarding pages:
   - Become Organizer
   - Create organizer profile
   - Submit for review
   - View approval status

5. Add admin organizer approval pages:
   - pending organizers
   - approve
   - reject
   - suspend

6. Add permissions:
   - organizer.view
   - organizer.create
   - organizer.edit
   - organizer.approve
   - organizer.reject
   - organizer.suspend

Files expected in patch:
- database/migrations/create_organizer_profiles_table.php
- app/Models/OrganizerProfile.php
- app/Http/Controllers/Frontend/OrganizerOnboardingController.php
- app/Http/Controllers/Admin/OrganizerApprovalController.php
- app/Http/Requests/OrganizerProfileStoreRequest.php
- app/Http/Requests/OrganizerProfileUpdateRequest.php
- routes/frontend.php
- routes/admin.php
- resources/views/frontend/pages/organizer/*
- resources/views/admin/pages/organizers/*
- database/seeders/OrganizerSeeder.php
- database/seeders/RolePermissionSeeder.php modified
- tests/Feature/OrganizerOnboardingTest.php
- tests/Feature/AdminOrganizerApprovalTest.php
- SNAPSHOT.md
- NEXT_STEP_PROMPT.md

Terminal test:
php artisan migrate
php artisan db:seed --class=RolePermissionSeeder
php artisan db:seed --class=OrganizerSeeder
php artisan test --filter=OrganizerOnboardingTest
php artisan test --filter=AdminOrganizerApprovalTest

Webpage test:
- Register/login as normal user
- Open Become Organizer
- Submit organizer profile
- Login as admin
- Approve organizer
- Confirm organizer status becomes approved

Expected result:
A user can become an approved organizer.

================================================================================
STEP 3 — ORGANIZER DASHBOARD FOUNDATION
================================================================================

Objective:
Create a dedicated organizer dashboard for approved organizers.

Main work:
1. Add middleware:
   - EnsureOrganizerApproved

2. Add organizer dashboard routes:
   - /organizer/dashboard
   - /organizer/profile
   - /organizer/events
   - /organizer/venues
   - /organizer/orders
   - /organizer/reports

3. Add organizer dashboard layout:
   - sidebar
   - topbar
   - dashboard cards

4. Dashboard cards:
   - total events
   - published events
   - pending events
   - total orders
   - total revenue
   - pending payout

5. Block unapproved organizers from dashboard.

Files expected in patch:
- app/Http/Middleware/EnsureOrganizerApproved.php
- bootstrap/app.php or middleware registration file
- app/Http/Controllers/Organizer/DashboardController.php
- app/Http/Controllers/Organizer/ProfileController.php
- routes/organizer.php or routes/web.php modified
- resources/views/organizer/layouts/*
- resources/views/organizer/dashboard.blade.php
- resources/views/organizer/profile/*
- tests/Feature/OrganizerDashboardAccessTest.php
- SNAPSHOT.md
- NEXT_STEP_PROMPT.md

Terminal test:
php artisan optimize:clear
php artisan route:list
php artisan test --filter=OrganizerDashboardAccessTest

Webpage test:
- Unapproved organizer cannot access dashboard
- Approved organizer can access dashboard
- Normal customer cannot access organizer dashboard

Expected result:
Organizer dashboard foundation is ready.

================================================================================
STEP 4 — VENUE OWNERSHIP AND ORGANIZER VENUE CRUD
================================================================================

Objective:
Convert venue system into organizer-owned venues.

Main work:
1. Add/repair venue ownership:
   - venues.organizer_profile_id or organizer_id
   - proper foreign key
   - relation to OrganizerProfile

2. Organizer can create/edit/delete own venues.

3. Admin can see all venues.

4. Venue fields:
   - organizer_profile_id
   - name
   - slug
   - type: physical, online, hybrid
   - address
   - city
   - country
   - map_link
   - latitude
   - longitude
   - capacity
   - description
   - image
   - status

5. Policies:
   - organizer can update only own venue
   - admin can manage all

Files expected in patch:
- migration to fix venues table
- app/Models/Venue.php
- app/Policies/VenuePolicy.php
- app/Http/Controllers/Organizer/VenueController.php
- app/Http/Requests/VenueStoreRequest.php
- app/Http/Requests/VenueUpdateRequest.php
- resources/views/organizer/venues/*
- resources/views/admin/pages/venue/* modified if needed
- routes/organizer.php
- tests/Feature/OrganizerVenueCrudTest.php
- database/seeders/VenueSeeder.php
- SNAPSHOT.md
- NEXT_STEP_PROMPT.md

Terminal test:
php artisan migrate
php artisan db:seed --class=VenueSeeder
php artisan test --filter=OrganizerVenueCrudTest

Webpage test:
- Organizer creates venue
- Organizer edits own venue
- Organizer cannot edit another organizer venue
- Admin can view all venues

Expected result:
Venue system becomes marketplace-ready.

================================================================================
STEP 5 — SEATING PLAN OWNERSHIP AND DESIGNER HARDENING
================================================================================

Objective:
Make seating plans organizer-owned and safe for ticket sales.

Main work:
1. Seating plans belong to organizer and venue.
2. Organizer can design seating plan for own venue.
3. Add seating plan status:
   - draft
   - active
   - locked
   - archived

4. Prevent editing locked seating plan after tickets are sold.
5. Add copy/duplicate seating plan feature.
6. Improve section and seat management.

Files expected in patch:
- migration to update seating_plans
- migration to update seating_sections if needed
- migration to update seating_seats if needed
- app/Models/SeatingPlan.php
- app/Models/SeatingSection.php
- app/Models/SeatingSeat.php
- app/Policies/SeatingPlanPolicy.php
- app/Http/Controllers/Organizer/SeatingPlanController.php
- app/Http/Controllers/Organizer/SeatingPlanDesignerController.php
- app/Services/SeatingPlanCloneService.php
- resources/views/organizer/seating-plans/*
- routes/organizer.php
- tests/Feature/OrganizerSeatingPlanTest.php
- database/seeders/SeatingPlanSeeder.php
- SNAPSHOT.md
- NEXT_STEP_PROMPT.md

Terminal test:
php artisan migrate
php artisan db:seed --class=SeatingPlanSeeder
php artisan test --filter=OrganizerSeatingPlanTest

Webpage test:
- Organizer creates seating plan
- Organizer adds sections/seats
- Organizer duplicates seating plan
- Organizer cannot edit locked seating plan

Expected result:
Custom venue and seat settings become organizer-ready.

================================================================================
STEP 6 — ORGANIZER EVENT CREATION WORKFLOW
================================================================================

Objective:
Move event creation from admin-only to organizer marketplace workflow.

Main work:
1. Organizer creates event.
2. Event belongs to organizer profile.
3. Event belongs to venue and optionally seating plan.
4. Event lifecycle:
   - draft
   - submitted
   - under_review
   - approved
   - rejected
   - published
   - cancelled
   - completed

5. Organizer can submit event for admin review.
6. Admin can approve/reject event.
7. Public site only shows approved and published events.

Files expected in patch:
- migration to update events table
- app/Models/Event.php
- app/Policies/EventPolicy.php
- app/Http/Controllers/Organizer/EventController.php
- app/Http/Controllers/Admin/EventApprovalController.php
- app/Http/Requests/EventStoreRequest.php
- app/Http/Requests/EventUpdateRequest.php
- resources/views/organizer/events/*
- resources/views/admin/pages/event-approvals/*
- resources/views/frontend/pages/allEvents.blade.php modified
- resources/views/frontend/pages/eventDetails.blade.php modified
- routes/organizer.php
- routes/admin.php
- routes/frontend.php
- database/seeders/EventMarketplaceSeeder.php
- tests/Feature/OrganizerEventWorkflowTest.php
- tests/Feature/AdminEventApprovalTest.php
- SNAPSHOT.md
- NEXT_STEP_PROMPT.md

Terminal test:
php artisan migrate
php artisan db:seed --class=EventMarketplaceSeeder
php artisan test --filter=OrganizerEventWorkflowTest
php artisan test --filter=AdminEventApprovalTest

Webpage test:
- Organizer creates draft event
- Organizer submits event
- Admin approves event
- Organizer publishes event
- Public users can see published event

Expected result:
Organizer event publishing workflow works.

================================================================================
STEP 7 — EVENT TICKET TYPES AND SALES RULES
================================================================================

Objective:
Create complete ticket type management for organizers.

Main work:
1. Organizer creates ticket types for own event.
2. Ticket type fields:
   - name
   - description
   - ticket_type: free, paid, donation, invite_only
   - price
   - currency
   - quantity
   - min_per_order
   - max_per_order
   - sales_start_at
   - sales_end_at
   - visibility
   - status
   - valid_section_ids
   - platform_fee_type
   - platform_fee_value
   - organizer_absorbs_fee

3. Link ticket types to seating sections for reserved-seat events.
4. Add availability calculation.
5. Prevent ticket editing after sales where unsafe.

Files expected in patch:
- migration to improve event_tickets
- app/Models/EventTicket.php
- app/Services/TicketAvailabilityService.php
- app/Policies/EventTicketPolicy.php
- app/Http/Controllers/Organizer/EventTicketController.php
- app/Http/Requests/EventTicketStoreRequest.php
- app/Http/Requests/EventTicketUpdateRequest.php
- resources/views/organizer/event-tickets/*
- routes/organizer.php
- tests/Feature/OrganizerEventTicketTest.php
- database/seeders/EventTicketSeeder.php
- SNAPSHOT.md
- NEXT_STEP_PROMPT.md

Terminal test:
php artisan migrate
php artisan db:seed --class=EventTicketSeeder
php artisan test --filter=OrganizerEventTicketTest

Webpage test:
- Organizer creates paid ticket
- Organizer creates free ticket
- Organizer links ticket to seating section
- Ticket appears on public event detail page

Expected result:
Ticket-type management becomes production-ready.

================================================================================
STEP 8 — PUBLIC EVENT DISCOVERY AND EVENT DETAIL PAGE
================================================================================

Objective:
Build customer-facing event browsing similar to Eventbrite.

Main work:
1. Public event listing:
   - search
   - category filter
   - city filter
   - date filter
   - price filter
   - online/physical filter

2. Event detail page:
   - organizer info
   - venue info
   - event images
   - ticket box
   - date/time
   - map link
   - related events

3. Only show:
   - approved
   - published
   - active events

Files expected in patch:
- app/Http/Controllers/Frontend/EventBrowseController.php
- app/Http/Controllers/Frontend/EventDetailController.php
- app/View/Components/EventCard.php if needed
- resources/views/frontend/pages/events/index.blade.php
- resources/views/frontend/pages/events/show.blade.php
- resources/views/frontend/components/event-card.blade.php
- routes/frontend.php
- tests/Feature/PublicEventBrowseTest.php
- SNAPSHOT.md
- NEXT_STEP_PROMPT.md

Terminal test:
php artisan test --filter=PublicEventBrowseTest

Webpage test:
- Browse events
- Search events
- Filter by category/date/city
- Open event details
- Confirm unpublished event is hidden

Expected result:
Public marketplace browsing works.

================================================================================
STEP 9 — CART, SEAT LOCKING, AND RESERVATION SYSTEM
================================================================================

Objective:
Create a safe cart and seat reservation system.

Main work:
1. For general admission:
   - reserve quantity
   - prevent overselling

2. For reserved seating:
   - lock selected seats
   - lock expires after fixed time
   - prevent another user from selecting locked seat

3. Add cart pages:
   - cart
   - checkout
   - reservation timer

4. Add cleanup command:
   - clear expired seat locks

5. Replace old temporary booking concept with current SeatLock system.

Files expected in patch:
- app/Models/SeatLock.php
- app/Services/SeatLockService.php
- app/Services/CartService.php
- app/Console/Commands/ClearExpiredSeatLocks.php
- app/Http/Controllers/Frontend/CartController.php
- app/Http/Controllers/Frontend/SeatSelectionController.php
- app/Http/Requests/CartAddRequest.php
- app/Http/Requests/SeatLockRequest.php
- resources/views/frontend/pages/tickets/cart.blade.php
- resources/views/frontend/pages/tickets/select_seats.blade.php
- routes/frontend.php
- tests/Feature/SeatLockTest.php
- tests/Feature/CartTest.php
- SNAPSHOT.md
- NEXT_STEP_PROMPT.md

Terminal test:
php artisan optimize:clear
php artisan test --filter=SeatLockTest
php artisan test --filter=CartTest

Webpage test:
- Select a seat
- Seat becomes locked
- Open another browser/session
- Same seat cannot be selected
- Wait until expiry or run cleanup command
- Seat becomes available again

Expected result:
Safe ticket reservation system works.

================================================================================
STEP 10 — ORDER CREATION AND TICKET ISSUANCE
================================================================================

Objective:
Create complete order placement and ticket generation without payment first.

Main work:
1. Checkout creates order.
2. Order contains order items.
3. Paid orders remain pending_payment.
4. Free orders can become paid/completed immediately.
5. Generate order tickets.
6. Generate unique ticket code.
7. Generate QR code payload.
8. Store ticket status:
   - issued
   - used
   - cancelled
   - refunded

Files expected in patch:
- migration to improve orders/order_items/order_tickets
- app/Models/Order.php
- app/Models/OrderItem.php
- app/Models/OrderTicket.php
- app/Services/OrderNumberService.php
- app/Services/OrderPlacementService.php
- app/Services/TicketIssuanceService.php
- app/Http/Controllers/Frontend/CheckoutController.php
- resources/views/frontend/pages/tickets/checkout.blade.php
- resources/views/frontend/pages/tickets/order-success.blade.php
- resources/views/user/pages/orders/*
- routes/frontend.php
- tests/Feature/CheckoutOrderTest.php
- tests/Feature/TicketIssuanceTest.php
- database/seeders/OrderDemoSeeder.php
- SNAPSHOT.md
- NEXT_STEP_PROMPT.md

Terminal test:
php artisan migrate
php artisan db:seed --class=OrderDemoSeeder
php artisan test --filter=CheckoutOrderTest
php artisan test --filter=TicketIssuanceTest

Webpage test:
- Add free ticket to cart
- Checkout
- Order created
- Ticket issued
- User can view ticket

Expected result:
Order and ticket generation work before online payment integration.

================================================================================
STEP 11 — STRIPE PAYMENT INTEGRATION USING CURRENT ORDER SYSTEM
================================================================================

Objective:
Rewrite payment system to use current orders, not legacy bookings.

Main work:
1. Create payment_transactions table.
2. Checkout creates Stripe session/payment intent.
3. Payment success updates:
   - order status
   - payment status
   - payment transaction
   - ticket issue status

4. Payment cancel keeps order pending/cancelled.
5. Add webhook endpoint.
6. Verify webhook signature.

Files expected in patch:
- database/migrations/create_payment_transactions_table.php
- app/Models/PaymentTransaction.php
- app/Services/StripeCheckoutService.php
- app/Services/PaymentWebhookService.php
- app/Http/Controllers/Frontend/PaymentController.php rewritten
- app/Http/Controllers/Webhook/StripeWebhookController.php
- config/services.php modified
- routes/web.php
- routes/frontend.php
- tests/Feature/StripeCheckoutTest.php
- tests/Feature/PaymentWebhookTest.php
- SNAPSHOT.md
- NEXT_STEP_PROMPT.md

Terminal test:
php artisan migrate
php artisan test --filter=StripeCheckoutTest
php artisan test --filter=PaymentWebhookTest

Webpage test:
- Buy paid ticket
- Redirect to Stripe checkout
- Complete test payment
- Return to success page
- Confirm order is paid
- Confirm ticket issued

Expected result:
Payment flow works with the current order/ticket system.

================================================================================
STEP 12 — CUSTOMER DASHBOARD, MY ORDERS, AND MY TICKETS
================================================================================

Objective:
Give customers a proper account area.

Main work:
1. Customer dashboard.
2. My orders.
3. My tickets.
4. Ticket detail.
5. Download/print ticket.
6. Resend ticket email.

Files expected in patch:
- app/Http/Controllers/User/DashboardController.php
- app/Http/Controllers/User/OrderController.php
- app/Http/Controllers/User/TicketController.php
- resources/views/user/pages/dashboard.blade.php
- resources/views/user/pages/orders/*
- resources/views/user/pages/tickets/*
- routes/user.php or web.php modified
- tests/Feature/UserOrderTicketDashboardTest.php
- SNAPSHOT.md
- NEXT_STEP_PROMPT.md

Terminal test:
php artisan test --filter=UserOrderTicketDashboardTest

Webpage test:
- Login as customer
- Open dashboard
- View orders
- View tickets
- Open ticket detail

Expected result:
Customer account area works.

================================================================================
STEP 13 — QR CODE CHECK-IN SYSTEM
================================================================================

Objective:
Allow organizers/check-in staff to validate tickets at the event entrance.

Main work:
1. Add ticket_check_ins table.
2. Add check-in page.
3. Add QR lookup endpoint.
4. Prevent duplicate check-in.
5. Add organizer permission:
   - only event owner or assigned staff can check in.

6. Check-in result states:
   - valid
   - already_checked_in
   - cancelled
   - refunded
   - wrong_event
   - not_found

Files expected in patch:
- database/migrations/create_ticket_check_ins_table.php
- app/Models/TicketCheckIn.php
- app/Services/TicketCheckInService.php
- app/Http/Controllers/Organizer/CheckInController.php
- resources/views/organizer/check-in/*
- routes/organizer.php
- tests/Feature/TicketCheckInTest.php
- SNAPSHOT.md
- NEXT_STEP_PROMPT.md

Terminal test:
php artisan migrate
php artisan test --filter=TicketCheckInTest

Webpage test:
- Organizer opens check-in page
- Enters ticket code or scans QR
- Ticket is marked checked in
- Same ticket cannot be checked in twice

Expected result:
Basic entrance check-in works.

================================================================================
STEP 14 — ORGANIZER SALES REPORTS AND ATTENDEE MANAGEMENT
================================================================================

Objective:
Organizer can manage attendees and view sales.

Main work:
1. Event attendee list.
2. Export attendees CSV.
3. Sales by event.
4. Sales by ticket type.
5. Check-in count.
6. Revenue summary.

Files expected in patch:
- app/Http/Controllers/Organizer/AttendeeController.php
- app/Http/Controllers/Organizer/SalesReportController.php
- app/Exports/AttendeesExport.php if Excel package is used
- resources/views/organizer/attendees/*
- resources/views/organizer/reports/*
- routes/organizer.php
- tests/Feature/OrganizerAttendeeReportTest.php
- SNAPSHOT.md
- NEXT_STEP_PROMPT.md

Terminal test:
php artisan test --filter=OrganizerAttendeeReportTest

Webpage test:
- Organizer opens event attendees
- Organizer exports attendee list
- Organizer views sales report

Expected result:
Organizer can manage event attendees and sales.

================================================================================
STEP 15 — PLATFORM COMMISSION, ORGANIZER LEDGER, AND PAYOUT FOUNDATION
================================================================================

Objective:
Build finance foundation for marketplace revenue sharing.

Main work:
1. Add platform commission settings.
2. Add organizer ledger.
3. Add platform commission ledger.
4. Add payout requests.
5. Calculate:
   - gross sale
   - platform commission
   - payment gateway fee
   - organizer net earning

6. Organizer can request payout.
7. Admin can approve/reject/mark payout paid.

Files expected in patch:
- database/migrations/create_platform_commission_settings_table.php
- database/migrations/create_organizer_ledgers_table.php
- database/migrations/create_platform_commission_ledgers_table.php
- database/migrations/create_organizer_payouts_table.php
- app/Models/PlatformCommissionSetting.php
- app/Models/OrganizerLedger.php
- app/Models/PlatformCommissionLedger.php
- app/Models/OrganizerPayout.php
- app/Services/CommissionCalculationService.php
- app/Services/OrganizerLedgerService.php
- app/Http/Controllers/Admin/PayoutController.php
- app/Http/Controllers/Organizer/PayoutController.php
- resources/views/admin/pages/payouts/*
- resources/views/organizer/payouts/*
- tests/Feature/CommissionLedgerTest.php
- tests/Feature/OrganizerPayoutTest.php
- SNAPSHOT.md
- NEXT_STEP_PROMPT.md

Terminal test:
php artisan migrate
php artisan test --filter=CommissionLedgerTest
php artisan test --filter=OrganizerPayoutTest

Webpage test:
- Paid order creates organizer earning
- Admin sees platform commission
- Organizer requests payout
- Admin approves payout

Expected result:
Marketplace finance foundation works.

================================================================================
STEP 16 — REFUND, CANCELLATION, AND ORDER SAFETY
================================================================================

Objective:
Handle real business cases after tickets are sold.

Main work:
1. Organizer can request event cancellation.
2. Admin can approve cancellation.
3. Customer can request refund if allowed.
4. Admin/organizer can approve refund depending on rules.
5. Refund updates:
   - order status
   - ticket status
   - organizer ledger
   - platform ledger
   - payout eligibility

6. Seat/ticket availability returns if refund/cancel rules allow.

Files expected in patch:
- database/migrations/create_refund_requests_table.php
- database/migrations/create_refund_transactions_table.php
- app/Models/RefundRequest.php
- app/Models/RefundTransaction.php
- app/Services/RefundService.php
- app/Services/EventCancellationService.php
- app/Http/Controllers/User/RefundRequestController.php
- app/Http/Controllers/Organizer/EventCancellationController.php
- app/Http/Controllers/Admin/RefundController.php
- resources/views/user/pages/refunds/*
- resources/views/organizer/cancellations/*
- resources/views/admin/pages/refunds/*
- tests/Feature/RefundWorkflowTest.php
- tests/Feature/EventCancellationTest.php
- SNAPSHOT.md
- NEXT_STEP_PROMPT.md

Terminal test:
php artisan migrate
php artisan test --filter=RefundWorkflowTest
php artisan test --filter=EventCancellationTest

Webpage test:
- Customer requests refund
- Admin approves refund
- Ticket becomes refunded
- Ledger adjusts correctly

Expected result:
Refund/cancellation flow works safely.

================================================================================
STEP 17 — ORGANIZER TEAM AND STAFF ACCESS
================================================================================

Objective:
Allow organizer to invite team members and assign event staff.

Main work:
1. Organizer team members.
2. Roles:
   - owner
   - manager
   - check_in_staff
   - finance_viewer

3. Staff can access only assigned organizer/event tools.
4. Check-in staff can only check tickets.

Files expected in patch:
- database/migrations/create_organizer_team_members_table.php
- app/Models/OrganizerTeamMember.php
- app/Policies/OrganizerTeamPolicy.php
- app/Http/Controllers/Organizer/TeamMemberController.php
- app/Http/Requests/OrganizerTeamMemberRequest.php
- resources/views/organizer/team/*
- tests/Feature/OrganizerTeamAccessTest.php
- SNAPSHOT.md
- NEXT_STEP_PROMPT.md

Terminal test:
php artisan migrate
php artisan test --filter=OrganizerTeamAccessTest

Webpage test:
- Organizer adds team member
- Team member logs in
- Check-in staff can access check-in only
- Finance viewer can view reports only

Expected result:
Organizer team access works.

================================================================================
STEP 18 — NOTIFICATIONS AND EMAILS
================================================================================

Objective:
Add important transactional emails.

Main work:
1. Organizer approval email.
2. Event approved/rejected email.
3. Order confirmation email.
4. Ticket email.
5. Refund status email.
6. Payout status email.

Files expected in patch:
- app/Mail/OrganizerApprovedMail.php
- app/Mail/EventApprovedMail.php
- app/Mail/OrderConfirmationMail.php
- app/Mail/TicketIssuedMail.php
- app/Mail/RefundStatusMail.php
- app/Mail/PayoutStatusMail.php
- resources/views/emails/*
- notification service if needed
- tests/Feature/NotificationMailTest.php
- SNAPSHOT.md
- NEXT_STEP_PROMPT.md

Terminal test:
php artisan test --filter=NotificationMailTest

Webpage test:
- Complete order
- Confirm email is sent/logged
- Approve organizer/event
- Confirm email is sent/logged

Expected result:
Core marketplace emails work.

================================================================================
STEP 19 — ADMIN MARKETPLACE REPORTING DASHBOARD
================================================================================

Objective:
Give admin a complete platform overview.

Main work:
1. Dashboard cards:
   - total organizers
   - pending organizers
   - total events
   - pending events
   - total orders
   - gross sales
   - platform commission
   - pending payouts
   - refund requests

2. Reports:
   - sales by date
   - sales by organizer
   - sales by event
   - commission report
   - payout report
   - refund report

Files expected in patch:
- app/Http/Controllers/Admin/MarketplaceDashboardController.php
- app/Http/Controllers/Admin/MarketplaceReportController.php
- resources/views/admin/dashboard.blade.php modified
- resources/views/admin/pages/reports/*
- tests/Feature/AdminMarketplaceDashboardTest.php
- SNAPSHOT.md
- NEXT_STEP_PROMPT.md

Terminal test:
php artisan test --filter=AdminMarketplaceDashboardTest

Webpage test:
- Admin dashboard shows marketplace data
- Admin can filter reports by date
- Admin can view organizer/event sales

Expected result:
Admin has business-level marketplace reporting.

================================================================================
STEP 20 — SECURITY, POLICIES, RATE LIMITING, AND AUDIT LOGS
================================================================================

Objective:
Harden the platform for production.

Main work:
1. Add policies for:
   - Event
   - Venue
   - SeatingPlan
   - EventTicket
   - Order
   - Payout
   - Refund

2. Add audit log table:
   - user/admin
   - action
   - model
   - old_values
   - new_values
   - ip
   - user_agent

3. Add rate limits:
   - login
   - checkout
   - seat lock
   - payment webhook
   - check-in endpoint

4. Add security middleware checks.

Files expected in patch:
- database/migrations/create_audit_logs_table.php
- app/Models/AuditLog.php
- app/Services/AuditLogService.php
- app/Policies/*
- app/Http/Middleware/*
- bootstrap/app.php modified
- tests/Feature/SecurityPolicyTest.php
- tests/Feature/AuditLogTest.php
- SNAPSHOT.md
- NEXT_STEP_PROMPT.md

Terminal test:
php artisan migrate
php artisan test --filter=SecurityPolicyTest
php artisan test --filter=AuditLogTest

Webpage test:
- Organizer cannot access other organizer event
- Audit log records important admin actions
- Seat lock endpoint is rate-limited

Expected result:
Authorization and audit safety are production-ready.

================================================================================
STEP 21 — FINAL UI POLISH AND MARKETPLACE UX
================================================================================

Objective:
Polish public, organizer, admin, and customer flows.

Main work:
1. Improve homepage marketplace sections:
   - featured events
   - trending events
   - categories
   - online events
   - city events

2. Improve organizer dashboard UI.
3. Improve event detail ticket box.
4. Improve checkout UX.
5. Improve ticket display.
6. Add empty states and error messages.

Files expected in patch:
- frontend Blade files
- organizer Blade files
- admin Blade files
- user Blade files
- CSS/JS files only if necessary
- tests/Feature/MarketplaceUiSmokeTest.php
- SNAPSHOT.md
- NEXT_STEP_PROMPT.md

Terminal test:
php artisan test --filter=MarketplaceUiSmokeTest

Webpage test:
- Browse homepage
- Open event detail
- Buy ticket
- View customer ticket
- Open organizer dashboard
- Open admin dashboard

Expected result:
System feels like a complete product instead of a patched project.

================================================================================
STEP 22 — FINAL QA, SEEDERS, DEMO DATA, AND RELEASE HARDENING
================================================================================

Objective:
Create final demo-ready and production-ready version.

Main work:
1. Complete demo seeders:
   - admins
   - users
   - organizers
   - venues
   - seating plans
   - events
   - tickets
   - orders
   - payouts
   - refunds

2. Add full test suite:
   - organizer onboarding
   - event approval
   - venue CRUD
   - seating plan
   - ticket creation
   - cart
   - checkout
   - payment
   - check-in
   - refund
   - payout
   - policy security

3. Add deployment checklist.
4. Add production .env.example.
5. Add backup/restore notes.
6. Add final README.

Files expected in patch:
- database/seeders/DemoMarketplaceSeeder.php
- tests/Feature/MarketplaceEndToEndTest.php
- tests/Feature/MarketplaceSecurityRegressionTest.php
- README.md
- DEPLOYMENT.md
- .env.example
- SNAPSHOT.md
- NEXT_STEP_PROMPT.md

Terminal test:
php artisan migrate:fresh --seed
php artisan db:seed --class=DemoMarketplaceSeeder
php artisan test

Webpage test:
- Admin login works
- Organizer login works
- Customer login works
- Event purchase works
- Ticket check-in works
- Payout workflow works
- Refund workflow works

Expected result:
Project is ready for real demo, client review, and production hardening.

================================================================================
RECOMMENDED CHATGPT PROMPT TEMPLATE FOR EACH STEP
================================================================================

Use this prompt for every step by changing the step number and title:

Act as a Senior Laravel Architect, Senior Multi-Vendor Marketplace Architect,
Senior Laravel Refactoring Expert, Senior QA Engineer, Senior Project Manager,
and Full-Stack Developer.

I am a complete beginner in Laravel.

I have uploaded my latest current Laravel Event Management project codebase as ZIP.

You must inspect my latest uploaded codebase first and generate ONLY:

[STEP NUMBER] — [STEP TITLE]

This is a patch-based implementation.
Do not rebuild the project from scratch.
Do not regenerate old modules.
Do not skip required files.
Do not include vendor, node_modules, .env, logs, cache, or generated files.

Current target:
Convert my current Laravel event project into a full Eventbrite-style multi-vendor
event marketplace.

Important rules:
- Generate a real downloadable patch ZIP.
- Patch ZIP must contain ONLY new and modified files.
- Preserve Laravel folder structure.
- Include migrations, models, controllers, requests, services, policies, routes,
  Blade views, seeders, and tests required for this step.
- Include SNAPSHOT.md.
- Include NEXT_STEP_PROMPT.md.
- Include beginner-friendly testing instructions.
- Include terminal commands.
- Include webpage testing instructions.
- Preserve existing working code unless this step explicitly needs to replace it.
- Use the newer ticketing architecture:
  SeatingPlan, SeatingSection, SeatingSeat, SeatLock, EventTicket, Order,
  OrderItem, OrderTicket.
- Do not use old broken legacy architecture:
  Booking, TemporaryBooking, TemporaryBookingSeat, EventSeat, EventSeatType.

Now generate ONLY this step:
[PASTE STEP DETAILS HERE]

================================================================================
END OF ROADMAP
================================================================================
