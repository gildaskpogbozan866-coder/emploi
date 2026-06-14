<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;

class ContactMessageController extends Controller
{
    public function index()
    {
        $messages = ContactMessage::latest()->paginate(20);
        return view('admin.contact-messages.index', compact('messages'));
    }

    public function show(ContactMessage $message)
    {
        $message->marquerLu();
        return view('admin.contact-messages.show', compact('message'));
    }

    public function destroy(ContactMessage $message)
    {
        $message->delete();
        return redirect()->route('admin.contact-messages.index')
            ->with('success', 'Message supprimé.');
    }
}
