<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ParticipantGroup;
use App\Models\Registration;
use App\Models\RegistrationActivity;
use Illuminate\Support\Facades\DB;

class ParticipantGroupController extends Controller
{
    public function index(Request $request)
    {
        $groups = ParticipantGroup::withCount('registrations')->latest()->paginate(20);
        return view('admin.groups.index', compact('groups'));
    }

    public function create(Request $request)
    {
        // On cherche toutes les inscriptions confirmées qui ne sont pas encore dans un groupe participant,
        // groupées par UUID pour calculer le nombre de personnes (COUNT) qu'elles contiennent.
        $eligibles = Registration::select(
            'uuid',
            'registration_activity_id',
            'option',
            'group_name',
            DB::raw('MAX(full_name) as primary_name'),
            DB::raw('COUNT(id) as people_count')
        )
            ->with('registrationActivity')
            ->where('status', 'confirmed')
            ->whereNull('participant_group_id')
            ->groupBy('uuid', 'registration_activity_id', 'option', 'group_name')
            ->get();

        return view('admin.groups.create', compact('eligibles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'target_size' => 'required|integer|min:1',
            'selected_uuids' => 'required|array',
            'selected_uuids.*' => 'string'
        ]);

        // Vérifier mathématiquement si la sélection est valide côté backend
        $totalPeople = 0;
        foreach ($validated['selected_uuids'] as $uuid) {
            $count = Registration::where('uuid', $uuid)
                ->where('status', 'confirmed')
                ->whereNull('participant_group_id')
                ->count();

            if ($count === 0) {
                return back()->with('error', 'Une inscription sélectionnée n\'est plus disponible.');
            }
            $totalPeople += $count;
        }

        if ($totalPeople !== (int) $validated['target_size']) {
            return back()->with('error', 'Le total de personnes sélectionnées (' . $totalPeople . ') ne correspond pas au nombre requis (' . $validated['target_size'] . ').');
        }

        // Créer le groupe
        $group = ParticipantGroup::create([
            'name' => $validated['name'],
            'target_size' => $validated['target_size']
        ]);

        // Assigner les inscriptions sélectionnées à ce groupe
        Registration::whereIn('uuid', $validated['selected_uuids'])
            ->update(['participant_group_id' => $group->id]);

        return redirect()->route('admin.participant_groups.index')->with('success', 'Groupe créé avec succès. ' . $totalPeople . ' participants y ont été ajoutés.');
    }

    public function show($id)
    {
        $group = ParticipantGroup::with('registrations.registrationActivity')->findOrFail($id);

        return view('admin.groups.show', compact('group'));
    }
}
