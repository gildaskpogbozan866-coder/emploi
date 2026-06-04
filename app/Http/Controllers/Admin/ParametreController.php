<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ParametreController extends Controller
{
    public function index()
    {
        $parametres = [
            'site_nom'          => config('app.name'),
            'site_email'        => config('mail.from.address'),
            'maintenance_mode'  => app()->isDownForMaintenance(),
        ];

        return view('admin.parametres', compact('parametres'));
    }

    public function update(Request $request)
    {
        // En production : sauvegarder en BDD ou .env
        return back()->with('success', 'Paramètres mis à jour.');
    }
}
