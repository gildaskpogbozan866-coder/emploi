<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\Notification;
use App\Models\ParametreApp;
use App\Models\User;
use App\Notifications\NouveauMessageContactNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    public function envoyer(Request $request)
    {
        $type = $request->input('type', 'contact');

        if ($type === 'newsletter') {
            $request->validate(['email' => 'required|email']);
            Log::info('Newsletter inscription: ' . $request->email);
            return back()->with('success', 'Vous êtes maintenant abonné(e) à notre newsletter !');
        }

        if ($this->recaptchaActif()) {
            $verify = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret'   => ParametreApp::get('recaptcha_secret_key'),
                'response' => $request->input('g-recaptcha-response'),
                'remoteip' => $request->ip(),
            ]);
            if (!$verify->json('success')) {
                return back()->withErrors(['recaptcha' => 'Vérification anti-robot échouée. Veuillez cocher la case et réessayer.'])->withInput();
            }
        }

        $validated = $request->validate([
            'prenom'  => 'required|string|max:100',
            'nom'     => 'nullable|string|max:100',
            'email'   => 'required|email|max:191',
            'sujet'   => 'required|string|in:question,partenariat,signalement,technique,autre',
            'message' => 'required|string|min:20|max:3000',
        ]);

        $contactMessage = ContactMessage::create($validated);

        $admins = User::where('role', 'admin')->get();

        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'type'    => 'contact',
                'titre'   => 'Nouveau message de contact',
                'contenu' => "{$validated['prenom']} {$validated['nom']} — {$contactMessage->sujet_label}",
                'lien'    => route('admin.contact-messages.show', $contactMessage),
            ]);

            $admin->notify(new NouveauMessageContactNotification($contactMessage));
        }

        return back()->with('contact_sent', [
            'prenom' => $validated['prenom'],
            'email'  => $validated['email'],
            'sujet'  => $validated['sujet'],
        ]);
    }

    private function recaptchaActif(): bool
    {
        return !app()->isLocal()
            && ParametreApp::get('recaptcha_site_key') !== ''
            && ParametreApp::get('recaptcha_secret_key') !== '';
    }
}
