<?php

namespace App\Http\Controllers\Annonceur;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Auth::user()->notifications()->latest()->paginate(20);
        Auth::user()->notifications()->where('lu', false)->update(['lu' => true]);
        return view('annonceur.notifications', compact('notifications'));
    }
}
