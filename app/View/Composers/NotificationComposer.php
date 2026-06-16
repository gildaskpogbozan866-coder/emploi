<?php

namespace App\View\Composers;

use App\Models\Candidature;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NotificationComposer
{
    public function compose(View $view): void
    {
        if (!Auth::check()) {
            $view->with([
                'notifNonLues'          => 0,
                'dernierNotifs'         => collect(),
                'messagesNonLus'        => 0,
                'candidaturesNouvelles' => 0,
            ]);
            return;
        }

        $userId = Auth::id();
        $user   = Auth::user();

        $messagesNonLus = Message::whereHas('conversation', fn($q) =>
                $q->where('user1_id', $userId)->orWhere('user2_id', $userId)
            )
            ->where('expediteur_id', '!=', $userId)
            ->where('lu', false)
            ->count();

        $candidaturesNouvelles = 0;
        if ($user->hasRole('recruteur')) {
            $candidaturesNouvelles = Candidature::whereHas('offre', fn($q) =>
                    $q->where('recruteur_id', $userId)
                )
                ->where('statut', 'envoyee')
                ->count();
        }

        $view->with([
            'notifNonLues'          => $user->notifications()->where('lu', false)->count(),
            'dernierNotifs'         => $user->notifications()->latest()->limit(5)->get(),
            'messagesNonLus'        => $messagesNonLus,
            'candidaturesNouvelles' => $candidaturesNouvelles,
        ]);
    }
}
