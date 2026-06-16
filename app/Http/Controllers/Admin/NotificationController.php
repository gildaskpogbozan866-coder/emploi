<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Auth::user()->notifications()->latest()->paginate(30);
        Auth::user()->notifications()->where('lu', false)->update(['lu' => true]);
        return view('admin.notifications', compact('notifications'));
    }
}
