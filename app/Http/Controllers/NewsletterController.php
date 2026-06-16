<?php

namespace App\Http\Controllers;

use App\Mail\NewsletterConfirmationMail;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class NewsletterController extends Controller
{
    public function abonner(Request $request)
    {
        $request->validate([
            'email'  => 'required|email|max:255',
            'prenom' => 'nullable|string|max:100',
        ], [
            'email.required' => 'Veuillez entrer votre adresse e-mail.',
            'email.email'    => 'Adresse e-mail invalide.',
        ]);

        $existing = NewsletterSubscriber::where('email', $request->email)->first();

        if ($existing) {
            if ($existing->statut === 'inactif') {
                $existing->update(['statut' => 'actif', 'prenom' => $request->prenom ?? $existing->prenom]);
                return back()->with('newsletter_success', 'Votre abonnement a bien été réactivé !');
            }
            return back()->with('newsletter_info', 'Cette adresse e-mail est déjà inscrite à notre newsletter.');
        }

        $subscriber = NewsletterSubscriber::create([
            'email'  => $request->email,
            'prenom' => $request->prenom,
            'source' => $request->get('source', 'footer'),
        ]);

        try {
            Mail::to($subscriber->email)->send(new NewsletterConfirmationMail($subscriber));
        } catch (\Throwable $e) {
            Log::warning('Email confirmation newsletter non envoyé', ['error' => $e->getMessage()]);
        }

        return back()->with('newsletter_success', "Merci ! Vous êtes maintenant inscrit(e) à notre newsletter.");
    }

    public function desabonner(string $token)
    {
        $subscriber = NewsletterSubscriber::where('token', $token)->first();

        if (!$subscriber) {
            return redirect('/')->with('error', 'Lien de désabonnement invalide ou expiré.');
        }

        if ($subscriber->statut === 'inactif') {
            return redirect('/')->with('info', 'Vous êtes déjà désabonné(e) de notre newsletter.');
        }

        $subscriber->update(['statut' => 'inactif']);

        return view('public.newsletter-desabonnement', compact('subscriber'));
    }
}
