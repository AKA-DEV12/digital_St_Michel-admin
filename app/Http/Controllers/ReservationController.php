<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Reservation;
use Illuminate\Support\Facades\Mail;

class ReservationController extends Controller
{
    /**
     * Display a listing of reservations based on status.
     */
    public function index(Request $request)
    {
        $status = $request->get('status', 'pending');
        $search = $request->get('search');
        $date_from = $request->get('date_from');
        $date_to = $request->get('date_to');

        $query = Reservation::with('room')
            ->where('status', $status);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('reservation_object', 'like', "%{$search}%");
            });
        }

        if ($date_from) {
            $query->where('reservation_date', '>=', $date_from);
        }

        if ($date_to) {
            $query->where('reservation_date', '<=', $date_to);
        }

        $reservations = $query->orderBy('reservation_date', 'asc')
            ->paginate(33)
            ->withQueryString();

        return view('admin.reservations.index', compact('reservations', 'status'));
    }

    public function export(Request $request)
    {
        $status = $request->get('status', 'pending');
        $search = $request->get('search');
        $date_from = $request->get('date_from');
        $date_to = $request->get('date_to');

        $query = Reservation::with('room')->where('status', $status);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('reservation_object', 'like', "%{$search}%");
            });
        }

        if ($date_from) {
            $query->where('reservation_date', '>=', $date_from);
        }

        if ($date_to) {
            $query->where('reservation_date', '<=', $date_to);
        }

        $reservations = $query->get();

        $filename = "reservations_{$status}_" . date('Y-m-d') . ".csv";
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = ['ID', 'Client', 'Email', 'Objet', 'Salle', 'Date', 'Horaire', 'Statut'];

        $callback = function () use ($reservations, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($reservations as $res) {
                fputcsv($file, [
                    $res->id,
                    $res->first_name . ' ' . $res->last_name,
                    $res->email,
                    $res->reservation_object,
                    $res->room->name ?? 'N/A',
                    $res->reservation_date,
                    $res->time_slot,
                    $res->status
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Validate a reservation.
     */
    public function validateReservation(Reservation $reservation)
    {
        $reservation->update(['status' => 'validated']);

        // Logic to send email would go here
        // Mail::to($reservation->email)->send(new ReservationStatusMail($reservation));

        return back()->with('success', 'Réservation validée avec succès.');
    }

    /**
     * Cancel a reservation.
     */
    public function cancelReservation(Reservation $reservation)
    {
        $reservation->update(['status' => 'cancelled']);

        // Logic to send email would go here
        // Mail::to($reservation->email)->send(new ReservationStatusMail($reservation));

        return back()->with('success', 'Réservation annulée.');
    }
}
