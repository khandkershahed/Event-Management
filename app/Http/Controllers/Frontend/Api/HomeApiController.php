<?php

namespace App\Http\Controllers\Frontend\Api;


use App\Models\Event;
use App\Models\Contact;
use App\Models\Setting;
use App\Models\Category;
use App\Models\EventType;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use App\Http\Resources\NewsResource;
use App\Http\Resources\EventResource;
use Illuminate\Support\Facades\Session;
use App\Http\Resources\EventTypeResource;
use Illuminate\Support\Facades\Validator;

class HomeApiController extends Controller
{
    public function siteInformations(): JsonResponse
    {
        try {
            // Assuming there's only one row in the settings table
            $setting = Setting::first();

            if (!$setting) {
                return response()->json([
                    'success' => false,
                    'message' => 'Settings not found.',
                    'data'    => null
                ], 404);
            }

            $data = [
                // Branding
                'website_name'              => $setting->website_name,
                'site_title'                => $setting->site_title,
                'site_motto'                => $setting->site_motto,
                'footer_description'        => $setting->footer_description,
                'site_logo_white'           => $setting->site_logo_white ? URL::to('storage/' . $setting->site_logo_white)       : null,
                'site_logo_black'           => $setting->site_logo_black ? URL::to('storage/' . $setting->site_logo_black)       : null,
                'site_favicon'              => $setting->site_favicon ? URL::to('storage/' . $setting->site_favicon)          : null,
                'login_background_image'    => $setting->login_background_image ? URL::to('storage/' . $setting->login_background_image) : null,

                // Contact Info
                'primary_email'             => $setting->primary_email,
                'support_email'             => $setting->support_email,
                'info_email'                => $setting->info_email,
                'news_email'                => $setting->news_email,
                'primary_phone'             => $setting->primary_phone,
                'fax'                       => $setting->fax,
                'alternative_phone'         => $setting->alternative_phone,
                'whatsapp_number'           => $setting->whatsapp_number,

                // Address
                'address_one'               => $setting->address_one,
                'address_two'               => $setting->address_two,

                // Timezone & Language
                'default_language'          => $setting->default_language,
                'default_currency'          => $setting->default_currency,
                'system_timezone'           => $setting->system_timezone,

                // SEO & Analytics
                'site_url'                  => $setting->site_url,
                'meta_title'                => $setting->meta_title,
                'meta_keyword'              => $setting->meta_keyword,
                'meta_tags'                 => $setting->meta_tags,
                'meta_description'          => $setting->meta_description,
                'google_analytics'          => $setting->google_analytics,
                'google_adsense'            => $setting->google_adsense,
                'facebook_pixel_id'         => $setting->facebook_pixel_id,
                'og_image'                  => $setting->og_image ? URL::to('storage/' . $setting->og_image)              : null,
                'og_title'                  => $setting->og_title,
                'og_description'            => $setting->og_description,
                'canonical_url'             => $setting->canonical_url,

                // Copyright
                'copyright_title'           => $setting->copyright_title,
                'copyright_url'             => $setting->copyright_url,

                // Social URLs
                'facebook_url'              => $setting->facebook_url,
                'instagram_url'             => $setting->instagram_url,
                'linkedin_url'              => $setting->linkedin_url,
                'whatsapp_url'              => $setting->whatsapp_url,
                'twitter_url'               => $setting->twitter_url,
                'youtube_url'               => $setting->youtube_url,
                'pinterest_url'             => $setting->pinterest_url,
                'reddit_url'                => $setting->reddit_url,
                'tumblr_url'                => $setting->tumblr_url,
                'tiktok_url'                => $setting->tiktok_url,
                'website_url'               => $setting->website_url,

                // // Feature Toggles
                // 'maintenance_mode'          => (bool) $setting->maintenance_mode,
                // 'enable_user_registration'  => (bool) $setting->enable_user_registration,
                // 'enable_email_verification' => (bool) $setting->enable_email_verification,
                // 'enable_api_access'         => (bool) $setting->enable_api_access,
                // 'enable_multilanguage'      => (bool) $setting->enable_multilanguage,
                // 'is_demo'                   => (bool) $setting->is_demo,

                // Business Info
                'company_name'              => $setting->company_name,
                // 'minimum_order_amount'      => $setting->minimum_order_amount,

                // Business Hours
                'business_hours'            => json_decode($setting->business_hours, true),

                // // Email Config
                // 'mail_driver'               => $setting->mail_driver,
                // 'mail_host'                 => $setting->mail_host,
                // 'mail_port'                 => $setting->mail_port,
                // 'mail_username'             => $setting->mail_username,
                // 'mail_password'             => $setting->mail_password,
                // 'mail_encryption'           => $setting->mail_encryption,
                // 'mail_from_address'         => $setting->mail_from_address,
                // 'mail_from_name'            => $setting->mail_from_name,

                // // Security
                // 'captcha_enabled'           => (bool) $setting->captcha_enabled,
                // 'captcha_site_key'          => $setting->captcha_site_key,
                // 'captcha_secret_key'        => $setting->captcha_secret_key,
                // 'cookie_consent_enabled'    => (bool) $setting->cookie_consent_enabled,
                // 'cookie_consent_text'       => $setting->cookie_consent_text,
                // 'privacy_policy_url'        => $setting->privacy_policy_url,
                // 'terms_conditions_url'      => $setting->terms_conditions_url,

                // Advanced
                'theme_color'               => $setting->theme_color,
                'dark_mode'                 => (bool) $setting->dark_mode,
                'custom_css'                => $setting->custom_css,
                'custom_js'                 => $setting->custom_js,

                // Custom Settings
                'custom_settings'           => json_decode($setting->custom_settings, true),

                // Timestamps
                // 'created_at'                => $setting->created_at,
                // 'updated_at'                => $setting->updated_at,
            ];

            return response()->json([
                'success' => true,
                'message' => 'Site information retrieved successfully.',
                'data' => $data,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving site information.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function allEventTypes()
    {
        try {
            // Load top-level event types with recursive children
            $event_types = EventType::with('events')->where('status', 'active')
                ->orderBy('serial')
                ->get();

            // Transform data for API response
            $data = $event_types->map(fn($cat) => $this->transformEventType($cat));

            return response()->json([
                'success' => true,
                'message' => 'All Event Types retrieved successfully.',
                'data'    => $data,
            ], 200);
        } catch (\Exception $e) {
            Log::error('Failed to fetch Event Types: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve Event Types.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    private function transformEventType($eventType)
    {
        return [
            'id'           => $eventType->id,
            'name'         => $eventType->name,
            'slug'         => $eventType->slug,
            'code'         => $eventType->code,
            'status'       => $eventType->status,
            'logo'         => $eventType->logo ? url('storage/' . $eventType->logo) : null,
            'image'        => $eventType->image ? url('storage/' . $eventType->image) : null,
            'banner_image' => $eventType->banner_image ? url('storage/' . $eventType->banner_image) : null,
            'events'       => $eventType->events->map(function ($event) {
                return [
                    'id'                   => $event->id,
                    'name'                 => $event->name,
                    'slug'                 => $event->slug,
                    'start_date'           => $event->start_date,
                    'end_date'             => $event->end_date,
                    'venue'                => $event->venue,
                    'total_capacity'       => $event->total_capacity,
                    'status'               => $event->status,
                    'logo'                 => $event->logo ? url('storage/' . $event->logo)                : null,
                    'image'                => $event->image ? url('storage/' . $event->image)              : null,
                    'banner_image'         => $event->banner_image ? url('storage/' . $event->banner_image) : null,
                    'video_teaser_url'     => $event->video_teaser_url,
                    'location_map_url'     => $event->location_map_url,
                    'start_time'           => $event->start_time,
                    'end_time'             => $event->end_time,
                    'organizer_name'       => $event->organizer_name,
                    'organizer_brand'      => $event->organizer_brand,
                    'purchase_deadline'    => $event->purchase_deadline,
                    'age_restriction'      => $event->age_restriction,
                    'terms_and_conditions' => $event->terms_and_conditions,
                ];
            }),
        ];
    }
    public function eventTypeDetails($slug)
    {
        try {
            $eventType = EventType::where('slug', $slug)->firstOrFail();

            return response()->json([
                'success' => true,
                'message' => 'Event Type details retrieved successfully.',
                'data'    => $this->transformEventType($eventType),
            ], 200);
        } catch (\Exception $e) {
            Log::error('Failed to fetch Event Type details: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve Event Type details.',
                'error'   => $e->getMessage(),
            ], 404);
        }
    }
    public function typeWiseEvents($slug)
    {
        try {
            $eventType = EventType::where('slug', $slug)->firstOrFail();

            // Fetch events for this type
            $events = $eventType->events()->where('status', 'active')->get();

            if ($events->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No events found for this type.',
                    'data'    => [],
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Events retrieved successfully.',
                'event_type_details' => new EventTypeResource($eventType),
                'events'    => EventResource::collection($events),
            ], 200);
        } catch (\Exception $e) {
            Log::error('Failed to fetch events for type: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve events for this type.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    // allEvents
    public function allEvents()
    {
        try {
            $events = Event::where('status', 'active')
                ->with(['eventType', 'images'])
                ->latest('id')
                ->get();

            if ($events->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No events found.',
                    'data'    => [],
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'All events retrieved successfully.',
                'data'    => EventResource::collection($events),
            ], 200);
        } catch (\Exception $e) {
            Log::error('Failed to fetch all events: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve events.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function eventDetails($slug)
    {
        try {
            $event = Event::where('slug', $slug)
                ->with(['images', 'eventType', 'eventSeats.eventSeatType']) // include seat type relation
                ->where('status', 'active')
                ->first();

            if (!$event) {
                return response()->json([
                    'success' => false,
                    'message' => 'Event not found.',
                ], 404);
            }

            $relatedEvents = Event::where('event_type_id', $event->event_type_id)
                ->where('slug', '!=', $slug)
                ->where('status', 'active')
                ->latest()
                ->get();

            // Group seats by seat type
            $groupedSeats = $event->eventSeats
                ->groupBy(function ($seat) {
                    return $seat->eventSeatType->name;
                })
                ->map(function ($seats, $seatTypeName) {
                    return [
                        'seat_type' => $seatTypeName,
                        'seat_type_id' => $seats->first()->seat_type_id,
                        'seats' => $seats->map(function ($seat) {
                            return [
                                'id' => $seat->id,
                                'name' => $seat->name,
                                'row' => $seat->row,
                                'column' => $seat->column,
                                'status' => $seat->status,
                                'price' => $seat->price,
                                'code' => $seat->code,
                            ];
                        })->values(),
                    ];
                })->values();

            return response()->json([
                'success' => true,
                'message' => 'Event details retrieved successfully.',
                'event_details' => new EventResource($event),
                'event_images' => $event->images->map(function ($image) {
                    return [
                        'id'    => $image->id,
                        'image' => $image->image ? url('storage/' . $image->image) : null,
                    ];
                }),
                'event_seats' => $groupedSeats,
                'related_events' => EventResource::collection($relatedEvents),
            ], 200);
        } catch (\Exception $e) {
            Log::error('Failed to fetch event details: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve event details.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function contactStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'       => 'required|string|max:150',
            'email'      => 'required|email|max:150',
            'phone'      => 'nullable|string|max:20',
            'subject'    => 'nullable|string',
            'message'    => 'nullable|string',
            'ip_address' => 'nullable|ip|max:100',
        ], [
            'name.required'  => 'The name field is required.',
            'name.string'    => 'The name must be a string.',
            'name.max'       => 'The name may not be greater than :max characters.',
            'email.required' => 'The email field is required.',
            'email.email'    => 'Please enter a valid email address.',
            'email.max'      => 'The email may not be greater than :max characters.',
            'phone.string'   => 'The phone must be a string.',
            'phone.max'      => 'The phone may not be greater than :max characters.',
            'phone.regex'    => 'The phone field must contain only numeric characters and must be a proper number.',
            'subject.string' => 'The subject must be a string.',
            'message.string' => 'The message must be a string.',
            'ip_address.ip'  => 'Please enter a valid IP address.',
            'ip_address.max' => 'The IP address may not be greater than :max characters.',
        ]);

        if ($request->filled('phone')) {
            $validator->sometimes('phone', 'regex:/^[0-9]+$/i', function ($input) {
                return $input->phone;
            });
        }

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $typePrefix = 'MSG';
        $today      = date('dmy');
        $lastCode   = Contact::where('code', 'like', $typePrefix . '-' . $today . '%')
            ->orderBy('id', 'desc')
            ->first();

        $newNumber = $lastCode ? (int) explode('-', $lastCode->code)[2] + 1 : 1;
        $code      = $typePrefix . '-' . $today . '-' . $newNumber;

        $contact = Contact::create([
            'code'       => $code,
            'name'       => $request->name,
            'email'      => $request->email,
            'phone'      => $request->phone,
            'subject'    => $request->subject,
            'message'    => $request->message,
            'ip_address' => $request->ip(),
            'status'     => 'pending',
            'call'       => $request->call,
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Thank You. We have received your message. We will contact you very soon.',
            'data'    => $contact
        ], 201);
    }
}
