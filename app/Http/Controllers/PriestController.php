<?php

namespace App\Http\Controllers;

use App\Models\Priest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PriestController extends Controller
{
    /**
     * Export resources.
     */
    public function export(Request $request, \App\Services\ExportService $exportService)
    {
        $search = $request->get('search');
        $query = Priest::query();

        // Enforce user isolation: Admin sees only their created priests unless they are Super Admin
        if (auth()->check() && !auth()->user()->hasRole('Super Admin')) {
            $query->where('user_id', auth()->id());
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('role', 'like', "%{$search}%");
            });
        }

        $priests = $query->orderBy('last_name', 'asc')->get();

        return $exportService->export(
            $request,
            'Liste du Clergé',
            'clerge_' . date('Y-m-d'),
            ['ID', 'Nom Complet', 'Rôle / Titre', 'Audience', 'Statut', 'Créé le'],
            $priests,
            function ($priest) {
                return [
                    $priest->id,
                    $priest->first_name . ' ' . $priest->last_name,
                    $priest->role,
                    $priest->audience,
                    $priest->is_active ? 'Actif' : 'Inactif',
                    $priest->created_at ? $priest->created_at->format('Y-m-d H:i') : ''
                ];
            }
        );
    }

    /**
     * Display a listing of the priests.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $query = Priest::query();

        // Enforce user isolation: Admin sees only their created priests unless they are Super Admin
        if (auth()->check() && !auth()->user()->hasRole('Super Admin')) {
            $query->where('user_id', auth()->id());
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('role', 'like', "%{$search}%");
            });
        }

        $priests = $query->orderBy('last_name', 'asc')->paginate(33)->withQueryString();
        return view('admin.priests.index', compact('priests'));
    }

    /**
     * Show the form for creating a new priest.
     */
    public function create()
    {
        return view('admin.priests.create');
    }

    /**
     * Store a newly created priest in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'audience' => 'nullable|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'time_slot_start' => 'nullable|array',
            'time_slot_start.*' => 'nullable|date_format:H:i',
            'time_slot_end' => 'nullable|array',
            'time_slot_end.*' => 'nullable|date_format:H:i',
            'unavailable_dates' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $data = $validated;

        // Track the user who created this priest
        $data['user_id'] = auth()->id();

        // Build time slots array
        $slots = [];
        if ($request->has('time_slot_start') && $request->has('time_slot_end')) {
            $starts = $request->input('time_slot_start');
            $ends = $request->input('time_slot_end');
            foreach ($starts as $index => $start) {
                if (!empty($start) && !empty($ends[$index])) {
                    $slots[] = $start . ' - ' . $ends[$index];
                }
            }
        }
        $data['available_time_slots'] = count($slots) > 0 ? $slots : null;

        // Clean up array fields to avoid column not found error
        unset($data['time_slot_start']);
        unset($data['time_slot_end']);

        if (!empty($data['unavailable_dates'])) {
            // It might come as comma separated string from flatpickr
            $data['unavailable_dates'] = array_values(array_filter(array_map('trim', explode(",", $data['unavailable_dates']))));
        } else {
            $data['unavailable_dates'] = null;
        }

        if (!isset($data['is_active'])) {
            $data['is_active'] = true; // default
        }

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = time() . '_' . Str::slug($request->first_name . '_' . $request->last_name) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('assets/images/priests'), $filename);
            $data['photo_path'] = 'assets/images/priests/' . $filename;
        }

        Priest::create($data);

        return redirect()->route('admin.priests.index')->with('success', 'Prêtre ajouté avec succès.');
    }

    /**
     * Show the form for editing the specified priest.
     */
    public function edit(Priest $priest)
    {
        if (auth()->check() && !auth()->user()->hasRole('Super Admin') && $priest->user_id !== auth()->id()) {
            abort(403, 'Accès non autorisé.');
        }

        return view('admin.priests.edit', compact('priest'));
    }

    /**
     * Update the specified priest in storage.
     */
    public function update(Request $request, Priest $priest)
    {
        if (auth()->check() && !auth()->user()->hasRole('Super Admin') && $priest->user_id !== auth()->id()) {
            abort(403, 'Accès non autorisé. Vous ne pouvez modifier que les prêtres que vous avez créés.');
        }

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'audience' => 'nullable|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'available_time_slots' => 'nullable|string',
            'unavailable_dates' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $data = $validated;

        // Build time slots array
        $slots = [];
        if ($request->has('time_slot_start') && $request->has('time_slot_end')) {
            $starts = $request->input('time_slot_start');
            $ends = $request->input('time_slot_end');
            foreach ($starts as $index => $start) {
                if (!empty($start) && !empty($ends[$index])) {
                    $slots[] = $start . ' - ' . $ends[$index];
                }
            }
        }
        $data['available_time_slots'] = count($slots) > 0 ? $slots : null;

        // Clean up array fields
        unset($data['time_slot_start']);
        unset($data['time_slot_end']);

        if (!empty($data['unavailable_dates'])) {
            $data['unavailable_dates'] = array_values(array_filter(array_map('trim', explode(",", $data['unavailable_dates']))));
        } else {
            $data['unavailable_dates'] = null;
        }

        if (!isset($data['is_active'])) {
            $data['is_active'] = $request->has('is_active');
        }

        if ($request->hasFile('photo')) {
            // Remove old photo if exists and not default or seeder data
            if ($priest->photo_path && file_exists(public_path($priest->photo_path))) {
                @unlink(public_path($priest->photo_path));
            }

            $file = $request->file('photo');
            $filename = time() . '_' . Str::slug($request->first_name . '_' . $request->last_name) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('assets/images/priests'), $filename);
            $data['photo_path'] = 'assets/images/priests/' . $filename;
        }

        $priest->update($data);

        return redirect()->route('admin.priests.index')->with('success', 'Informations du prêtre mises à jour.');
    }

    /**
     * Toggle the active status of the priest.
     */
    public function toggleActive(Priest $priest)
    {
        if (auth()->check() && !auth()->user()->hasRole('Super Admin') && $priest->user_id !== auth()->id()) {
            abort(403, 'Accès non autorisé.');
        }

        $priest->update(['is_active' => !$priest->is_active]);
        $status = $priest->is_active ? 'activé' : 'désactivé';
        return redirect()->route('admin.priests.index')->with('success', "Le statut du prêtre a été {$status}.");
    }

    /**
     * Remove the specified priest from storage.
     */
    public function destroy(Priest $priest)
    {
        if (auth()->check() && !auth()->user()->hasRole('Super Admin') && $priest->user_id !== auth()->id()) {
            abort(403, 'Accès non autorisé.');
        }

        // Don't delete if they have appointments to avoid constraint errors or lost history
        if ($priest->appointments()->count() > 0) {
            return redirect()->route('admin.priests.index')->with('error', 'Impossible de supprimer ce prêtre car il a des rendez-vous associés. Vous pouvez le désactiver à la place.');
        }

        if ($priest->photo_path && file_exists(public_path($priest->photo_path))) {
            @unlink(public_path($priest->photo_path));
        }

        $priest->delete();
        return redirect()->route('admin.priests.index')->with('success', 'Prêtre supprimé avec succès.');
    }
}
