@extends('layouts.dashboard')

@section('title', 'Administration - DocManager')

@section('content')
<div class="p-4 sm:p-6" x-data="{ showCreateModal: false }">

   

    

    <div class="flex justify-between items-center mb-6 gap-2">
        <h1 class="text-xl font-bold">Gestion des utilisateurs</h1>
        <button
            @click="showCreateModal = true"
            class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-3 sm:px-4 py-2 rounded-lg transition"
        >
            + <span class="hidden sm:inline">Créer un compte</span>
        </button>
    </div>

    @if ($users->isEmpty())
        <p class="text-gray-400 dark:text-gray-500 text-sm">Aucun utilisateur pour le moment.</p>
    @else
        {{-- Vue tableau sur desktop --}}
        <div class="hidden md:block overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-800 text-left text-gray-500 dark:text-gray-400">
                    <tr>
                        <th class="px-4 py-3">Nom</th>
                        <th class="px-4 py-3">Pseudo</th>
                        <th class="px-4 py-3">Catégories</th>
                        <th class="px-4 py-3">Documents</th>
                        <th class="px-4 py-3">Stockage</th>
                        <th class="px-4 py-3">Créé le</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach ($users as $user)
                        <tr>
                            <td class="px-4 py-3">{{ $user->name }}</td>
                            <td class="px-4 py-3 text-gray-500 dark:text-gray-400">{{ $user->username }}</td>
                            <td class="px-4 py-3">{{ $user->categories_count }}</td>
                            <td class="px-4 py-3">{{ $user->documents_count }}</td>
                            <td class="px-4 py-3" x-data="{ editingQuota: false }">
                                <template x-if="!editingQuota">
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs">{{ round($user->usedStorage() / 1024 / 1024, 1) }} / {{ round($user->storage_quota / 1024 / 1024) }} Mo</span>
                                        <button @click="editingQuota = true" class="text-xs text-indigo-600 hover:underline">✏️</button>
                                    </div>
                                </template>
                                <template x-if="editingQuota">
                                    <form method="POST" action="{{ route('admin.users.quota', $user) }}" class="flex items-center gap-1">
                                        @csrf
                                        @method('PATCH')
                                        <input
                                            type="number"
                                            name="storage_quota_mo"
                                            value="{{ round($user->storage_quota / 1024 / 1024) }}"
                                            min="1"
                                            class="w-20 px-2 py-1 text-xs border border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-700"
                                        >
                                        <span class="text-xs text-gray-400">Mo</span>
                                        <button type="submit" class="text-xs text-indigo-600 hover:underline">OK</button>
                                    </form>
                                </template>
                            </td>
                            <td class="px-4 py-3 text-gray-500 dark:text-gray-400">{{ $user->created_at->format('d/m/Y') }}</td>
                            <td class="px-4 py-3 text-right">
                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Supprimer ce compte et toutes ses données ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline text-xs">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Vue cartes sur mobile --}}
        <div class="md:hidden space-y-3">
            @foreach ($users as $user)
                <div class="p-4 rounded-xl border border-gray-200 dark:border-gray-700">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <p class="font-medium">{{ $user->name }}</p>
                            <p class="text-xs text-gray-400">@{{ $user->username }}</p>
                        </div>
                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Supprimer ce compte et toutes ses données ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 text-xs">Supprimer</button>
                        </form>
                    </div>
                    <div class="text-xs text-gray-500 dark:text-gray-400 flex gap-4">
                        <span>{{ $user->categories_count }} catégories</span>
                        <span>{{ $user->documents_count }} documents</span>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    {{-- Modal de création --}}
    <div
        x-show="showCreateModal"
        x-cloak
        class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 px-4"
    >
        <div @click.outside="showCreateModal = false" class="bg-white dark:bg-gray-800 rounded-xl p-6 w-full max-w-sm">
            <h3 class="font-bold mb-4">Créer un compte utilisateur</h3>
            <form method="POST" action="{{ route('admin.users.store') }}">
                @csrf
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Nom complet</label>
                <input
                    type="text"
                    name="name"
                    required
                    autofocus
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 mb-3 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                >
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Pseudo (identifiant de connexion)</label>
                <input
                    type="text"
                    name="username"
                    required
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 mb-4 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                >
                <p class="text-xs text-gray-400 mb-4">Un mot de passe temporaire sera généré automatiquement et affiché après création — à transmettre à l'utilisateur.</p>
                <div class="flex justify-end gap-2">
                    <button type="button" @click="showCreateModal = false" class="px-4 py-2 text-sm rounded-lg text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                        Annuler
                    </button>
                    <button type="submit" class="px-4 py-2 text-sm rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white">
                        Créer
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection