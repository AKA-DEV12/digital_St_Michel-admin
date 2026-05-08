<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    /**
     * Export resources.
     */
    public function export(Request $request, \App\Services\ExportService $exportService)
    {
        $search = $request->get('search');
        $query = Group::query();

        if ($search) {
            $query->where('nom_groupe', 'like', "%{$search}%");
        }

        $groups = $query->withCount('members')->orderBy('nom_groupe')->get();

        return $exportService->export(
            $request,
            'Groupes Paroissiaux',
            'groups_' . date('Y-m-d'),
            ['ID', 'Nom du Groupe', 'Nombre de Membres', 'Créé le'],
            $groups,
            function ($group) {
                return [
                    $group->id,
                    $group->nom_groupe,
                    $group->members_count,
                    $group->created_at ? $group->created_at->format('Y-m-d H:i') : ''
                ];
            }
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.groups.create');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $query = Group::withCount('members')
            ->withCount(['members as new_members_count' => function ($q) {
                $q->whereMonth('group_group_member.created_at', now()->month)
                  ->whereYear('group_group_member.created_at', now()->year);
            }]);

        if ($search) {
            $query->where('nom_groupe', 'like', "%{$search}%");
        }

        $groups = $query->orderBy('nom_groupe')->paginate(33)->withQueryString();
        return view('admin.groups.index', compact('groups'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Group $group)
    {
        $group->load('members');
        return view('admin.groups.show', compact('group'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom_groupe' => 'required|string|max:255|unique:groups,nom_groupe',
        ], [
            'nom_groupe.required' => 'Le nom du groupe est obligatoire',
            'nom_groupe.unique' => 'Ce nom de groupe existe déjà',
        ]);

        Group::create($validated);

        return redirect()->route('groups.index')
            ->with('success', 'Groupe ajouté avec succès.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Group $group)
    {
        $validated = $request->validate([
            'nom_groupe' => 'required|string|max:255|unique:groups,nom_groupe,' . $group->id,
        ], [
            'nom_groupe.required' => 'Le nom du groupe est obligatoire',
            'nom_groupe.unique' => 'Ce nom de groupe existe déjà',
        ]);

        $group->update($validated);

        return redirect()->route('groups.index')
            ->with('success', 'Groupe mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Group $group)
    {
        // Vérifier si le groupe a des membres
        if ($group->members()->count() > 0) {
            return redirect()->route('groups.index')
                ->with('error', 'Impossible de supprimer ce groupe car il contient des membres.');
        }

        $group->delete();

        return redirect()->route('groups.index')
            ->with('success', 'Groupe supprimé avec succès.');
    }
}
