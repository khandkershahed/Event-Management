<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventCancellationRequest;
use App\Models\Order;
use App\Models\OrganizerPayout;
use App\Models\OrganizerProfile;
use App\Models\PlatformCommissionLedger;
use App\Models\RefundRequest;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class MarketplaceReportController extends Controller
{
    public function dashboard(Request $request): View
    {
        $paidOrders = $this->paidOrderQuery($request);

        $kpis = [
            'total_organizers' => OrganizerProfile::query()->count(),
            'pending_organizers' => OrganizerProfile::query()->where('status', OrganizerProfile::STATUS_PENDING)->count(),
            'total_events' => Event::query()->count(),
            'pending_event_approvals' => Event::query()->whereIn('status', [Event::STATUS_SUBMITTED, Event::STATUS_UNDER_REVIEW])->count(),
            'published_events' => Event::query()->where('status', Event::STATUS_PUBLISHED)->count(),
            'total_orders' => Order::query()->count(),
            'paid_orders' => (clone $paidOrders)->count(),
            'gross_sales' => (clone $paidOrders)->sum('total'),
            'platform_commission' => $this->commissionQuery($request)->sum('commission_amount'),
            'pending_payouts' => OrganizerPayout::query()->where('status', OrganizerPayout::STATUS_PENDING)->sum('amount'),
            'refund_requests' => RefundRequest::query()->count(),
            'event_cancellation_requests' => EventCancellationRequest::query()->count(),
        ];

        $organizers = $this->organizerOptions();
        $events = $this->eventOptions();

        return view('admin.pages.marketplace-reports.dashboard', compact('kpis', 'organizers', 'events'));
    }

    public function salesByDate(Request $request): View
    {
        $orders = $this->paidOrderQuery($request);

        $rows = $orders
            ->selectRaw('DATE(orders.created_at) as sale_date')
            ->selectRaw('COUNT(*) as total_orders')
            ->selectRaw('COUNT(*) as paid_orders')
            ->selectRaw('COALESCE(SUM(orders.total), 0) as gross_sales')
            ->groupBy(DB::raw('DATE(orders.created_at)'))
            ->orderByDesc('sale_date')
            ->paginate(20)
            ->withQueryString();

        $organizers = $this->organizerOptions();
        $events = $this->eventOptions();

        return view('admin.pages.marketplace-reports.sales-by-date', compact('rows', 'organizers', 'events'));
    }

    public function salesByOrganizer(Request $request): View
    {
        $rows = $this->paidOrderQuery($request)
            ->leftJoin('events', 'events.id', '=', 'orders.event_id')
            ->leftJoin('organizer_profiles', 'organizer_profiles.id', '=', 'events.organizer_profile_id')
            ->leftJoin('platform_commission_ledgers', 'platform_commission_ledgers.order_id', '=', 'orders.id')
            ->selectRaw('organizer_profiles.id as organizer_id')
            ->selectRaw('organizer_profiles.organization_name as organizer_name')
            ->selectRaw('COUNT(DISTINCT events.id) as total_events')
            ->selectRaw('COUNT(DISTINCT orders.id) as paid_orders')
            ->selectRaw('COALESCE(SUM(orders.total), 0) as gross_sales')
            ->selectRaw('COALESCE(SUM(platform_commission_ledgers.commission_amount), 0) as platform_commission')
            ->groupBy('organizer_profiles.id', 'organizer_profiles.organization_name')
            ->orderByDesc('gross_sales')
            ->paginate(20)
            ->withQueryString();

        $organizers = $this->organizerOptions();
        $events = $this->eventOptions();

        return view('admin.pages.marketplace-reports.sales-by-organizer', compact('rows', 'organizers', 'events'));
    }

    public function salesByEvent(Request $request): View
    {
        $rows = $this->paidOrderQuery($request)
            ->leftJoin('events', 'events.id', '=', 'orders.event_id')
            ->leftJoin('organizer_profiles', 'organizer_profiles.id', '=', 'events.organizer_profile_id')
            ->selectRaw('events.id as event_id')
            ->selectRaw('events.name as event_title')
            ->selectRaw('organizer_profiles.organization_name as organizer_name')
            ->selectRaw('COUNT(DISTINCT orders.id) as paid_orders')
            ->selectRaw('(SELECT COUNT(*) FROM order_tickets WHERE order_tickets.event_id = events.id) as tickets_sold')
            ->selectRaw('COALESCE(SUM(orders.total), 0) as gross_sales')
            ->groupBy('events.id', 'events.name', 'organizer_profiles.organization_name')
            ->orderByDesc('gross_sales')
            ->paginate(20)
            ->withQueryString();

        $organizers = $this->organizerOptions();
        $events = $this->eventOptions();

        return view('admin.pages.marketplace-reports.sales-by-event', compact('rows', 'organizers', 'events'));
    }

    public function commissions(Request $request): View
    {
        $rows = $this->commissionQuery($request)
            ->with(['organizerProfile', 'order.event'])
            ->latest('created_at')
            ->paginate(20)
            ->withQueryString();

        $organizers = $this->organizerOptions();
        $events = $this->eventOptions();
        $statuses = [PlatformCommissionLedger::STATUS_POSTED, PlatformCommissionLedger::STATUS_VOID];

        return view('admin.pages.marketplace-reports.commissions', compact('rows', 'organizers', 'events', 'statuses'));
    }

    public function payouts(Request $request): View
    {
        $rows = OrganizerPayout::query()
            ->with('organizerProfile')
            ->when($this->validId($request->input('organizer_id')), fn (Builder $query) => $query->where('organizer_profile_id', (int) $request->input('organizer_id')))
            ->when($this->validStatus($request->input('status'), OrganizerPayout::statuses()), fn (Builder $query) => $query->where('status', $request->input('status')))
            ->when($this->safeDate($request->input('date_from')), fn (Builder $query, Carbon $date) => $query->whereDate('created_at', '>=', $date->toDateString()))
            ->when($this->safeDate($request->input('date_to')), fn (Builder $query, Carbon $date) => $query->whereDate('created_at', '<=', $date->toDateString()))
            ->latest('created_at')
            ->paginate(20)
            ->withQueryString();

        $organizers = $this->organizerOptions();
        $statuses = OrganizerPayout::statuses();

        return view('admin.pages.marketplace-reports.payouts', compact('rows', 'organizers', 'statuses'));
    }

    public function refunds(Request $request): View
    {
        $rows = RefundRequest::query()
            ->with(['order', 'organizerProfile', 'event', 'transactions'])
            ->when($this->validId($request->input('organizer_id')), fn (Builder $query) => $query->where('organizer_profile_id', (int) $request->input('organizer_id')))
            ->when($this->validId($request->input('event_id')), fn (Builder $query) => $query->where('event_id', (int) $request->input('event_id')))
            ->when($this->validStatus($request->input('status'), RefundRequest::statuses()), fn (Builder $query) => $query->where('status', $request->input('status')))
            ->when($this->safeDate($request->input('date_from')), fn (Builder $query, Carbon $date) => $query->whereDate('created_at', '>=', $date->toDateString()))
            ->when($this->safeDate($request->input('date_to')), fn (Builder $query, Carbon $date) => $query->whereDate('created_at', '<=', $date->toDateString()))
            ->latest('created_at')
            ->paginate(20)
            ->withQueryString();

        $organizers = $this->organizerOptions();
        $events = $this->eventOptions();
        $statuses = RefundRequest::statuses();

        return view('admin.pages.marketplace-reports.refunds', compact('rows', 'organizers', 'events', 'statuses'));
    }

    private function paidOrderQuery(Request $request): QueryBuilder
    {
        return DB::table('orders')
            ->where('orders.payment_status', Order::PAYMENT_PAID)
            ->whereIn('orders.status', [Order::STATUS_PAID, Order::STATUS_COMPLETED])
            ->when($this->validId($request->input('organizer_id')), function (QueryBuilder $query) use ($request): void {
                $query->join('events as organizer_filter_events', 'organizer_filter_events.id', '=', 'orders.event_id')
                    ->where('organizer_filter_events.organizer_profile_id', (int) $request->input('organizer_id'));
            })
            ->when($this->validId($request->input('event_id')), fn (QueryBuilder $query) => $query->where('orders.event_id', (int) $request->input('event_id')))
            ->when($this->safeDate($request->input('date_from')), fn (QueryBuilder $query, Carbon $date) => $query->whereDate('orders.created_at', '>=', $date->toDateString()))
            ->when($this->safeDate($request->input('date_to')), fn (QueryBuilder $query, Carbon $date) => $query->whereDate('orders.created_at', '<=', $date->toDateString()));
    }

    private function commissionQuery(Request $request): Builder
    {
        return PlatformCommissionLedger::query()
            ->when($this->validId($request->input('organizer_id')), fn (Builder $query) => $query->where('organizer_profile_id', (int) $request->input('organizer_id')))
            ->when($this->validId($request->input('event_id')), function (Builder $query) use ($request): void {
                $query->whereHas('order', fn (Builder $orderQuery) => $orderQuery->where('event_id', (int) $request->input('event_id')));
            })
            ->when($this->validStatus($request->input('status'), [PlatformCommissionLedger::STATUS_POSTED, PlatformCommissionLedger::STATUS_VOID]), fn (Builder $query) => $query->where('status', $request->input('status')))
            ->when($this->safeDate($request->input('date_from')), fn (Builder $query, Carbon $date) => $query->whereDate('created_at', '>=', $date->toDateString()))
            ->when($this->safeDate($request->input('date_to')), fn (Builder $query, Carbon $date) => $query->whereDate('created_at', '<=', $date->toDateString()));
    }

    private function safeDate(?string $value): ?Carbon
    {
        if (blank($value)) {
            return null;
        }

        try {
            return Carbon::parse($value);
        } catch (\Throwable) {
            return null;
        }
    }

    private function validId(mixed $value): bool
    {
        return filled($value) && filter_var($value, FILTER_VALIDATE_INT) !== false && (int) $value > 0;
    }

    private function validStatus(?string $value, array $allowed): bool
    {
        return filled($value) && in_array($value, $allowed, true);
    }

    private function organizerOptions()
    {
        return OrganizerProfile::query()->orderBy('organization_name')->get(['id', 'organization_name']);
    }

    private function eventOptions()
    {
        return Event::query()->orderBy('name')->get(['id', 'name']);
    }
}
