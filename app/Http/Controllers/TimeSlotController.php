<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\TimeSlot;

class TimeSlotController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $timeSlots = TimeSlot::orderBy('start_time')->paginate(33);
        return view('admin.time_slots.index', compact('timeSlots'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
        ]);

        TimeSlot::create($request->all());

        return redirect()->route('time_slots.index')->with('success', 'Créneau ajouté.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TimeSlot $timeSlot)
    {
        $request->validate([
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
        ]);

        $timeSlot->update($request->all());

        return redirect()->route('time_slots.index')->with('success', 'Créneau mis à jour.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TimeSlot $timeSlot)
    {
        $timeSlot->delete();
        return redirect()->route('time_slots.index')->with('success', 'Créneau supprimé.');
    }
}
