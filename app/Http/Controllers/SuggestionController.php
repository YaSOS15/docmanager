<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SuggestionController extends Controller
{
    public function create()
    {
        $mySuggestions = auth()->user()->suggestions()->orderBy('created_at', 'desc')->get();

        return view('suggestions.create', ['mySuggestions' => $mySuggestions]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'content' => ['required', 'string', 'max:2000'],
        ], [
            'content.required' => 'Merci d\'écrire ta suggestion avant d\'envoyer.',
            'content.max' => 'Ta suggestion est trop longue (2000 caractères maximum).',
        ]);

        auth()->user()->suggestions()->create($validated);

        return back()->with('success', 'Ta suggestion a bien été envoyée. Merci !');
    }
}