<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use Illuminate\Http\Request;

class QrCodeController extends Controller
{
    /**
     * Valide un QR Code scanné par un agent.
     * URL attendue : /api/scan/{uuid}
     */
    public function scan(Request $request, $uuid)
    {
        $registration = Registration::where('uuid', $uuid)->with('registrationActivity')->first();

        if (!$registration) {
            return response()->json([
                'status' => 'error',
                'message' => 'Inscription introuvable.'
            ], 404);
        }

        if ($registration->qr_code_scanned) {
            return response()->json([
                'status' => 'warning',
                'message' => "Ce QR Code a déjà été scanné le " . $registration->updated_at->format('d/m/Y à H:i') . ".",
                'data' => [
                    'participant' => $registration->full_name,
                    'activity' => $registration->registrationActivity->title ?? 'N/A'
                ]
            ], 200);
        }

        // Marquer comme scanné et enregistrer l'agent
        $agent = $request->user(); // L'agent authentifié via Sanctum
        $registration->update([
            'qr_code_scanned' => true,
            'scanned_by_agent_id' => $agent ? $agent->id : null
        ]);

        return response()->json([
            'status' => 'success',
            'message' => "Validation réussie pour : " . $registration->full_name,
            'data' => [
                'participant' => $registration->full_name,
                'activity' => $registration->registrationActivity->title ?? 'N/A',
                'scan_time' => $registration->updated_at->format('d/m/Y à H:i')
            ]
        ], 200);
    }

    /**
     * Liste les participants scannés par un agent spécifique.
     * Route : /api/agents/{agent_id}/scanned-participants
     */
    public function scannedByAgent($agentId)
    {
        $registrations = Registration::where('scanned_by_agent_id', $agentId)
            ->where('qr_code_scanned', true)
            ->with('registrationActivity')
            ->latest()
            ->get();

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
