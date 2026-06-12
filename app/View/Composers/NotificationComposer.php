<?php

namespace App\View\Composers;

use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NotificationComposer
{
    public function compose(View $view): void
    {
        if (!Auth::check()) {
            $view->with([
                'notifNonLues'   => 0,
                'dernierNotifs'  => collect(),
                'messagesNonLus' => 0,
            ]);
            return;
        }

        $userId = Auth::id();

        $messagesNonLus = Message::whereHas('conversation', fn($q) =>
                $q->where('user1_id', $userId)->orWhere('user2_id', $userId)
            )
            ->where('expediteur_id', '!=', $userId)
            ->where('lu', false)
            ->count();

        $view->with([
            'notifNonLues'   => Auth::user()->notifications()->where('lu', false)->count(),
            'dernierNotifs'  => Auth::user()->notifications()->latest()->limit(5)->get(),
            'messagesNonLus' => $messagesNonLus,
        ]);
    }
}
