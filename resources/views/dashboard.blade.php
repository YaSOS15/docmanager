@extends('layouts.dashboard')

@section('title', 'Dashboard - DocManager')

@section('content')
<div class="p-4 sm:p-6 lg:p-8" x-data="{ showCreateModal: false, showUploadModal: false, previewDoc: null }">

    @php
        $usedMo = round(auth()->user()->usedStorage() / 1024 / 1024, 1);
        $quotaMo = round(auth()->user()->storage_quota / 1024 / 1024, 0);
        $percent = auth()->user()->storagePercentUsed();
    @endphp
    <div class="mb-6 p-4 rounded-xl bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 shadow-sm">
        <div class="flex justify-between text-xs font-medium text-gray-500 dark:text-gray-400 mb-2">
            <span>Stockage utilisé</span>
            <span>{{ $usedMo }} Mo / {{ $quotaMo }} Mo</span>
        </div>
        <div class="w-full bg-gray-100 dark:bg-gray-800 rounded-full h-2 overflow-hidden">
            <div
                class="h-2 rounded-full transition-all {{ $percent >= 90 ? 'bg-red-500' : ($percent >= 70 ? 'bg-amber-500' : 'gradient-primary') }}"
                style="width: {{ max(2, $percent) }}%"
            ></div>
        </div>
    </div>

    



    {{-- Fil d'ariane --}}
    <nav class="flex items-center flex-wrap gap-1.5 text-sm mb-4 text-gray-400 dark:text-gray-500">
        <a href="{{ route('dashboard') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 font-medium transition">
            Accueil
        </a>
        @foreach ($breadcrumb as $crumb)
            <span class="text-gray-300 dark:text-gray-700">/</span>
            <a href="{{ route('dashboard.category', $crumb) }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 font-medium transition">
                {{ $crumb->name }}
            </a>
        @endforeach
    </nav>

    {{-- En-tête + boutons d'action --}}
    <div class="flex flex-wrap justify-between items-center mb-6 gap-3">
        <h1 class="text-2xl font-bold tracking-tight truncate">
            {{ $currentCategory ? $currentCategory->name : 'Mes documents' }}
        </h1>
        <div class="flex items-center gap-2 shrink-0">

            <div class="flex items-center gap-1 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-lg p-1">
                <form method="POST" action="{{ route('profile.view-mode') }}">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="view_mode" value="grid">
                    <button type="submit" title="Vue grille" class="p-1.5 rounded-md transition {{ auth()->user()->view_mode === 'grid' ? 'bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400' : 'text-gray-400 hover:text-gray-600 dark:hover:text-gray-300' }}">
                        <x-icon name="grid" class="w-4 h-4" />
                    </button>
                </form>
                <form method="POST" action="{{ route('profile.view-mode') }}">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="view_mode" value="list">
                    <button type="submit" title="Vue liste" class="p-1.5 rounded-md transition {{ auth()->user()->view_mode === 'list' ? 'bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400' : 'text-gray-400 hover:text-gray-600 dark:hover:text-gray-300' }}">
                        <x-icon name="list" class="w-4 h-4" />
                    </button>
                </form>
            </div>

            <button
                @click="showUploadModal = true"
                class="flex items-center gap-1.5 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 hover:border-gray-300 dark:hover:border-gray-700 text-sm font-medium px-3 sm:px-4 py-2 rounded-lg transition"
            >
                <x-icon name="upload" class="w-4 h-4" />
                <span class="hidden sm:inline">Ajouter un fichier</span>
            </button>
            <button
                @click="showCreateModal = true"
                class="flex items-center gap-1.5 gradient-primary gradient-primary-hover text-white text-sm font-medium px-3 sm:px-4 py-2 rounded-lg transition shadow-sm shadow-indigo-500/25"
            >
                <x-icon name="plus" class="w-4 h-4" />
                <span class="hidden sm:inline">Nouvelle catégorie</span>
            </button>
        </div>
    </div>

    @if ($categories->isEmpty() && $documents->isEmpty())
        <div class="flex flex-col items-center justify-center py-16 text-center">
            <div class="w-14 h-14 rounded-2xl bg-gray-100 dark:bg-gray-800 flex items-center justify-center mb-3">
                <x-icon name="folder" class="w-7 h-7 text-gray-400" />
            </div>
            <p class="text-gray-400 dark:text-gray-500 text-sm">
                Rien ici pour le moment. Crée une catégorie ou ajoute un fichier pour commencer.
            </p>
        </div>
    @endif

    {{-- ================= CATEGORIES ================= --}}
    @if ($categories->isNotEmpty())

        @if (auth()->user()->view_mode === 'grid')
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3 mb-8">
                @foreach ($categories as $cat)
                    <div class="relative group" x-data="{ menuOpen: false, renaming: false }">
                        <a href="{{ route('dashboard.category', $cat) }}" class="flex flex-col items-center p-5 rounded-xl bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 hover:border-indigo-200 dark:hover:border-indigo-500/30 hover:shadow-md hover:shadow-indigo-500/5 transition-all">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-amber-100 to-orange-100 dark:from-amber-500/10 dark:to-orange-500/10 flex items-center justify-center mb-3">
                                <x-icon name="folder-solid" class="w-6 h-6 text-amber-500" />
                            </div>
                            <template x-if="!renaming">
                                <span class="text-sm text-center font-medium truncate w-full">
                                    {{ $cat->name }}
                                </span>
                            </template>
                        </a>

                        <button
                            @click.stop.prevent="menuOpen = !menuOpen"
                            class="absolute top-2 right-2 w-7 h-7 flex items-center justify-center rounded-lg text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 opacity-0 group-hover:opacity-100 transition"
                        >
                            <x-icon name="dots" class="w-4 h-4" />
                        </button>

                        <div
                            x-show="menuOpen"
                            x-cloak
                            @click.outside="menuOpen = false"
                            class="absolute top-10 right-2 bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-xl shadow-lg z-10 w-36 text-sm overflow-hidden py-1"
                        >
                            <button
                                @click="renaming = true; menuOpen = false; $nextTick(() => $refs['rename-input-{{ $cat->id }}'].focus())"
                                class="w-full flex items-center gap-2 text-left px-3 py-2 hover:bg-gray-50 dark:hover:bg-gray-800"
                            >
                                <x-icon name="pencil" class="w-4 h-4 text-gray-400" /> Renommer
                            </button>
                            <form method="POST" action="{{ route('categories.destroy', $cat) }}" onsubmit="return confirm('Supprimer cette catégorie et tout son contenu ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full flex items-center gap-2 text-left px-3 py-2 hover:bg-gray-50 dark:hover:bg-gray-800 text-red-500">
                                    <x-icon name="trash" class="w-4 h-4" /> Supprimer
                                </button>
                            </form>
                        </div>

                        <template x-if="renaming">
                            <form
                                method="POST"
                                action="{{ route('categories.update', $cat) }}"
                                @click.stop.prevent
                                class="absolute inset-x-0 top-[76px] px-3"
                            >
                                @csrf
                                @method('PATCH')
                                <input
                                    type="text"
                                    name="name"
                                    value="{{ $cat->name }}"
                                    x-ref="rename-input-{{ $cat->id }}"
                                    @blur="renaming = false; $el.form.requestSubmit()"
                                    @keydown.escape="renaming = false"
                                    class="w-full text-xs px-2 py-1 border border-indigo-400 rounded bg-white dark:bg-gray-800 focus:outline-none"
                                >
                            </form>
                        </template>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-xl divide-y divide-gray-100 dark:divide-gray-800 mb-8 overflow-hidden">
                @foreach ($categories as $cat)
                    <div class="relative flex items-center gap-3 px-4 py-3 group hover:bg-gray-50 dark:hover:bg-gray-800/50 transition" x-data="{ menuOpen: false, renaming: false }">
                        <a href="{{ route('dashboard.category', $cat) }}" class="flex items-center gap-3 flex-1 min-w-0">
                            <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-amber-100 to-orange-100 dark:from-amber-500/10 dark:to-orange-500/10 flex items-center justify-center shrink-0">
                                <x-icon name="folder-solid" class="w-4.5 h-4.5 text-amber-500" />
                            </div>
                            <template x-if="!renaming">
                                <span class="text-sm font-medium truncate">
                                    {{ $cat->name }}
                                </span>
                            </template>
                        </a>

                        <template x-if="renaming">
                            <form
                                method="POST"
                                action="{{ route('categories.update', $cat) }}"
                                @click.stop.prevent
                                class="flex-1"
                            >
                                @csrf
                                @method('PATCH')
                                <input
                                    type="text"
                                    name="name"
                                    value="{{ $cat->name }}"
                                    x-ref="rename-input-list-{{ $cat->id }}"
                                    @blur="renaming = false; $el.form.requestSubmit()"
                                    @keydown.escape="renaming = false"
                                    class="w-full text-xs px-2 py-1 border border-indigo-400 rounded bg-white dark:bg-gray-800 focus:outline-none"
                                >
                            </form>
                        </template>

                        <button
                            @click.stop.prevent="menuOpen = !menuOpen"
                            class="w-7 h-7 flex items-center justify-center rounded-lg text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 opacity-0 group-hover:opacity-100 transition shrink-0"
                        >
                            <x-icon name="dots" class="w-4 h-4" />
                        </button>

                        <div
                            x-show="menuOpen"
                            x-cloak
                            @click.outside="menuOpen = false"
                            class="absolute top-12 right-2 bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-xl shadow-lg z-10 w-36 text-sm overflow-hidden py-1"
                        >
                            <button
                                @click="renaming = true; menuOpen = false; $nextTick(() => $refs['rename-input-list-{{ $cat->id }}'].focus())"
                                class="w-full flex items-center gap-2 text-left px-3 py-2 hover:bg-gray-50 dark:hover:bg-gray-800"
                            >
                                <x-icon name="pencil" class="w-4 h-4 text-gray-400" /> Renommer
                            </button>
                            <form method="POST" action="{{ route('categories.destroy', $cat) }}" onsubmit="return confirm('Supprimer cette catégorie et tout son contenu ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full flex items-center gap-2 text-left px-3 py-2 hover:bg-gray-50 dark:hover:bg-gray-800 text-red-500">
                                    <x-icon name="trash" class="w-4 h-4" /> Supprimer
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    @endif

    {{-- ================= DOCUMENTS ================= --}}
    @if ($documents->isNotEmpty())
        <h2 class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wide mb-3">Documents</h2>

        @php
            $previewableMimes = ['application/pdf', 'image/jpeg', 'image/png', 'image/jpg'];
        @endphp

        @if (auth()->user()->view_mode === 'grid')
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3">
                @foreach ($documents as $doc)
                    <div class="relative group" x-data="{ menuOpen: false }">
                        <button
                            type="button"
                            @click="previewDoc = { name: @js($doc->original_name), mime: @js($doc->mime_type), previewUrl: @js(route('documents.preview', $doc)), downloadUrl: @js(route('documents.download', $doc)), previewable: @js(in_array($doc->mime_type, $previewableMimes)) }"
                            class="w-full flex flex-col items-center p-5 rounded-xl bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 hover:border-indigo-200 dark:hover:border-indigo-500/30 hover:shadow-md hover:shadow-indigo-500/5 transition-all"
                        >
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-100 to-blue-100 dark:from-indigo-500/10 dark:to-blue-500/10 flex items-center justify-center mb-3">
                                <x-icon name="document" class="w-6 h-6 text-indigo-500" />
                            </div>
                            <span class="text-sm text-center font-medium truncate w-full">{{ $doc->original_name }}</span>
                            <span class="text-xs text-gray-400 mt-1">{{ number_format($doc->size / 1024, 0) }} Ko</span>
                        </button>

                        <button
                            @click.stop.prevent="menuOpen = !menuOpen"
                            class="absolute top-2 right-2 w-7 h-7 flex items-center justify-center rounded-lg text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 opacity-0 group-hover:opacity-100 transition"
                        >
                            <x-icon name="dots" class="w-4 h-4" />
                        </button>

                        <div
                            x-show="menuOpen"
                            x-cloak
                            @click.outside="menuOpen = false"
                            class="absolute top-10 right-2 bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-xl shadow-lg z-10 w-40 text-sm overflow-hidden py-1"
                        >
                            <a href="{{ route('documents.download', $doc) }}" class="flex items-center gap-2 px-3 py-2 hover:bg-gray-50 dark:hover:bg-gray-800">
                                <x-icon name="download" class="w-4 h-4 text-gray-400" /> Télécharger
                            </a>
                            <form method="POST" action="{{ route('documents.destroy', $doc) }}" onsubmit="return confirm('Supprimer ce document ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full flex items-center gap-2 text-left px-3 py-2 hover:bg-gray-50 dark:hover:bg-gray-800 text-red-500">
                                    <x-icon name="trash" class="w-4 h-4" /> Supprimer
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-xl divide-y divide-gray-100 dark:divide-gray-800 overflow-hidden">
                @foreach ($documents as $doc)
                    <div class="relative flex items-center gap-3 px-4 py-3 group hover:bg-gray-50 dark:hover:bg-gray-800/50 transition" x-data="{ menuOpen: false }">
                        <button
                            type="button"
                            @click="previewDoc = { name: @js($doc->original_name), mime: @js($doc->mime_type), previewUrl: @js(route('documents.preview', $doc)), downloadUrl: @js(route('documents.download', $doc)), previewable: @js(in_array($doc->mime_type, $previewableMimes)) }"
                            class="flex items-center gap-3 flex-1 min-w-0 text-left"
                        >
                            <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-indigo-100 to-blue-100 dark:from-indigo-500/10 dark:to-blue-500/10 flex items-center justify-center shrink-0">
                                <x-icon name="document" class="w-4.5 h-4.5 text-indigo-500" />
                            </div>
                            <span class="text-sm font-medium truncate">{{ $doc->original_name }}</span>
                            <span class="text-xs text-gray-400 shrink-0">{{ number_format($doc->size / 1024, 0) }} Ko</span>
                        </button>

                        <button
                            @click.stop.prevent="menuOpen = !menuOpen"
                            class="w-7 h-7 flex items-center justify-center rounded-lg text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 opacity-0 group-hover:opacity-100 transition shrink-0"
                        >
                            <x-icon name="dots" class="w-4 h-4" />
                        </button>

                        <div
                            x-show="menuOpen"
                            x-cloak
                            @click.outside="menuOpen = false"
                            class="absolute top-12 right-2 bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-xl shadow-lg z-10 w-40 text-sm overflow-hidden py-1"
                        >
                            <a href="{{ route('documents.download', $doc) }}" class="flex items-center gap-2 px-3 py-2 hover:bg-gray-50 dark:hover:bg-gray-800">
                                <x-icon name="download" class="w-4 h-4 text-gray-400" /> Télécharger
                            </a>
                            <form method="POST" action="{{ route('documents.destroy', $doc) }}" onsubmit="return confirm('Supprimer ce document ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full flex items-center gap-2 text-left px-3 py-2 hover:bg-gray-50 dark:hover:bg-gray-800 text-red-500">
                                    <x-icon name="trash" class="w-4 h-4" /> Supprimer
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    @endif

    {{-- Modal de création de catégorie --}}
    <div
        x-show="showCreateModal"
        x-cloak
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-black/40 backdrop-blur-sm flex items-center justify-center z-50 px-4"
    >
        <div
            @click.outside="showCreateModal = false"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            class="bg-white dark:bg-gray-900 rounded-2xl p-6 w-full max-w-sm shadow-xl"
        >
            <h3 class="font-bold text-lg mb-4">Nouvelle catégorie</h3>
            <form method="POST" action="{{ route('categories.store') }}">
                @csrf
                @if ($currentCategory)
                    <input type="hidden" name="parent_id" value="{{ $currentCategory->id }}">
                @endif
                <input
                    type="text"
                    name="name"
                    placeholder="Nom de la catégorie"
                    required
                    autofocus
                    class="w-full px-3.5 py-2.5 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 mb-4 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                >
                <div class="flex justify-end gap-2">
                    <button type="button" @click="showCreateModal = false" class="px-4 py-2 text-sm font-medium rounded-lg text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                        Annuler
                    </button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium rounded-lg gradient-primary gradient-primary-hover text-white transition shadow-sm shadow-indigo-500/25">
                        Créer
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal d'upload de fichier --}}
    <div
        x-show="showUploadModal"
        x-cloak
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-black/40 backdrop-blur-sm flex items-center justify-center z-50 px-4"
    >
        <div
            @click.outside="showCreateModal = false"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            class="bg-white dark:bg-gray-900 rounded-2xl p-6 w-full max-w-sm shadow-xl"
        >
            <h3 class="font-bold text-lg mb-1">Ajouter un fichier</h3>
            @if (!$currentCategory)
                <p class="text-xs text-amber-600 dark:text-amber-400 mb-4">
                    Entre dans une catégorie pour y ajouter un fichier.
                </p>
            @else
                <p class="text-xs text-gray-400 mb-4">
                    Dans : {{ $currentCategory->name }}
                </p>
                <form method="POST" action="{{ route('documents.store') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="category_id" value="{{ $currentCategory->id }}">
                    <input
                        type="file"
                        name="file"
                        required
                        accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                        class="w-full text-sm mb-1 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:bg-indigo-50 dark:file:bg-indigo-500/10 file:text-indigo-600 dark:file:text-indigo-400 file:text-sm file:font-medium"
                    >
                    <p class="text-xs text-gray-400 mb-4">PDF, Word, JPG, PNG — 20 Mo max.</p>
                    <div class="flex justify-end gap-2">
                        <button type="button" @click="showUploadModal = false" class="px-4 py-2 text-sm font-medium rounded-lg text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                            Annuler
                        </button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium rounded-lg gradient-primary gradient-primary-hover text-white transition shadow-sm shadow-indigo-500/25">
                            Envoyer
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </div>

    {{-- Modal d'aperçu de document --}}
    <div
        x-show="previewDoc !== null"
        x-cloak
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 px-4 py-8"
    >
        <div
            @click.outside="showCreateModal = false"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            class="bg-white dark:bg-gray-900 rounded-2xl p-6 w-full max-w-sm shadow-xl"
        >
            <div class="flex justify-between items-center px-5 py-4 border-b border-gray-100 dark:border-gray-800">
                <span class="text-sm font-medium truncate" x-text="previewDoc?.name"></span>
                <div class="flex items-center gap-4 shrink-0">
                    <a :href="previewDoc?.downloadUrl" class="text-xs font-medium text-indigo-600 dark:text-indigo-400 hover:underline flex items-center gap-1">
                        <x-icon name="download" class="w-4 h-4" /> Télécharger
                    </a>
                    <button @click="previewDoc = null" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <x-icon name="x" class="w-5 h-5" />
                    </button>
                </div>
            </div>
            <div class="flex-1 overflow-auto bg-gray-50 dark:bg-gray-950 flex items-center justify-center">
                <template x-if="previewDoc && previewDoc.previewable && previewDoc.mime === 'application/pdf'">
                    <iframe :src="previewDoc.previewUrl" class="w-full h-full"></iframe>
                </template>
                <template x-if="previewDoc && previewDoc.previewable && previewDoc.mime !== 'application/pdf'">
                    <img :src="previewDoc.previewUrl" class="max-w-full max-h-full object-contain">
                </template>
                <template x-if="previewDoc && !previewDoc.previewable">
                    <div class="text-center p-6">
                        <div class="w-14 h-14 rounded-2xl bg-gray-100 dark:bg-gray-800 flex items-center justify-center mb-3 mx-auto">
                            <x-icon name="document" class="w-7 h-7 text-gray-400" />
                        </div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Aperçu non disponible pour ce type de fichier.</p>
                    </div>
                </template>
            </div>
        </div>
    </div>

</div>
@endsection