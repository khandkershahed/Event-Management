<?php

namespace App\Services\Dashboard;

use App\Models\Event;
use App\Models\MarketplaceEventReview;
use App\Models\MarketplaceModerationFlag;
use App\Models\MarketplaceSupportTicket;
use App\Models\Order;
use App\Models\OrderTicket;
use App\Models\OrganizerPayout;
use App\Models\OrganizerProfile;
use App\Models\PlatformCommissionLedger;
use App\Models\RefundRequest;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AdminDashboardService
{
    public function data(): array
    {
        $from = now()->subDays(29)->startOfDay();

        return [
            'stats' => [
                'total_users' => User::query()->count(),
                'total_organizers' => OrganizerProfile::query()->count(),
                'pending_organizers' => OrganizerProfile::query()->where('status', OrganizerProfile::STATUS_PENDING)->count(),
                'approved_organizers' => OrganizerProfile::query()->where('status', OrganizerProfile::STATUS_APPROVED)->count(),
                'total_events' => Event::query()->count(),
                'pending_events' => Event::query()->pendingReview()->count(),
                'published_events' => Event::query()->where('status', Event::STATUS_PUBLISHED)->count(),
                'total_orders' => Order::query()->count(),
                'paid_orders' => Order::query()->whereIn('status', [Order::STATUS_PAID, Order::STATUS_COMPLETED])->count(),
                'pending_payment_orders' => Order::query()->where('status', Order::STATUS_PENDING_PAYMENT)->count(),
                'issued_tickets' => OrderTicket::query()->where('status', OrderTicket::STATUS_ISSUED)->count(),
                'gross_sales' => (float) Order::query()->where('payment_status', Order::PAYMENT_PAID)->sum('total'),
                'platform_commission' => (float) PlatformCommissionLedger::query()->where('status', PlatformCommissionLedger::STATUS_POSTED)->sum('commission_amount'),
                'refunds' => (float) RefundRequest::query()->whereIn('status', [RefundRequest::STATUS_APPROVED, RefundRequest::STATUS_PENDING])->sum('amount'),
                'pending_refunds' => RefundRequest::query()->where('status', RefundRequest::STATUS_PENDING)->count(),
                'payouts' => (float) OrganizerPayout::query()->whereIn('status', [OrganizerPayout::STATUS_APPROVED, OrganizerPayout::STATUS_PAID, OrganizerPayout::STATUS_PENDING])->sum('amount'),
                'pending_payouts' => OrganizerPayout::query()->where('status', OrganizerPayout::STATUS_PENDING)->count(),
                'support_tickets' => MarketplaceSupportTicket::query()->count(),
                'open_support_tickets' => MarketplaceSupportTicket::query()->whereIn('status', [MarketplaceSupportTicket::STATUS_OPEN, MarketplaceSupportTicket::STATUS_PENDING, MarketplaceSupportTicket::STATUS_WAITING_ORGANIZER])->count(),
                'moderation_flags' => MarketplaceModerationFlag::query()->where('status', 'active')->count(),
                'reviews' => MarketplaceEventReview::query()->count(),
                'unread_admin_notifications' => DB::table('notifications')->whereNull('read_at')->where('notifiable_type', 'App\\Models\\Admin')->count(),
            ],
            'recent_orders' => Order::query()->with(['user', 'event.organizerProfile'])->latest()->limit(8)->get(),
            'pending_organizers' => OrganizerProfile::query()->with('user')->where('status', OrganizerProfile::STATUS_PENDING)->latest()->limit(8)->get(),
            'pending_events' => Event::query()->with('organizerProfile')->pendingReview()->latest()->limit(8)->get(),
            'pending_refunds' => RefundRequest::query()->with(['order', 'user', 'event'])->where('status', RefundRequest::STATUS_PENDING)->latest()->limit(8)->get(),
            'pending_payouts' => OrganizerPayout::query()->with('organizerProfile')->where('status', OrganizerPayout::STATUS_PENDING)->latest()->limit(8)->get(),
            'open_support_tickets' => MarketplaceSupportTicket::query()->with(['user', 'organizerProfile'])->whereIn('status', [MarketplaceSupportTicket::STATUS_OPEN, MarketplaceSupportTicket::STATUS_PENDING, MarketplaceSupportTicket::STATUS_WAITING_ORGANIZER])->latest()->limit(8)->get(),
            'sales_chart' => $this->dailySales($from),
            'orders_by_status' => $this->countsByStatus(Order::class),
            'events_by_status' => $this->countsByStatus(Event::class),
            'payout_refund_summary' => [
                'pending_payouts' => OrganizerPayout::query()->where('status', OrganizerPayout::STATUS_PENDING)->count(),
                'paid_payouts' => OrganizerPayout::query()->where('status', OrganizerPayout::STATUS_PAID)->count(),
                'pending_refunds' => RefundRequest::query()->where('status', RefundRequest::STATUS_PENDING)->count(),
                'approved_refunds' => RefundRequest::query()->where('status', RefundRequest::STATUS_APPROVED)->count(),
            ],
        ];
    }

    public function sidebarCounters(): array
    {
        return [
            'pending_organizers' => OrganizerProfile::query()->where('status', OrganizerProfile::STATUS_PENDING)->count(),
            'pending_events' => Event::query()->pendingReview()->count(),
            'pending_refunds' => RefundRequest::query()->where('status', RefundRequest::STATUS_PENDING)->count(),
            'pending_payouts' => OrganizerPayout::query()->where('status', OrganizerPayout::STATUS_PENDING)->count(),
            'open_support' => MarketplaceSupportTicket::query()->whereIn('status', [MarketplaceSupportTicket::STATUS_OPEN, MarketplaceSupportTicket::STATUS_PENDING, MarketplaceSupportTicket::STATUS_WAITING_ORGANIZER])->count(),
            'moderation_flags' => MarketplaceModerationFlag::query()->where('status', 'active')->count(),
            'unread_notifications' => DB::table('notifications')->whereNull('read_at')->where('notifiable_type', 'App\\Models\\Admin')->count(),
        ];
    }

    private function dailySales(Carbon $from): array
    {
        $rows = Order::query()
            ->selectRaw('DATE(created_at) as sale_date, COUNT(*) as orders_count, COALESCE(SUM(total), 0) as sales_total')
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

    private function countsByStatus(string $modelClass): array
    {
        return $modelClass::query()
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->map(fn ($total) => (int) $total)
            ->all();
    }
}
