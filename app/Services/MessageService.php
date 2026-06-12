<?php

namespace App\Services;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class MessageService
{
    /**
     * Trouve ou crée une conversation entre deux utilisateurs.
     * Normalise toujours : user1_id = min(id), user2_id = max(id)
     * afin que la contrainte unique(user1_id, user2_id) soit symétrique.
     */
    public function initier(int $fromId, int $toId): Conversation
    {
        [$u1, $u2] = [min($fromId, $toId), max($fromId, $toId)];

        return Conversation::firstOrCreate(
            ['user1_id' => $u1, 'user2_id' => $u2],
            ['dernier_message_at' => now()]
        );
    }

    /**
     * Envoie un message dans une conversation (texte et/ou fichier).
     * Désarchive automatiquement la conversation pour le destinataire.
     */
    public function envoyer(
        Conversation $conv,
        int $expediteurId,
        ?string $contenu,
        ?UploadedFile $fichier = null
    ): Message {
        $fichierPath = null;
        $mimeType    = null;

        if ($fichier) {
            $fichierPath = $fichier->store('messages', 'public');
            $mimeType    = $fichier->getMimeType();
        }

        $message = Message::create([
            'conversation_id' => $conv->id,
            'expediteur_id'   => $expediteurId,
            'contenu'         => $contenu ?: null,
            'fichier'         => $fichierPath,
            'mime_type'       => $mimeType,
        ]);

        $conv->update(['dernier_message_at' => now()]);

        // Désarchiver pour le destinataire si besoin
        $this->desarchiverPourDestinataire($conv, $expediteurId);

        return $message;
    }

    /**
     * Crée une notification in-app pour le destinataire du message.
     */
    public function notifier(Conversation $conv, Message $message, int $expediteurId): void
    {
        $expediteur   = User::find($expediteurId);
        $destinataire = $conv->autreParticipant($expediteurId);

        $lien = route($this->routeConversation($destinataire), $conv);

        Notification::create([
            'user_id' => $destinataire->id,
            'type'    => 'message',
            'titre'   => 'Nouveau message de ' . $expediteur->nom_complet,
            'contenu' => $message->contenu
                ? Str::limit($message->contenu, 80)
                : 'Pièce jointe reçue',
            'lien'    => $lien,
        ]);
    }

    /**
     * Marque comme lus tous les messages reçus dans une conversation.
     */
    public function marquerLus(Conversation $conv, int $userId): void
    {
        $conv->messages()
            ->where('expediteur_id', '!=', $userId)
            ->where('lu', false)
            ->update(['lu' => true]);
    }

    /**
     * Archive la conversation pour un utilisateur donné.
     */
    public function archiver(Conversation $conv, int $userId): void
    {
        $champ = $conv->user1_id === $userId ? 'archived_by_user1' : 'archived_by_user2';
        $conv->update([$champ => true]);
    }

    /**
     * Retourne les conversations actives (non archivées) d'un utilisateur,
     * avec le comptage des messages non-lus.
     */
    public function conversations(int $userId): Collection
    {
        return Conversation::where(function ($q) use ($userId) {
                $q->where('user1_id', $userId)->where('archived_by_user1', false);
            })
            ->orWhere(function ($q) use ($userId) {
                $q->where('user2_id', $userId)->where('archived_by_user2', false);
            })
            ->with(['user1', 'user2', 'dernierMessage'])
            ->withCount(['messages as non_lus' => fn($q) => $q
                ->where('expediteur_id', '!=', $userId)
                ->where('lu', false)])
            ->orderByDesc('dernier_message_at')
            ->get();
    }

    // ── Helpers privés ────────────────────────────────────────

    private function desarchiverPourDestinataire(Conversation $conv, int $expediteurId): void
    {
        $destId = $conv->autreParticipant($expediteurId)->id;
        $champ  = $conv->user1_id === $destId ? 'archived_by_user1' : 'archived_by_user2';

        if ($conv->$champ) {
            $conv->update([$champ => false]);
        }
    }

    private function routeConversation(User $user): string
    {
        return match($user->role) {
            'recruteur' => 'recruteur.messagerie.show',
            'admin'     => 'admin.messagerie.show',
            default     => 'candidat.messagerie.show',
        };
    }
}
