<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PlatformCommissionSetting;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PlatformCommissionController extends Controller
{
    public function edit(): View
    {
        return view('admin.pages.platform-commission.edit', [
            'setting' => PlatformCommissionSetting::activeSetting(),
            'types' => PlatformCommissionSetting::types(),
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'commission_type' => ['required', 'in:' . implode(',', PlatformCommissionSetting::types())],
            'commission_value' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        PlatformCommissionSetting::query()->update(['is_active' => false]);
        PlatformCommissionSetting::create([
            'name' => $data['name'],
            'commission_type' => $data['commission_type'],
            'commission_value' => $data['commission_value'],
            'description' => $data['description'] ?? null,
            'is_active' => true,
        ]);

        return redirect()->route('admin.platform-commission.edit')->with('success', 'Platform commission setting updated.');
    }
}
