<?php

namespace App\Http\Controllers;

use App\Models\Movement;
use Illuminate\Http\Request;

class MovementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $query = Movement::query();

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        $movements = $query->orderBy('name')->paginate(33)->withQueryString();
        return view('admin.movements.index', compact('movements'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:movements',
        ]);

        Movement::create($validated);

        return redirect()->route('movements.index')
            ->with('success', 'Mouvement ajouté avec succès.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Movement $movement)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:movements,name,' . $movement->id,
        ]);

        $movement->update($validated);

        return redirect()->route('movements.index')
            ->with('success', 'Mouvement mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Movement $movement)
    {
        $movement->delete();

        return redirect()->route('movements.index')
            ->with('success', 'Mouvement supprimé avec succès.');
    }
}
