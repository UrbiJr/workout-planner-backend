<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class Register extends Controller
{
    /**
     * Registra un nuovo utente
     */
    public function __invoke(Request $request): JsonResponse
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

        $request->session()->regenerate();

        // Crea un API token per l'utente
        Auth::login($user);

        return response()->json([
            'message' => 'Registration successful',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ]
        ], 201);
    }
}
