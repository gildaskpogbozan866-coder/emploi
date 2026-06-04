<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\TalentProfil;
use Illuminate\Http\Request;

class TalentController extends Controller
{
    public function index(Request $request)
    {
        $query = TalentProfil::visible()->with('user')->latest();

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sq) use ($q) {
                $sq->where('metier', 'like', "%$q%")
                   ->orWhere('competences', 'like', "%$q%")
                   ->orWhere('specialite', 'like', "%$q%");
            });
        }

        if ($request->filled('pays')) {
            $query->where('pays', $request->pays);
        }

        $profils = $query->paginate(12)->withQueryString();

        return view('public.talent.list', compact('profils'));
    }

    public function detail(TalentProfil $profil)
    {
        $profil->increment('vues');
        $profil->load('user');
        return view('public.talent.detail', compact('profil'));
    }

    public function tarif()
    {
        return view('public.talent.tarif');
    }

    public function achat(TalentProfil $profil)
    {
        return view('public.talent.achat', compact('profil'));
    }
}
