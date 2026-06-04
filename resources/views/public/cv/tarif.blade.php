@extends('layouts.app')
@section('title', 'Tarifs CVthèque — Emploi Bouge Bénin')

@section('content')
<section style="padding:64px 20px;background:#f8fafc;min-height:60vh">
  <div style="max-width:860px;margin:0 auto">
    <div style="text-align:center;margin-bottom:48px">
      <span style="background:#F5C842;color:#042C53;font-size:12px;font-weight:800;padding:4px 16px;border-radius:20px;text-transform:uppercase;letter-spacing:.06em">CVthèque</span>
      <h1 style="font-size:2.2rem;font-weight:800;color:#042C53;margin:16px 0 12px">Déposez votre CV et soyez visible</h1>
      <p style="font-size:1.05rem;color:#64748b;max-width:560px;margin:0 auto;line-height:1.65">
        Accédez à des recruteurs vérifiés et augmentez vos chances d'être recruté avec un CV optimisé.
      </p>
    </div>

    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:20px;margin-bottom:48px">
      {{-- Plan Gratuit --}}
      <div style="background:#fff;border:2px solid #e2e8f0;border-radius:18px;padding:32px;display:flex;flex-direction:column">
        <h3 style="font-size:1.1rem;font-weight:700;color:#042C53;margin:0 0 8px">Plan Gratuit</h3>
        <p style="font-size:2rem;font-weight:800;color:#94a3b8;margin:0 0 4px">0 FCFA</p>
        <p style="font-size:12px;color:#94a3b8;margin:0 0 20px">Pour toujours</p>
        <ul style="list-style:none;padding:0;margin:0 0 28px;display:flex;flex-direction:column;gap:10px;flex:1">
          @foreach(['Dépôt de 1 CV dans la CVthèque','Visible par les recruteurs de base','Candidatures illimitées','Alertes emploi basiques'] as $f)
            <li style="font-size:13.5px;color:#475569;display:flex;align-items:flex-start;gap:8px">
              <span style="color:#38A169;font-weight:700;flex-shrink:0">✓</span> {{ $f }}
            </li>
          @endforeach
        </ul>
        <a href="{{ route('cv.public.depot') }}" style="display:block;text-align:center;padding:11px 20px;border:1.5px solid #cbd5e0;border-radius:8px;font-weight:700;font-size:14px;color:#475569;text-decoration:none">
          Déposer gratuitement
        </a>
      </div>

      {{-- Plan Premium --}}
      <div style="background:linear-gradient(135deg,#021e3a 0%,#185FA5 100%);border:2px solid transparent;border-radius:18px;padding:32px;display:flex;flex-direction:column;position:relative">
        <div style="position:absolute;top:-14px;left:50%;transform:translateX(-50%);background:#F5C842;color:#042C53;font-size:11px;font-weight:800;padding:4px 18px;border-radius:20px;text-transform:uppercase;letter-spacing:.05em;white-space:nowrap">Recommandé</div>
        <h3 style="font-size:1.1rem;font-weight:700;color:#fff;margin:0 0 8px">Plan Premium CV</h3>
        <p style="font-size:2rem;font-weight:800;color:#F5C842;margin:0 0 4px">5 000 FCFA</p>
        <p style="font-size:12px;color:rgba(255,255,255,.6);margin:0 0 20px">par mois (30 jours)</p>
        <ul style="list-style:none;padding:0;margin:0 0 28px;display:flex;flex-direction:column;gap:10px;flex:1">
          @foreach(['CV mis en avant dans la CVthèque','Visibilité maximale auprès des recruteurs','Accès CVthèque recruteurs Premium','Candidatures illimitées + alertes prioritaires','Support prioritaire'] as $f)
            <li style="font-size:13.5px;color:rgba(255,255,255,.85);display:flex;align-items:flex-start;gap:8px">
              <span style="color:#F5C842;font-weight:700;flex-shrink:0">✓</span> {{ $f }}
            </li>
          @endforeach
        </ul>
        @auth
          <a href="{{ route('candidat.abonnement') }}" style="display:block;text-align:center;padding:11px 20px;background:#F5C842;border-radius:8px;font-weight:800;font-size:14px;color:#042C53;text-decoration:none">
            ★ Passer au Premium — 5 000 FCFA
          </a>
        @else
          <a href="{{ route('auth.inscription') }}" style="display:block;text-align:center;padding:11px 20px;background:#F5C842;border-radius:8px;font-weight:800;font-size:14px;color:#042C53;text-decoration:none">
            ★ S'inscrire et passer au Premium
          </a>
        @endauth
      </div>
    </div>

    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:14px;padding:24px 28px;text-align:center">
      <h3 style="font-size:1rem;font-weight:700;color:#042C53;margin:0 0 8px">Vous êtes recruteur ?</h3>
      <p style="font-size:13.5px;color:#64748b;margin:0 0 16px">Accédez à toute la CVthèque et contactez directement les candidats qualifiés.</p>
      <a href="{{ route('auth.inscription') }}" style="padding:10px 24px;background:#185FA5;color:#fff;border-radius:8px;font-weight:700;font-size:13.5px;text-decoration:none">
        Créer un compte recruteur
      </a>
    </div>
  </div>
</section>
@endsection
