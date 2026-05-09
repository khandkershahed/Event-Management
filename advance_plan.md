ADVANCED IMPLEMENTATION PLAN AFTER 30-STEP MARKETPLACE COMPLETION

ADVANCED STEP A1 — Organizer Login Routing + Role-Based Landing
Scope:
- Detect approved organizer accounts after normal `/login`.
- Redirect approved organizers to `/organizer/dashboard`.
- Keep normal customers redirected to `/user/dashboard`.
- Keep moderators/admin guards unchanged.
- Add regression tests for customer vs organizer login routing.

ADVANCED STEP A2 — Organizer/Admin Visual Seat Map Designer Completion
Scope:
- Give organizers the same visual seat-map designer experience as admin.
- Allow organizer-owned seating plans to open `/organizer/seating-plans/{id}/designer`.
- Save visual `design_json`.
- Rebuild `seating_sections` and `seating_seats` from designer output.
- Preserve locked-plan restrictions.
- Preserve organizer ownership restrictions.

ADVANCED STEP A3 — Public Visual Seat Selection
Scope:
- Render the same saved visual seat map on the customer seat-selection page.
- Show seat states: available, selected, locked, sold, unavailable.
- Allow customer to select a seat from the map.
- Allow customer to cancel their own selected seat lock before checkout.
- Keep existing list fallback for old seating plans without designer JSON.

ADVANCED STEP A4 — Ticket Type / Section Assignment Matrix
Scope:
- Improve ticket setup so organizers/admins can visually assign ticket types to seating sections.
- Use existing `event_tickets.valid_section_ids`.
- Show which ticket type controls which section.
- Add validation to prevent invalid section/ticket combinations.
- Do not change checkout/order/payment logic.

ADVANCED STEP A5 — Dynamic Customer Dashboard Completion
Scope:
- Add real stats for orders, paid orders, pending payments, issued tickets, saved events, followed organizers, support tickets, refund requests, and unread notifications.
- Add recent saved events, recent orders, recent tickets, support tickets, and refund activity.
- Improve dashboard empty states.

ADVANCED STEP A6 — User Panel Completely dynamic with each and every section
Scope:
- Make User sidebar dynamic.
- Check and make each user panel page() dynamic.
- Preserve UI.
- Make User profile update sections dynamic.
- No Demo options will be present in frontend and user panel.

ADVANCED STEP A7 — Final Seat Map QA + Production Hardening
Scope:
- Add regression tests for organizer designer access.
- Add regression tests for public visual seat selection.
- Add route safety tests.
- Verify no old Booking/TemporaryBooking/EventSeat/EventSeatType architecture returns.


