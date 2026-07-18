@extends('layouts.app')

@section('title', 'Connexion - DocManager')

@section('content')
<div class="min-h-screen flex">

    {{-- Côté gauche : branding --}}
    <div class="hidden lg:flex lg:w-1/2 gradient-primary relative overflow-hidden items-center justify-center p-12">
        <div class="absolute -top-24 -left-24 w-96 h-96 bg-white/10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-32 -right-16 w-96 h-96 bg-white/10 rounded-full blur-3xl"></div>
        <div class="absolute top-1/3 right-1/4 w-64 h-64 bg-white/5 rounded-full blur-2xl"></div>

        <div class="relative z-10 max-w-md text-white">
            <div class="w-14 h-14 rounded-2xl bg-white/15 backdrop-blur-sm flex items-center justify-center mb-8">
                <x-icon name="folder-solid" class="w-7 h-7 text-white" />
            </div>
            <h1 class="text-4xl font-bold tracking-tight mb-4">DocManager</h1>
            <p class="text-lg text-white/80 leading-relaxed mb-8">
                Tous tes documents, parfaitement rangés. Crée tes propres catégories, retrouve n'importe quel fichier en quelques secondes.
            </p>
            <div class="space-y-3">
                <div class="flex items-center gap-3 text-sm text-white/90">
                    <div class="w-8 h-8 rounded-lg bg-white/15 backdrop-blur-sm flex items-center justify-center shrink-0">
                        <x-icon name="folder" class="w-4 h-4" />
                    </div>
                    Catégories et sous-catégories illimitées
                </div>
                <div class="flex items-center gap-3 text-sm text-white/90">
                    <div class="w-8 h-8 rounded-lg bg-white/15 backdrop-blur-sm flex items-center justify-center shrink-0">
                        <x-icon name="search" class="w-4 h-4" />
                    </div>
                    Recherche instantanée
                </div>
                <div class="flex items-center gap-3 text-sm text-white/90">
                    <div class="w-8 h-8 rounded-lg bg-white/15 backdrop-blur-sm flex items-center justify-center shrink-0">
                        <x-icon name="document" class="w-4 h-4" />
                    </div>
                    Aperçu direct de tes fichiers
                </div>
            </div>
        </div>
    </div>

    {{-- Côté droit : formulaire --}}
    <div class="w-full lg:w-1/2 flex items-center justify-center px-4 sm:px-8 py-12 bg-gray-50 dark:bg-gray-950">
        <div class="w-full max-w-sm">

            <div class="lg:hidden flex items-center gap-2.5 mb-8 justify-center">
                <div class="w-9 h-9 rounded-lg gradient-primary flex items-center justify-center shrink-0">
                    <x-icon name="folder-solid" class="w-5 h-5 text-white" />
                </div>
                <span class="text-lg font-bold tracking-tight">DocManager</span>
            </div>

            <div class="mb-8">
                <h2 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white mb-1">Bienvenue</h2>
                <p class="text-gray-500 dark:text-gray-400 text-sm">Connecte-toi pour accéder à tes documents.</p>
            </div>

            @if ($errors->any())
                <div class="mb-4 p-3 rounded-lg bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 text-sm border border-red-100 dark:border-red-500/20">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}" x-data="{ showPassword: false }">
                @csrf

                <div class="mb-4">
                    <label for="username" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        Nom d'utilisateur
                    </label>
                    <input
                        type="text"
                        name="username"
                        id="username"
                        value="{{ old('username') }}"
                        required
                        autofocus
                        class="w-full px-3.5 py-2.5 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition"
                    >
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        Mot de passe
                    </label>
                    <div class="relative">
                        <input
                            :type="showPassword ? 'text' : 'password'"
                            name="password"
                            id="password"
                            required
                            class="w-full px-3.5 py-2.5 pr-11 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition"
                        >
                        <button
                            type="button"
                            @click="showPassword = !showPassword"
                            class="absolute right-0 top-0 h-full px-3 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
                            tabindex="-1"
                        >
                            <svg x-show="!showPassword" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                            <svg x-show="showPassword" x-cloak xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="flex items-center mb-6">
                    <input
                        type="checkbox"
                        name="remember"
                        id="remember"
                        class="w-4 h-4 rounded border-gray-300 dark:border-gray-600 text-indigo-600 focus:ring-indigo-500"
                    >
                    <label for="remember" class="ml-2 text-sm text-gray-600 dark:text-gray-400">
                        Rester connecté
                    </label>
                </div>

                <button
                    type="submit"
                    class="w-full gradient-primary gradient-primary-hover text-white font-medium py-2.5 rounded-lg transition shadow-sm shadow-indigo-500/25"
                >
                    Se connecter
                </button>
            </form>

            <p class="text-xs text-gray-400 text-center mt-8">
                Accès uniquement sur invitation de l'administrateur.
            </p>
        </div>
    </div>

</div>
@endsection