<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Event;
use App\Models\Venue;
use App\Models\EventType;
use App\Models\EventImage;
use App\Models\SeatingPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class EventController extends Controller
{
    /**
     * Display a listing of events.
     */
    public function index()
    {
        return view('admin.pages.event.index', [
            'events' => Event::latest()->get(),
        ]);
    }

    /**
     * Show the form to create a new event.
     */
    public function create()
    {
        return view('admin.pages.event.create', [
            'event_types'   => EventType::latest()->active()->get(),
            'venues'        => Venue::orderBy('name')->get(),
            'seating_plans' => collect(), // initially empty until venue selected
        ]);
    }

    /**
     * Store a newly created event.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'event_type_id'     => 'nullable|exists:event_types,id',
            'name'              => 'required|string|max:255|unique:events,name',
            'slug'              => 'nullable|string|max:255|unique:events,slug',
            'venue_id'          => 'required|exists:venues,id',
            'seating_plan_id'   => 'required|exists:seating_plans,id',
            'tagline'           => 'nullable|string|max:255',
            'description'       => 'nullable|string',
            'logo'              => 'nullable|image|max:2048',
            'image'             => 'nullable|image|max:2048',
            'banner_image'      => 'nullable|image|max:2048',
            'organizer_logo'    => 'nullable|image|max:2048',
            'venue_image'       => 'nullable|image|max:2048',
            'video_teaser_url'  => 'nullable|url',
            'location_map_url'  => 'nullable|url',
            'start_date'        => 'nullable|date',
            'end_date'          => 'nullable|date|after_or_equal:start_date',
            'start_time'        => 'nullable',
            'end_time'          => 'nullable',
            'venue'             => 'nullable|string',
            'is_featured'       => 'nullable|boolean',
            'organizer_name'    => 'nullable|string',
            'organizer_brand'   => 'nullable|string',
            'purchase_deadline' => 'nullable|date',
            'total_capacity'    => 'nullable|integer|min:0',
            'age_restriction'   => 'nullable|string|max:50',
            'terms_and_conditions' => 'nullable|string',
            'status'            => 'required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $err) {
                Session::flash('error', $err);
            }
            return back()->withInput();
        }

        DB::beginTransaction();

        try {

            // Handle file uploads
            $files = [
                'logo',
                'image',
                'banner_image',
                'organizer_logo',
                'venue_image'
            ];

            $uploaded = [];

            foreach ($files as $key) {
                if ($request->hasFile($key)) {
                    $upload = customUpload($request->file($key), 'events/' . $key);
                    if ($upload['status'] === 0) {
                        throw new \Exception($upload['error_message']);
                    }
                    $uploaded[$key] = $upload['file_path'];
                } else $uploaded[$key] = null;
            }

            // Create event
            $event = Event::create([
                'event_type_id'        => $request->event_type_id,
                'name'                 => $request->name,
                'slug'                 => $request->slug,
                'tagline'              => $request->tagline,
                'description'          => $request->description,
                'logo'                 => $uploaded['logo'],
                'image'                => $uploaded['image'],
                'banner_image'         => $uploaded['banner_image'],
                'organizer_logo'       => $uploaded['organizer_logo'],
                'venue_image'          => $uploaded['venue_image'],
                'venue_id'             => $request->venue_id,
                'seating_plan_id'      => $request->seating_plan_id,
                'video_teaser_url'     => $request->video_teaser_url,
                'location_map_url'     => $request->location_map_url,
                'start_date'           => $request->start_date,
                'end_date'             => $request->end_date,
                'start_time'           => $request->start_time,
                'end_time'             => $request->end_time,
                'venue'                => $request->venue,
                'is_featured'          => $request->is_featured ?? 0,
                'organizer_name'       => $request->organizer_name,
                'organizer_brand'      => $request->organizer_brand,
                'purchase_deadline'    => $request->purchase_deadline,
                'total_capacity'       => $request->total_capacity,
                'age_restriction'      => $request->age_restriction,
                'terms_and_conditions' => $request->terms_and_conditions,
                'status'               => $request->status,
                'added_by'             => Auth::guard('admin')->user()->name ?? 'system',
            ]);

            // Multi images
            if ($request->hasFile('multi_images')) {
                foreach ($request->file('multi_images') as $img) {
                    $upload = customUpload($img, 'events/multi_images');
                    if ($upload['status'] === 0) {
                        throw new \Exception($upload['error_message']);
                    }
                    EventImage::create([
                        'event_id' => $event->id,
                        'image'    => $upload['file_path'],
                        'created_at' => now(),
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('admin.event.index')
                ->with('success', 'Event created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Session::flash('error', 'Error: ' . $e->getMessage());
            return back()->withInput();
        }
    }

    /**
     * Show the form for editing an event.
     */
    public function edit($id)
    {
        $event = Event::findOrFail($id);

        return view('admin.pages.event.edit', [
            'event'         => $event,
            'event_types'   => EventType::latest()->active()->get(),
            'venues'        => Venue::orderBy('name')->get(),
            'seating_plans' => SeatingPlan::where('venue_id', $event->venue_id)->get(),
        ]);
    }

    /**
     * Update the specified event.
     */
    public function update(Request $request, Event $event)
    {
        $validator = Validator::make($request->all(), [
            'event_type_id'     => 'required|exists:event_types,id',
            'venue_id'          => 'required|exists:venues,id',
            'seating_plan_id'   => 'required|exists:seating_plans,id',
            'name'              => 'required|string|max:255|unique:events,name,' . $event->id,
            'tagline'           => 'nullable|string|max:255',
            'description'       => 'nullable|string',
            'logo'              => 'nullable|image|max:2048',
            'image'             => 'nullable|image|max:2048',
            'banner_image'      => 'nullable|image|max:2048',
            'organizer_logo'    => 'nullable|image|max:2048',
            'venue_image'       => 'nullable|image|max:2048',
            'video_teaser_url'  => 'nullable|url',
            'location_map_url'  => 'nullable|url',
            'start_date'        => 'nullable|date',
            'end_date'          => 'nullable|date|after_or_equal:start_date',
            'status'            => 'required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $err) {
                Session::flash('error', $err);
            }
            return back()->withInput();
        }

        DB::beginTransaction();

        try {

            // Image fields
            $files = ['logo', 'image', 'banner_image', 'organizer_logo', 'venue_image'];

            foreach ($files as $key) {
                if ($request->hasFile($key)) {
                    $upload = customUpload($request->file($key), 'events/' . $key);
                    if ($upload['status'] === 0) {
                        throw new \Exception($upload['error_message']);
                    }

                    if ($event->$key && Storage::disk('public')->exists($event->$key)) {
                        Storage::disk('public')->delete($event->$key);
                    }

                    $event->$key = $upload['file_path'];
                }
            }

            // Update event
            $event->update([
                'event_type_id'   => $request->event_type_id,
                'venue_id'        => $request->venue_id,
                'seating_plan_id' => $request->seating_plan_id,
                'name'            => $request->name,
                'tagline'         => $request->tagline,
                'description'     => $request->description,
                'video_teaser_url' => $request->video_teaser_url,
                'location_map_url' => $request->location_map_url,
                'start_date'      => $request->start_date,
                'end_date'        => $request->end_date,
                'start_time'      => $request->start_time,
                'end_time'        => $request->end_time,
                'venue'           => $request->venue,
                'organizer_name'  => $request->organizer_name,
                'organizer_brand' => $request->organizer_brand,
                'purchase_deadline' => $request->purchase_deadline,
                'total_capacity'    => $request->total_capacity,
                'age_restriction'   => $request->age_restriction,
                'is_featured'       => $request->is_featured ?? 0,
                'terms_and_conditions' => $request->terms_and_conditions,
                'status'           => $request->status,
                'updated_by'       => Auth::guard('admin')->user()->name ?? 'system',
            ]);

            // Multi images
            if ($request->hasFile('multi_images')) {
                foreach ($request->file('multi_images') as $img) {
                    $upload = customUpload($img, 'events/multi_images');
                    EventImage::create([
                        'event_id' => $event->id,
                        'image'    => $upload['file_path'],
                    ]);
                }
            }

            DB::commit();

            return back()->with('success', 'Event updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Session::flash('error', 'Error: ' . $e->getMessage());
            return back()->withInput();
        }
    }

    /**
     * Destroy event.
     */
    public function destroy(Event $event)
    {
        $files = ['logo', 'image', 'banner_image'];

        foreach ($files as $key) {
            if ($event->$key && Storage::disk('public')->exists($event->$key)) {
                Storage::disk('public')->delete($event->$key);
            }
        }

        $event->delete();

        return redirect()->route('admin.event.index')->with('success', 'Event deleted.');
    }
}
