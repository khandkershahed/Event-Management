<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            // Branding
            $table->string('website_name', 250)->nullable();
            $table->string('site_title', 250)->nullable();
            $table->text('site_motto')->nullable();
            $table->string('site_logo_white', 255)->nullable();
            $table->string('site_logo_black', 255)->nullable();
            $table->string('site_favicon', 255)->nullable();
            $table->string('login_background_image')->nullable();

            // Contact Information
            $table->string('primary_email', 100)->nullable();
            $table->string('support_email', 100)->nullable();
            $table->string('info_email', 100)->nullable();
            $table->string('sales_email', 100)->nullable();
            $table->string('primary_phone', 20)->nullable();
            $table->string('alternative_phone', 20)->nullable();
            $table->string('whatsapp_number', 20)->nullable();

            // Address
            $table->text('address_line_one')->nullable();
            $table->text('address_line_two')->nullable();

            // Timezone & Language
            $table->string('default_language', 50)->nullable();
            $table->string('default_currency', 50)->nullable();
            $table->string('system_timezone', 100)->nullable();

            // SEO & Analytics
            $table->text('site_url')->nullable();
            $table->text('meta_title')->nullable();
            $table->text('meta_keyword')->nullable();
            $table->text('meta_tags')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('google_analytics')->nullable();
            $table->text('google_adsense')->nullable();
            $table->text('facebook_pixel_id')->nullable();
            $table->string('og_image', 255)->nullable();
            $table->string('og_title', 255)->nullable();
            $table->text('og_description')->nullable();
            $table->text('canonical_url')->nullable();
            // Copyright
            $table->text('copyright_title')->nullable();
            $table->text('copyright_url')->nullable();

            // Social Media URLs
            $table->text('facebook_url')->nullable();
            $table->text('instagram_url')->nullable();
            $table->text('linkedin_url')->nullable();
            $table->text('whatsapp_url')->nullable();
            $table->text('twitter_url')->nullable();
            $table->text('youtube_url')->nullable();
            $table->text('pinterest_url')->nullable();
            $table->text('reddit_url')->nullable();
            $table->text('tumblr_url')->nullable();
            $table->text('tiktok_url')->nullable();
            $table->text('website_url')->nullable();

            // Feature Toggles
            $table->boolean('maintenance_mode')->default(false);
            $table->boolean('enable_user_registration')->default(true);
            $table->boolean('enable_email_verification')->default(false);
            $table->boolean('enable_api_access')->default(false);
            $table->boolean('enable_multilanguage')->default(false);
            $table->boolean('is_demo')->default(false);

            // Business Settings
            $table->string('company_name')->nullable();
            $table->integer('minimum_order_amount')->nullable();

            // Business Hours (optional JSON structure for flexibility)
            $table->json('business_hours')->nullable();

            // Email Settings
            $table->string('mail_driver')->nullable();
            $table->string('mail_host')->nullable();
            $table->string('mail_port')->nullable();
            $table->string('mail_username')->nullable();
            $table->string('mail_password')->nullable();
            $table->string('mail_encryption')->nullable();
            $table->string('mail_from_address')->nullable();
            $table->string('mail_from_name')->nullable();

            // Security & Compliance
            $table->boolean('captcha_enabled')->default(false);
            $table->string('captcha_site_key')->nullable();
            $table->string('captcha_secret_key')->nullable();
            $table->boolean('cookie_consent_enabled')->default(false);
            $table->text('cookie_consent_text')->nullable();
            $table->text('privacy_policy_url')->nullable();
            $table->text('terms_conditions_url')->nullable();

            // Advanced Settings
            $table->string('theme_color', 50)->nullable();
            $table->boolean('dark_mode')->default(false);
            $table->longText('custom_css')->nullable();
            $table->longText('custom_js')->nullable();

            // Extensible Field for Plugin-Like Settings
            $table->json('custom_settings')->nullable();

            // Auditing
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
