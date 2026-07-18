@extends('layouts.dashboard')

@section('title', 'Recherche - DocManager')

@section('content')
<div
    class="p-4 sm:p-6"
    x-data="{
        query: '',
        categories: [],
        documents: [],
        loading: false,
        searched: false,
        timeout: null,
        search() {
            clearTimeout(this.timeout);
            if (this.query.trim() === '') {
                this.categories = [];
                this.documents = [];
                this.searched = false;
                return;
            }
            this.timeout = setTimeout(() => {
                this.loading = true;
                fetch('{{ route('search.index') }}?q=' + encodeURIComponent(this.query), {
                    headers: { 'Accept': 'application/json' }
                })
                    .then(res => res.json())
                    .then(data => {
                        this.categories = data.categories;
                        this.documents = data.documents;
                        this.searched = true;
                        this.loading = false;
                    });
            }, 300);
        }
    }"
>
    <h1 class="text-xl font-bold mb-1">🔍 Recherche</h1>

    <div class="relative mb-6">
        <input
            type="text"
            x-model="query"
            @input="search()"
            placeholder="Rechercher un dossier ou un fichier..."
            autofocus
            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
        >
        <span x-show="loading" x-cloak class="absolute right-4 top-1/2 -translate-y-1/2 text-xs text-gray-400">
            Recherche...
        </span>
    </div>

    <template x-if="!searched && query.trim() === ''">
        <p class="text-gray-400 dark:text-gray-500 text-sm">Tape un mot-clé pour commencer la recherche.</p>
    </template>

    <template x-if="searched && categories.length === 0 && documents.length === 0">
        <p class="text-gray-400 dark:text-gray-500 text-sm">Aucun résultat pour « <span x-text="query"></span> ».</p>
    </template>

    {{-- Catégories trouvées --}}
    <template x-if="categories.length > 0">
        <div class="mb-8">
            <h2 class="text-sm font-semibold text-gray-500 dark:text-gray-400 mb-3">
                Dossiers (<span x-text="categories.length"></span>)
            </h2>
            <div class="border border-gray-200 dark:border-gray-700 rounded-xl divide-y divide-gray-200 dark:divide-gray-700">
                <template x-for="cat in categories" :key="cat.id">
                    <a :href="cat.url" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                        <span class="text-2xl shrink-0">📁</span>
                        <span class="text-sm font-medium truncate" x-text="cat.name"></span>
                    </a>
                </template>
            </div>
        </div>
    </template>

    {{-- Documents trouvés --}}
    <template x-if="documents.length > 0">
        <div>
            <h2 class="text-sm font-semibold text-gray-500 dark:text-gray-400 mb-3">
                Fichiers (<span x-text="documents.length"></span>)
            </h2>
            <div class="border border-gray-200 dark:border-gray-700 rounded-xl divide-y divide-gray-200 dark:divide-gray-700">
                <template x-for="doc in documents" :key="doc.id">
                    <div class="flex items-center gap-3 px-4 py-3">
                        <span class="text-2xl shrink-0">📄</span>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium truncate" x-text="doc.name"></p>
                            <p class="text-xs text-gray-400">
                                Dans : <span x-text="doc.category_name"></span>
                            </p>
                        </div>
                        <a x-show="doc.category_url" :href="doc.category_url" class="text-xs text-indigo-600 hover:underline whitespace-nowrap">
                            Voir le dossier
                        </a>
                        <a :href="doc.download_url" class="text-xs text-indigo-600 hover:underline whitespace-nowrap">
                            ⬇️ Télécharger
                        </a>
                    </div>
                </template>
            </div>
        </div>
    </template>

</div>
@endsection