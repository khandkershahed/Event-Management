<?php

namespace App\Http\Controllers\Admin;

use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.pages.setting.index', ['setting' => Setting::first()]);
    }

    public function updateOrcreateSetting(Request $request)
    {
        try {
            // Get existing setting or new instance
            $webSetting = Setting::firstOrNew([]);

            // Files to handle
            $files = [
                'site_favicon'    => $request->file('site_favicon'),
                'site_logo_white' => $request->file('site_logo_white'),
                'site_logo_black' => $request->file('site_logo_black'),
                'login_background_image' => $request->file('login_background_image'),
                'og_image' => $request->file('og_image'),
            ];
            $uploadedFiles = [];

            foreach ($files as $key => $file) {
                if (!empty($file)) {
                    $filePath = 'webSetting/' . $key;
                    $oldFile = $webSetting->$key ?? null;

                    if ($oldFile) {
                        Storage::disk('public')->delete($oldFile);
                    }
                    $uploadedFiles[$key] = customUpload($file, $filePath);

                    if ($uploadedFiles[$key]['status'] === 0) {
                        return redirect()->back()->with('error', $uploadedFiles[$key]['error_message']);
                    }
                } else {
                    $uploadedFiles[$key] = ['status' => 0];
                }
            }

            // Build data array for updateOrCreate
            $data = [
                // Branding
                'website_name'           => $request->website_name,
                'site_title'             => $request->site_title,
                'site_motto'             => $request->site_motto,
                'site_logo_white'        => $uploadedFiles['site_logo_white']['status'] == 1 ? $uploadedFiles['site_logo_white']['file_path'] : $webSetting->site_logo_white,
                'site_logo_black'        => $uploadedFiles['site_logo_black']['status'] == 1 ? $uploadedFiles['site_logo_black']['file_path'] : $webSetting->site_logo_black,
                'site_favicon'           => $uploadedFiles['site_favicon']['status'] == 1 ? $uploadedFiles['site_favicon']['file_path'] : $webSetting->site_favicon,
                'login_background_image' => $uploadedFiles['login_background_image']['status'] == 1 ? $uploadedFiles['login_background_image']['file_path'] : $webSetting->login_background_image,

                // Contact Information
                'primary_email'          => $request->primary_email,
                'support_email'          => $request->support_email,
                'info_email'             => $request->info_email,
                'news_email'             => $request->news_email,
                'primary_phone'          => $request->primary_phone,
                'fax'                    => $request->fax,
                'alternative_phone'      => $request->alternative_phone,
                'whatsapp_number'        => $request->whatsapp_number,

                // Address
                'address_one'            => $request->address_one,
                'address_two'            => $request->address_two,

                // Timezone & Language
                'default_language'       => $request->default_language,
                'default_currency'       => $request->default_currency,
                'system_timezone'        => $request->system_timezone,

                // SEO & Analytics
                'site_url'               => $request->site_url,
                'meta_title'             => $request->meta_title,
                'meta_keyword'           => $request->meta_keyword,
                'meta_tags'              => $request->meta_tags,
                'meta_description'       => $request->meta_description,
                'google_analytics'       => $request->google_analytics,
                'google_adsense'         => $request->google_adsense,
                'facebook_pixel_id'      => $request->facebook_pixel_id,
                'og_image'               => $uploadedFiles['og_image']['status'] == 1 ? $uploadedFiles['og_image']['file_path'] : $webSetting->og_image,
                'og_title'               => $request->og_title,
                'og_description'         => $request->og_description,
                'canonical_url'          => $request->canonical_url,

                // Copyright
                'copyright_title'        => $request->copyright_title,
                'copyright_url'          => $request->copyright_url,

                // Social Media URLs
                'facebook_url'           => $request->facebook_url,
                'instagram_url'          => $request->instagram_url,
                'linkedin_url'           => $request->linkedin_url,
                'whatsapp_url'           => $request->whatsapp_url,
                'twitter_url'            => $request->twitter_url,
                'youtube_url'            => $request->youtube_url,
                'pinterest_url'          => $request->pinterest_url,
                'reddit_url'             => $request->reddit_url,
                'tumblr_url'             => $request->tumblr_url,
                'tiktok_url'             => $request->tiktok_url,
                'website_url'            => $request->website_url,

                // Feature Toggles
                'maintenance_mode'       => $request->has('maintenance_mode') ? (bool)$request->maintenance_mode : false,
                'enable_user_registration' => $request->has('enable_user_registration') ? (bool)$request->enable_user_registration : true,
                'enable_email_verification' => $request->has('enable_email_verification') ? (bool)$request->enable_email_verification : false,
                'enable_api_access'       => $request->has('enable_api_access') ? (bool)$request->enable_api_access : false,
                'enable_multilanguage'    => $request->has('enable_multilanguage') ? (bool)$request->enable_multilanguage : false,
                'is_demo'                 => $request->has('is_demo') ? (bool)$request->is_demo : false,

                // Business Settings
                'company_name'           => $request->company_name,
                'minimum_order_amount'   => $request->minimum_order_amount,

                // Business Hours JSON (optional)
                'business_hours'         => $request->business_hours ? json_encode($request->business_hours) : $webSetting->business_hours,

                // Email Settings
                'mail_driver'            => $request->mail_driver,
                'mail_host'              => $request->mail_host,
                'mail_port'              => $request->mail_port,
                'mail_username'          => $request->mail_username,
                'mail_password'          => $request->mail_password,
                'mail_encryption'        => $request->mail_encryption,
                'mail_from_address'      => $request->mail_from_address,
                'mail_from_name'         => $request->mail_from_name,

                // Security & Compliance
                'captcha_enabled'        => $request->has('captcha_enabled') ? (bool)$request->captcha_enabled : false,
                'captcha_site_key'       => $request->captcha_site_key,
                'captcha_secret_key'    => $request->captcha_secret_key,
                'cookie_consent_enabled' => $request->has('cookie_consent_enabled') ? (bool)$request->cookie_consent_enabled : false,
                'cookie_consent_text'    => $request->cookie_consent_text,
                'privacy_policy_url'     => $request->privacy_policy_url,
                'terms_conditions_url'   => $request->terms_conditions_url,

                // Advanced Settings
                'theme_color'            => $request->theme_color,
                'dark_mode'              => $request->has('dark_mode') ? (bool)$request->dark_mode : false,
                'custom_css'             => $request->custom_css,
                'custom_js'              => $request->custom_js,

                // Extensible JSON field
                'custom_settings'        => $request->custom_settings ? json_encode($request->custom_settings) : $webSetting->custom_settings,

                // Auditing
                'created_by'             => $webSetting->created_by ?? Auth::guard('admin')->user()->id,
                'updated_by'             => Auth::guard('admin')->user()->id,
            ];

            // Update or create the setting
            $setting = Setting::updateOrCreate([], $data);

            $message = $setting->wasRecentlyCreated ? 'Setting created successfully' : 'Setting updated successfully';

            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            Session::flash('error', $e->getMessage());
            return redirect()->back()->withInput();
        }
    }
}
