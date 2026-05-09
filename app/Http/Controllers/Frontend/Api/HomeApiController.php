<?php

namespace App\Http\Controllers\Frontend\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use App\Http\Resources\EventTypeResource;
use App\Models\Event;
use App\Models\EventType;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

class HomeApiController extends Controller
{
    public function siteInformations(): JsonResponse
    {
        $setting = Setting::first();

        if (!$setting) {
            return response()->json([
                'success' => false,
                'message' => 'Settings not found.',
                'data' => null,
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Site information retrieved successfully.',
            'data' => [
                'website_name' => $setting->website_name,
                'site_title' => $setting->site_title,
                'site_motto' => $setting->site_motto,
                'footer_description' => $setting->footer_description,
                'site_logo_white' => $setting->site_logo_white ? URL::to('storage/' . $setting->site_logo_white) : null,
                'site_logo_black' => $setting->site_logo_black ? URL::to('storage/' . $setting->site_logo_black) : null,
                'site_favicon' => $setting->site_favicon ? URL::to('storage/' . $setting->site_favicon) : null,
                'primary_email' => $setting->primary_email,
                'support_email' => $setting->support_email,
                'primary_phone' => $setting->primary_phone,
                'default_currency' => $setting->default_currency,
                'system_timezone' => $setting->system_timezone,
                'site_url' => $setting->site_url,
                'meta_title' => $setting->meta_title,
                'meta_description' => $setting->meta_description,
                'copyright_title' => $setting->copyright_title,
                'copyright_url' => $setting->copyright_url,
                'facebook_url' => $setting->facebook_url,
                'instagram_url' => $setting->instagram_url,
                'linkedin_url' => $setting->linkedin_url,
                'twitter_url' => $setting->twitter_url,
                'youtube_url' => $setting->youtube_url,
                'company_name' => $setting->company_name,
                'business_hours' => json_decode($setting->business_hours, true),
                'theme_color' => $setting->theme_color,
                'dark_mode' => (bool) $setting->dark_mode,
                'custom_settings' => json_decode($setting->custom_settings, true),
            ],
        ]);
    }

    public function allEventTypes(): JsonResponse
    {
        try {
            $eventTypes = EventType::with('events')->where('status', 'active')->orderBy('serial')->get();

            return response()->json([
                'success' => true,
                'message' => 'All Event Types retrieved successfully.',
                'data' => $eventTypes->map(fn ($eventType) => $this->transformEventType($eventType)),
            ]);
        } catch (\Throwable $e) {
            Log::error('Failed to fetch Event Types: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve Event Types.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function typeWiseEvents(string $slug): JsonResponse
    {
        try {
            $eventType = EventType::where('slug', $slug)->firstOrFail();
            $events = $eventType->events()->where('status', 'active')->get();

            return response()->json([
                'success' => true,
                'message' => 'Events retrieved successfully.',
                'event_type_details' => new EventTypeResource($eventType),
                'events' => EventResource::collection($events),
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve events for this type.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function allEvents(): JsonResponse
    {
        try {
            $events = Event::where('status', 'active')->with(['eventType', 'images'])->latest('id')->get();

            return response()->json([
                'success' => true,
                'message' => 'All events retrieved successfully.',
                'data' => EventResource::collection($events),
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve events.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function eventDetails(string $slug): JsonResponse
    {
        try {
            $event = Event::where('slug', $slug)
                ->with(['images', 'eventType', 'seatingPlan.sections.seats', 'tickets'])
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

            $sections = optional($event->seatingPlan)->sections ?? collect();
            $seatMap = $sections->map(function ($section) {
                return [
                    'section_id' => $section->id,
                    'section_name' => $section->name,
                    'section_type' => $section->type,
                    'capacity' => $section->capacity,
                    'seats' => $section->seats->map(function ($seat) {
                        return [
                            'id' => $seat->id,
                            'label' => $seat->label,
                            'row_label' => $seat->row_label,
                            'seat_number' => $seat->seat_number,
                            'is_disabled' => (bool) $seat->is_disabled,
                        ];
                    })->values(),
                ];
            })->values();

            return response()->json([
                'success' => true,
                'message' => 'Event details retrieved successfully.',
                'event_details' => new EventResource($event),
                'event_images' => $event->images->map(fn ($image) => [
                    'id' => $image->id,
                    'image' => $image->image ? url('storage/' . $image->image) : null,
                ]),
                'ticket_types' => $event->tickets,
                'seat_map' => $seatMap,
                'related_events' => EventResource::collection($relatedEvents),
            ]);
        } catch (\Throwable $e) {
            Log::error('Failed to fetch event details: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve event details.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function globalSearch(Request $request): JsonResponse
    {
        $term = $request->string('q')->toString() ?: $request->string('search')->toString();

        $events = Event::query()
            ->where('status', 'active')
            ->when($term, fn ($query) => $query->where('name', 'like', '%' . $term . '%'))
            ->latest('id')
            ->limit(10)
            ->get();

        return response()->json([
            'success' => true,
            'data' => EventResource::collection($events),
        ]);
    }

    public function searchSuggestions(Request $request): JsonResponse
    {
        $term = $request->string('q')->toString() ?: $request->string('search')->toString();

        $suggestions = Event::query()
            ->where('status', 'active')
            ->when($term, fn ($query) => $query->where('name', 'like', '%' . $term . '%'))
            ->orderBy('name')
            ->limit(8)
            ->pluck('name');

        return response()->json([
            'success' => true,
            'data' => $suggestions,
        ]);
    }

    public function contactStore(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:150',
            'email' => 'required|email|max:150',
            'phone' => 'nullable|string|max:20',
            'subject' => 'nullable|string|max:255',
            'message' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        return response()->json([
            'status' => 'disabled',
            'message' => 'Contact storage is disabled in the Step 1 baseline because the legacy contact module was removed.',
        ], 202);
    }

    private function transformEventType(EventType $eventType): array
    {
        return [
            'id' => $eventType->id,
            'name' => $eventType->name,
            'slug' => $eventType->slug,
            'code' => $eventType->code,
            'status' => $eventType->status,
            'logo' => $eventType->logo ? url('storage/' . $eventType->logo) : null,
            'image' => $eventType->image ? url('storage/' . $eventType->image) : null,
            'banner_image' => $eventType->banner_image ? url('storage/' . $eventType->banner_image) : null,
            'events' => EventResource::collection($eventType->events),
        ];
    }
}
