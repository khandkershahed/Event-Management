<?php

namespace App\Http\Controllers\Admin;

use App\Models\PageBanner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PageBannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['banners'] = PageBanner::latest('id')->get();
        return view('admin.pages.pageBanner.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.pages.pageBanner.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'page_name'   => 'nullable',
            'image'       => 'nullable|file|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'badge'       => 'nullable|string|max:191',
            'button_name' => 'nullable|string|max:200',
            'button_link' => 'nullable|string',
            'status'      => 'nullable|in:active,inactive',
        ], [
            'page_name.required'        => 'The Page Name is required.',
            'image.required'            => 'The Image field is required.',
            'image.file'                => 'The Image must be a file.',
            'image.mimes'               => 'The Image must be a file of type: jpeg, png, jpg, gif.',
            'image.max'                 => 'The Image may not be greater than 2MB.',
            'status.required'           => 'The Status field is required.',
            'status.in'                 => 'The status must be one of: active, inactive.',
        ]);

        if ($validator->fails()) {
            Session::flash('error', $validator);
            return redirect()->back()->withErrors($validator)->withInput();
        }
        DB::beginTransaction();

        try {
            $files = [
                'image' => $request->file('image'),
                'bg_image' => $request->file('bg_image'),
            ];
            $uploadedFiles = [];
            foreach ($files as $key => $file) {
                if (!empty($file)) {
                    $filePath = 'pagebanner/' . $key;
                    $uploadedFiles[$key] = customUpload($file, $filePath);
                    if ($uploadedFiles[$key]['status'] === 0) {
                        return redirect()->back()->with('error', $uploadedFiles[$key]['error_message']);
                    }
                } else {
                    $uploadedFiles[$key] = ['status' => 0];
                }
            }

            PageBanner::create([
                'page_name'   => $request->page_name,
                'image'       => $uploadedFiles['image']['status'] == 1 ? $uploadedFiles['image']['file_path'] : null,
                'bg_image'    => $uploadedFiles['bg_image']['status'] == 1 ? $uploadedFiles['bg_image']['file_path'] : null,
                'badge'       => $request->badge,
                'button_name' => $request->button_name,
                'button_link' => $request->button_link,
                'title'       => $request->title,
                'subtitle'    => $request->subtitle,
                'banner_link' => $request->banner_link,
                'status'      => $request->status,
            ]);

            DB::commit();

            return redirect()->route('admin.banner.index')->with('success', 'Banner created successfully');
        } catch (\Exception $e) {
            // Rollback the database transaction in case of an error
            DB::rollback();
            Session::flash('error', $e->getMessage());
            // Return back with error message
            return redirect()->back()->withInput()->with('error', 'An error occurred while creating the Brand: ' . $e->getMessage());
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
        $data['banner'] = PageBanner::findOrFail($id);
        return view('admin.pages.pageBanner.edit', $data);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // dd($request->all());
        $banner = PageBanner::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'page_name'   => 'nullable',
            'image'       => 'nullable|file|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'badge'       => 'nullable|string|max:191',
            'button_name' => 'nullable|string|max:200',
            'button_link' => 'nullable|string',
            'status'      => 'nullable|in:active,inactive',
        ], [
            'page_name.required'        => 'The Page Name is required.',
            'image.required'            => 'The Image field is required.',
            'image.file'                => 'The Image must be a file.',
            'image.mimes'               => 'The Image must be a file of type: jpeg, png, jpg, gif.',
            'image.max'                 => 'The Image may not be greater than 2MB.',
            'status.required'           => 'The Status field is required.',
            'status.in'                 => 'The status must be one of: active, inactive.',
        ]);

        if ($validator->fails()) {
            Session::flash('error', $validator);
            return redirect()->back()->withErrors($validator)->withInput();
        }
        DB::beginTransaction();

        try {
            $files = [
                'image' => $request->file('image'),
                'bg_image' => $request->file('bg_image'),
            ];
            $uploadedFiles = [];

            foreach ($files as $key => $file) {
                if (!empty($file)) {
                    $filePath = 'pagebanner/' . $key;
                    $oldFile = $banner->$key ?? null;

                    if ($oldFile) {
                        Storage::delete("public/" . $oldFile);
                    }
                    $uploadedFiles[$key] = customUpload($file, $filePath);
                    if ($uploadedFiles[$key]['status'] === 0) {
                        return redirect()->back()->with('error', $uploadedFiles[$key]['error_message']);
                    }
                } else {
                    $uploadedFiles[$key] = ['status' => 0];
                }
            }
// dd($uploadedFiles['bg_image']['file_path'] );
            $banner->update([
                'page_name'   => $request->page_name,
                'image'       => $uploadedFiles['image']['status'] == 1 ? $uploadedFiles['image']['file_path'] : $banner->image,
                'bg_image'    => $uploadedFiles['bg_image']['status'] == 1 ? $uploadedFiles['bg_image']['file_path'] : $banner->bg_image,
                'badge'       => $request->badge,
                'button_name' => $request->button_name,
                'button_link' => $request->button_link,
                'title'       => $request->title,
                'subtitle'    => $request->subtitle,
                'banner_link' => $request->banner_link,
                'status'      => $request->status,
            ]);

            DB::commit();

            return redirect()->route('admin.banner.index')->with('success', 'Banner updated successfully');
        } catch (\Exception $e) {
            // Rollback the database transaction in case of an error
            DB::rollback();
            Session::flash('error', $e->getMessage());
            // Return back with error message
            return redirect()->back()->withInput()->with('error', 'An error occurred while creating the Brand: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $banner = PageBanner::findOrFail($id);
        $files = [
            'image'    => $banner->image,
            'bg_image' => $banner->bg_image,
        ];
        foreach ($files as $key => $file) {
            if (!empty($file)) {
                $oldFile = $banner->$key ?? null;
                if ($oldFile) {
                    Storage::delete("public/" . $oldFile);
                }
            }
        }
        $banner->delete();
    }
}
