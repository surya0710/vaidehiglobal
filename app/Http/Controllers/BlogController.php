<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Category;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function index($categorySlug = null)
    {
        $categories         = Category::select('name','slug')->get();
        if (!empty($categorySlug)) {
            $category       = Category::where('slug', $categorySlug)->first();
        } else {
            $category       = null;
        }
        if (!empty($categorySlug) && $category) {
            $latestBlogs    = Blog::where('category_id', $category->id)->latest()->paginate(10);
        } else {
            $latestBlogs    = Blog::latest()->paginate(10);
        }

        return response()->json([
            'data' => collect($latestBlogs->items())->map(function ($blog) {
                return [
                    'id'            => $blog->id,
                    'title'         => $blog->title,
                    'slug'          => $blog->slug,
                    'content'       => Str::limit(strip_tags($blog->content), 120), // 🔥 FIX
                    'created_at'    => $blog->created_at,
                    'read_time'     => $blog->read_time,
                    'category_name' => $blog->category->name
                ];
            }),
            'meta' => [
                'total' => $latestBlogs->total(),
                'per_page' => $latestBlogs->perPage(),
                'current_page' => $latestBlogs->currentPage(),
                'last_page' => $latestBlogs->lastPage(),
            ],
            'categories' => $categories
        ]);
    }

    public function fetchBlogBySlug($slug)
    {
        $blog = Blog::where('slug', $slug)->first();
        $imagePrefix = env('APP_URL') . '/uploads/blogs/';
        return response()->json([
            'data' => $blog,
            'prefix' => $imagePrefix
        ]);
    }
}