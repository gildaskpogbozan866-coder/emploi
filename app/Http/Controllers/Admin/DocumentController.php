<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\TypeDocument;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        $query = Document::with(['user', 'type'])->latest();

        if ($q = $request->input('q')) {
            $query->whereHas('user', fn($u) => $u->where('nom', 'like', "%$q%")
                ->orWhere('prenom', 'like', "%$q%")
                ->orWhere('email', 'like', "%$q%"))
                ->orWhere('nom', 'like', "%$q%")
                ->orWhere('pays', 'like', "%$q%");
        }

        if ($type = $request->input('type')) {
            $query->where('type_document_id', $type);
        }

        $documents = $query->paginate(30)->withQueryString();
        $types     = TypeDocument::orderBy('nom')->get();

        return view('admin.documents.list', compact('documents', 'types'));
    }

    public function show(Document $document)
    {
        $document->load(['user', 'type']);
        return view('admin.documents.detail', compact('document'));
    }

    public function destroy(Document $document)
    {
        \Illuminate\Support\Facades\Storage::disk('public')->delete($document->fichier);
        $document->delete();
        return redirect()->route('admin.documents.list')->with('success', 'Document supprimé.');
    }
}
