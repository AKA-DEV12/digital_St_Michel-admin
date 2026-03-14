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
            'total_reservations' => \App\Models\Reservation::count(),
            'pending_reservations' => \App\Models\Reservation::where('status', 'pending')->count(),
            'today_reservations' => \App\Models\Reservation::whereDate('reservation_date', Carbon::today())->count(),
            'total_rooms' => \App\Models\Room::count(),
            'available_rooms' => \App\Models\Room::where('status', 'disponible')->count(),
            'total_posts' => \App\Models\BlogPost::count(),
            'total_priests' => \App\Models\Priest::count(),
            'total_registrations' => \App\Models\Registration::count(),
        ];

        $recent_reservations = \App\Models\Reservation::with('room')->latest()->take(6)->get();

        return view('admin.dashboard', compact('stats', 'recent_reservations'));
    }
}
