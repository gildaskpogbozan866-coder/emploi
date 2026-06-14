<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ParametreApp;
use App\Models\RecruteurDocumentType;
use Illuminate\Http\Request;

class RecruteurDocumentTypeController extends Controller
{
    public function index()
    {
        $types            = RecruteurDocumentType::orderBy('ordre')->orderBy('id')->get();
        $validationActive = (bool) ParametreApp::get('recruteur_validation_docs', '0');

        return view('admin.document-types.index', compact('types', 'validationActive'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nom'             => 'required|string|max:150',
            'description'     => 'nullable|string|max:500',
            'accepte_fichier' => 'boolean',
            'accepte_texte'   => 'boolean',
            'est_requis'      => 'boolean',
            'ordre'           => 'integer|min:0|max:999',
        ]);

        $data['accepte_fichier'] = $request->boolean('accepte_fichier');
        $data['accepte_texte']   = $request->boolean('accepte_texte');
        $data['est_requis']      = $request->boolean('est_requis');
        $data['est_actif']       = true;

        if (!$data['accepte_fichier'] && !$data['accepte_texte']) {
            return back()->withErrors(['accepte_fichier' => 'Activez au moins un mode de saisie (fichier ou texte).'])->withInput();
        }

        RecruteurDocumentType::create($data);

        return back()->with('success', 'Type de document ajouté.');
    }

    public function update(Request $request, RecruteurDocumentType $type)
    {
        $data = $request->validate([
            'nom'             => 'required|string|max:150',
            'description'     => 'nullable|string|max:500',
            'accepte_fichier' => 'boolean',
            'accepte_texte'   => 'boolean',
            'est_requis'      => 'boolean',
            'est_actif'       => 'boolean',
            'ordre'           => 'integer|min:0|max:999',
        ]);

        $data['accepte_fichier'] = $request->boolean('accepte_fichier');
        $data['accepte_texte']   = $request->boolean('accepte_texte');
        $data['est_requis']      = $request->boolean('est_requis');
        $data['est_actif']       = $request->boolean('est_actif');

        if (!$data['accepte_fichier'] && !$data['accepte_texte']) {
            return back()->withErrors(['accepte_fichier' => 'Activez au moins un mode de saisie.'])->withInput();
        }

        $type->update($data);

        return back()->with('success', 'Type de document mis à jour.');
    }

    public function destroy(RecruteurDocumentType $type)
    {
        $type->delete();
        return back()->with('success', 'Type de document supprimé.');
    }

    public function toggle(Request $request)
    {
        $actif = $request->boolean('validation_requise') ? '1' : '0';

        if ($actif === '1' && !RecruteurDocumentType::where('est_actif', true)->exists()) {
            return back()->with('error', 'Ajoutez au moins un type de document actif avant d\'activer la validation.');
        }

        ParametreApp::set('recruteur_validation_docs', $actif);

        $msg = $actif === '1'
            ? 'Validation des documents activée. Les nouveaux recruteurs devront soumettre un dossier.'
            : 'Validation des documents désactivée. Les recruteurs accèdent directement après inscription.';

        return back()->with('success', $msg);
    }
}
