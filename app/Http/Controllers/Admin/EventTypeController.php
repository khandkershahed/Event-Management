<?php

namespace App\Http\Controllers\Admin;

use App\Models\EventType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Admin\EventTypeRequest;

class EventTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.pages.eventType.index', [
            'event_types' => EventType::latest()->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.pages.eventType.create');
    }

    /**
     * Store a newly created resource in storage.
     */


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'         => 'required|string|max:200|unique:event_types,name',
            'code'         => 'nullable|string|max:220',
            'serial'       => 'nullable|string|max:220',
            'status'       => 'required|in:active,inactive',
            'description'  => 'nullable|string',
            'logo'         => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'image'        => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            foreach ($errors as $error) {
                Session::flash('error', $error, ['timeOut' => 30000]);
            }
            return redirect()->back()->withInput();
        }
        $files = [
            'logo'         => $request->file('logo'),
            'image'        => $request->file('image'),
            'banner_image' => $request->file('banner_image'),
        ];
        $uploadedFiles = [];
        foreach ($files as $key => $file) {
            if (! empty($file)) {
                $filePath            = 'event-type/' . $key;
                $uploadedFiles[$key] = customUpload($file, $filePath);
                if ($uploadedFiles[$key]['status'] === 0) {
                    return redirect()->back()->with('error', $uploadedFiles[$key]['error_message']);
                }
            } else {
                $uploadedFiles[$key] = ['status' => 0];
            }
        }
        // Prepare data array
        EventType::create([
            'name'         => $request->name,
            'code'         => $request->code,
            'serial'       => $request->serial,
            'status'       => $request->status,
            'description'  => $request->description,
            'logo'         => $uploadedFiles['logo']['status']         == 1 ? $uploadedFiles['logo']['file_path']        : null,
            'image'        => $uploadedFiles['image']['status']        == 1 ? $uploadedFiles['image']['file_path']       : null,
            'banner_image' => $uploadedFiles['banner_image']['status'] == 1 ? $uploadedFiles['banner_image']['file_path'] : null,
            'added_by'     => Auth::guard('admin')->user()->name,
        ]);


        return redirect()->back()->with('success', 'Event type created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // return view('admin.pages.categories.show', [
        //     'category' => EventType::findOrFail($id),
        // ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $event_type = EventType::findOrFail($id);

        return view('admin.pages.eventType.edit', [
            'event_type' => $event_type,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EventType $event_type)
    {
        $validator = Validator::make($request->all(), [
            'name'         => 'required|string|max:200|unique:event_types,name,' . $event_type->id,
            'code'         => 'nullable|string|max:220',
            'serial'       => 'nullable|string|max:220',
            'status'       => 'required|in:active,inactive',
            'description'  => 'nullable|string',
            'logo'         => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'image'        => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            foreach ($errors as $error) {
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
            ];
            $uploadedFiles = [];
            foreach ($files as $key => $file) {
                if (! empty($file)) {
                    $filePath = 'event-type/' . $key;
                    $oldFile  = $event_type->$key ?? null;
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
            // Update the category with the new or existing file paths
            $event_type->update([
                'name'         => $request->name,
                'code'         => $request->code,
                'serial'       => $request->serial,
                'status'       => $request->status,
                'description'  => $request->description,
                'logo'         => $uploadedFiles['logo']['status'] == 1 ? $uploadedFiles['logo']['file_path'] : $event_type->logo,
                'image'        => $uploadedFiles['image']['status'] == 1 ? $uploadedFiles['image']['file_path'] : $event_type->image,
                'banner_image' => $uploadedFiles['banner_image']['status'] == 1 ? $uploadedFiles['banner_image']['file_path'] : $event_type->banner_image,
                'updated_by'   => Auth::guard('admin')->user()->name,
            ]);

            DB::commit();
            Session::flash('success', 'Event Type updated successfully!', ['timeOut' => 30000]);
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('error', 'An error occurred while updating the Event Type: ' . $e->getMessage(), ['timeOut' => 30000]);
            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EventType $event_type)
    {
        $files = [
            'logo'         => $event_type->logo,
            'image'        => $event_type->image,
            'banner_image' => $event_type->banner_image,
        ];
        foreach ($files as $key => $file) {
            if (! empty($file)) {
                $oldFile = $event_type->$key ?? null;
                if ($oldFile && Storage::disk('public')->exists($oldFile)) {
                    Storage::disk('public')->delete($oldFile);
                }
            }
        }
        $event_type->delete();
    }
}
