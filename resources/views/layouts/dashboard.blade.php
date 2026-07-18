<!DOCTYPE html>
<html lang="fr" class="{{ auth()->user()->theme === 'dark' ? 'dark' : '' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'DocManager')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 dark:bg-gray-950 text-gray-900 dark:text-gray-100 antialiased" x-data="{ sidebarOpen: false }">

    <div class="flex h-screen overflow-hidden">

        {{-- Overlay mobile --}}
        <div
            x-show="sidebarOpen"
            x-cloak
            @click="sidebarOpen = false"
            class="fixed inset-0 bg-black/50 backdrop-blur-sm z-30 md:hidden"
        ></div>

        {{-- Sidebar --}}
        <aside
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed z-40 inset-y-0 left-0 w-64 bg-white dark:bg-gray-900 border-r border-gray-100 dark:border-gray-800 transform transition-transform duration-200 ease-in-out md:translate-x-0 md:static md:inset-auto flex flex-col"
        >
            {{-- Logo --}}
            <div class="h-16 flex items-center gap-2.5 px-5 border-b border-gray-100 dark:border-gray-800">
                <div class="w-8 h-8 rounded-lg gradient-primary flex items-center justify-center shrink-0">
                    <x-icon name="folder-solid" class="w-4.5 h-4.5 text-white" />
                </div>
                <span class="text-base font-bold tracking-tight">DocManager</span>
            </div>

            <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-0.5">
                @php
                    $navLink = function ($active) {
                        return $active
                            ? 'bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400'
                            : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white';
                    };
                @endphp

                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ $navLink(request()->routeIs('dashboard') || request()->routeIs('dashboard.category')) }}">
                    <x-icon name="home" class="w-5 h-5 shrink-0" />
                    Mes documents
                </a>
                <a href="{{ route('search.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ $navLink(request()->routeIs('search.index')) }}">
                    <x-icon name="search" class="w-5 h-5 shrink-0" />
                    Recherche
                </a>
                <a href="{{ route('trash.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ $navLink(request()->routeIs('trash.index')) }}">
                    <x-icon name="trash" class="w-5 h-5 shrink-0" />
                    Corbeille
                </a>
                <a href="{{ route('suggestions.create') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ $navLink(request()->routeIs('suggestions.create')) }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 shrink-0">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 0 1 .865-.501 48.172 48.172 0 0 0 3.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
                    </svg>
                    Boîte à idées
                </a>
                <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ $navLink(request()->routeIs('profile.edit')) }}">
                    <x-icon name="settings" class="w-5 h-5 shrink-0" />
                    Mon profil
                </a>
                @if (auth()->user()->isAdmin())
                    <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ $navLink(request()->routeIs('admin.users.index')) }}">
                        <x-icon name="users" class="w-5 h-5 shrink-0" />
                        Administration
                    </a>
                    <a href="{{ route('admin.suggestions.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ $navLink(request()->routeIs('admin.suggestions.index')) }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 0 1 .865-.501 48.172 48.172 0 0 0 3.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
                        </svg>
                        Suggestions reçues
                    </a>
                @endif
            </nav>

            {{-- Bas de sidebar : utilisateur --}}
            <div class="p-3 border-t border-gray-100 dark:border-gray-800">
                <div class="flex items-center gap-2.5 px-2 py-2 rounded-lg">
                    <div class="w-8 h-8 rounded-full gradient-primary flex items-center justify-center text-white text-xs font-bold shrink-0">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium truncate">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-400 truncate">{{ auth()->user()->username }}</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" title="Déconnexion" class="text-gray-400 hover:text-red-500 transition">
                            <x-icon name="logout" class="w-4.5 h-4.5" />
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        {{-- Contenu principal --}}
        <div class="flex-1 flex flex-col overflow-hidden">

            {{-- Topbar mobile --}}
            <header class="h-16 flex items-center justify-between px-4 border-b border-gray-100 dark:border-gray-800 bg-white dark:bg-gray-900 md:hidden">
                <button @click="sidebarOpen = true" class="text-gray-600 dark:text-gray-300">
                    <x-icon name="menu" class="w-6 h-6" />
                </button>
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 rounded gradient-primary flex items-center justify-center shrink-0">
                        <x-icon name="folder-solid" class="w-3.5 h-3.5 text-white" />
                    </div>
                    <span class="font-bold text-sm">DocManager</span>
                </div>
                <div class="w-6"></div>
            </header>

            <main class="flex-1 overflow-y-auto flex flex-col">
                <x-toast />
                <div class="flex-1">
                    @yield('content')
                </div>
                <x-footer />
            </main>
        </div>
    </div>

</body>
</html>