@extends('layouts.dashboard')

@section('title', 'Suggestions reçues - DocManager')

@section('content')
<div class="p-4 sm:p-6 lg:p-8">

    <div class="flex items-center gap-3 mb-1">
        <div class="w-10 h-10 rounded-xl bg-pink-50 dark:bg-pink-500/10 flex items-center justify-center shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-pink-500">
                <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 0 1 .865-.501 48.172 48.172 0 0 0 3.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
            </svg>
        </div>
        <h1 class="text-xl font-bold tracking-tight">Suggestions reçues</h1>
    </div>
    <p class="text-sm text-gray-500 dark:text-gray-400 mb-6 ml-13">
        {{ $suggestions->count() }} suggestion(s) au total.
    </p>

    @if ($suggestions->isEmpty())
        <div class="flex flex-col items-center justify-center py-16 text-center">
            <div class="w-14 h-14 rounded-2xl bg-gray-100 dark:bg-gray-800 flex items-center justify-center mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-7 text-gray-400">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 0 1 .865-.501 48.172 48.172 0 0 0 3.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
                </svg>
            </div>
            <p class="text-gray-400 dark:text-gray-500 text-sm">Aucune suggestion pour le moment.</p>
        </div>
    @else
        <div class="space-y-3">
            @foreach ($suggestions as $suggestion)
                <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-2xl p-5 shadow-sm">
                    <p class="text-sm text-gray-700 dark:text-gray-200 mb-3 whitespace-pre-line">{{ $suggestion->content }}</p>
                    <div class="flex items-center gap-2 text-xs text-gray-400">
                        <div class="w-5 h-5 rounded-full gradient-primary flex items-center justify-center text-white text-[10px] font-bold shrink-0">
                            {{ strtoupper(substr($suggestion->user->name ?? '?', 0, 1)) }}
                        </div>
                        <span class="font-medium text-gray-500 dark:text-gray-400">
                            {{ $suggestion->user ? '@' . $suggestion->user->username : 'Utilisateur supprimé' }}
                        </span>
                        <span>·</span>
                        <span>{{ $suggestion->created_at->format('d/m/Y à H:i') }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

</div>
@endsection