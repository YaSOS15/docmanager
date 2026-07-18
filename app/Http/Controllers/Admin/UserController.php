<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('id', '!=', auth()->id())
            ->withCount(['documents', 'categories'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.users.index', ['users' => $users]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
        ]);

        $temporaryPassword = Str::random(10);

        User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'password' => Hash::make($temporaryPassword),
            'role' => 'user',
        ]);

        return back()->with('success', "Compte créé. Mot de passe temporaire : {$temporaryPassword}");
    }

    public function updateQuota(Request $request, User $user)
    {
        $validated = $request->validate([
            'storage_quota_mo' => ['required', 'integer', 'min:1', 'max:100000'],
        ]);

        $user->update([
            'storage_quota' => $validated['storage_quota_mo'] * 1024 * 1024,
        ]);

        return back()->with('success', 'Quota mis à jour pour ' . $user->username . '.');
    }

    public function destroy(User $user)
    {
        if ($user->isAdmin()) {
            abort(403, 'Impossible de supprimer un administrateur.');
        }

        $user->delete();

        return back()->with('success', 'Compte supprimé avec succès.');
    }
}