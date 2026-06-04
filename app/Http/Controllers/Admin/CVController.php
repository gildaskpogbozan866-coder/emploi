<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CV;
use Illuminate\Http\Request;

class CVController extends Controller
{
    public function index(Request $request)
    {
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

        $cvs = $query->paginate(20)->withQueryString();
        return view('admin.cvs.list', compact('cvs'));
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
