@php $user = auth()->user(); @endphp

{{-- Alerte suspension (visible même si la session reste ouverte après suspension) --}}
@unless($user->actif)
<div style="background:#fef2f2;border:1px solid #fca5a5;border-radius:10px;padding:16px 20px;max-width:560px;margin-bottom:18px;display:flex;gap:14px;align-items:flex-start">
  <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="#dc2626" stroke-width="2" style="flex-shrink:0;margin-top:1px"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
  <div>
    <div style="font-weight:700;color:#991b1b;font-size:.92rem;margin-bottom:4px">Compte suspendu</div>
    <div style="font-size:.84rem;color:#7f1d1d;line-height:1.55">Votre compte a été suspendu par un administrateur. Vous ne pouvez plus accéder à certaines fonctionnalités. Pour toute contestation, contactez-nous à <a href="mailto:support@emploibouge.bj" style="color:#991b1b;font-weight:600">support@emploibouge.bj</a>.</div>
  </div>
</div>
@endunless

{{-- Statut du compte --}}
<div style="background:#fff;border:1px solid #e2e8f0;border-radius:10px;padding:18px 20px;max-width:560px;margin-bottom:18px">
  <div style="font-size:.82rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8;margin-bottom:14px">Statut du compte</div>
  <div style="display:flex;flex-direction:column;gap:10px">

    {{-- Email vérifié --}}
    <div style="display:flex;align-items:center;justify-content:space-between">
      <div style="display:flex;align-items:center;gap:8px;font-size:.88rem;color:#374151">
        <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="color:#6b7280"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
        Adresse e-mail vérifiée
      </div>
      @if($user->hasVerifiedEmail())
        <span style="display:inline-flex;align-items:center;gap:5px;background:#d1fae5;color:#065f46;font-size:.75rem;font-weight:700;padding:3px 10px;border-radius:20px">
          <svg width="11" height="11" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
          Vérifiée
        </span>
      @else
        <div style="display:flex;align-items:center;gap:8px">
          <span style="background:#fef3c7;color:#d97706;font-size:.75rem;font-weight:700;padding:3px 10px;border-radius:20px">Non vérifiée</span>
          <form method="POST" action="{{ route('verification.send') }}" style="margin:0">
            @csrf
            <button type="submit" style="background:none;border:none;font-size:.78rem;color:#185FA5;font-weight:600;cursor:pointer;padding:0;text-decoration:underline">Renvoyer</button>
          </form>
        </div>
      @endif
    </div>

    {{-- Compte actif --}}
    <div style="display:flex;align-items:center;justify-content:space-between">
      <div style="display:flex;align-items:center;gap:8px;font-size:.88rem;color:#374151">
        <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="color:#6b7280"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
        État du compte
      </div>
      @if($user->actif)
        <span style="display:inline-flex;align-items:center;gap:5px;background:#d1fae5;color:#065f46;font-size:.75rem;font-weight:700;padding:3px 10px;border-radius:20px">
          <svg width="11" height="11" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
          Actif
        </span>
      @else
        <span style="background:#fef2f2;color:#dc2626;font-size:.75rem;font-weight:700;padding:3px 10px;border-radius:20px">Suspendu</span>
      @endif
    </div>

    {{-- Membre depuis --}}
    <div style="display:flex;align-items:center;justify-content:space-between">
      <div style="display:flex;align-items:center;gap:8px;font-size:.88rem;color:#374151">
        <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="color:#6b7280"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
        Membre depuis
      </div>
      <span style="font-size:.82rem;color:#6b7280">{{ $user->created_at->translatedFormat('d F Y') }}</span>
    </div>

  </div>
</div>
