@extends('layouts.app')
@section('title', $service->nom.' — Emploi Bouge Bénin')

@section('content')
<section style="padding:48px 20px;background:#f8fafc;min-height:60vh">
  <div style="max-width:820px;margin:0 auto">
    <div style="margin-bottom:20px">
      <a href="{{ route('service.list') }}" style="color:#185FA5;text-decoration:none;font-size:13.5px">
        ← Retour aux services
      </a>
    </div>

    <div style="display:grid;grid-template-columns:1fr 320px;gap:24px;align-items:start">
      <div style="background:#fff;border:1px solid #e2e8f0;border-radius:18px;padding:32px">
        <span style="background:#F5C842;color:#042C53;font-size:11px;font-weight:800;padding:3px 14px;border-radius:20px;text-transform:uppercase;letter-spacing:.05em">Service</span>
        <h1 style="font-size:1.8rem;font-weight:800;color:#042C53;margin:16px 0 12px">{{ $service->nom }}</h1>

        @if($service->description)
          <p style="font-size:1rem;color:#64748b;line-height:1.65;margin:0 0 24px">{{ $service->description }}</p>
        @endif

        @if($service->details)
          <div style="margin-bottom:24px;padding-top:24px;border-top:1px solid #f1f5f9">
            <h3 style="font-size:1rem;font-weight:700;color:#042C53;margin:0 0 14px">Ce qui est inclus</h3>
            <div style="font-size:14.5px;color:#374151;line-height:1.75">
              @foreach(explode("\n", $service->details) as $line)
                @if(trim($line))
                  <p style="margin:0 0 8px;display:flex;align-items:flex-start;gap:8px">
                    <span style="color:#38A169;font-weight:700;flex-shrink:0">✓</span> {{ trim($line) }}
                  </p>
                @endif
              @endforeach
            </div>
          </div>
        @endif

        <div style="display:flex;gap:10px;flex-wrap:wrap;margin-top:4px">
          @if($service->delai)
            <span style="background:#f1f5f9;color:#374151;padding:6px 14px;border-radius:20px;font-size:13px">
              ⏱ Livraison : {{ $service->delai }}
            </span>
          @endif
        </div>
      </div>

      <div style="position:sticky;top:24px">
        <div style="background:#fff;border:2px solid #e2e8f0;border-radius:18px;padding:28px">
          <p style="font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8;margin:0 0 8px">Prix</p>
          <p style="font-size:2.2rem;font-weight:800;color:#185FA5;margin:0 0 4px">{{ number_format($service->prix, 0, ',', ' ') }}</p>
          <p style="font-size:14px;color:#94a3b8;margin:0 0 24px">FCFA tout compris</p>

          @if($service->delai)
            <div style="display:flex;align-items:center;gap:8px;margin-bottom:20px;padding:12px;background:#f8fafc;border-radius:10px">
              <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
              <span style="font-size:13px;color:#374151">Livré sous <strong>{{ $service->delai }}</strong></span>
            </div>
          @endif

          <a href="{{ route('service.commande', $service) }}" style="display:block;text-align:center;padding:13px 20px;background:#F5C842;border-radius:10px;font-weight:800;font-size:15px;color:#042C53;text-decoration:none;margin-bottom:12px">
            Commander maintenant
          </a>
          <div style="text-align:center">
            <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="#38A169" stroke-width="2" style="vertical-align:middle;margin-right:4px"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            <span style="font-size:12px;color:#38A169;font-weight:600">Satisfaction garantie · 1 révision offerte</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
