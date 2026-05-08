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
        $now = Carbon::now();
        $today = Carbon::today();

        $stats = [
            'total_reservations' => \App\Models\Reservation::count(),
            'pending_reservations' => \App\Models\Reservation::where('status', 'pending')->count(),
            'today_reservations' => \App\Models\Reservation::whereDate('reservation_date', $today)->count(),
            
            'total_mass_requests' => \App\Models\MassRequest::count(),
            'pending_mass_requests' => \App\Models\MassRequest::where('status', 'pending')->count(),
            
            'total_registrations' => \App\Models\Registration::count(),
            'total_catechists' => \App\Models\Catechist::count(),
            'total_group_members' => \App\Models\GroupMember::count(),
            
            'total_posts' => \App\Models\BlogPost::count(),
            'total_priests' => \App\Models\Priest::count(),
            
            'today_appointments' => \App\Models\PriestAppointment::whereDate('appointment_date', $today)->count(),
            'pending_appointments' => \App\Models\PriestAppointment::where('status', 'pending')->count(),

            'total_revenue' => \App\Models\Reservation::where('status', 'validated')->sum('price') + 
                               \App\Models\Registration::where('status', 'confirmed')->sum('amount') +
                               \App\Models\MassRequest::where('status', 'confirmed')->sum('amount'),
            
            'total_children' => \App\Models\GroupMember::sum('nombre_enfant'),
        ];

        // Stats pour les graphiques
        $matrimonial_stats = \App\Models\GroupMember::select('situation_matrimoniale', \DB::raw('count(*) as total'))
            ->groupBy('situation_matrimoniale')
            ->get();

        $professional_stats = \App\Models\GroupMember::select('situation_professionnelle', \DB::raw('count(*) as total'))
            ->whereNotNull('situation_professionnelle')
            ->groupBy('situation_professionnelle')
            ->orderBy('total', 'desc')
            ->take(10)
            ->get();

        $members = \App\Models\GroupMember::whereNotNull('date_naissance')->get();
        $age_stats = [
            'enfants' => $members->filter(fn($m) => $m->date_naissance->age < 15)->count(),
            'jeunes' => $members->filter(fn($m) => $m->date_naissance->age >= 15 && $m->date_naissance->age <= 35)->count(),
            'adultes' => $members->filter(fn($m) => $m->date_naissance->age > 35)->count(),
        ];

        $recent_reservations = \App\Models\Reservation::with('room')->latest()->take(5)->get();
        $recent_mass_requests = \App\Models\MassRequest::latest()->take(5)->get();
        $upcoming_appointments = \App\Models\PriestAppointment::with('priest')
            ->whereDate('appointment_date', '>=', $today)
            ->where('status', '!=', 'cancelled')
            ->orderBy('appointment_date')
            ->orderBy('time_slot')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'stats', 
            'recent_reservations', 
            'recent_mass_requests', 
            'upcoming_appointments',
            'matrimonial_stats',
            'professional_stats',
            'age_stats'
        ));
    }

    public function documentation()
    {
        return view('admin.documentation');
    }
}
