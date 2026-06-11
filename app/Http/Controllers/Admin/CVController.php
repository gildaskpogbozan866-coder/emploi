<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CV;
use App\Models\Document;
use App\Models\TypeDocument;
use Illuminate\Http\Request;

class CVController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->input('type', 'cvs');

        if ($type === 'documents') {
            $query = Document::with(['user', 'type'])->latest();

            if ($request->filled('q')) {
                $q = $request->q;
                $query->where(function ($sq) use ($q) {
                    $sq->where('nom', 'like', "%$q%")
                       ->orWhere('pays', 'like', "%$q%")
                       ->orWhereHas('user', fn($u) => $u->where('prenom', 'like', "%$q%")->orWhere('nom', 'like', "%$q%"));
                });
            }

            if ($request->filled('type_doc')) {
                $query->where('type_document_id', $request->type_doc);
            }

            $items      = $query->paginate(20)->withQueryString();
            $typeDocs   = TypeDocument::orderBy('nom')->get();

            return view('admin.cvs.list', compact('type', 'items', 'typeDocs'));
        }

        // CVs
        $query = CV::with('candidat')->latest();

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sq) use ($q) {
                $sq->where('titre_poste', 'like', "%$q%")
                   ->orWhere('pays', 'like', "%$q%");
            });
        }

        if ($request->filled('plan')) {
            $query->where('plan', $request->plan);
        }

        $items    = $query->paginate(20)->withQueryString();
        $typeDocs = collect();

        return view('admin.cvs.list', compact('type', 'items', 'typeDocs'));
    }

    public function show(CV $cv)
    {
        $cv->load('candidat');
        return view('admin.cvs.detail', compact('cv'));
    }

    public function destroy(CV $cv)
    {
        $cv->delete();
        return redirect()->route('admin.cvs.list')->with('success', 'CV supprimé.');
    }
}
