<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BiraConsultantBlog;
use App\Models\BiraConsultantBlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Helpers\UploadLimitHelper;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = BiraConsultantBlog::with(['author', 'category'])
            ->orderBy('title', 'asc')
            ->paginate(15);

        return view('admin.blogs.index', compact('blogs'));
    }

    public function query(Request $request)
    {
        $query = trim($request->input('query-blog', ''));

        $blogs = BiraConsultantBlog::with(['author', 'category'])
            ->where('title', 'like', '%' . $query . '%')
            ->orWhere('slug', 'like', '%' . $query . '%')
            ->orderBy('title', 'asc')
            ->paginate(15);

        return view('admin.blogs.index', compact('blogs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'           => 'required|string|max:255',
            'excerpt'         => 'nullable|string|max:255',
            'content'         => 'nullable|string',
            'featured_image'  => 'nullable|image|mimes:jpeg,png,jpg|max:' . UploadLimitHelper::maxRule('blog_images'),
            'category_id'     => 'nullable|exists:biraconsultant_blog_categories,id',
            'status'          => 'required|in:draft,published,archived',
            'published_at'    => 'nullable|date',
        ]);

        BiraConsultantBlog::create([
            'title'          => $request->title,
            'slug'           => Str::slug($request->title),
            'excerpt'        => $request->excerpt,
            'content'        => $request->content,
            'featured_image' => $request->featured_image,
            'author_id'      => Auth::id(),
            'category_id'    => $request->category_id,
            'status'         => $request->status,
            'published_at'   => $request->published_at,
        ]);

        Session::flash('success', 'Blog post created successfully.');
        return redirect()->route('admin.blogs.index');
    }

    public function edit($slug)
    {
        $blog = BiraConsultantBlog::with(['author', 'category'])->where('slug', $slug)->firstOrFail();
        return response()->json($blog);
    }

    public function update(Request $request)
    {
    $validated = $request->validate([
        'edit_blog_id'     => 'required|exists:biraconsultant_blogs,id',
        'title'            => 'required|string|max:255',
        'excerpt'          => 'nullable|string|max:255',
        'content'          => 'nullable|string',
        'featured_image'   => 'nullable|image|mimes:jpeg,png,jpg|max:' . UploadLimitHelper::maxRule('blog_images'),
        'category_id'      => 'nullable|exists:biraconsultant_blog_categories,id',
        'status'           => 'required|in:draft,published,archived',
        'published_at'     => 'nullable|date',
    ]);

    $blog = BiraConsultantBlog::findOrFail($validated['edit_blog_id']);

    // Handle image upload
    if ($request->hasFile('featured_image')) {
        // Delete old image if it exists
        if ($blog->featured_image) {
            $existingImagePath = str_replace(asset('storage/') . '/', '', $blog->featured_image);
            $fullPath = public_path('storage/' . $existingImagePath);
            if (file_exists($fullPath)) {
                unlink($fullPath);
            }
        }

        // Store new image with random name
        $randomFileName = Str::random(20) . '.' . $request->file('featured_image')->getClientOriginalExtension();
        $path = $request->file('featured_image')->storeAs('blogs/uploads/images', $randomFileName, 'public');
        $blog->featured_image = asset('storage/' . $path);
    }

    $blog->title = $validated['title'];
    $blog->slug = Str::slug($validated['title']);
    $blog->excerpt = $validated['excerpt'];
    $blog->content = $validated['content'];
    $blog->category_id = $validated['category_id'];
    $blog->status = $validated['status'];
    $blog->published_at = $validated['published_at'];
    $blog->save();

    Session::flash('success', 'Blog post updated successfully.');
    return redirect()->route('admin.blogs.index');
    }

    public function delete($slug)
    {
    $blog = BiraConsultantBlog::where('slug', $slug)->firstOrFail();

    // Delete the featured image file if it exists
    if ($blog->featured_image) {
        $imagePath = str_replace(asset('storage/') . '/', '', $blog->featured_image);
        $fullPath = public_path('storage/' . $imagePath);
        if (File::exists($fullPath)) {
            File::delete($fullPath);
        }
    }

    $blog->delete();

    Session::flash('success', 'Blog post deleted successfully.');
    return redirect()->route('admin.blogs.index');
    }
}