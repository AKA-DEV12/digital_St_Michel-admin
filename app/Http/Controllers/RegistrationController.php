<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Registration;
use App\Models\RegistrationActivity;

class RegistrationController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'pending');
        $search = $request->get('search');
        $activity_id = $request->get('activity_id');

        $query = Registration::with(['registrationActivity', 'participantGroup'])
            ->selectRaw('uuid, registration_activity_id, `option`, group_name, status, SUM(amount) as total_amount, MAX(full_name) as primary_name, MAX(phone_number) as primary_phone, MAX(created_at) as created_at, MAX(participant_group_id) as participant_group_id')
            ->where('status', $status)
            ->groupBy('uuid', 'registration_activity_id', \DB::raw('`option`'), 'group_name', 'status');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                    ->orWhere('phone_number', 'like', "%{$search}%")
                    ->orWhere('uuid', 'like', "%{$search}%")
                    ->orWhere('group_name', 'like', "%{$search}%");
            });
        }

        if ($activity_id) {
            $query->where('registration_activity_id', $activity_id);
        }

        $registrations = $query->latest('created_at')->paginate(33)->withQueryString();
        $activities = RegistrationActivity::all();

        // Calculate Wallet Total for confirmed registrations based on current filters
        $walletQuery = Registration::where('status', 'confirmed');
        if ($search) {
            $walletQuery->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                    ->orWhere('phone_number', 'like', "%{$search}%")
                    ->orWhere('uuid', 'like', "%{$search}%")
                    ->orWhere('group_name', 'like', "%{$search}%");
            });
        }
        if ($activity_id) {
            $walletQuery->where('registration_activity_id', $activity_id);
        }
        $walletTotal = $walletQuery->sum('amount');

        // Calculate Pending Wallet Total for pending registrations
        $pendingWalletQuery = Registration::where('status', 'pending');
        if ($search) {
            $pendingWalletQuery->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                    ->orWhere('phone_number', 'like', "%{$search}%")
                    ->orWhere('uuid', 'like', "%{$search}%")
                    ->orWhere('group_name', 'like', "%{$search}%");
            });
        }
        if ($activity_id) {
            $pendingWalletQuery->where('registration_activity_id', $activity_id);
        }
        $pendingWalletTotal = $pendingWalletQuery->sum('amount');

        return view('admin.registrations.index', compact('registrations', 'activities', 'walletTotal', 'pendingWalletTotal'));
    }

    public function scanned()
    {
        $registrations = Registration::where('qr_code_scanned', 1)
            ->with('registrationActivity')
            ->latest('updated_at')
            ->paginate(15);

        return view('admin.registrations.scanned', compact('registrations'));
    }

    public function show($uuid)
    {
        $registrations = Registration::where('uuid', $uuid)->get();

        if ($registrations->isEmpty()) {
            abort(404);
        }

        $registration = $registrations->first();
        $participants = $registrations;

        return view('admin.registrations.show', compact('registration', 'participants'));
    }

    public function updateStatus(Request $request, $uuid)
    {
        $registration = Registration::where('uuid', $uuid)->first();

        if (!$registration) {
            return back()->with('error', 'Inscription introuvable.');
        }

        if ($registration->status !== 'pending') {
            return back()->with('error', 'Le statut de cette inscription a déjà été défini et ne peut plus être modifié.');
        }

        $validated = $request->validate([
            'status' => 'required|string|in:confirmed,cancelled'
        ]);

        Registration::where('uuid', $uuid)->update(['status' => $validated['status']]);

        if ($validated['status'] === 'confirmed') {
            $registration = Registration::where('uuid', $uuid)->first();
            if ($registration && $registration->payment_email) {
                try {
                    \Illuminate\Support\Facades\Mail::to($registration->payment_email)
                        ->send(new \App\Mail\RegistrationConfirmed($uuid));
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error("Erreur d'envoi de mail de confirmation pour UUID $uuid: " . $e->getMessage());
                    return back()->with('error', "Statut mis à jour, mais l'envoi du mail a échoué : " . $e->getMessage());
                }
            }
        }

        return back()->with('success', 'Statut de l\'inscription mis à jour pour tout le groupe' . ($validated['status'] === 'confirmed' ? ' et mail de confirmation envoyé.' : '.'));
    }
}
