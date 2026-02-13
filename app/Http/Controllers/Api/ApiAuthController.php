<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Agent;
use Illuminate\Support\Facades\Hash;

class ApiAuthController extends Controller
{
    //Connexion
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);


        $agent = Agent::where('email', $request->email)->first();

        if (!$agent || !Hash::check($request->password, $agent->password)) {

            return response()->json(
                [
                    'status' => 'error',
                    'code' => 'INVALID_CREDENTIALS',
                    'message' => 'Email ou mot de passe incorrect'
                ],
                401
            );

        }
        $token = $agent->createToken('auth-token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'code' => 'LOGIN_SUCCESS',
            'message' => 'Connexion réussie',
            'token' => $token,
            'user' => $agent
        ], 200);



    }
}
