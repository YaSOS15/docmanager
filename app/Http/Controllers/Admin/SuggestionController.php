<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Suggestion;

class SuggestionController extends Controller
{
    public function index()
    {
        $suggestions = Suggestion::with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        Suggestion::where('is_read', false)->update(['is_read' => true]);

        return view('admin.suggestions.index', ['suggestions' => $suggestions]);
    }
}