<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_reservations' => Reservation::count(),
            'pending_reservations' => Reservation::where('status', 'pending')->count(),
            'today_reservations' => Reservation::whereDate('reservation_date', Carbon::today())->count(),
            'total_rooms' => Room::count(),
            'available_rooms' => Room::where('status', 'disponible')->count(),
        ];

        $recent_reservations = Reservation::with('room')->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recent_reservations'));
    }
}
