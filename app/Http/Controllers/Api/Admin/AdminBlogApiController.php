<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\BiraConsultantBlog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminBlogApiController extends Controller
{
    public function index(Request $request)
    {
        $query = BiraConsultantBlog::with(['author', 'category']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('title', 'like', "%{$search}%");
        }

        $blogs = $query->latest()->paginate(15);
        return response()->json(['success' => true, 'data' => $blogs]);
    }

    public function show($slug)
    {
        $blog = BiraConsultantBlog::with(['author', 'category'])
            ->where('slug', $slug)
            ->firstOrFail();

        return response()->json(['success' => true, 'data' => $blog]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'category_id' => 'nullable|exists:biraconsultant_blog_categories,id',
            'status' => 'required|in:draft,published,archived',
            'published_at' => 'nullable|date',
        ]);

        $blog = BiraConsultantBlog::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'excerpt' => $request->excerpt,
            'content' => $request->content,
            'author_id' => auth()->id(),
            'category_id' => $request->category_id,
            'status' => $request->status,
            'published_at' => $request->published_at,
        ]);

        return response()->json(['success' => true, 'data' => $blog], 201);
    }

    public function update(Request $request, $slug)
    {
        $blog = BiraConsultantBlog::where('slug', $slug)->firstOrFail();

        $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'category_id' => 'nullable|exists:biraconsultant_blog_categories,id',
            'status' => 'required|in:draft,published,archived',
            'published_at' => 'nullable|date',
        ]);

        $blog->update([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'excerpt' => $request->excerpt,
            'content' => $request->content,
            'category_id' => $request->category_id,
            'status' => $request->status,
            'published_at' => $request->published_at,
        ]);

        return response()->json(['success' => true, 'data' => $blog]);
    }

    public function destroy($slug)
    {
        $blog = BiraConsultantBlog::where('slug', $slug)->firstOrFail();

        if ($blog->featured_image) {
            $imagePath = str_replace(asset('storage/') . '/', '', $blog->featured_image);
            $fullPath = public_path('storage/' . $imagePath);
            if (file_exists($fullPath)) {
                unlink($fullPath);
            }
        }

        $blog->delete();
        return response()->json(['success' => true]);
    }
}
