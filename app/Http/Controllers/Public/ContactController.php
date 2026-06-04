<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    public function envoyer(Request $request)
    {
        $type = $request->input('type', 'contact');

        if ($type === 'newsletter') {
            $request->validate(['email' => 'required|email']);
            // TODO: ajouter à la liste newsletter
            Log::info('Newsletter inscription: ' . $request->email);
            return back()->with('success', 'Vous êtes maintenant abonné(e) à notre newsletter !');
        }

        $request->validate([
            'prenom'  => 'required|string|max:100',
            'nom'     => 'required|string|max:100',
            'email'   => 'required|email',
            'sujet'   => 'required|string',
            'message' => 'required|string|min:20',
        ]);

        // En production: Mail::to('contact@emploibougebenin.com')->send(new ContactMail($request->all()));
        Log::info('Message contact reçu', $request->only('prenom', 'nom', 'email', 'sujet'));

        return back()->with('success', 'Votre message a bien été envoyé. Nous vous répondrons sous 48h.');
    }
}
