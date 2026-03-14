<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PriestAppointment;
use Illuminate\Support\Facades\Mail;
use App\Mail\PriestAppointmentStatusUpdated;

class PriestAppointmentController extends Controller
{
    /**
     * Display a listing of priest appointments based on status.
     */
    public function index(Request $request)
    {
        $status = $request->get('status', 'pending');
        $search = $request->get('search');
        $date_from = $request->get('date_from');
        $date_to = $request->get('date_to');

        $query = PriestAppointment::with('priest')
            ->where('status', $status);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('object', 'like', "%{$search}%");
            });
        }

        if ($date_from) {
            $query->where('appointment_date', '>=', $date_from);
        }

        if ($date_to) {
            $query->where('appointment_date', '<=', $date_to);
        }

        $appointments = $query->orderBy('appointment_date', 'asc')
            ->paginate(33)
            ->withQueryString();

        return view('admin.priest_appointments.index', compact('appointments', 'status'));
    }

    /**
     * Export appointments to CSV.
     */
    public function export(Request $request, \App\Services\ExportService $exportService)
    {
        $status = $request->get('status', 'pending');
        $search = $request->get('search');
        $date_from = $request->get('date_from');
        $date_to = $request->get('date_to');

        $query = PriestAppointment::with('priest')->where('status', $status);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('object', 'like', "%{$search}%");
            });
        }

        if ($date_from) {
            $query->where('appointment_date', '>=', $date_from);
        }

        if ($date_to) {
            $query->where('appointment_date', '<=', $date_to);
        }

        $appointments = $query->get();
        $columns = ['ID', 'Père', 'Client', 'Telephone', 'Email', 'Objet', 'Date', 'Horaire', 'Statut'];
        $filenameBase = "rdv_peres_{$status}_" . date('Y-m-d');

        return $exportService->export(
            $request,
            'Rendez-vous Pères - ' . ucfirst($status),
            $filenameBase,
            $columns,
            $appointments,
            function ($appt) {
                return [
                    $appt->id,
                    $appt->priest->first_name . ' ' . $appt->priest->last_name,
                    $appt->full_name,
                    $appt->phone,
                    $appt->email,
                    $appt->object,
                    $appt->appointment_date,
                    $appt->time_slot,
                    $appt->status
                ];
            }
        );
    }

    /**
     * Validate an appointment and notify the user.
     */
    public function validateAppointment(PriestAppointment $appointment)
    {
        $appointment->update(['status' => 'validated']);

        try {
            if ($appointment->email) {
                Mail::to($appointment->email)->send(new PriestAppointmentStatusUpdated($appointment));
            }
        } catch (\Exception $e) {
            \Log::error("Erreur d'envoi d'e-mail de validation pour le RDV #{$appointment->id}: " . $e->getMessage());
            return back()->with('warning', 'Rendez-vous validé, mais l\'e-mail de confirmation n\'a pas pu être envoyé.');
        }

        return back()->with('success', 'Rendez-vous validé avec succès.');
    }

    /**
     * Cancel an appointment and notify the user.
     */
    public function cancelAppointment(PriestAppointment $appointment)
    {
        $appointment->update(['status' => 'cancelled']);

        try {
            if ($appointment->email) {
                Mail::to($appointment->email)->send(new PriestAppointmentStatusUpdated($appointment));
            }
        } catch (\Exception $e) {
            \Log::error("Erreur d'envoi d'e-mail d'annulation pour le RDV #{$appointment->id}: " . $e->getMessage());
            return back()->with('warning', 'Rendez-vous annulé, mais l\'e-mail n\'a pas pu être envoyé.');
        }

        return back()->with('success', 'Rendez-vous annulé.');
    }
}
