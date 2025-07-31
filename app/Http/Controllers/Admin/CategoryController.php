<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryRequest;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.pages.categories.index', [
            'categories' => Category::with('children')->whereNull('parent_id')->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories        = $this->buildCategories(Category::active()->get());
        $categoriesOptions = $this->buildCategoriesOptions($categories);

        return view('admin.pages.categories.create', [
            'categoriesOptions' => $categoriesOptions,
        ]);
    }

    private function buildCategories($categories, $parentId = null)
    {
        $result = [];

        foreach ($categories as $category) {
            if ($category->parent_id == $parentId) {
                $children = $this->buildCategories($categories, $category->id);

                if ($children) {
                    $category->children = $children;
                }

                $result[] = $category;
            }
        }

        return $result;
    }

    private function buildCategoriesOptions($selectedId = null, $excludeId = null, $parentId = null, $prefix = '')
    {
        $categories = Category::active()->where('parent_id', $parentId)->where('id', '!=', $excludeId)->get();
        $options    = '';

        foreach ($categories as $category) {
            $selected = $category->id == $selectedId ? 'selected' : '';
            $options .= '<option value="' . $category->id . '" ' . $selected . '>' . $prefix . $category->name . '</option>';
            $options .= $this->buildCategoriesOptions($selectedId, $excludeId, $category->id, $prefix . '--');
        }

        return $options;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request)
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
            $category = Category::create([
                'name'         => $request->name,
                'parent_id'    => $request->parent_id,

                'logo'         => $uploadedFiles['logo']['status'] == 1 ? $uploadedFiles['logo']['file_path'] : null,
                'image'        => $uploadedFiles['image']['status'] == 1 ? $uploadedFiles['image']['file_path'] : null,
                'banner_image' => $uploadedFiles['banner_image']['status'] == 1 ? $uploadedFiles['banner_image']['file_path'] : null,

                'added_by'     => Auth::guard('admin')->user()->id,

                'description'  => $request->description,
                'status'       => $request->status,
            ]);

            // Commit the database transaction
            DB::commit();

            //Mail Send
            // $admins = Admin::where('mail_status', 'mail')->get();
            // foreach ($admins as $admin) {
            //     Mail::to($admin->email)->send(new CategoryCreated($category));
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
            'category' => Category::findOrFail($id),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $category          = Category::findOrFail($id);
        $categoriesOptions = $this->buildCategoriesOptions($category->parent_id, $category->id);

        return view('admin.pages.categories.edit', [
            'category'          => $category,
            'categoriesOptions' => $categoriesOptions,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request, Category $category)
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
                    $oldFile  = $category->$key ?? null;

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

            // Update the category with the new or existing file paths
            $category->update([
                'name'         => $request->name,
                'parent_id'    => $request->parent_id,
                'logo'         => $uploadedFiles['logo']['status'] == 1 ? $uploadedFiles['logo']['file_path'] : $category->logo,
                'image'        => $uploadedFiles['image']['status'] == 1 ? $uploadedFiles['image']['file_path'] : $category->image,
                'banner_image' => $uploadedFiles['banner_image']['status'] == 1 ? $uploadedFiles['banner_image']['file_path'] : $category->banner_image,
                'description'  => $request->description,
                'status'       => $request->status,

                'added_by'     => Auth::guard('admin')->user()->id,
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Category updated successfully');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'An error occurred while updating the category: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $files = [
            'logo'         => $category->logo,
            'image'        => $category->image,
            'banner_image' => $category->banner_image,
        ];
        foreach ($files as $key => $file) {
            if (! empty($file)) {
                $oldFile = $category->$key ?? null;
                if ($oldFile) {
                    Storage::delete("public/" . $oldFile);
                }
            }
        }
        $category->delete();
    }
}
