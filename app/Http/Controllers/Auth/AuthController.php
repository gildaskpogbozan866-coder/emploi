<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\IncriptionRequest;
use App\Models\ParametreApp;
use App\Models\User;
use Spatie\Permission\Models\Role as SpatieRole;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Password;

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

    public function showCompteConfirme()
    {
        return view('auth.compte-confirme');
    }

    public function showMotDePasseOublie()
    {
        return view('auth.mot-de-passe-oublie');
    }

    public function showReinitialisation(Request $request, string $token)
    {
        return view('auth.reinitialiser-mot-de-passe', [
            'token' => $token,
            'email' => $request->query('email', ''),
        ]);
    }

    // ── Connexion ─────────────────────────────────────────
    public function connecter(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ], [
            'email.required'    => 'L\'adresse e-mail est requise.',
            'email.email'       => 'Veuillez entrer une adresse e-mail valide.',
            'password.required' => 'Le mot de passe est requis.',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! $user->actif) {
            return back()->withErrors(['credentials' => 'Email ou mot de passe incorrect.'])->withInput();
        }

        if (! Auth::attempt(
            ['email' => $request->email, 'password' => $request->password],
            $request->boolean('remember')
        )) {
            return back()->withErrors(['credentials' => 'Email ou mot de passe incorrect.'])->withInput();
        }

        $request->session()->regenerate();

        return redirect($this->dashboardUrl(Auth::user()));
    }

    // ── Inscription ───────────────────────────────────────
    public function inscrire(IncriptionRequest $request)
    {
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

        $role = $request->role;

        $user = User::create([
            'prenom'     => $request->prenom,
            'nom'        => $request->nom,
            'email'      => $request->email,
            'password'   => Hash::make($request->password),
            'tel'        => $request->tel,
            'pays'       => $request->pays,
            'role'       => $role,
            'entreprise' => $request->entreprise,
            'metier'     => $request->metier,
        ]);

        SpatieRole::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        $user->assignRole($role);

        Auth::login($user, remember: true);

        $user->sendEmailVerificationNotification();

        return redirect()->route('verification.notice');
    }

    // ── Mot de passe oublié ───────────────────────────────
    public function envoyerLienReinitialisation(Request $request)
    {
        $request->validate(['email' => 'required|email'], [
            'email.required' => 'L\'adresse e-mail est requise.',
            'email.email'    => 'Veuillez entrer une adresse e-mail valide.',
        ]);

        $status = Password::sendResetLink($request->only('email'));

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('success', 'Un lien de réinitialisation a été envoyé à votre adresse e-mail.');
        }

        return back()->withErrors(['email' => __($status)])->withInput();
    }

    // ── Réinitialisation du mot de passe ──────────────────
    public function reinitialiserMotDePasse(Request $request)
    {
        $request->validate([
            'token'                 => 'required',
            'email'                 => 'required|email',
            'password'              => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required',
        ], [
            'password.min'       => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.confirmed' => 'Les mots de passe ne correspondent pas.',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill(['password' => Hash::make($password)])->save();
                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('auth.connexion')
                ->with('success', 'Mot de passe réinitialisé avec succès. Vous pouvez vous connecter.');
        }

        return back()->withErrors(['email' => __($status)])->withInput();
    }

    // ── Changement de mot de passe (connecté) ────────────
    public function showChangerMotDePasse()
    {
        return view('auth.changer-mot-de-passe');
    }

    public function changerMotDePasse(Request $request)
    {
        $request->validate([
            'mot_de_passe_actuel'   => 'required|string',
            'password'              => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required',
        ], [
            'mot_de_passe_actuel.required' => 'Le mot de passe actuel est requis.',
            'password.min'                 => 'Le nouveau mot de passe doit contenir au moins 8 caractères.',
            'password.confirmed'           => 'Les nouveaux mots de passe ne correspondent pas.',
        ]);

        if (! Hash::check($request->mot_de_passe_actuel, Auth::user()->password)) {
            return back()->withErrors(['mot_de_passe_actuel' => 'Mot de passe actuel incorrect.']);
        }

        Auth::user()->update(['password' => Hash::make($request->password)]);

        return back()->with('mdp_success', 'Mot de passe modifié avec succès.');
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
    private function recaptchaActif(): bool
    {
        return !app()->isLocal()
            && ParametreApp::get('recaptcha_site_key') !== ''
            && ParametreApp::get('recaptcha_secret_key') !== '';
    }

    private function dashboardUrl(User $user): string
    {
        return match ($user->role) {
            'admin'      => route('admin.dashboard'),
            'recruteur'  => route('recruteur.dashboard'),
            'annonceur'  => route('annonceur.dashboard'),
            default      => route('candidat.dashboard'),
        };
    }

    private function redirectDashboard(User $user)
    {
        return redirect($this->dashboardUrl($user));
    }
}
