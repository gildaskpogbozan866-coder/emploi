<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\IncriptionRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AuthController extends Controller
{
    // ── Pages ────────────────────────────────────────────
    public function showConnexion()
    {
        if (Auth::check()) {
            return $this->redirectDashboard(Auth::user());
        }
        return view('auth.connexion');
    }

    public function showInscription()
    {
        if (Auth::check()) {
            return $this->redirectDashboard(Auth::user());
        }
        return view('auth.inscription');
    }

    public function showVerificationEmail()
    {
        return view('auth.verification-email');
    }

    public function showCompteConfirme()
    {
        return view('auth.compte-confirme');
    }


    // ── Inscription ───────────────────────────────────────
    public function inscrire(IncriptionRequest $request)
    {
        

        $code = $this->genererCode();

        DB::table('otp_codes')->where('email', $request->email)->delete();
        DB::table('otp_codes')->insert([
            'email'      => $request->email,
            'code'       => $code,
            'type'       => 'register',
            'payload'    => json_encode($request->all()),
            'expires_at' => Carbon::now()->addMinutes(10),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        session(['otp_email' => $request->email]);

        // En production: Mail::to($request->email)->send(new OtpMail($code));

        return redirect()->route('auth.verification-email')
            ->with('otp_debug', $code); // retirer en production
    }

    // ── Connexion (envoi OTP) ─────────────────────────────
    public function envoyerOtp(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return back()->withErrors(['email' => 'Aucun compte associé à cet e-mail.']);
        }

        if (!$user->actif) {
            return back()->withErrors(['email' => 'Ce compte a été suspendu.']);
        }

        $code = $this->genererCode();

        DB::table('otp_codes')->where('email', $request->email)->delete();
        DB::table('otp_codes')->insert([
            'email'      => $request->email,
            'code'       => $code,
            'type'       => 'login',
            'expires_at' => Carbon::now()->addMinutes(10),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        session(['otp_email' => $request->email]);

        // En production: Mail::to($request->email)->send(new OtpMail($code));

        return redirect()->route('auth.verification-email')
            ->with('otp_debug', $code);
    }

    // ── Vérification OTP ──────────────────────────────────
    public function verifierOtp(Request $request)
    {
        $request->validate(['code' => 'required|string|size:6']);

        $email = session('otp_email');

        if (!$email) {
            return redirect()->route('auth.connexion')->withErrors(['code' => 'Session expirée. Recommencez.']);
        }

        $record = DB::table('otp_codes')
            ->where('email', $email)
            ->where('code', $request->code)
            ->where('expires_at', '>', now())
            ->first();

        if (!$record) {
            return back()->withErrors(['code' => 'Code invalide ou expiré.']);
        }

        DB::table('otp_codes')->where('id', $record->id)->delete();

        if ($record->type === 'register') {
            $payload = json_decode($record->payload, true);
            $role    = $payload['role'] ?? 'candidat';

            $user = User::create([
                'prenom'            => $payload['prenom'],
                'nom'               => $payload['nom'],
                'email'             => $payload['email'],
                'tel'               => $payload['tel'] ?? null,
                'pays'              => $payload['pays'],
                'role'              => $role,
                'entreprise'        => $payload['entreprise'] ?? null,
                'metier'            => $payload['metier'] ?? null,
                'email_verified_at' => now(),
            ]);

            // Assigner le rôle Spatie automatiquement
            $user->assignRole($role);
        } else {
            $user = User::where('email', $email)->firstOrFail();
        }

        Auth::login($user, remember: true);
        session()->forget('otp_email');

        // Première inscription → page de bienvenue avec choix du dashboard.
        // Reconnexion → redirection directe vers le bon espace, sans friction.
        if ($record->type === 'register') {
            return redirect()->route('auth.compte-confirme');
        }

        return $this->redirectDashboard($user);
    }

    // ── Renvoi OTP ────────────────────────────────────────
    public function renvoyerOtp(Request $request)
    {
        $email = session('otp_email');

        if (!$email) {
            return redirect()->route('auth.connexion');
        }

        $existing = DB::table('otp_codes')->where('email', $email)->first();

        $code = $this->genererCode();

        DB::table('otp_codes')->where('email', $email)->delete();
        DB::table('otp_codes')->insert([
            'email'      => $email,
            'code'       => $code,
            'type'       => $existing->type    ?? 'login',
            'payload'    => $existing->payload ?? null,
            'expires_at' => Carbon::now()->addMinutes(10),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // En production: Mail::to($email)->send(new OtpMail($code));

        return redirect()->route('auth.verification-email')
            ->with('otp_debug', $code)
            ->with('resent', true);
    }

    // ── Déconnexion ───────────────────────────────────────
    public function deconnecter(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }

    // ── Helpers ───────────────────────────────────────────
    private function genererCode(): string
    {
        return str_pad((string) random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
    }

    private function redirectDashboard(User $user)
    {
        return match ($user->role) {
            'admin'     => redirect()->route('admin.dashboard'),
            'recruteur' => redirect()->route('recruteur.dashboard'),
            'talent'    => redirect()->route('talent.dashboard'),
            default     => redirect()->route('candidat.dashboard'),
        };
    }
}
