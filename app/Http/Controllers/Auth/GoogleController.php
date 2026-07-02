<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Spatie\Permission\Models\Role as SpatieRole;

class GoogleController extends Controller
{
    public function redirect(Request $request)
    {
        $role = $request->input('role');

        if ($role && in_array($role, ['candidat', 'recruteur', 'annonceur'])) {
            // Encode role in OAuth state — Google echoes it back, no session/cookie needed
            $customState = $role . '|' . Str::random(40);

            $redirectResponse = Socialite::driver('google')
                ->with(['state' => $customState])
                ->redirect();

            // Override the auto-generated Socialite state in session with our custom one
            $request->session()->put('state', $customState);

            return $redirectResponse;
        }

        return Socialite::driver('google')->redirect();
    }

    public function callback(Request $request)
    {
        // Extract role from state parameter before Socialite consumes it
        $stateParam    = $request->input('state', '');
        $parts         = explode('|', $stateParam, 2);
        $roleFromState = (count($parts) === 2 && in_array($parts[0], ['candidat', 'recruteur', 'annonceur']))
            ? $parts[0]
            : null;

        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('auth.connexion')
                ->withErrors(['credentials' => 'La connexion avec Google a échoué. Veuillez réessayer.']);
        }

        // Existing user with Google ID
        $user = User::where('google_id', $googleUser->getId())->first();
        if ($user) {
            if (!$user->actif) {
                return redirect()->route('auth.connexion')
                    ->withErrors(['credentials' => 'Votre compte est désactivé.']);
            }
            Auth::login($user, remember: true);
            return redirect($this->dashboardUrl($user));
        }

        // Existing user with same email but no Google ID
        $existingByEmail = User::where('email', $googleUser->getEmail())->first();
        if ($existingByEmail) {
            if (!$existingByEmail->actif) {
                return redirect()->route('auth.connexion')
                    ->withErrors(['credentials' => 'Votre compte est désactivé.']);
            }
            $existingByEmail->update(['google_id' => $googleUser->getId()]);
            if (!$existingByEmail->hasVerifiedEmail()) {
                $existingByEmail->markEmailAsVerified();
            }
            Auth::login($existingByEmail, remember: true);
            return redirect($this->dashboardUrl($existingByEmail));
        }

        // New user
        $raw    = $googleUser->getRaw();
        $prenom = $raw['given_name']  ?? (explode(' ', $googleUser->getName() ?? '')[0] ?? '');
        $nom    = $raw['family_name'] ?? (explode(' ', $googleUser->getName() ?? '')[1] ?? '');

        if ($roleFromState) {
            $user = User::create([
                'prenom'            => $prenom,
                'nom'               => $nom,
                'email'             => $googleUser->getEmail(),
                'google_id'         => $googleUser->getId(),
                'avatar'            => $googleUser->getAvatar(),
                'password'          => null,
                'role'              => $roleFromState,
                'pays'              => 'Bénin',
                'actif'             => true,
                'email_verified_at' => now(),
            ]);
            SpatieRole::firstOrCreate(['name' => $roleFromState, 'guard_name' => 'web']);
            $user->assignRole($roleFromState);
            Auth::login($user, remember: true);
            return redirect($this->dashboardUrl($user));
        }

        // No role in state — ask for role selection
        $request->session()->put('google_user', [
            'google_id' => $googleUser->getId(),
            'prenom'    => $prenom,
            'nom'       => $nom,
            'email'     => $googleUser->getEmail(),
            'avatar'    => $googleUser->getAvatar(),
        ]);

        return redirect()->route('auth.google.role');
    }

    public function showRoleSelect(Request $request)
    {
        if (!$request->session()->has('google_user')) {
            return redirect()->route('auth.inscription');
        }

        $googleData = $request->session()->get('google_user');
        return view('auth.google-role', compact('googleData'));
    }

    public function createWithRole(Request $request)
    {
        if (!$request->session()->has('google_user')) {
            return redirect()->route('auth.inscription');
        }

        $request->validate(['role' => 'required|in:candidat,recruteur,annonceur'], [
            'role.required' => 'Veuillez choisir votre type de compte.',
            'role.in'       => 'Type de compte invalide.',
        ]);

        $googleData = $request->session()->pull('google_user');
        $role       = $request->role;

        $user = User::create([
            'prenom'             => $googleData['prenom'],
            'nom'                => $googleData['nom'],
            'email'              => $googleData['email'],
            'google_id'          => $googleData['google_id'],
            'avatar'             => $googleData['avatar'],
            'password'           => null,
            'role'               => $role,
            'pays'               => 'Bénin',
            'actif'              => true,
            'email_verified_at'  => now(),
        ]);

        SpatieRole::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        $user->assignRole($role);

        Auth::login($user, remember: true);

        return redirect($this->dashboardUrl($user));
    }

    private function dashboardUrl(User $user): string
    {
        return match ($user->role) {
            'admin'     => route('admin.dashboard'),
            'recruteur' => route('recruteur.dashboard'),
            'annonceur' => route('annonceur.dashboard'),
            default     => route('candidat.dashboard'),
        };
    }
}
