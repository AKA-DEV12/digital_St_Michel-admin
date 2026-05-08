<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\BlogCategory;
use App\Models\BlogTag;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
    protected $cloudinaryService;

    public function __construct(CloudinaryService $cloudinaryService)
    {
        $this->cloudinaryService = $cloudinaryService;
    }

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
            'featured_media_type' => 'required|in:image,video',
            'featured_image' => 'nullable|required_if:featured_media_type,image|image|mimes:jpeg,png,jpg,gif|max:10240',
            'url_video' => 'nullable|required_if:featured_media_type,video|file|mimes:mp4,avi,mov,wmv,flv,webm|max:51200',
            'secondary_images' => 'nullable|array|max:2',
            'secondary_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
            'gallery_images' => 'nullable|array',
            'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['slug'] = Str::slug($validated['title']);
        $validated['published_at'] = $request->status === 'published' ? now() : null;

        // Gestion de l'image principale avec Cloudinary
        if ($request->hasFile('featured_image') && $request->file('featured_image')->isValid()) {
            $url = $this->cloudinaryService->uploadFile($request->file('featured_image'), 'blog/featured');
            if ($url) {
                $validated['featured_image'] = $url;
            }
        }

        // Gestion de la vidéo avec Cloudinary
        if ($request->hasFile('url_video') && $request->file('url_video')->isValid()) {
            $url = $this->cloudinaryService->uploadVideo($request->file('url_video'), 'blog/videos');
            if ($url) {
                $validated['url_video'] = $url;
            }
        }

        $post = BlogPost::create($validated);

        if ($request->has('tags')) {
            $post->tags()->sync($request->tags);
        }

        // Gestion des images secondaires avec Cloudinary
        if ($request->hasFile('secondary_images')) {
            foreach ($request->file('secondary_images') as $file) {
                if ($file && $file->isValid()) {
                    $url = $this->cloudinaryService->uploadFile($file, 'blog/secondary');
                    if ($url) {
                        $post->images()->create([
                            'image_path' => $url,
                            'is_gallery' => false
                        ]);
                    }
                }
            }
        }

        // Gestion des images de galerie avec Cloudinary
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $file) {
                if ($file && $file->isValid()) {
                    $url = $this->cloudinaryService->uploadFile($file, 'blog/gallery');
                    if ($url) {
                        $post->images()->create([
                            'image_path' => $url,
                            'is_gallery' => true
                        ]);
                    }
                }
            }
        }

        return redirect()->route('admin.blog.index')->with('success', 'Article créé avec succès.');
    }

    public function edit(BlogPost $post)
    {
        $post->load('images');
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
            'featured_media_type' => 'required|in:image,video',
            'featured_image' => 'nullable|required_if:featured_media_type,image|image|mimes:jpeg,png,jpg,gif|max:10240',
            'url_video' => 'nullable|required_if:featured_media_type,video|file|mimes:mp4,avi,mov,wmv,flv,webm|max:51200',
            'secondary_images' => 'nullable|array|max:2',
            'secondary_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
            'gallery_images' => 'nullable|array',
            'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
        ]);

        $validated['slug'] = Str::slug($validated['title']);

        if ($request->status === 'published' && !$post->published_at) {
            $validated['published_at'] = now();
        }

        // Gestion de l'image principale avec Cloudinary
        if ($request->hasFile('featured_image') && $request->file('featured_image')->isValid()) {
            $url = $this->cloudinaryService->uploadFile($request->file('featured_image'), 'blog/featured');
            if ($url) {
                $validated['featured_image'] = $url;
            }
        }

        // Gestion de la vidéo avec Cloudinary
        if ($request->hasFile('url_video') && $request->file('url_video')->isValid()) {
            $url = $this->cloudinaryService->uploadVideo($request->file('url_video'), 'blog/videos');
            if ($url) {
                $validated['url_video'] = $url;
            }
        }

        $post->update($validated);

        if ($request->has('tags')) {
            $post->tags()->sync($request->tags);
        }

        // Gestion des images - suppression et recréation
        $post->images()->delete();
        
        // Gestion des images secondaires avec Cloudinary
        if ($request->hasFile('secondary_images')) {
            foreach ($request->file('secondary_images') as $file) {
                if ($file && $file->isValid()) {
                    $url = $this->cloudinaryService->uploadFile($file, 'blog/secondary');
                    if ($url) {
                        $post->images()->create([
                            'image_path' => $url,
                            'is_gallery' => false
                        ]);
                    }
                }
            }
        }

        // Gestion des images de galerie avec Cloudinary
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $file) {
                if ($file && $file->isValid()) {
                    $url = $this->cloudinaryService->uploadFile($file, 'blog/gallery');
                    if ($url) {
                        $post->images()->create([
                            'image_path' => $url,
                            'is_gallery' => true
                        ]);
                    }
                }
            }
        }

        return redirect()->route('admin.blog.index')->with('success', 'Article mis à jour avec succès.');
    }

    public function destroy(BlogPost $post)
    {
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
        $validated = $request->validate([
            'name' => 'required|unique:blog_categories|max:255',
            'description' => 'nullable|string'
        ]);

        BlogCategory::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'] ?? null
        ]);

        return back()->with('success', 'Catégorie ajoutée avec succès.');
    }

    public function destroyCategory(BlogCategory $category)
    {
        // Optionnel: Vérifier si la catégorie a des articles avant de supprimer ou laisser faire la cascade
        $category->delete();
        return back()->with('success', 'Catégorie supprimée avec succès.');
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
        return back()->with('success', 'Tag ajouté avec succès.');
    }

    public function destroyTag(BlogTag $tag)
    {
        $tag->delete();
        return back()->with('success', 'Tag supprimé avec succès.');
    }
}
