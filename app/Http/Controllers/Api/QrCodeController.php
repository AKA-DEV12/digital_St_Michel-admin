<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use Illuminate\Http\Request;

class QrCodeController extends Controller
{
    /**
     * Cette fonction permet de valider le ticket d'un participant après avoir scanné son QR Code. /api/scan/{id}
     */
    public function scan(Request $request, $id)
    {
        // On cherche l'inscription dans la base de données grâce au code unique (ID) du ticket
        $registration = Registration::where('id', $id)->with('registrationActivity')->first();

        // Si on ne trouve pas le ticket, on renvoie un message d'erreur
        if (!$registration) {
            return response()->json([
                'status' => 'error',
                'message' => 'Inscription introuvable. Ce ticket n\'est pas connu.'
            ], 404);
        }

        // Si le ticket a déjà été scanné auparavant, on prévient l'agent pour éviter les fraudes
        if ($registration->qr_code_scanned === 1) {
            return response()->json([
                'status' => 'warning',
                'message' => "Ce QR Code a déjà été scanné le " . $registration->updated_at->format('d/m/Y à H:i') . ".",
                'data' => [
                    'participant' => $registration->full_name,
                    'activity' => $registration->registrationActivity->title ?? 'N/A'
                ]
            ], 200);
        }

        // Si tout est bon, on marque le ticket comme "scanné"
        // On enregistre aussi l'ID de l'agent qui a fait le scan pour le suivi
        $agent = $request->user();
        $registration->update([
            'qr_code_scanned' => 1,
            'scanned_by_agent_id' => $agent ? $agent->id : null
        ]);

        // On renvoie un message de succès avec le nom du participant
        return response()->json([
            'status' => 'success',
            'message' => "Vérification réussie pour : " . $registration->full_name,
            'data' => [
                'participant' => $registration->full_name,
                'activity' => $registration->registrationActivity->title ?? 'N/A',
                'scan_time' => $registration->updated_at->format('d/m/Y à H:i')
            ]
        ], 200);
    }

    /**
     * Cette fonction permet d'afficher la liste de tout ce qu'un agent a scanné.
     * C'est utile pour que l'agent puisse voir son historique de travail.
     */
    public function scannedByAgent($agentId)
    {
        // On cherche toutes les inscriptions validées par cet agent précis
        $registrations = Registration::where('scanned_by_agent_id', $agentId)
            ->where('qr_code_scanned', 1)
            ->with('registrationActivity')
            ->latest() // On affiche les plus récents en premier
            ->get();

        // On renvoie la liste complète au format JSON
        return response()->json([
            'status' => 'success',
            'agent_id' => $agentId,
            'count' => $registrations->count(),
            'data' => $registrations->map(function ($reg) {
                return [
                    'participant' => $reg->full_name,
                    'activity' => $reg->registrationActivity->title ?? 'N/A',
                    'scanned_at' => $reg->updated_at->format('d/m/Y H:i'),
                ];
            })
        ]);
    }
}
