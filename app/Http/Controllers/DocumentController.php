<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Document;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
   public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'file' => ['required', 'file', 'max:20480', 'mimes:pdf,doc,docx,jpg,jpeg,png'],
        ]);

        $category = Category::findOrFail($validated['category_id']);
        $this->authorize('view', $category);

        $file = $request->file('file');
        $user = auth()->user();

        if (($user->usedStorage() + $file->getSize()) > $user->storage_quota) {
            return back()->withErrors([
                'file' => 'Quota de stockage dépassé. Libère de l\'espace ou contacte l\'administrateur.',
            ]);
        }

        $storedPath = $file->store('documents/' . auth()->id(), 'local');

        auth()->user()->documents()->create([
            'category_id' => $category->id,
            'original_name' => $file->getClientOriginalName(),
            'stored_path' => $storedPath,
            'mime_type' => $file->getClientMimeType(),
            'size' => $file->getSize(),
        ]);

        return back()->with('success', 'Document ajouté avec succès.');
    }

    public function download(Document $document)
    {
        $this->authorize('view', $document);

        return response()->download(
            storage_path('app/private/' . $document->stored_path),
            $document->original_name
        );
    }

    public function preview(Document $document)
    {
        $this->authorize('view', $document);

        $allowedPreviewTypes = ['application/pdf', 'image/jpeg', 'image/png', 'image/jpg'];

        if (! in_array($document->mime_type, $allowedPreviewTypes)) {
            abort(415, 'Aperçu non disponible pour ce type de fichier.');
        }

        $path = storage_path('app/private/' . $document->stored_path);

        return response()->file($path, [
            'Content-Type' => $document->mime_type,
            'Content-Disposition' => 'inline; filename="' . $document->original_name . '"',
        ]);
    }

    public function destroy(Document $document)
    {
        $this->authorize('delete', $document);

        $document->delete();

        return back()->with('success', 'Document déplacé vers la corbeille.');
    }
}