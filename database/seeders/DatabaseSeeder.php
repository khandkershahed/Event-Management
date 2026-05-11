<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            RolePermissionSeeder::class,
            SettingSeeder::class,
            OrganizerSeeder::class,
            VenueSeeder::class,
            SeatingPlanSeeder::class,
            EventMarketplaceSeeder::class,
            EventTicketSeeder::class,
            CartSeatLockSeeder::class,
            OrderDemoSeeder::class,
            UserOrderTicketSeeder::class,
            OrganizerReportSeeder::class,
            MarketplaceFinanceSeeder::class,
            RefundCancellationSeeder::class,
            OrganizerTeamSeeder::class,
            OrganizerPayoutMethodSeeder::class,
            DemoMarketplaceSeeder::class,
            AdvancedSeatMapDesignSeeder::class,
            AdvancedPublicSeatSelectionSeeder::class,
            AdvancedTicketSectionMatrixSeeder::class,
            AdvancedDynamicDashboardSeeder::class,
            AdvancedDashboardOperationsSeeder::class,
            AdvancedOrganizerAuthEventPanelSeeder::class,
        ]);
    }
}
