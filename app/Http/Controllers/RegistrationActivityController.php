<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RegistrationActivity;

class RegistrationActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $query = RegistrationActivity::query();

        if ($search) {
            $query->where('title', 'like', "%{$search}%")
                ->orWhere('subtitle', 'like', "%{$search}%")
                ->orWhere('location', 'like', "%{$search}%");
        }

        $activities = $query->orderBy('date', 'desc')->paginate(33)->withQueryString();
        return view('admin.activities.index', compact('activities'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'start_time' => 'nullable',
            'end_time' => 'nullable',
            'location' => 'nullable|string|max:255',
            'registration_amount' => 'nullable|integer',
            'wave_number' => 'nullable|string|max:255',
            'mtn_number' => 'nullable|string|max:255',
            'orange_number' => 'nullable|string|max:255',
            'moov_number' => 'nullable|string|max:255',
            'color' => 'required|string|in:blue,indigo,slate,purple,emerald',
            'is_active' => 'boolean'
        ]);

        RegistrationActivity::create($validated);

        return redirect()->route('activities.index')
            ->with('success', 'Activité ajoutée avec succès.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RegistrationActivity $activity)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'start_time' => 'nullable',
            'end_time' => 'nullable',
            'location' => 'nullable|string|max:255',
            'registration_amount' => 'nullable|integer',
            'wave_number' => 'nullable|string|max:255',
            'mtn_number' => 'nullable|string|max:255',
            'orange_number' => 'nullable|string|max:255',
            'moov_number' => 'nullable|string|max:255',
            'color' => 'required|string|in:blue,indigo,slate,purple,emerald',
            'is_active' => 'boolean'
        ]);

        $activity->update($validated);

        return redirect()->route('activities.index')
            ->with('success', 'Activité mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RegistrationActivity $activity)
    {
        $activity->delete();

        return redirect()->route('activities.index')
            ->with('success', 'Activité supprimée avec succès.');
    }
}