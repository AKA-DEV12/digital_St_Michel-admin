<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    /**
     * Export resources.
     */
    public function export(Request $request, \App\Services\ExportService $exportService)
    {
        $search = $request->get('search');
        $query = Room::query();

        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
        }

        $rooms = $query->get();

        return $exportService->export(
            $request,
            'Liste des Salles',
            'salles_' . date('Y-m-d'),
            ['ID', 'Nom', 'Description', 'Capacité', 'Prix/H', 'Prix/½J', 'Prix/J', 'Statut', 'Créé le'],
            $rooms,
            function ($room) {
                return [
                    $room->id,
                    $room->name,
                    strip_tags($room->description),
                    $room->capacity,
                    $room->price_per_hour ? number_format($room->price_per_hour, 0, ',', ' ') . ' FCFA' : 'N/A',
                    $room->price_half_day ? number_format($room->price_half_day, 0, ',', ' ') . ' FCFA' : 'N/A',
                    $room->price_full_day ? number_format($room->price_full_day, 0, ',', ' ') . ' FCFA' : 'N/A',
                    ucfirst(strtolower($room->status)),
                    $room->created_at ? $room->created_at->format('Y-m-d H:i') : ''
                ];
            }
        );
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $query = Room::query();

        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
        }

        $rooms = $query->paginate(33)->withQueryString();
        
        // Get payment settings for the modal
        $paymentSettings = \App\Models\SiteSetting::whereIn('key', [
            'wave_number', 'mtn_number', 'orange_number', 'moov_number', 'payment_notification_email'
        ])->pluck('value', 'key');
        
        return view('admin.rooms.index', compact('rooms', 'paymentSettings'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'capacity' => 'required|integer|min:1',
            'icon' => 'nullable|string|max:50',
            'status' => 'required|in:disponible,indisponible',
            'price_per_hour' => 'nullable|numeric|min:0',
            'price_half_day' => 'nullable|numeric|min:0',
            'price_full_day' => 'nullable|numeric|min:0',
        ]);

        Room::create($validated);

        return redirect()->route('rooms.index')->with('success', 'Salle créée avec succès.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Room $room)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'capacity' => 'required|integer|min:1',
            'icon' => 'nullable|string|max:50',
            'status' => 'required|in:disponible,indisponible',
            'price_per_hour' => 'nullable|numeric|min:0',
            'price_half_day' => 'nullable|numeric|min:0',
            'price_full_day' => 'nullable|numeric|min:0',
        ]);

        $room->update($validated);

        return redirect()->route('rooms.index')->with('success', 'Salle mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Room $room)
    {
        $room->delete();
        return redirect()->route('rooms.index')->with('success', 'Salle supprimée avec succès.');
    }
}
