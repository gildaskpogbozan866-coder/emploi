<?php

namespace App\Http\Controllers\Recruteur;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\User;
use App\Services\MessageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function __construct(private readonly MessageService $service) {}

    public function index()
    {
        $conversations = $this->service->conversations(Auth::id());

        return view('recruteur.messagerie', compact('conversations'));
    }

    public function show(Conversation $conversation)
    {
        $this->autoriser($conversation);

        $this->service->marquerLus($conversation, Auth::id());

        $messages = $conversation->messages()->with('expediteur')->oldest()->get();
        $autre    = $conversation->autreParticipant(Auth::id());

        return view('recruteur.messagerie-detail', compact('conversation', 'messages', 'autre'));
    }

    public function store(Request $request, Conversation $conversation)
    {
        $this->autoriser($conversation);

        $request->validate([
            'contenu' => 'nullable|string|max:2000',
            'fichier' => 'nullable|file|mimes:pdf,jpg,jpeg,png,webp,doc,docx|max:5120',
        ]);

        abort_if(!$request->filled('contenu') && !$request->hasFile('fichier'), 422);

        $message = $this->service->envoyer(
            $conversation,
            Auth::id(),
            $request->contenu,
            $request->file('fichier')
        );

        $this->service->notifier($conversation, $message, Auth::id());

        if ($request->expectsJson()) {
            return response()->json(['message' => $message->load('expediteur')]);
        }

        return back()->with('success', 'Message envoyé.');
    }

    public function initier(User $user)
    {
        $conversation = $this->service->initier(Auth::id(), $user->id);

        return redirect()->route('recruteur.messagerie.show', $conversation);
    }

    public function rafraichir(Request $request, Conversation $conversation)
    {
        $this->autoriser($conversation);

        $depuis   = $request->integer('depuis', 0);
        $messages = $conversation->messages()
            ->with('expediteur')
            ->where('id', '>', $depuis)
            ->oldest()
            ->get();

        $this->service->marquerLus($conversation, Auth::id());

        return response()->json(['messages' => $messages]);
    }

    public function archiver(Conversation $conversation)
    {
        $this->autoriser($conversation);

        $this->service->archiver($conversation, Auth::id());

        return redirect()->route('recruteur.messagerie')->with('success', 'Conversation archivée.');
    }

    private function autoriser(Conversation $conversation): void
    {
        abort_if(
            $conversation->user1_id !== Auth::id() && $conversation->user2_id !== Auth::id(),
            403
        );
    }
}
