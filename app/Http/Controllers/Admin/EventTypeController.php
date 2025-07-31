<?php

namespace App\Http\Controllers\Admin;

use App\Models\EventType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Admin\EventTypeRequest;

class EventTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.pages.categories.index', [
            'categories' => EventType::with('children')->whereNull('parent_id')->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories        = $this->buildCategories(EventType::active()->get());
        $categoriesOptions = $this->buildCategoriesOptions($categories);

        return view('admin.pages.categories.create', [
            'categoriesOptions' => $categoriesOptions,
        ]);
    }

    private function buildCategories($categories, $parentId = null)
    {
        $result = [];

        foreach ($categories as $event_type) {
            if ($event_type->parent_id == $parentId) {
                $children = $this->buildCategories($categories, $event_type->id);

                if ($children) {
                    $event_type->children = $children;
                }

                $result[] = $event_type;
            }
        }

        return $result;
    }

    private function buildCategoriesOptions($selectedId = null, $excludeId = null, $parentId = null, $prefix = '')
    {
        $categories = EventType::active()->where('parent_id', $parentId)->where('id', '!=', $excludeId)->get();
        $options    = '';

        foreach ($categories as $event_type) {
            $selected = $event_type->id == $selectedId ? 'selected' : '';
            $options .= '<option value="' . $event_type->id . '" ' . $selected . '>' . $prefix . $event_type->name . '</option>';
            $options .= $this->buildCategoriesOptions($selectedId, $excludeId, $event_type->id, $prefix . '--');
        }

        return $options;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EventTypeRequest $request)
    {
        // Start the database transaction
        DB::beginTransaction();

        try {
            // Initialize variables to store file paths
            $files = [
                'logo'         => $request->file('logo'),
                'image'        => $request->file('image'),
                'banner_image' => $request->file('banner_image'),
            ];
            $uploadedFiles = [];
            foreach ($files as $key => $file) {
                if (! empty($file)) {
                    $filePath            = 'category/' . $key;
                    $uploadedFiles[$key] = customUpload($file, $filePath);
                    if ($uploadedFiles[$key]['status'] === 0) {
                        return redirect()->back()->with('error', $uploadedFiles[$key]['error_message']);
                    }
                } else {
                    $uploadedFiles[$key] = ['status' => 0];
                }
            }
            // Create the category model instance
            $event_type = EventType::create([
                'name'         => $request->name,
                'bangla_name'  => $request->bangla_name,
                'parent_id'    => $request->parent_id,
                'code'         => $request->code,
                'serial'       => $request->serial,

                'logo'         => $uploadedFiles['logo']['status']         == 1 ? $uploadedFiles['logo']['file_path']        : null,
                'image'        => $uploadedFiles['image']['status']        == 1 ? $uploadedFiles['image']['file_path']       : null,
                'banner_image' => $uploadedFiles['banner_image']['status'] == 1 ? $uploadedFiles['banner_image']['file_path'] : null,

                'added_by'     => Auth::guard('admin')->user()->name,

                'description'  => $request->description,
                'status'       => $request->status,
            ]);

            // Commit the database transaction
            DB::commit();

            //Mail Send
            // $admins = Admin::where('mail_status', 'mail')->get();
            // foreach ($admins as $admin) {
            //     Mail::to($admin->email)->send(new CategoryCreated($event_type));
            // }

            return redirect()->route('admin.categories.index')->with('success', 'Category created successfully');
        } catch (\Exception $e) {
            // Rollback the database transaction in case of an error
            DB::rollback();

            // Return back with error message
            return redirect()->back()->withInput()->with('error', 'An error occurred while creating the Category: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return view('admin.pages.categories.show', [
            'category' => EventType::findOrFail($id),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $event_type          = EventType::findOrFail($id);
        $categoriesOptions = $this->buildCategoriesOptions($event_type->parent_id, $event_type->id);

        return view('admin.pages.categories.edit', [
            'category'          => $event_type,
            'categoriesOptions' => $categoriesOptions,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EventTypeRequest $request, EventType $event_type)
    {
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
                    $filePath = 'category/' . $key;
                    $oldFile  = $event_type->$key ?? null;

                    // Delete old file from public storage
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
                'bangla_name'  => $request->bangla_name,
                'parent_id'    => $request->parent_id,
                'code'         => $request->code,
                'serial'       => $request->serial,
                'logo'         => $uploadedFiles['logo']['status'] == 1 ? $uploadedFiles['logo']['file_path'] : $event_type->logo,
                'image'        => $uploadedFiles['image']['status'] == 1 ? $uploadedFiles['image']['file_path'] : $event_type->image,
                'banner_image' => $uploadedFiles['banner_image']['status'] == 1 ? $uploadedFiles['banner_image']['file_path'] : $event_type->banner_image,
                'description'  => $request->description,
                'status'       => $request->status,

                'updated_by'   => Auth::guard('admin')->user()->name,
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Category updated successfully');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withInput()->with('error', 'An error occurred while updating the category: ' . $e->getMessage());
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
