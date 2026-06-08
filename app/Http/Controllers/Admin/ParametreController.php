<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ParametreApp;
use Illuminate\Http\Request;

class ParametreController extends Controller
{
    public function index()
    {
        $parametres = [
            'site_nom'                  => config('app.name'),
            'site_email'                => config('mail.from.address'),
            'maintenance_mode'          => app()->isDownForMaintenance(),
            'admin_notification_email'  => ParametreApp::get('admin_notification_email', config('emploi.admin_notification_email')),
        ];

        return view('admin.parametres', compact('parametres'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'admin_notification_email' => 'nullable|email|max:255',
        ], [
            'admin_notification_email.email' => 'Veuillez entrer une adresse e-mail valide.',
        ]);

        ParametreApp::set('admin_notification_email', $request->admin_notification_email ?? '');

        return back()->with('success', 'Paramètres mis à jour.');
    }
}
