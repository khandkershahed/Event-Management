<?php

namespace App\Providers;

use App\Models\Setting;
use Carbon\Carbon;
use Exception;
use Illuminate\Pagination\Paginator;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use stdClass;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $setting = $this->defaultSetting();

        try {
            if (Schema::hasTable('settings')) {
                $setting = Setting::first() ?: $setting;
            }
        } catch (Exception $e) {
            // Keep route:list, fresh installs, and tests safe before database setup.
        }

        Carbon::setLocale('en');
        date_default_timezone_set('Asia/Dhaka');

        View::share('setting', $setting);
        View::share('getOnlineVisitorCount', null);
        View::share('getTodayVisitorCount', null);
        View::share('formattedHours', $this->formatBusinessHours($setting));

        Paginator::useBootstrap();

        $this->configureMarketplaceRateLimits();
    }

    private function configureMarketplaceRateLimits(): void
    {
        RateLimiter::for('marketplace-cart', function (Request $request) {
            return Limit::perMinute(30)->by($this->rateLimitKey($request, 'cart'));
        });

        RateLimiter::for('marketplace-checkout', function (Request $request) {
            return Limit::perMinute(10)->by($this->rateLimitKey($request, 'checkout'));
        });

        RateLimiter::for('marketplace-seat-lock', function (Request $request) {
            return Limit::perMinute(30)->by($this->rateLimitKey($request, 'seat-lock'));
        });

        RateLimiter::for('marketplace-check-in', function (Request $request) {
            return Limit::perMinute(60)->by($this->rateLimitKey($request, 'check-in'));
        });

        RateLimiter::for('marketplace-webhook', function (Request $request) {
            return Limit::perMinute(120)->by('stripe-webhook|' . $request->ip());
        });
    }

    private function rateLimitKey(Request $request, string $prefix): string
    {
        return $prefix . '|' . optional($request->user())->id . '|' . $request->session()->getId() . '|' . $request->ip();
    }

    private function defaultSetting(): stdClass
    {
        return (object) [
            'website_name' => config('app.name', 'Event Tailor'),
            'site_name' => config('app.name', 'Event Tailor'),
            'site_title' => config('app.name', 'Event Tailor'),
            'site_motto' => 'Create, manage, and sell event tickets online.',
            'footer_description' => 'A clean event marketplace baseline.',
            'site_logo_white' => null,
            'site_logo_black' => null,
            'site_favicon' => null,
            'login_background_image' => null,
            'primary_email' => 'admin@example.com',
            'support_email' => 'support@example.com',
            'info_email' => 'info@example.com',
            'news_email' => null,
            'primary_phone' => null,
            'fax' => null,
            'alternative_phone' => null,
            'whatsapp_number' => null,
            'address_one' => null,
            'address_two' => null,
            'address_line_one' => null,
            'address_line_two' => null,
            'default_language' => 'en',
            'default_currency' => 'BDT',
            'system_timezone' => 'Asia/Dhaka',
            'site_url' => config('app.url'),
            'meta_title' => config('app.name', 'Event Tailor'),
            'meta_keyword' => null,
            'meta_tags' => null,
            'meta_description' => 'Event marketplace baseline.',
            'google_analytics' => null,
            'google_adsense' => null,
            'facebook_pixel_id' => null,
            'og_image' => null,
            'og_title' => config('app.name', 'Event Tailor'),
            'og_description' => 'Event marketplace baseline.',
            'canonical_url' => config('app.url'),
            'copyright_title' => '© ' . date('Y') . ' Event Tailor. All rights reserved.',
            'copyright_url' => config('app.url'),
            'facebook_url' => null,
            'instagram_url' => null,
            'linkedin_url' => null,
            'whatsapp_url' => null,
            'twitter_url' => null,
            'youtube_url' => null,
            'pinterest_url' => null,
            'reddit_url' => null,
            'tumblr_url' => null,
            'tiktok_url' => null,
            'website_url' => config('app.url'),
            'company_name' => 'Event Tailor',
            'minimum_order_amount' => 0,
            'business_hours' => json_encode([
                'saturday' => ['start' => '09:00', 'end' => '18:00'],
                'sunday' => ['start' => '09:00', 'end' => '18:00'],
                'monday' => ['start' => '09:00', 'end' => '18:00'],
                'tuesday' => ['start' => '09:00', 'end' => '18:00'],
                'wednesday' => ['start' => '09:00', 'end' => '18:00'],
                'thursday' => ['start' => '09:00', 'end' => '18:00'],
                'friday' => ['start' => null, 'end' => null],
            ]),
            'theme_color' => '#3490dc',
            'dark_mode' => false,
            'custom_css' => null,
            'custom_js' => null,
            'custom_settings' => json_encode([]),
        ];
    }

    private function formatBusinessHours(object $setting): array
    {
        $businessHours = json_decode(optional($setting)->business_hours, true) ?: [];
        $weekDays = [
            'saturday' => 'Saturday',
            'sunday' => 'Sunday',
            'monday' => 'Monday',
            'tuesday' => 'Tuesday',
            'wednesday' => 'Wednesday',
            'thursday' => 'Thursday',
            'friday' => 'Friday',
        ];

        $formatted = [];

        foreach ($weekDays as $key => $label) {
            $start = $businessHours[$key]['start'] ?? null;
            $end = $businessHours[$key]['end'] ?? null;

            if (!$start || !$end) {
                $formatted[] = $label . ': Closed';
                continue;
            }

            $formatted[] = $label . ': ' . Carbon::createFromTimeString($start)->format('gA') . ' – ' . Carbon::createFromTimeString($end)->format('gA');
        }

        return $formatted;
    }
}
