<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    public function login(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'email' => 'required|email|string',
                'password' => 'required|string'
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()->all(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $email = $request->input('email');
        $password = $request->input('password');

        $user = User::where('email', $email)->first();

        if ($user && Hash::check($password, $user->password)) {
            Auth::login($user);

            // Create a token using Sanctum
            $token = $user->createToken('token')->plainTextToken;

            return response()->json([
                'user' => $user,
                'token' => $token
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'error' => 'Invalid Email and Password'
            ], Response::HTTP_UNAUTHORIZED);
        }
    }

    public function logout(Request $request)
    {
        // Revoke the token that was used to authenticate the user
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            "message" => "User logged out successfully",
            "success" => true
        ], Response::HTTP_OK);
    }
}
