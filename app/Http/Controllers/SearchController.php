<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Document;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = trim($request->input('q', ''));

        [$categories, $documents] = $this->search($query);

        if ($request->wantsJson()) {
            return response()->json([
                'categories' => $categories->map(fn ($cat) => [
                    'id' => $cat->id,
                    'name' => $cat->name,
                    'url' => route('dashboard.category', $cat),
                ]),
                'documents' => $documents->map(fn ($doc) => [
                    'id' => $doc->id,
                    'name' => $doc->original_name,
                    'category_name' => $doc->category?->name ?? 'Catégorie supprimée',
                    'category_url' => $doc->category ? route('dashboard.category', $doc->category) : null,
                    'download_url' => route('documents.download', $doc),
                ]),
            ]);
        }

        return view('search.index', [
            'query' => $query,
            'categories' => $categories,
            'documents' => $documents,
        ]);
    }

    private function search(string $query): array
    {
        if ($query === '') {
            return [collect(), collect()];
        }

        $categories = Category::where('user_id', auth()->id())
            ->where('name', 'like', "%{$query}%")
            ->orderBy('name')
            ->get();

        $documents = Document::where('user_id', auth()->id())
            ->where('original_name', 'like', "%{$query}%")
            ->orderBy('original_name')
            ->get();

        return [$categories, $documents];
    }
}