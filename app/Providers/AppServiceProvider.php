<?php

namespace App\Providers;

use Exception;
use Carbon\Carbon;
use App\Models\Setting;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Set default values
        View::share('setting', null);
        View::share('getOnlineVisitorCount', null);
        View::share('getTodayVisitorCount', null);
        View::share('formattedHours', null);
        $setting = null;
        try {
            if (Schema::hasTable('settings')) {
                $setting = Setting::first();
                View::share('setting', $setting);
            }
        } catch (Exception $e) {
            
        }


        Carbon::setLocale('en'); // Optional: Set the locale to English (can be changed if needed)
        date_default_timezone_set('Asia/Dhaka'); // Set PHP default timezone

        $businessHours = json_decode(optional($setting)->business_hours, true);

        // Define the day order and proper case labels
        $weekDays = [
            'saturday'  => 'Saturday',
            'sunday'    => 'Sunday',
            'monday'    => 'Monday',
            'tuesday'   => 'Tuesday',
            'wednesday' => 'Wednesday',
            'thursday'  => 'Thursday',
            'friday'    => 'Friday',
        ];

        $grouped = [];
        $currentGroup = null;

        foreach ($weekDays as $dayKey => $dayLabel) {
            $start = $businessHours[$dayKey]['start'] ?? null;
            $end = $businessHours[$dayKey]['end'] ?? null;

            // Use null to represent closed days
            $hours = $start && $end ? $start . '-' . $end : 'closed';

            if ($currentGroup && $grouped[$currentGroup]['hours'] === $hours) {
                $grouped[$currentGroup]['days'][] = $dayLabel;
            } else {
                $currentGroup = uniqid();
                $grouped[$currentGroup] = [
                    'hours' => $hours,
                    'days'  => [$dayLabel],
                ];
            }
        }

        $formattedHours = [];

        foreach ($grouped as $group) {
            $days = $group['days'];
            $hours = $group['hours'];

            if ($hours === 'closed') {
                $formattedHours[] = implode('–', [$days[0]]) . ': Closed';
            } else {
                [$start, $end] = explode('-', $hours);
                $formattedStart = Carbon::createFromTimeString($start)->format('gA'); // 09:00 -> 9AM
                $formattedEnd = Carbon::createFromTimeString($end)->format('gA');     // 18:00 -> 6PM
                $formattedHours[] = implode('–', [$days[0], end($days)]) . ": {$formattedStart} – {$formattedEnd}";
            }
        }
        try {
            // Check for table existence and set actual values
            if (Schema::hasTable('settings')) {
                View::share('setting', $setting);
                View::share('formattedHours', $formattedHours);
            }
        } catch (Exception $e) {
            // Log the exception if needed
        }
        Paginator::useBootstrap();
    }
}
