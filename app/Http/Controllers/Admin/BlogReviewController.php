<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BlogReviewController extends Controller
{
    public function index()
    {
        $reviews = BlogReview::latest()->paginate(10);
        return view('admin.reviews.index', compact('reviews'));
    }

    public function create()
    {
        return view('admin.reviews.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'score' => 'required|numeric|min:0|max:10',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('reviews', 'public');
        }

        BlogReview::create($data);

        return redirect()->route('admin.reviews.index')->with('success', 'Critique créée avec succès.');
    }

    public function edit(BlogReview $review)
    {
        return view('admin.reviews.edit', compact('review'));
    }

    public function update(Request $request, BlogReview $review)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'score' => 'required|numeric|min:0|max:10',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            if ($review->image) {
                Storage::disk('public')->delete($review->image);
            }
            $data['image'] = $request->file('image')->store('reviews', 'public');
        }

        $review->update($data);

        return redirect()->route('admin.reviews.index')->with('success', 'Critique mise à jour avec succès.');
    }

    public function destroy(BlogReview $review)
    {
        if ($review->image) {
            Storage::disk('public')->delete($review->image);
        }
        $review->delete();
        return redirect()->route('admin.reviews.index')->with('success', 'Critique supprimée avec succès.');
    }
}
