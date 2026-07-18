@extends('layouts.dashboard')

@section('title', 'Mon profil - DocManager')

@section('content')
<div class="p-4 sm:p-6 lg:p-8 max-w-2xl">

    <div class="flex items-center gap-3 mb-8">
        <div class="w-11 h-11 rounded-xl gradient-primary flex items-center justify-center text-white font-bold shrink-0">
            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
        </div>
        <div>
            <h1 class="text-xl font-bold tracking-tight">{{ auth()->user()->name }}</h1>
            <p class="text-sm text-gray-400">{{ '@' . auth()->user()->username }}</p>
        </div>
    </div>

    {{-- Thème --}}
    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-800 p-5 mb-4 shadow-sm">
        <div class="flex items-center justify-between gap-4">
            <div class="flex items-center gap-2 min-w-0">
                <div class="w-8 h-8 rounded-lg bg-purple-50 dark:bg-purple-500/10 flex items-center justify-center shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4.5 h-4.5 text-purple-500">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" />
                    </svg>
                </div>
                <div class="min-w-0">
                    <h2 class="font-semibold text-sm">Thème</h2>
                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate">Apparence de ton espace</p>
                </div>
            </div>

            <div class="flex items-center gap-1 bg-gray-100 dark:bg-gray-800 rounded-lg p-1 shrink-0">
                <form method="POST" action="{{ route('profile.theme') }}">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="theme" value="light">
                    <button type="submit" title="Clair" class="p-1.5 rounded-md transition {{ auth()->user()->theme === 'light' ? 'bg-white dark:bg-gray-700 shadow text-indigo-600 dark:text-indigo-400' : 'text-gray-400 hover:text-gray-600 dark:hover:text-gray-300' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" />
                        </svg>
                    </button>
                </form>
                <form method="POST" action="{{ route('profile.theme') }}">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="theme" value="dark">
                    <button type="submit" title="Sombre" class="p-1.5 rounded-md transition {{ auth()->user()->theme === 'dark' ? 'bg-white dark:bg-gray-700 shadow text-indigo-600 dark:text-indigo-400' : 'text-gray-400 hover:text-gray-600 dark:hover:text-gray-300' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Mode d'affichage --}}
    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-800 p-5 mb-4 shadow-sm">
        <div class="flex items-center justify-between gap-4">
            <div class="flex items-center gap-2 min-w-0">
                <div class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-500/10 flex items-center justify-center shrink-0">
                    <x-icon name="grid" class="w-4.5 h-4.5 text-blue-500" />
                </div>
                <div class="min-w-0">
                    <h2 class="font-semibold text-sm">Affichage des documents</h2>
                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate">Grille ou liste</p>
                </div>
            </div>

            <div class="flex items-center gap-1 bg-gray-100 dark:bg-gray-800 rounded-lg p-1 shrink-0">
                <form method="POST" action="{{ route('profile.view-mode') }}">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="view_mode" value="grid">
                    <button type="submit" title="Grille" class="p-1.5 rounded-md transition {{ auth()->user()->view_mode === 'grid' ? 'bg-white dark:bg-gray-700 shadow text-indigo-600 dark:text-indigo-400' : 'text-gray-400 hover:text-gray-600 dark:hover:text-gray-300' }}">
                        <x-icon name="grid" class="w-4 h-4" />
                    </button>
                </form>
                <form method="POST" action="{{ route('profile.view-mode') }}">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="view_mode" value="list">
                    <button type="submit" title="Liste" class="p-1.5 rounded-md transition {{ auth()->user()->view_mode === 'list' ? 'bg-white dark:bg-gray-700 shadow text-indigo-600 dark:text-indigo-400' : 'text-gray-400 hover:text-gray-600 dark:hover:text-gray-300' }}">
                        <x-icon name="list" class="w-4 h-4" />
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Nom d'utilisateur --}}
    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-800 p-5 mb-4 shadow-sm">
        <div class="flex items-center gap-2 mb-1">
            <div class="w-8 h-8 rounded-lg bg-amber-50 dark:bg-amber-500/10 flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4.5 h-4.5 text-amber-500">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                </svg>
            </div>
            <h2 class="font-semibold">Identifiant de connexion</h2>
        </div>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4 ml-10">
            Actuel : <strong class="text-gray-700 dark:text-gray-300">{{ auth()->user()->username }}</strong>
        </p>
        <form method="POST" action="{{ route('profile.username') }}">
            @csrf
            @method('PATCH')
            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Nouveau pseudo</label>
            <input
                type="text"
                name="username"
                required
                class="w-full px-3.5 py-2.5 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 mb-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition"
            >
            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Mot de passe actuel (confirmation)</label>
            <input
                type="password"
                name="current_password"
                required
                class="w-full px-3.5 py-2.5 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 mb-4 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition"
            >
            <button type="submit" class="gradient-primary gradient-primary-hover text-white text-sm font-medium px-4 py-2.5 rounded-lg transition shadow-sm shadow-indigo-500/25">
                Mettre à jour
            </button>
        </form>
    </div>

    {{-- Mot de passe --}}
    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-800 p-5 shadow-sm">
        <div class="flex items-center gap-2 mb-1">
            <div class="w-8 h-8 rounded-lg bg-rose-50 dark:bg-rose-500/10 flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4.5 h-4.5 text-rose-500">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                </svg>
            </div>
            <h2 class="font-semibold">Mot de passe</h2>
        </div>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4 ml-10">Min. 8 caractères, majuscule, minuscule, chiffre et symbole.</p>
        <form method="POST" action="{{ route('profile.password') }}">
            @csrf
            @method('PATCH')
            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Mot de passe actuel</label>
            <input
                type="password"
                name="current_password"
                required
                class="w-full px-3.5 py-2.5 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 mb-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition"
            >
            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Nouveau mot de passe</label>
            <input
                type="password"
                name="password"
                required
                class="w-full px-3.5 py-2.5 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 mb-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition"
            >
            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Confirme le nouveau mot de passe</label>
            <input
                type="password"
                name="password_confirmation"
                required
                class="w-full px-3.5 py-2.5 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 mb-4 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition"
            >
            <button type="submit" class="gradient-primary gradient-primary-hover text-white text-sm font-medium px-4 py-2.5 rounded-lg transition shadow-sm shadow-indigo-500/25">
                Changer le mot de passe
            </button>
        </form>
    </div>

</div>
@endsection