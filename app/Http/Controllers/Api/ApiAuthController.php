<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ApiAuthController extends Controller
{
    //Connexion
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);


        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {

            return response()->json(
                [
                    'status' => 'error',
                    'code' => 'INVALID_CREDENTIALS',
                    'message' => 'Email ou mot de passe incorrect'
                ],
                401
            );

        }
        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'code' => 'LOGIN_SUCCESS',
            'message' => 'Connexion réussie',
            'token' => $token,
            'user' => $user
        ], 200);



    }
}
