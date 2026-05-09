<?php

namespace App\Services;

use App\Models\Order;
use App\Models\PlatformCommissionSetting;

class CommissionCalculationService
{
    public function calculateForOrder(Order $order, ?PlatformCommissionSetting $setting = null): array
    {
        $setting = $setting ?: PlatformCommissionSetting::activeSetting();
        $gross = round((float) $order->total, 2);
        $commission = 0.00;

        if ($setting->commission_type === PlatformCommissionSetting::TYPE_FIXED) {
            $commission = (float) $setting->commission_value;
        }

        if ($setting->commission_type === PlatformCommissionSetting::TYPE_PERCENT) {
            $commission = $gross * ((float) $setting->commission_value / 100);
        }

        $commission = round(min(max($commission, 0), $gross), 2);
        $net = round(max(0, $gross - $commission), 2);

        return [
            'gross_amount' => $gross,
            'commission_amount' => $commission,
            'organizer_net_amount' => $net,
            'currency' => $order->currency ?: 'BDT',
            'commission_type' => $setting->commission_type,
            'commission_value' => (float) $setting->commission_value,
            'setting_id' => $setting->id,
        ];
    }
}
