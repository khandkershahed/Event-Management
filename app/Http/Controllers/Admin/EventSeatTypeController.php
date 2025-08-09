<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\EventSeatType;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class EventSeatTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.pages.eventSeatType.index', [
            'event_seat_types' => EventSeatType::latest()->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.pages.eventSeatType.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'        => 'required|string|max:255|unique:event_seat_types,name',
            'code'        => 'nullable|string|max:220',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description' => 'nullable|string',
            'status'      => 'required|in:active,inactive',
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
                'image'        => $request->file('image'),
            ];
            $uploadedFiles = [];
            foreach ($files as $key => $file) {
                if (! empty($file)) {
                    $filePath            = 'event-seat-type/' . $key;
                    $uploadedFiles[$key] = customUpload($file, $filePath);
                    if ($uploadedFiles[$key]['status'] === 0) {
                        return redirect()->back()->with('error', $uploadedFiles[$key]['error_message']);
                    }
                } else {
                    $uploadedFiles[$key] = ['status' => 0];
                }
            }

            EventSeatType::create([
                'name'        => $request->name,
                'code'        => $request->code,
                'image'       => $uploadedFiles['image']['status'] === 1 ? $uploadedFiles['image']['file_path'] : null,
                'description' => $request->description,
                'status'      => $request->status,
                'added_by'    => Auth::guard('admin')->user()->name ?? 'system',
            ]);

            DB::commit();
            Session::flash('success', 'Seat Type created successfully!', ['timeOut' => 30000]);
            return redirect()->route('admin.event-seat-type.index');
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('error', 'An error occurred while creating the Seat Type: ' . $e->getMessage(), ['timeOut' => 30000]);
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
        return view('admin.pages.eventSeatType.edit', [
            'eventSeatType' => EventSeatType::findOrFail($id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EventSeatType $event_seat_type)
    {
        $validator = Validator::make($request->all(), [
            'name'         => 'required|string|max:200|unique:event_seat_types,name,' . $event_seat_type->id,
            'code'         => 'nullable|string|max:220',
            'serial'       => 'nullable|string|max:220',
            'status'       => 'required|in:active,inactive',
            'description'  => 'nullable|string',
            'image'        => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
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
                'image' => $request->file('image'),
            ];
            $uploadedFiles = [];
            foreach ($files as $key => $file) {
                if (! empty($file)) {
                    $filePath = 'event-seat-type/' . $key;
                    $oldFile  = $event_seat_type->$key ?? null;
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
            $event_seat_type->update([
                'name'         => $request->name,
                'code'         => $request->code,
                'status'       => $request->status,
                'description'  => $request->description,
                'image'        => $uploadedFiles['image']['status'] == 1 ? $uploadedFiles['image']['file_path'] : $event_seat_type->image,
                'updated_by'   => Auth::guard('admin')->user()->name,
            ]);

            DB::commit();
            Session::flash('success', 'Event Seat Type updated successfully!', ['timeOut' => 30000]);
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('error', 'An error occurred while updating the Event Seat Type: ' . $e->getMessage(), ['timeOut' => 30000]);
            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EventSeatType $event_seat_type)
    {
        $event_seat_type->delete();
    }
}
