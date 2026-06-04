<?php

namespace App\Http\Controllers\Candidat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Auth::user()->notifications()->latest()->paginate(20);
        Auth::user()->notifications()->where('lu', false)->update(['lu' => true]);
        return view('candidat.notifications', compact('notifications'));
    }

    public function marquerLues()
    {
        Auth::user()->notifications()->where('lu', false)->update(['lu' => true]);
        return back()->with('success', 'Toutes les notifications marquées comme lues.');
    }
}
