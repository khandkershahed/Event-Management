<?php

namespace App\Services\Dashboard;

use App\Models\Event;
use App\Models\MarketplaceSupportTicket;
use App\Models\Order;
use App\Models\OrderTicket;
use App\Models\OrganizerLedger;
use App\Models\OrganizerPayout;
use App\Models\OrganizerProfile;
use App\Models\RefundRequest;
use App\Models\TicketCheckIn;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class OrganizerDashboardService
{
    public function data(OrganizerProfile $profile): array
    {
        $eventIds = $profile->events()->pluck('id');
        $orderQuery = Order::query()->whereIn('event_id', $eventIds);
        $ledgerBalance = (float) $profile->organizerLedgers()->where('status', OrganizerLedger::STATUS_POSTED)->selectRaw("COALESCE(SUM(CASE WHEN direction = 'credit' THEN amount ELSE -amount END),0) as balance")->value('balance');

        return [
            'stats' => [
                'total_events' => $profile->events()->count(),
                'draft_events' => $profile->events()->where('status', Event::STATUS_DRAFT)->count(),
                'submitted_events' => $profile->events()->whereIn('status', [Event::STATUS_SUBMITTED, Event::STATUS_UNDER_REVIEW])->count(),
                'published_events' => $profile->events()->where('status', Event::STATUS_PUBLISHED)->count(),
                'venues' => $profile->venues()->count(),
                'seating_plans' => $profile->seatingPlans()->count(),
                'ticket_types' => DB::table('event_tickets')->whereIn('event_id', $eventIds)->count(),
                'total_orders' => (clone $orderQuery)->count(),
                'paid_orders' => (clone $orderQuery)->whereIn('status', [Order::STATUS_PAID, Order::STATUS_COMPLETED])->count(),
                'pending_payment_orders' => (clone $orderQuery)->where('status', Order::STATUS_PENDING_PAYMENT)->count(),
                'revenue' => (float) (clone $orderQuery)->where('payment_status', Order::PAYMENT_PAID)->sum('total'),
                'net_earnings' => (float) $profile->organizerLedgers()->where('status', OrganizerLedger::STATUS_POSTED)->where('direction', OrganizerLedger::DIRECTION_CREDIT)->sum('amount'),
                'issued_tickets' => OrderTicket::query()->whereIn('event_id', $eventIds)->where('status', OrderTicket::STATUS_ISSUED)->count(),
                'checked_in_tickets' => TicketCheckIn::query()->whereIn('event_id', $eventIds)->where('result', TicketCheckIn::RESULT_VALID)->count(),
                'pending_refunds' => RefundRequest::query()->where('organizer_profile_id', $profile->id)->where('status', RefundRequest::STATUS_PENDING)->count(),
                'support_tickets' => MarketplaceSupportTicket::query()->where('organizer_profile_id', $profile->id)->count(),
                'pending_payouts' => OrganizerPayout::query()->where('organizer_profile_id', $profile->id)->where('status', OrganizerPayout::STATUS_PENDING)->count(),
                'paid_payouts' => OrganizerPayout::query()->where('organizer_profile_id', $profile->id)->where('status', OrganizerPayout::STATUS_PAID)->count(),
                'ledger_balance' => $ledgerBalance,
            ],
            'recent_orders' => Order::query()->with(['user', 'event'])->whereIn('event_id', $eventIds)->latest()->limit(8)->get(),
            'latest_events' => $profile->events()->latest()->limit(8)->get(),
            'recent_payouts' => $profile->organizerPayouts()->latest()->limit(8)->get(),
            'refund_requests' => RefundRequest::query()->with(['order', 'user', 'event'])->where('organizer_profile_id', $profile->id)->latest()->limit(8)->get(),
            'support_tickets' => MarketplaceSupportTicket::query()->with(['user', 'event'])->where('organizer_profile_id', $profile->id)->latest()->limit(8)->get(),
            'recent_check_ins' => TicketCheckIn::query()->with(['orderTicket', 'event'])->whereIn('event_id', $eventIds)->latest()->limit(8)->get(),
            'sales_chart' => $this->dailySales($eventIds->all()),
            'orders_by_status' => Order::query()->whereIn('event_id', $eventIds)->select('status', DB::raw('COUNT(*) as total'))->groupBy('status')->pluck('total', 'status')->map(fn ($total) => (int) $total)->all(),
            'ticket_sales_by_event' => OrderTicket::query()->whereIn('event_id', $eventIds)->select('event_id', DB::raw('COUNT(*) as total'))->groupBy('event_id')->pluck('total', 'event_id')->all(),
            'payout_refund_summary' => [
                'pending_payouts' => OrganizerPayout::query()->where('organizer_profile_id', $profile->id)->where('status', OrganizerPayout::STATUS_PENDING)->count(),
                'paid_payouts' => OrganizerPayout::query()->where('organizer_profile_id', $profile->id)->where('status', OrganizerPayout::STATUS_PAID)->count(),
                'pending_refunds' => RefundRequest::query()->where('organizer_profile_id', $profile->id)->where('status', RefundRequest::STATUS_PENDING)->count(),
                'approved_refunds' => RefundRequest::query()->where('organizer_profile_id', $profile->id)->where('status', RefundRequest::STATUS_APPROVED)->count(),
            ],
        ];
    }

    public function sidebarCounters(OrganizerProfile $profile): array
    {
        $eventIds = $profile->events()->pluck('id');

        return [
            'notifications' => DB::table('notifications')->whereNull('read_at')->where('notifiable_type', 'App\\Models\\User')->where('notifiable_id', $profile->user_id)->count(),
            'support' => MarketplaceSupportTicket::query()->where('organizer_profile_id', $profile->id)->whereIn('status', [MarketplaceSupportTicket::STATUS_OPEN, MarketplaceSupportTicket::STATUS_PENDING, MarketplaceSupportTicket::STATUS_WAITING_ORGANIZER])->count(),
            'pending_events' => $profile->events()->whereIn('status', [Event::STATUS_DRAFT, Event::STATUS_SUBMITTED, Event::STATUS_UNDER_REVIEW])->count(),
            'orders' => Order::query()->whereIn('event_id', $eventIds)->count(),
            'check_ins' => TicketCheckIn::query()->whereIn('event_id', $eventIds)->where('result', TicketCheckIn::RESULT_VALID)->count(),
            'payouts' => OrganizerPayout::query()->where('organizer_profile_id', $profile->id)->where('status', OrganizerPayout::STATUS_PENDING)->count(),
            'reviews' => $profile->marketplaceEventReviews()->where('status', 'pending')->count(),
        ];
    }

    private function dailySales(array $eventIds): array
    {
        $from = now()->subDays(29)->startOfDay();
        $rows = Order::query()
            ->selectRaw('DATE(created_at) as sale_date, COUNT(*) as orders_count, COALESCE(SUM(total), 0) as sales_total')
            ->whereIn('event_id', $eventIds)
            ->where('payment_status', Order::PAYMENT_PAID)
            ->where('created_at', '>=', $from)
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('sale_date')
            ->get()
            ->keyBy('sale_date');

        $days = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            $row = $rows->get($date);
            $days[] = [
                'label' => Carbon::parse($date)->format('M d'),
                'orders' => (int) ($row->orders_count ?? 0),
                'sales' => (float) ($row->sales_total ?? 0),
            ];
        }

        return $days;
    }
}
