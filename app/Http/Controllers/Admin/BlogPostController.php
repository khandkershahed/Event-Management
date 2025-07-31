<?php

namespace App\Http\Controllers\Admin;

use App\Models\BlogTag;
use App\Models\BlogPost;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class BlogPostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'blogPosts' => BlogPost::latest('id')->get(),

        ];
        return view('admin.pages.blogPost.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = [
            'blogTags' => BlogTag::latest('id')->get(['name', 'id']),
            'blogCategories' => BlogCategory::latest('id')->get(['name', 'id']),

        ];
        return view('admin.pages.blogPost.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            // Custom validation rules
            $validator = Validator::make($request->all(), [
                'category_id' => 'nullable',
                'tag_id' => 'nullable',
                'featured' => 'nullable|in:0,1',
                'type' => 'nullable|string|max:255',
                'badge' => 'nullable|string|max:255',
                'title' => 'nullable|string|unique:blog_posts,title,',
                'header' => 'nullable|string|max:255',
                'short_description' => 'nullable|string',
                'long_description' => 'nullable|string',
                'author' => 'nullable|string|max:255',
                'address' => 'nullable|string|max:255',
                'tags' => 'nullable',
                'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:3072',
                'additional_url' => 'nullable|string|max:255|url',
                'footer' => 'nullable|string',
                'status' => 'nullable|string|max:255',
            ], [
                'logo.image' => 'The logo must be an image.',
                'image.image' => 'The image must be an image.',
                'banner_image.image' => 'The banner image must be an image.',
                'additional_url.url' => 'The additional URL must be a valid URL.',
            ]);

            if ($validator->fails()) {
                foreach ($validator->messages()->all() as $message) {
                    Session::flash('error', $message);
                }
                return redirect()->back()->withInput();
            }

            // Handle file uploads
            $files = [
                'logo' => $request->file('logo'),
                'image' => $request->file('image'),
                'banner_image' => $request->file('banner_image'),
            ];

            $uploadedFiles = [];
            foreach ($files as $key => $file) {
                if ($file) {
                    $filePath = 'blog_posts/' . $key;
                    $uploadedFiles[$key] = customUpload($file, $filePath);
                    if ($uploadedFiles[$key]['status'] === 0) {
                        return redirect()->back()->with('error', $uploadedFiles[$key]['error_message']);
                    }
                } else {
                    $uploadedFiles[$key] = ['status' => 0];
                }
            }

            // Create a new BlogPost record
            $blogPost = BlogPost::create([
                'category_id'       => json_encode($request->category_id),
                'tag_id'            => json_encode($request->tag_id),
                'featured'          => $request->featured,
                'type'              => $request->type,
                'badge'             => $request->badge,
                'title'             => $request->title,
                'header'            => $request->header,
                'short_description' => $request->short_description,
                'long_description'  => $request->long_description,
                'author'            => $request->author,
                'address'           => $request->address,
                'tags'              => $request->tags,
                'logo'              => $uploadedFiles['logo']['status'] === 1 ? $uploadedFiles['logo']['file_path'] : null,
                'image'             => $uploadedFiles['image']['status'] === 1 ? $uploadedFiles['image']['file_path'] : null,
                'banner_image'      => $uploadedFiles['banner_image']['status'] === 1 ? $uploadedFiles['banner_image']['file_path'] : null,
                'additional_url'    => $request->additional_url,
                'footer'            => $request->footer,
                'status'            => $request->status,
            ]);

            // Commit the transaction
            DB::commit();

            return redirect()->route('admin.blog-post.index')->with('success', 'Blog post created successfully.');
        } catch (\Exception $e) {
            // Rollback the transaction if there's an error
            DB::rollback();

            // Return with error message
            return redirect()->back()->withInput()->with('error', 'An error occurred while creating the blog post: ' . $e->getMessage());
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
        $data = [
            'blogPost' => BlogPost::findOrFail($id),
            'blogTags' => BlogTag::latest('id')->get(['name', 'id']),
            'blogCategories' => BlogCategory::latest('id')->get(['name', 'id']),

        ];
        return view('admin.pages.blogPost.edit',$data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // dd($request->all());
        DB::beginTransaction();

        try {
            // Find the existing blog post
            $blogPost = BlogPost::findOrFail($id);

            // Custom validation rules
            $validator = Validator::make($request->all(), [
                'category_id' => 'nullable',
                'tag_id' => 'nullable',
                'featured' => 'nullable|in:0,1',
                'type' => 'nullable|string|max:255',
                'badge' => 'nullable|string|max:255',
                'title' => 'nullable|string',
                'header' => 'nullable|string|max:255',
                'author' => 'nullable|string|max:255',
                'address' => 'nullable|string|max:255',
                'tags' => 'nullable',
                'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:3072',
                'additional_url' => 'nullable|string|max:255|url',
                'status' => 'nullable|string|max:255',
            ], [
                'logo.image' => 'The logo must be an image.',
                'image.image' => 'The image must be an image.',
                'banner_image.image' => 'The banner image must be an image.',
                'additional_url.url' => 'The additional URL must be a valid URL.',
            ]);

            if ($validator->fails()) {
                foreach ($validator->messages()->all() as $message) {
                    Session::flash('error', $message);
                }
                return redirect()->back()->withInput();
            }

            // Handle file uploads
            $files = [
                'logo' => $request->file('logo'),
                'image' => $request->file('image'),
                'banner_image' => $request->file('banner_image'),
            ];
            $uploadedFiles = [];
            foreach ($files as $key => $file) {
                if (!empty($file)) {
                    $filePath = 'blog_posts/' . $key;
                    $oldFile = $blogPost->$key ?? null;
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
            // dd($request->all());
            // dd($uploadedFiles['image']['file_path']);
            // Update the BlogPost record
            $blogPost->update([
                'category_id'       => json_encode($request->category_id),
                'tag_id'            => json_encode($request->tag_id),
                'featured'          => $request->featured,
                'type'              => $request->type,
                'badge'             => $request->badge,
                'title'             => $request->title,
                'header'            => $request->header,
                'short_description' => $request->short_description,
                'long_description'  => $request->long_description,
                'author'            => $request->author,
                'address'           => $request->address,
                'tags'              => $request->tags,
                'logo'              => $uploadedFiles['logo']['status']         == 1 ? $uploadedFiles['logo']['file_path']         : $blogPost->logo,
                'image'             => $uploadedFiles['image']['status']        == 1 ? $uploadedFiles['image']['file_path']        : $blogPost->image,
                'banner_image'      => $uploadedFiles['banner_image']['status'] == 1 ? $uploadedFiles['banner_image']['file_path'] : $blogPost->banner_image,
                'additional_url'    => $request->additional_url,
                'footer'            => $request->footer,
                'status'            => $request->status,
            ]);

            // Commit the transaction
            DB::commit();

            return redirect()->back()->with('success', 'Blog post updated successfully.');
        } catch (\Exception $e) {
            // Rollback the transaction if there's an error
            DB::rollback();

            // Return with error message
            return redirect()->back()->withInput()->with('error', 'An error occurred while updating the blog post: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $blogPost = BlogPost::findOrFail($id);
        $files = [
            'logo' => $blogPost->logo,
            'image' => $blogPost->image,
            'banner_image' => $blogPost->banner_image,
        ];
        foreach ($files as $key => $file) {
            if (!empty($file)) {
                $oldFile = $blogPost->$key ?? null;
                if ($oldFile) {
                    Storage::delete("public/" . $oldFile);
                }
            }
        }
        $blogPost->delete();
    }
}
