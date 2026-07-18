<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('profile.edit');
    }

    public function updateTheme(Request $request)
    {
        $validated = $request->validate([
            'theme' => ['required', 'in:light,dark'],
        ]);

        auth()->user()->update($validated);

        return back()->with('success', 'Thème mis à jour.');
    }

    public function updateViewMode(Request $request)
    {
        $validated = $request->validate([
            'view_mode' => ['required', 'in:list,grid'],
        ]);

        auth()->user()->update($validated);

        return back()->with('success', 'Affichage mis à jour.');
    }

    public function updateUsername(Request $request)
    {
        $validated = $request->validate([
            'username' => ['required', 'string', 'max:255', 'unique:users,username,' . auth()->id()],
            'current_password' => ['required', 'current_password'],
        ], [
            'username.required' => 'Le pseudo est obligatoire.',
            'username.unique' => 'Ce pseudo est déjà utilisé par un autre compte.',
            'current_password.required' => 'Le mot de passe actuel est obligatoire.',
            'current_password.current_password' => 'Le mot de passe actuel est incorrect.',
        ]);

        auth()->user()->update(['username' => $validated['username']]);

        return back()->with('success', 'Nom d\'utilisateur mis à jour.');
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
        ], [
            'current_password.required' => 'Le mot de passe actuel est obligatoire.',
            'current_password.current_password' => 'Le mot de passe actuel est incorrect.',
            'password.required' => 'Le nouveau mot de passe est obligatoire.',
            'password.confirmed' => 'La confirmation ne correspond pas au nouveau mot de passe.',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.mixed' => 'Le mot de passe doit contenir au moins une majuscule et une minuscule.',
            'password.numbers' => 'Le mot de passe doit contenir au moins un chiffre.',
            'password.symbols' => 'Le mot de passe doit contenir au moins un symbole (ex : ! @ # $ %).',
        ]);

        auth()->user()->update(['password' => Hash::make($validated['password'])]);

        return back()->with('success', 'Mot de passe mis à jour.');
    }
}