<?php

namespace App\Http\Controllers\Admin;

use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class BlogCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'blogCategories' => BlogCategory::latest('id')->get(),
        ];
        return view('admin.pages.blogCategory.index', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $validator = Validator::make($request->all(), [
                'name'        => 'required|string|max:255|unique:blog_categories,name',
                'meta_title'  => 'nullable|string|max:255',
                'description' => 'nullable|string',
                'status'      => 'required|in:active,inactive',
            ], [
                'name.required'       => 'The name field is required.',
                'name.string'         => 'The name must be a string.',
                'name.max'            => 'The name may not be greater than :max characters.',
                'name.unique'         => 'This name has already been taken.',
                'meta_title.string'   => 'The meta title must be a string.',
                'meta_title.max'      => 'The meta title may not be greater than :max characters.',
                'description.string'  => 'The description must be a string.',
                'status.required'     => 'The status field is required.',
                'status.in'           => 'The status must be one of: active, inactive.',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $uploadedFiles = $this->handleFileUpload($request);

            if (isset($uploadedFiles['error'])) {
                return redirect()->back()->with('error', $uploadedFiles['error']);
            }

            BlogCategory::create([
                'name'        => $request->name,
                'image'       => $uploadedFiles['image'] ?? null,
                'meta_title'  => $request->meta_title,
                'description' => $request->description,
                'status'      => $request->status,
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Data has been saved successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withInput()->with('error', 'An error occurred while creating the Category: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        DB::beginTransaction();
        $blog_category = BlogCategory::findOrFail($id);
        try {
            $validator = Validator::make($request->all(), [
                'name'        => 'nullable|string|max:255|unique:blog_categories,name,' . $id,
                'meta_title'  => 'nullable|string|max:255',
                'description' => 'nullable|string',
                'status'      => 'required|in:active,inactive',
            ], [
                'name.string'         => 'The name must be a string.',
                'name.max'            => 'The name may not be greater than :max characters.',
                'name.unique'         => 'This name has already been taken.',
                'meta_title.string'   => 'The meta title must be a string.',
                'meta_title.max'      => 'The meta title may not be greater than :max characters.',
                'description.string'  => 'The description must be a string.',
                'status.required'     => 'The status field is required.',
                'status.in'           => 'The status must be one of: active, inactive.',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $uploadedFiles = $this->handleFileUpload($request, $blog_category);

            if (isset($uploadedFiles['error'])) {
                return redirect()->back()->with('error', $uploadedFiles['error']);
            }

            $blog_category->update([
                'name'        => $request->name ?? $blog_category->name,
                'image'       => $uploadedFiles['image'] ?? $blog_category->image,
                'meta_title'  => $request->meta_title,
                'description' => $request->description,
                'status'      => $request->status,
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Data has been updated successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withInput()->with('error', 'An error occurred while updating the Category: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BlogCategory $blog_category)
    {
        // Delete the image if it exists
        $files = [
            'image' => $blog_category->image,
        ];
        foreach ($files as $key => $file) {
            if (!empty($file)) {
                Storage::delete("public/" . $file);
            }
        }
        $blog_category->delete();
    }

    /**
     * Handle file upload.
     */
    protected function handleFileUpload(Request $request, BlogCategory $blog_category = null)
    {
        $files = [
            'image' => $request->file('image'),
        ];

        $uploadedFiles = [];
        foreach ($files as $key => $file) {
            if (!empty($file)) {
                $filePath = 'blog_category/' . $key;
                if ($blog_category) {
                    $oldFile = $blog_category->$key ?? null;
                    if ($oldFile) {
                        Storage::delete("public/" . $oldFile);
                    }
                }
                $uploadResult = customUpload($file, $filePath);
                if ($uploadResult['status'] === 0) {
                    return ['error' => $uploadResult['error_message']];
                }
                $uploadedFiles[$key] = $uploadResult['file_path'];
            } else {
                $uploadedFiles[$key] = null;
            }
        }

        return $uploadedFiles;
    }
}
