<?php

namespace App\Http\Controllers\Concerns;

use App\Models\ParametreApp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

trait VerifiesRecaptcha
{
    private function recaptchaActif(): bool
    {
        return !app()->isLocal()
            && ParametreApp::get('recaptcha_site_key') !== ''
            && ParametreApp::get('recaptcha_secret_key') !== '';
    }

    private function recaptchaValide(Request $request): bool
    {
        if (!$this->recaptchaActif()) {
            return true;
        }

        $verify = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret'   => ParametreApp::get('recaptcha_secret_key'),
            'response' => $request->input('g-recaptcha-response'),
            'remoteip' => $request->ip(),
        ]);

        return (bool) $verify->json('success');
    }
}
