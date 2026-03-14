<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\BlogCategory;
use App\Models\BlogTag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
    public function export(Request $request, \App\Services\ExportService $exportService)
    {
        $posts = BlogPost::with(['author', 'category'])->latest()->get();

        return $exportService->export(
            $request,
            'Articles de Blog',
            'articles_blog_' . date('Y-m-d'),
            ['ID', 'Titre', 'Catégorie', 'Auteur', 'Statut', 'Créé le'],
            $posts,
            function ($post) {
                return [
                    $post->id,
                    $post->title,
                    $post->category->name ?? 'N/A',
                    $post->author->name ?? 'N/A',
                    ucfirst(strtolower($post->status)),
                    $post->created_at ? $post->created_at->format('Y-m-d H:i') : ''
                ];
            }
        );
    }

    public function index()
    {
        $posts = BlogPost::with(['author', 'category'])->latest()->paginate(10);
        return view('admin.blog.index', compact('posts'));
    }

    public function create()
    {
        $categories = BlogCategory::all();
        $tags = BlogTag::all();
        return view('admin.blog.create', compact('categories', 'tags'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'blog_category_id' => 'required|exists:blog_categories,id',
            'status' => 'required|in:draft,published',
            'is_featured' => 'boolean',
            'is_popular' => 'boolean',
            'featured_image' => 'nullable|image|max:2048',
            'url_video' => 'nullable|url|max:255',
        ]);

        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')->store('blog/posts', 'public');
        }

        $validated['user_id'] = auth()->id();
        $validated['slug'] = Str::slug($validated['title']);
        $validated['published_at'] = $request->status === 'published' ? now() : null;

        $post = BlogPost::create($validated);

        if ($request->has('tags')) {
            $post->tags()->sync($request->tags);
        }

        return redirect()->route('admin.blog.index')->with('success', 'Article créé avec succès.');
    }

    public function edit(BlogPost $post)
    {
        $categories = BlogCategory::all();
        $tags = BlogTag::all();
        return view('admin.blog.edit', compact('post', 'categories', 'tags'));
    }

    public function update(Request $request, BlogPost $post)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'blog_category_id' => 'required|exists:blog_categories,id',
            'status' => 'required|in:draft,published',
            'is_featured' => 'boolean',
            'is_popular' => 'boolean',
            'featured_image' => 'nullable|image|max:2048',
            'url_video' => 'nullable|url|max:255',
        ]);

        if ($request->hasFile('featured_image')) {
            if ($post->featured_image) {
                Storage::disk('public')->delete($post->featured_image);
            }
            $validated['featured_image'] = $request->file('featured_image')->store('blog/posts', 'public');
        }

        $validated['slug'] = Str::slug($validated['title']);

        if ($request->status === 'published' && !$post->published_at) {
            $validated['published_at'] = now();
        }

        $post->update($validated);

        if ($request->has('tags')) {
            $post->tags()->sync($request->tags);
        }

        return redirect()->route('admin.blog.index')->with('success', 'Article mis à jour avec succès.');
    }

    public function destroy(BlogPost $post)
    {
        if ($post->featured_image) {
            Storage::disk('public')->delete($post->featured_image);
        }
        $post->delete();
        return redirect()->route('admin.blog.index')->with('success', 'Article supprimé.');
    }

    public function exportCategories(Request $request, \App\Services\ExportService $exportService)
    {
        $categories = BlogCategory::withCount('posts')->get();

        return $exportService->export(
            $request,
            'Catégories de Blog',
            'categories_blog_' . date('Y-m-d'),
            ['ID', 'Nom', 'Articles associés', 'Créé le'],
            $categories,
            function ($cat) {
                return [
                    $cat->id,
                    $cat->name,
                    $cat->posts_count,
                    $cat->created_at ? $cat->created_at->format('Y-m-d H:i') : ''
                ];
            }
        );
    }

    // Categories
    public function categories()
    {
        $categories = BlogCategory::withCount('posts')->paginate(10);
        return view('admin.blog.categories', compact('categories'));
    }

    public function storeCategory(Request $request)
    {
        $request->validate(['name' => 'required|unique:blog_categories|max:255']);
        BlogCategory::create(['name' => $request->name, 'slug' => Str::slug($request->name)]);
        return back()->with('success', 'Catégorie ajoutée.');
    }

    public function exportTags(Request $request, \App\Services\ExportService $exportService)
    {
        $tags = BlogTag::withCount('posts')->get();

        return $exportService->export(
            $request,
            'Mots-clés (Tags) de Blog',
            'tags_blog_' . date('Y-m-d'),
            ['ID', 'Nom', 'Articles associés', 'Créé le'],
            $tags,
            function ($tag) {
                return [
                    $tag->id,
                    $tag->name,
                    $tag->posts_count,
                    $tag->created_at ? $tag->created_at->format('Y-m-d H:i') : ''
                ];
            }
        );
    }

    // Tags
    public function tags()
    {
        $tags = BlogTag::withCount('posts')->paginate(10);
        return view('admin.blog.tags', compact('tags'));
    }

    public function storeTag(Request $request)
    {
        $request->validate(['name' => 'required|unique:blog_tags|max:255']);
        BlogTag::create(['name' => $request->name, 'slug' => Str::slug($request->name)]);
        return back()->with('success', 'Tag ajouté.');
    }
}
