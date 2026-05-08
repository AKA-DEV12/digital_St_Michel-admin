<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TimeSlot;
use App\Services\ExportService;
use Carbon\Carbon;

class TimeSlotController extends Controller
{
    public function export(Request $request, ExportService $exportService)
    {
        $timeSlots = TimeSlot::orderBy('start_time')->get();

        return $exportService->export(
            $request,
            'Créneaux Horaires',
            'creneaux_horaires_' . date('Y-m-d'),
            ['ID', 'Heure de début', 'Heure de fin', 'Créé le'],
            $timeSlots,
            function ($slot) {
                return [
                    $slot->id,
                    $slot->start_time,
                    $slot->end_time,
                    $slot->created_at ? $slot->created_at->format('Y-m-d H:i') : ''
                ];
            }
        );
    }

    public function index()
    {
        $timeSlots = TimeSlot::orderBy('start_time')->paginate(33);
        return view('admin.time_slots.index', compact('timeSlots'));
    }

    public function store(Request $request)
    {
        $this->validateTimeSlot($request);

        TimeSlot::create($request->all());

        return redirect()->route('time_slots.index')->with('success', 'Créneau ajouté.');
    }

    public function update(Request $request, TimeSlot $timeSlot)
    {
        $this->validateTimeSlot($request);

        $timeSlot->update($request->all());

        return redirect()->route('time_slots.index')->with('success', 'Créneau mis à jour.');
    }

    public function destroy(TimeSlot $timeSlot)
    {
        $timeSlot->delete();
        return redirect()->route('time_slots.index')->with('success', 'Créneau supprimé.');
    }

    /**
     * Validation centralisée
     */
    private function validateTimeSlot(Request $request)
    {
        $request->validate([
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
        ]);

        // Normalisation avec Carbon (robuste)
        $start = Carbon::parse($request->start_time)->seconds(0);
        $end = Carbon::parse($request->end_time)->seconds(0);

        // Vérification stricte : end = start + 2h
        if (!$start->copy()->addHours(2)->equalTo($end)) {
            return redirect()->back()
                ->withInput()
                ->withErrors([
                    'end_time' => 'Le créneau doit durer exactement 2 heures.'
                ])->throwResponse();
        }
    }
}