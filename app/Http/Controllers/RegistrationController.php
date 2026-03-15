<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Registration;
use App\Models\RegistrationActivity;

class RegistrationController extends Controller
{
    public function selector(Request $request)
    {
        $activities = RegistrationActivity::all();
        $target = $request->get('target', 'registrations');
        return view('admin.registrations.activity_selector', compact('activities', 'target'));
    }

    public function export(Request $request, \App\Services\ExportService $exportService)
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

        $registrations = $query->latest('created_at')->get();

        return $exportService->export(
            $request,
            'Inscriptions - ' . ucfirst($status),
            'inscriptions_' . $status . '_' . date('Y-m-d'),
            ['UUID', 'Nom / Groupe', 'Activité', 'Option', 'Montant', 'Créé le'],
            $registrations,
            function ($reg) {
                $name = $reg->participant_group_id && $reg->participantGroup
                    ? $reg->participantGroup->name
                    : ($reg->option === 'Individuel' ? $reg->primary_name : ($reg->group_name ?? 'Inscription multiple'));
                return [
                    $reg->uuid,
                    $name,
                    $reg->registrationActivity->title ?? 'N/A',
                    $reg->option,
                    $reg->total_amount . ' FCFA',
                    $reg->created_at ? \Carbon\Carbon::parse($reg->created_at)->format('Y-m-d H:i') : ''
                ];
            }
        );
    }

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

    public function exportScanned(Request $request, \App\Services\ExportService $exportService)
    {
        $activity_id = $request->get('activity_id');

        $query = Registration::where('qr_code_scanned', 1)->with('registrationActivity')->latest('updated_at');
        if ($activity_id) {
            $query->where('registration_activity_id', $activity_id);
        }
        $registrations = $query->get();

        return $exportService->export(
            $request,
            'Présences Scannées',
            'presences_scannees_' . date('Y-m-d'),
            ['ID', 'Participant', 'Activité', 'Date de Scan'],
            $registrations,
            function ($reg) {
                return [
                    $reg->id,
                    $reg->full_name,
                    $reg->registrationActivity->title ?? 'N/A',
                    $reg->updated_at ? $reg->updated_at->format('Y-m-d H:i:s') : ''
                ];
            }
        );
    }

    public function scanned(Request $request)
    {
        $activity_id = $request->get('activity_id');

        $registrations = Registration::where('qr_code_scanned', 1)
            ->with('registrationActivity')
            ->latest('updated_at')
            ->paginate(15);

        return view('admin.registrations.scanned', compact('registrations', 'activity_id'));
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

    public function destroy($uuid)
    {
        $registration = Registration::where('uuid', $uuid)->first();

        // Use get()->each->delete() instead of direct query delete to trigger Eloquent events
        // (like cleaning up physical payment receipt files).
        Registration::where('uuid', $uuid)->get()->each->delete();

        return back()->with('success', 'L\'inscription a été supprimée avec succès.');
    }
}
