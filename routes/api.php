<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiAuthController;
use App\Http\Controllers\Api\QrCodeController;

// Route pour permettre à un agent de se connecter (envoie l'email et le mot de passe)
Route::post('/agent/login', [ApiAuthController::class, 'login']);

// Toutes les routes à l'intérieur de ce bloc nécessitent que l'agent soit connecté
Route::middleware('auth:sanctum')->group(function () {

    // Récupère les informations de l'agent qui est actuellement connecté
    Route::get('/agent', function (Request $request) {
        return response()->json($request->user());
    });

    // Permet de valider un ticket en scannant son code unique (ID) Car l‘uuid peut etre la meme pour plusieurs ligne indiquants qu‘ils sont du meme groupe
    // On accepte les méthodes POST et GET pour plus de facilité
    Route::post('/scan/{id}', [QrCodeController::class, 'scan']);
    Route::get('/scan/{id}', [QrCodeController::class, 'scan']);

    // Permet de voir la liste de toutes les personnes qu'un agent a déjà validées
    Route::get('/agents/{id}/scanned', [QrCodeController::class, 'scannedByAgent']);
});