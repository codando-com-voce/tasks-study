<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginAuthenticationRequest;
use App\Http\Requests\RegisterAuthenticationRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class AuthenticationController extends Controller
{
    public function register(RegisterAuthenticationRequest $request): JsonResponse
    {
        $fields = $request->validated();

        User::query()->create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => Hash::make($fields['password']),
        ]);


        return response()->json([
            'message' => 'User registered successfully.',
        ], 201);
    }

    public function login(LoginAuthenticationRequest $request): JsonResponse
    {
        $fields = $request->validated();

        if (auth()->attempt(['email' => $fields['email'], 'password' => $fields['password']])) {
            $user = auth()->user();
            $token = $user->createToken('authToken')->plainTextToken;

            return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => $user,
            ], 200);
        }

        return response()->json([
            'message' => 'Invalid credentials.',
        ], 401);
    }

    public function logout(): JsonResponse
    {
        auth()->user()->tokens()->delete();

        return response()->json([
            'message' => 'User logged out successfully.',
        ], 200);
    }


}
