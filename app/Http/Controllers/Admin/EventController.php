<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Event;
use App\Models\EventType;
use App\Models\EventImage;
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
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.pages.event.index', [
            'events' => Event::latest()->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.pages.event.create', [
            'event_types' => EventType::latest()->active()->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'event_type_id'        => 'nullable|exists:event_types,id',
            'name'                 => 'required|string|max:255|unique:events,name',
            'slug'                 => 'nullable|string|max:255|unique:events,slug',
            'tagline'              => 'nullable|string|max:255',
            'description'          => 'nullable|string',
            'logo'                 => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'image'                => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'banner_image'         => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'organizer_logo'       => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'video_teaser_url'     => 'nullable|url',
            'location_map_url'     => 'nullable|url',
            'start_date'           => 'nullable|date',
            'end_date'             => 'nullable|date|after_or_equal:start_date',
            'start_time'           => 'nullable|date_format:H:i',
            'end_time'             => 'nullable|date_format:H:i',
            'venue'                => 'nullable|string',
            'is_featured'          => 'nullable|boolean',
            'organizer_name'       => 'nullable|string|max:255',
            'organizer_brand'      => 'nullable|string|max:255',
            'purchase_deadline'    => 'nullable|date',
            'total_capacity'       => 'nullable|integer|min:0',
            'age_restriction'      => 'nullable|string|max:50',
            'terms_and_conditions' => 'nullable|string',
            'status'               => 'required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                Session::flash('error', $error, ['timeOut' => 30000]);
            }
            return redirect()->back()->withInput();
        }

        DB::beginTransaction();

        try {
            // Handle file uploads
            $files = [
                'logo'         => $request->file('logo'),
                'image'        => $request->file('image'),
                'banner_image' => $request->file('banner_image'),
                'organizer_logo' => $request->file('organizer_logo'),
                'venue_image' => $request->file('venue_image'),
            ];

            $uploadedFiles = [];

            foreach ($files as $key => $file) {
                if (!empty($file)) {
                    $filePath = 'events/' . $key;
                    $uploadedFiles[$key] = customUpload($file, $filePath);
                    if ($uploadedFiles[$key]['status'] === 0) {
                        throw new \Exception($uploadedFiles[$key]['error_message']);
                    }
                } else {
                    $uploadedFiles[$key] = ['status' => 0];
                }
            }

            // Create event
            $event = Event::create([
                'event_type_id'        => $request->event_type_id,
                'name'                 => $request->name,
                'slug'                 => $request->slug,
                'tagline'              => $request->tagline,
                'description'          => $request->description,
                'logo'                 => $uploadedFiles['logo']['status'] == 1 ? $uploadedFiles['logo']['file_path'] : null,
                'image'                => $uploadedFiles['image']['status'] == 1 ? $uploadedFiles['image']['file_path'] : null,
                'banner_image'         => $uploadedFiles['banner_image']['status'] == 1 ? $uploadedFiles['banner_image']['file_path'] : null,
                'organizer_logo'       => $uploadedFiles['organizer_logo']['status'] == 1 ? $uploadedFiles['organizer_logo']['file_path'] : null,
                'venue_image'          => $uploadedFiles['venue_image']['status'] == 1 ? $uploadedFiles['venue_image']['file_path'] : null,
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

            if ($request->hasFile('multi_images')) {
                foreach ($request->file('multi_images') as $image) {
                    if ($image) {
                        $multiImageUpload = customUpload($image, 'events/multi_images');
                        if ($multiImageUpload['status'] === 0) {
                            return redirect()->back()->with('error', $multiImageUpload['error_message']);
                        }
                        EventImage::create([
                            'event_id'   => $event->id,
                            'image'      => $multiImageUpload['file_path'],
                            'created_at' => Carbon::now(),
                        ]);
                    }
                }
            }

            DB::commit();

            return redirect()->back()->with('success', 'Event created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Session::flash('error', 'An error occurred while creating the event: ' . $e->getMessage(), ['timeOut' => 30000]);
            return redirect()->back()->withInput();
        }
    }



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return view('admin.pages.event.edit', [
            'event' => Event::findOrFail($id),
            'event_types' => EventType::latest()->active()->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        $validator = Validator::make($request->all(), [
            'event_type_id'        => 'required|exists:event_types,id',
            'name'                 => 'required|string|max:200|unique:events,name,' . $event->id,
            'status'               => 'required|in:active,inactive',
            'tagline'              => 'nullable|string|max:255',
            'description'          => 'nullable|string',
            'logo'                 => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'image'                => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'banner_image'         => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'video_teaser_url'     => 'nullable|url',
            'location_map_url'     => 'nullable|url',
            'start_date'           => 'nullable|date',
            'end_date'             => 'nullable|date|after_or_equal:start_date',
            // 'start_time'           => 'nullable|date_format:H:i',
            // 'end_time'             => 'nullable|date_format:H:i',
            'venue'                => 'nullable|string',
            'organizer_name'       => 'nullable|string|max:255',
            'organizer_brand'      => 'nullable|string|max:255',
            'purchase_deadline'    => 'nullable|date',
            'total_capacity'       => 'nullable|integer|min:0',
            'age_restriction'      => 'nullable|string|max:100',
            'is_featured'          => 'nullable|boolean',
            'terms_and_conditions' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                Session::flash('error', $error, ['timeOut' => 30000]);
            }
            return redirect()->back()->withInput();
        }

        DB::beginTransaction();

        try {
            $files = [
                'logo'         => $request->file('logo'),
                'image'        => $request->file('image'),
                'banner_image' => $request->file('banner_image'),
                'organizer_logo' => $request->file('organizer_logo'),
                'venue_image' => $request->file('venue_image'),
            ];

            $uploadedFiles = [];

            foreach ($files as $key => $file) {
                if (!empty($file)) {
                    $filePath = 'event/' . $key;
                    $oldFile  = $event->$key ?? null;

                    if ($oldFile && Storage::disk('public')->exists($oldFile)) {
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

            $event->update([
                'event_type_id'        => $request->event_type_id,
                'name'                 => $request->name,
                'status'               => $request->status,
                'tagline'              => $request->tagline,
                'description'          => $request->description,
                'logo'                 => $uploadedFiles['logo']['status'] == 1 ? $uploadedFiles['logo']['file_path'] : $event->logo,
                'image'                => $uploadedFiles['image']['status'] == 1 ? $uploadedFiles['image']['file_path'] : $event->image,
                'banner_image'         => $uploadedFiles['banner_image']['status'] == 1 ? $uploadedFiles['banner_image']['file_path'] : $event->banner_image,
                'organizer_logo'       => $uploadedFiles['organizer_logo']['status'] == 1 ? $uploadedFiles['organizer_logo']['file_path'] : $event->organizer_logo,
                'venue_image'          => $uploadedFiles['venue_image']['status'] == 1 ? $uploadedFiles['venue_image']['file_path'] : $event->venue_image,
                'video_teaser_url'     => $request->video_teaser_url,
                'location_map_url'     => $request->location_map_url,
                'start_date'           => $request->start_date,
                'end_date'             => $request->end_date,
                'start_time'           => $request->start_time,
                'end_time'             => $request->end_time,
                'venue'                => $request->venue,
                'organizer_name'       => $request->organizer_name,
                'organizer_brand'      => $request->organizer_brand,
                'purchase_deadline'    => $request->purchase_deadline,
                'total_capacity'       => $request->total_capacity,
                'age_restriction'      => $request->age_restriction,
                'is_featured'          => $request->is_featured ?? 0,
                'terms_and_conditions' => $request->terms_and_conditions,
                'updated_by'           => Auth::guard('admin')->user()->name ?? 'system',
            ]);

            // Handle multiple image uploads
            if ($request->hasFile('multi_img')) {
                foreach ($request->file('multi_img') as $image) {
                    if ($image) {
                        $multiImageUpload = customUpload($image, 'events/multi_images');
                        if ($multiImageUpload['status'] === 0) {
                            return redirect()->back()->with('error', $multiImageUpload['error_message']);
                        }
                        EventImage::create([
                            'event_id'   => $event->id,
                            'image'      => $multiImageUpload['file_path'],
                            'created_at' => Carbon::now(),
                        ]);
                    }
                }
            }

            // Handle deletion of removed images
            if ($request->input('remove_images')) {
                $imagesToRemove = json_decode($request->input('remove_images'), true);
                foreach ($imagesToRemove as $imageId) {
                    $image = EventImage::find($imageId);
                    if ($image) {
                        if ($image->photo && Storage::exists("public/" . $image->photo)) {
                            Storage::delete("public/" . $image->photo);
                        }
                        $image->delete();
                    }
                }
            }

            DB::commit();
            Session::flash('success', 'Event updated successfully!', ['timeOut' => 30000]);
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollBack();
            Session::flash('error', 'An error occurred while updating the event: ' . $e->getMessage(), ['timeOut' => 30000]);
            return redirect()->back()->withInput();
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        $files = [
            'logo'         => $event->logo,
            'image'        => $event->image,
            'banner_image' => $event->banner_image,
        ];
        foreach ($files as $key => $file) {
            if (! empty($file)) {
                $oldFile = $event->$key ?? null;
                if ($oldFile && Storage::disk('public')->exists($oldFile)) {
                    Storage::disk('public')->delete($oldFile);
                }
            }
        }
        $event->delete();
    }
}
