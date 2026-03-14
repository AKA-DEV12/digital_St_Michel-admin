<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\BlogCategory;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    /**
     * Get latest published posts.
     */
    public function latest()
    {
        $posts = BlogPost::with(['category', 'author', 'tags'])
            ->published()
            ->latest()
            ->paginate(12);

        return response()->json($posts);
    }

    /**
     * Get featured posts for slider.
     */
    public function featured()
    {
        $posts = BlogPost::with(['category', 'author'])
            ->published()
            ->featured()
            ->latest()
            ->take(5)
            ->get();

        return response()->json($posts);
    }

    /**
     * Get a single post by slug.
     */
    public function show($slug)
    {
        $post = BlogPost::with(['category', 'author', 'tags'])
            ->where('slug', $slug)
            ->published()
            ->firstOrFail();

        return response()->json($post);
    }

    /**
     * Get all categories.
     */
    public function categories()
    {
        $categories = BlogCategory::withCount('posts')->get();
        return response()->json($categories);
    }

    /**
     * Get posts by category.
     */
    public function byCategory($slug)
    {
        $category = BlogCategory::where('slug', $slug)->firstOrFail();

        $posts = BlogPost::with(['category', 'author', 'tags'])
            ->where('blog_category_id', $category->id)
            ->published()
            ->latest()
            ->paginate(12);

        return response()->json([
            'category' => $category,
            'posts' => $posts
        ]);
    }
}
