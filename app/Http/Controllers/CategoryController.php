<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(?Category $category = null)
    {
        // Si un ID de catégorie est fourni dans l'URL, Laravel la charge automatiquement.
        if ($category && $category->exists) {
            $this->authorize('view', $category);

            $categories = $category->children()->where('user_id', auth()->id())->get();
            $documents = $category->documents()->get();
            $breadcrumb = $this->buildBreadcrumb($category);
        } else {
            $category = null;
            $categories = auth()->user()->categories()->whereNull('parent_id')->get();
            $documents = collect();
            $breadcrumb = [];
        }

        return view('dashboard', [
            'currentCategory' => $category,
            'categories' => $categories,
            'documents' => $documents,
            'breadcrumb' => $breadcrumb,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'parent_id' => ['nullable', 'exists:categories,id'],
        ]);

        if ($validated['parent_id'] ?? null) {
            $parent = Category::findOrFail($validated['parent_id']);
            $this->authorize('view', $parent);
        }

        auth()->user()->categories()->create($validated);

        return back()->with('success', 'Catégorie créée avec succès.');
    }

    public function update(Request $request, Category $category)
    {
        $this->authorize('update', $category);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $category->update($validated);

        return back()->with('success', 'Catégorie renommée avec succès.');
    }

    public function destroy(Category $category)
    {
        $this->authorize('delete', $category);

        $category->delete();

        return back()->with('success', 'Catégorie déplacée vers la corbeille.');
    }

    private function buildBreadcrumb(Category $category): array
    {
        $breadcrumb = [];
        $current = $category;

        while ($current) {
            array_unshift($breadcrumb, $current);
            $current = $current->parent;
        }

        return $breadcrumb;
    }
}