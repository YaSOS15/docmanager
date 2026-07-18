<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Document;
use Illuminate\Support\Facades\Storage;

class TrashController extends Controller
{
    public function index()
    {
        $categories = Category::onlyTrashed()
            ->where('user_id', auth()->id())
            ->orderBy('deleted_at', 'desc')
            ->get();

        $documents = Document::onlyTrashed()
            ->where('user_id', auth()->id())
            ->orderBy('deleted_at', 'desc')
            ->get();

        return view('trash.index', [
            'categories' => $categories,
            'documents' => $documents,
        ]);
    }

    public function restoreCategory(string $uuid)
    {
        $category = Category::onlyTrashed()->where('user_id', auth()->id())->where('uuid', $uuid)->firstOrFail();
        $category->restore();

        return back()->with('success', 'Catégorie restaurée.');
    }

    public function restoreDocument(string $uuid)
    {
        $document = Document::onlyTrashed()->where('user_id', auth()->id())->where('uuid', $uuid)->firstOrFail();
        $document->restore();

        return back()->with('success', 'Document restauré.');
    }

    public function forceDeleteCategory(string $uuid)
    {
        $category = Category::onlyTrashed()->where('user_id', auth()->id())->where('uuid', $uuid)->firstOrFail();
        $category->forceDelete();

        return back()->with('success', 'Catégorie supprimée définitivement.');
    }

    public function forceDeleteDocument(string $uuid)
    {
        $document = Document::onlyTrashed()->where('user_id', auth()->id())->where('uuid', $uuid)->firstOrFail();

        Storage::delete($document->stored_path);
        $document->forceDelete();

        return back()->with('success', 'Document supprimé définitivement.');
    }
}