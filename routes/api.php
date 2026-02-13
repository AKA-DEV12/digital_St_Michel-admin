<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiAuthController;
use App\Http\Controllers\Api\QrCodeController;

Route::post('/agent/login', [ApiAuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    // Récupération de l'utilisateur actuellement connecté
    Route::get('/agent', function (Request $request) {
        return response()->json($request->user());
    });

    // Scan QR Code
    Route::post('/scan/{uuid}', [QrCodeController::class, 'scan']);
    Route::get('/scan/{uuid}', [QrCodeController::class, 'scan']);

    // Liste des présences pour un agent spécifique (ID dans la route comme demandé)
    Route::get('/agents/{id}/scanned', [QrCodeController::class, 'scannedByAgent']);
});