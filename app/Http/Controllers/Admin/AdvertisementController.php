<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdvertisementController extends Controller
{
    public function index()
    {
        $ads = Advertisement::orderBy('order')->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.ads.index', compact('ads'));
    }

    public function create()
    {
        return view('admin.ads.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'image' => 'required|image|max:12288', // 12Mo max as requested
            'link_url' => 'nullable|url|max:255',
            'is_active' => 'boolean',
            'order' => 'integer'
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('ads', 'public');
        }

        Advertisement::create($validated);

        return redirect()->route('admin.ads.index')->with('success', 'Publicité créée avec succès.');
    }

    public function edit(Advertisement $ad)
    {
        return view('admin.ads.edit', compact('ad'));
    }

    public function update(Request $request, Advertisement $ad)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'image' => 'nullable|image|max:12288',
            'link_url' => 'nullable|url|max:255',
            'is_active' => 'boolean',
            'order' => 'integer'
        ]);

        if ($request->hasFile('image')) {
            if ($ad->image) {
                Storage::disk('public')->delete($ad->image);
            }
            $validated['image'] = $request->file('image')->store('ads', 'public');
        }

        $ad->update($validated);

        return redirect()->route('admin.ads.index')->with('success', 'Publicité mise à jour avec succès.');
    }

    public function destroy(Advertisement $ad)
    {
        if ($ad->image) {
            Storage::disk('public')->delete($ad->image);
        }
        $ad->delete();

        return redirect()->route('admin.ads.index')->with('success', 'Publicité supprimée avec succès.');
    }
}
