<?php

namespace App\Http\Controllers\Talent;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function index()
    {
        $conversations = Conversation::where('user1_id', Auth::id())
            ->orWhere('user2_id', Auth::id())
            ->with(['user1', 'user2', 'dernierMessage'])
            ->orderByDesc('dernier_message_at')
            ->get();

        return view('talent.messagerie', compact('conversations'));
    }

    public function show(Conversation $conversation)
    {
        abort_if(
            $conversation->user1_id !== Auth::id() && $conversation->user2_id !== Auth::id(),
            403
        );
        $messages = $conversation->messages()->with('expediteur')->oldest()->get();
        $autre    = $conversation->autreParticipant(Auth::id());

        $conversation->messages()
            ->where('expediteur_id', '!=', Auth::id())
            ->where('lu', false)
            ->update(['lu' => true]);

        return view('talent.messagerie-detail', compact('conversation', 'messages', 'autre'));
    }

    public function store(Request $request, Conversation $conversation)
    {
        abort_if(
            $conversation->user1_id !== Auth::id() && $conversation->user2_id !== Auth::id(),
            403
        );
        $request->validate(['contenu' => 'required|string|max:2000']);

        Message::create([
            'conversation_id' => $conversation->id,
            'expediteur_id'   => Auth::id(),
            'contenu'         => $request->contenu,
        ]);
        $conversation->update(['dernier_message_at' => now()]);

        return back()->with('success', 'Message envoyé.');
    }
}
