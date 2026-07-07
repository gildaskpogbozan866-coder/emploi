<?php

namespace App\Http\Controllers\Candidat;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\TypeDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class DocumentController extends Controller
{
    /**
     * Un "Curriculum Vitae" ne doit jamais exister comme Document — il doit
     * passer par CVController pour devenir un vrai modèle CV (visibilité,
     * champs obligatoires, éligibilité CVthèque). Sinon on se retrouve avec
     * deux entrées qui se ressemblent dans l'espace candidat sans que l'une
     * soit un vrai CV utilisable.
     */
    private function cvTypeId(): ?int
    {
        return TypeDocument::where('nom', 'like', '%Curriculum Vitae%')->value('id');
    }

    public function store(Request $request)
    {
        $request->validate([
            'type_document_id' => ['required', 'exists:type_documents,id', Rule::notIn([$this->cvTypeId()])],
            'fichier'          => ['required', 'file', 'mimes:pdf,jpg,jpeg,png,webp,doc,docx', 'max:5120'],
        ], [
            'type_document_id.not_in' => 'Pour déposer un CV, utilisez la page "Déposer un CV".',
        ]);

        $user = Auth::user();

        if ($user->documents()->count() >= 15) {
            return back()->withErrors(['fichier' => 'Limite de 15 documents atteinte.']);
        }

        $type = TypeDocument::find($request->type_document_id);
        $nom  = ($type ? $type->nom : 'Document') . ' — ' . now()->format('d/m/Y');

        $path = $request->file('fichier')->store('candidats/documents', 'public');

        $user->documents()->create([
            'type_document_id' => $request->type_document_id,
            'nom'              => $nom,
            'fichier'          => $path,
        ]);

        return back()->with('success', 'Document ajouté avec succès.');
    }

    public function edit(Document $document)
    {
        abort_unless((int) $document->user_id === (int) Auth::id(), 403);

        $typesDocuments = TypeDocument::actif()->get();
        return view('candidat.document-edit', compact('document', 'typesDocuments'));
    }

    public function update(Request $request, Document $document)
    {
        abort_unless((int) $document->user_id === (int) Auth::id(), 403);

        $request->validate([
            'type_document_id' => ['required', 'exists:type_documents,id', Rule::notIn([$this->cvTypeId()])],
            'nom'              => ['required', 'string', 'max:200'],
            'pays'             => ['nullable', 'string', 'max:100'],
            'ville'            => ['nullable', 'string', 'max:100'],
            'competences'      => ['nullable', 'string'],
            'experience'       => ['nullable', 'string'],
            'formation'        => ['nullable', 'string'],
            'langues'          => ['nullable', 'string'],
            'fichier'          => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,webp,doc,docx', 'max:5120'],
        ], [
            'type_document_id.not_in' => 'Pour déposer un CV, utilisez la page "Déposer un CV".',
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
        abort_unless((int) $document->user_id === (int) Auth::id(), 403);

        Storage::disk('public')->delete($document->fichier);
        $document->delete();

        return back()->with('success', 'Document supprimé.');
    }
}
