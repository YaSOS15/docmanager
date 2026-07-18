@extends('layouts.dashboard')

@section('title', 'Corbeille - DocManager')

@section('content')
<div class="p-4 sm:p-6 lg:p-8">

    <div class="flex items-center gap-3 mb-1">
        <div class="w-10 h-10 rounded-xl bg-red-50 dark:bg-red-500/10 flex items-center justify-center shrink-0">
            <x-icon name="trash" class="w-5 h-5 text-red-500" />
        </div>
        <h1 class="text-xl font-bold tracking-tight">Corbeille</h1>
    </div>
    <p class="text-sm text-gray-500 dark:text-gray-400 mb-6 ml-13">
        Les éléments sont supprimés définitivement après 30 jours.
    </p>

    @if ($categories->isEmpty() && $documents->isEmpty())
        <div class="flex flex-col items-center justify-center py-16 text-center">
            <div class="w-14 h-14 rounded-2xl bg-gray-100 dark:bg-gray-800 flex items-center justify-center mb-3">
                <x-icon name="trash" class="w-7 h-7 text-gray-400" />
            </div>
            <p class="text-gray-400 dark:text-gray-500 text-sm">La corbeille est vide.</p>
        </div>
    @endif

    {{-- Catégories supprimées --}}
    @if ($categories->isNotEmpty())
        <h2 class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wide mb-3">Catégories</h2>
        <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-2xl divide-y divide-gray-100 dark:divide-gray-800 mb-8 overflow-hidden shadow-sm">
            @foreach ($categories as $cat)
                <div class="flex flex-col sm:flex-row sm:items-center gap-3 px-4 py-3">
                    <div class="flex items-center gap-3 flex-1 min-w-0">
                        <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-amber-100 to-orange-100 dark:from-amber-500/10 dark:to-orange-500/10 flex items-center justify-center shrink-0 opacity-60">
                            <x-icon name="folder-solid" class="w-4.5 h-4.5 text-amber-500" />
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium truncate">{{ $cat->name }}</p>
                            <p class="text-xs text-gray-400">Supprimée le {{ $cat->deleted_at->format('d/m/Y à H:i') }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 pl-12 sm:pl-0 shrink-0">
                        <form method="POST" action="{{ route('trash.categories.restore', $cat->uuid) }}">
                            @csrf
                            <button type="submit" class="flex items-center gap-1 text-xs font-medium text-indigo-600 dark:text-indigo-400 hover:underline whitespace-nowrap">
                                <x-icon name="restore" class="w-3.5 h-3.5" /> Restaurer
                            </button>
                        </form>
                        <form method="POST" action="{{ route('trash.categories.force-delete', $cat->uuid) }}" onsubmit="return confirm('Suppression définitive et irréversible. Continuer ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-xs font-medium text-red-500 hover:underline whitespace-nowrap">
                                Supprimer définitivement
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    {{-- Documents supprimés --}}
    @if ($documents->isNotEmpty())
        <h2 class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wide mb-3">Documents</h2>
        <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-2xl divide-y divide-gray-100 dark:divide-gray-800 overflow-hidden shadow-sm">
            @foreach ($documents as $doc)
                <div class="flex flex-col sm:flex-row sm:items-center gap-3 px-4 py-3">
                    <div class="flex items-center gap-3 flex-1 min-w-0">
                        <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-indigo-100 to-blue-100 dark:from-indigo-500/10 dark:to-blue-500/10 flex items-center justify-center shrink-0 opacity-60">
                            <x-icon name="document" class="w-4.5 h-4.5 text-indigo-500" />
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium truncate">{{ $doc->original_name }}</p>
                            <p class="text-xs text-gray-400">Supprimé le {{ $doc->deleted_at->format('d/m/Y à H:i') }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 pl-12 sm:pl-0 shrink-0">
                        <form method="POST" action="{{ route('trash.documents.restore', $doc->uuid) }}">
                            @csrf
                            <button type="submit" class="flex items-center gap-1 text-xs font-medium text-indigo-600 dark:text-indigo-400 hover:underline whitespace-nowrap">
                                <x-icon name="restore" class="w-3.5 h-3.5" /> Restaurer
                            </button>
                        </form>
                        <form method="POST" action="{{ route('trash.documents.force-delete', $doc->uuid) }}" onsubmit="return confirm('Suppression définitive et irréversible. Continuer ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-xs font-medium text-red-500 hover:underline whitespace-nowrap">
                                Supprimer définitivement
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

</div>
@endsection