<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index(Request $request)
    {
        $conversations = Conversation::with(['user1','user2','dernierMessage'])
            ->orderByDesc('dernier_message_at')
            ->paginate(20);

        $stats = [
            'total_messages' => Message::count(),
            'non_lus'        => Message::where('lu', false)->count(),
        ];

        return view('admin.messagerie', compact('conversations', 'stats'));
    }
}
