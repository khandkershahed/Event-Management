<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\PlatformCommissionSetting;
use App\Services\OrganizerLedgerService;
use Illuminate\Database\Seeder;

class MarketplaceFinanceSeeder extends Seeder
{
    public function run(): void
    {
        PlatformCommissionSetting::query()->updateOrCreate(
            ['name' => 'Default Commission'],
            [
                'commission_type' => PlatformCommissionSetting::TYPE_PERCENT,
                'commission_value' => 5,
                'is_active' => true,
                'description' => 'Default marketplace commission used for demo payouts.',
            ]
        );

        $ledgerService = app(OrganizerLedgerService::class);

        Order::query()
            ->where('payment_status', Order::PAYMENT_PAID)
            ->where('total', '>', 0)
            ->with('event.organizerProfile')
            ->limit(20)
            ->get()
            ->each(fn (Order $order) => $ledgerService->postPaidOrder($order));
    }
}
