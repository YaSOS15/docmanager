@extends('layouts.dashboard')

@section('title', 'Suggestions - DocManager')

@section('content')
<div class="p-4 sm:p-6 lg:p-8 max-w-2xl">

    <div class="flex items-center gap-3 mb-1">
        <div class="w-10 h-10 rounded-xl bg-pink-50 dark:bg-pink-500/10 flex items-center justify-center shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-pink-500">
                <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 0 1 .865-.501 48.172 48.172 0 0 0 3.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
            </svg>
        </div>
        <h1 class="text-xl font-bold tracking-tight">Suggestions</h1>
    </div>
    <p class="text-sm text-gray-500 dark:text-gray-400 mb-6 ml-13">
        Une idée pour améliorer l'application ? Partage-la avec l'administrateur.
    </p>

    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-800 p-5 mb-8 shadow-sm">
        <form method="POST" action="{{ route('suggestions.store') }}">
            @csrf
            <textarea
                name="content"
                rows="5"
                placeholder="Écris ta suggestion ici..."
                required
                maxlength="2000"
                class="w-full px-3.5 py-2.5 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 mb-4 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition resize-none"
            >{{ old('content') }}</textarea>
            <button type="submit" class="gradient-primary gradient-primary-hover text-white text-sm font-medium px-5 py-2.5 rounded-lg transition shadow-sm shadow-indigo-500/25">
                Envoyer la suggestion
            </button>
        </form>
    </div>

    @if ($mySuggestions->isNotEmpty())
        <h2 class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wide mb-3">Mes suggestions envoyées</h2>
        <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-2xl divide-y divide-gray-100 dark:divide-gray-800 overflow-hidden shadow-sm">
            @foreach ($mySuggestions as $suggestion)
                <div class="px-4 py-3">
                    <p class="text-sm text-gray-700 dark:text-gray-300 mb-1">{{ $suggestion->content }}</p>
                    <p class="text-xs text-gray-400">{{ $suggestion->created_at->format('d/m/Y à H:i') }}</p>
                </div>
            @endforeach
        </div>
    @endif

</div>
@endsection