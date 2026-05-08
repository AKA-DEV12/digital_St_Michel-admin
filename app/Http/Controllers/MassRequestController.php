<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MassRequest;
use App\Models\MassTime;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\MassRequestConfirmed;

class MassRequestController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'pending');
        $search = $request->get('search');

        $query = MassRequest::where('status', $status);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name1', 'like', "%{$search}%")
                    ->orWhere('name2', 'like', "%{$search}%")
                    ->orWhere('name3', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $requests = $query->latest()->paginate(20)->withQueryString();

        // Wallet calculations
        $walletTotal = MassRequest::where('status', 'confirmed')->sum('amount');
        $pendingWalletTotal = MassRequest::where('status', 'pending')->sum('amount');

        return view('admin.mass_requests.index', compact('requests', 'walletTotal', 'pendingWalletTotal'));
    }

    public function show($id)
    {
        $request = MassRequest::findOrFail($id);
        return view('admin.mass_requests.show', compact('request'));
    }

    public function validateRequest(Request $request, $id)
    {
        $massRequest = MassRequest::findOrFail($id);

        if ($massRequest->status !== 'pending') {
            return back()->with('error', 'Cette demande a déjà été traitée.');
        }

        $massRequest->update(['status' => 'confirmed']);

        if ($massRequest->email) {
            try {
                Mail::to($massRequest->email)->send(new MassRequestConfirmed($massRequest));
            } catch (\Exception $e) {
                Log::error("Erreur d'envoi de mail pour Demande Messe #$id: " . $e->getMessage());
                return back()->with('success', 'Demande validée, mais l\'envoi du mail a échoué.');
            }
        }

        return back()->with('success', 'Demande validée avec succès et mail envoyé.');
    }

    public function cancelRequest($id)
    {
        $massRequest = MassRequest::findOrFail($id);
        $massRequest->update(['status' => 'cancelled']);
        return back()->with('success', 'Demande annulée.');
    }

    public function config()
    {
        $settings = [
            'mass_price' => SiteSetting::where('key', 'mass_price')->first()->value ?? '3000',
            'wave_number' => SiteSetting::where('key', 'wave_number')->first()->value ?? '',
            'mtn_number' => SiteSetting::where('key', 'mtn_number')->first()->value ?? '',
            'orange_number' => SiteSetting::where('key', 'orange_number')->first()->value ?? '',
            'moov_number' => SiteSetting::where('key', 'moov_number')->first()->value ?? '',
        ];

        $times = MassTime::orderBy('time')->get();

        return view('admin.mass_requests.config', compact('settings', 'times'));
    }

    public function updateSettings(Request $request)
    {
        $data = $request->validate([
            'mass_price' => 'required|numeric',
            'wave_number' => 'nullable|string',
            'mtn_number' => 'nullable|string',
            'orange_number' => 'nullable|string',
            'moov_number' => 'nullable|string',
        ]);

        foreach ($data as $key => $value) {
            SiteSetting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        return back()->with('success', 'Configuration mise à jour.');
    }

    public function storeTime(Request $request)
    {
        $request->validate(['time' => 'required']);
        MassTime::create(['time' => $request->time]);
        return back()->with('success', 'Créneau ajouté.');
    }

    public function destroyTime($id)
    {
        MassTime::findOrFail($id)->delete();
        return back()->with('success', 'Créneau supprimé.');
    }

    public function export(Request $request, \App\Services\ExportService $exportService)
    {
        $status = $request->get('status', 'pending');
        $query = MassRequest::where('status', $status);
        $requests = $query->latest()->get();

        return $exportService->export(
            $request,
            'Demandes de Messe - ' . ucfirst($status),
            'demandes_messe_' . $status . '_' . date('Y-m-d'),
            ['ID', 'Date Demandée', 'Créneaux', 'Noms', 'Objet', 'Montant', 'Email', 'Téléphone', 'Date Création'],
            $requests,
            function ($req) {
                return [
                    $req->id,
                    $req->requested_date->format('Y-m-d'),
                    implode(', ', $req->time_slots ?? []),
                    $req->name1 . ($req->name2 ? ', '.$req->name2 : '') . ($req->name3 ? ', '.$req->name3 : ''),
                    $req->request_object,
                    $req->amount . ' FCFA',
                    $req->email,
                    $req->phone,
                    $req->created_at->format('Y-m-d H:i')
                ];
            }
        );
    }
}
