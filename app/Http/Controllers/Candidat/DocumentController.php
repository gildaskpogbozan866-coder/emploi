<?php

namespace App\Http\Controllers\Candidat;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\TypeDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'type_document_id' => ['required', 'exists:type_documents,id'],
            'nom'              => ['required', 'string', 'max:200'],
            'fichier'          => ['required', 'file', 'mimes:pdf,jpg,jpeg,png,webp,doc,docx', 'max:5120'],
        ]);

        $user = Auth::user();

        if ($user->documents()->count() >= 15) {
            return back()->withErrors(['fichier' => 'Limite de 15 documents atteinte.']);
        }

        $path = $request->file('fichier')->store('candidats/documents', 'public');

        $user->documents()->create([
            'type_document_id' => $request->type_document_id,
            'nom'              => $request->nom,
            'fichier'          => $path,
        ]);

        return back()->with('success', 'Document ajouté avec succès.');
    }

    public function edit(Document $document)
    {
        abort_unless($document->user_id === Auth::id(), 403);

        $typesDocuments = TypeDocument::actif()->get();
        return view('candidat.document-edit', compact('document', 'typesDocuments'));
    }

    public function update(Request $request, Document $document)
    {
        abort_unless($document->user_id === Auth::id(), 403);

        $request->validate([
            'type_document_id' => ['required', 'exists:type_documents,id'],
            'nom'              => ['required', 'string', 'max:200'],
            'pays'             => ['nullable', 'string', 'max:100'],
            'ville'            => ['nullable', 'string', 'max:100'],
            'competences'      => ['nullable', 'string'],
            'experience'       => ['nullable', 'string'],
            'formation'        => ['nullable', 'string'],
            'langues'          => ['nullable', 'string'],
            'fichier'          => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,webp,doc,docx', 'max:5120'],
        ]);

        $data = $request->only(['type_document_id', 'nom', 'pays', 'ville', 'competences', 'experience', 'formation', 'langues']);

        if ($request->hasFile('fichier')) {
            Storage::disk('public')->delete($document->fichier);
            $data['fichier'] = $request->file('fichier')->store('candidats/documents', 'public');
        }

        $document->update($data);

        return redirect()->route('candidat.cvs')->with('success', 'Document mis à jour.');
    }

    public function destroy(Document $document)
    {
        abort_unless($document->user_id === Auth::id(), 403);

        Storage::disk('public')->delete($document->fichier);
        $document->delete();

        return back()->with('success', 'Document supprimé.');
    }
}
