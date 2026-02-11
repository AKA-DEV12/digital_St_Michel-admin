<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiAuthController;

// Route::post('/register', [ApiAuthController::class, 'register']);
Route::post('/agent/login', [ApiAuthController::class, 'login']);

//Recuperation de l‘utilisateur actuellement connecté sans redemander l‘email ou le mdp
Route::middleware('auth:sanctum')->get('/agent', function (Request $request) {
    return response()->json($request->user());
});