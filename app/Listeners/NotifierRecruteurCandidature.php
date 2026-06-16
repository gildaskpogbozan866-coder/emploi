<?php

namespace App\Listeners;

use App\Events\CandidatureDeposee;
use App\Mail\NouvellesCandidatureMail;
use App\Models\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class NotifierRecruteurCandidature implements ShouldQueue
{
    public string $queue = 'default';

    public function handle(CandidatureDeposee $event): void
    {
        $candidature = $event->candidature;
        $candidature->loadMissing(['offre.recruteur', 'candidat']);

        $recruteur = $candidature->offre?->recruteur;
        if (! $recruteur?->email) {
            return;
        }

        // Email au recruteur
        try {
            Mail::to($recruteur->email)
                ->queue(new NouvellesCandidatureMail($candidature));
        } catch (\Throwable $e) {
            Log::warning('Email recruteur candidature non envoyé', [
                'candidature_id' => $candidature->id,
                'error'          => $e->getMessage(),
            ]);
        }

        // Notification in-app au recruteur
        Notification::create([
            'user_id' => $recruteur->id,
            'type'    => 'candidature',
            'titre'   => 'Nouvelle candidature reçue',
            'contenu' => ($candidature->candidat?->nom_complet ?? 'Un candidat')
                . ' a postulé pour « ' . $candidature->offre->titre . ' ».',
            'lien'    => route('recruteur.candidatures.show', $candidature),
        ]);
    }

    public function failed(CandidatureDeposee $event, \Throwable $exception): void
    {
        Log::error('Listener NotifierRecruteurCandidature a échoué', [
            'candidature_id' => $event->candidature->id,
            'error'          => $exception->getMessage(),
        ]);
    }
}
