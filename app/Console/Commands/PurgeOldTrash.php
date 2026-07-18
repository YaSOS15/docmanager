<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Document;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class PurgeOldTrash extends Command
{
    protected $signature = 'trash:purge';

    protected $description = 'Supprime définitivement les catégories et documents dans la corbeille depuis plus de 30 jours';

    public function handle(): void
    {
        $documents = Document::onlyTrashed()
            ->where('deleted_at', '<=', now()->subDays(30))
            ->get();

        foreach ($documents as $document) {
            Storage::delete($document->stored_path);
            $document->forceDelete();
        }

        $categoriesCount = Category::onlyTrashed()
            ->where('deleted_at', '<=', now()->subDays(30))
            ->forceDelete();

        $this->info("Purge terminée : {$documents->count()} document(s) et {$categoriesCount} catégorie(s) supprimés définitivement.");
    }
}