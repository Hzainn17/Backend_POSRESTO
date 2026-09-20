<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    //login api
    public function login(Request $request)
    {
        // validate the request
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        //check if the user exists
        $user = \App\Models\User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'Invalid credentials'], 401);
        }

        //check if the password is correct
        if (!\Illuminate\Support\Facades\Hash::check($request->password, $user->password)) {
            return response()->json(['status' => 'error', 'message' => 'Invalid credentials'], 401);
        }

        //create token
        $token = $user->createToken('auth_token')->plainTextToken;

        //return response
        return response()->json(['status' => 'success', 'message' => 'Login successful', 'access_token' => $token, 'token_type' => 'Bearer', 'user' => $user]);
    }

    //logout api
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Logout successful',
        ]);
    }
}
