<?php
// app/Http/Controllers/Api/AuthController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // Registra un nuovo utente
    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed'
        ], [
            'name.required' => 'Inserisci il tuo nome.',
            'name.max' => 'Hai superato il limite di caratteri consentito.',
            'email.required' => 'Inserisci la tua email.',
            'email.max' => 'Hai superato il limite di caratteri consentito.',
            'email.unique' => 'Questa email è già utilizzata. Scegline un\'altra.',
            'password.required' => 'Inserisci una password.',
            'password.min' => 'La lunghezza minima della password è di 8 caratteri.'
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // Crea un API token per l'utente
        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'message' => 'Registration successful',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
            'token' => $token,
        ], 201);
    }

    // Login
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        // Attempt to log in
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            // Regenerate session for security
            $request->session()->regenerate();

            // Revoca i token esistenti e crea un nuovo API token
            Auth::user()->tokens()->delete();
            $token = Auth::user()->createToken('auth-token')->plainTextToken;

            return response()->json([
                'message' => 'Login successful',
                'user' => [
                    'id' => Auth::user()->id,
                    'name' => Auth::user()->name,
                    'email' => Auth::user()->email,
                ],
                'token' => $token,
            ]);
        }

        // If login fails, redirect back with error
        throw ValidationException::withMessages([
            'email' => ['The provided credentials do not match our records.'],
        ]);
    }

    // Logout
    public function logout(Request $request): JsonResponse
    {
        // Elimina l'access token
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully',
        ]);
    }
}
