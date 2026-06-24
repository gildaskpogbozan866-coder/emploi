@extends('layouts.recruteur')
@section('title', 'Candidature | Recruteur')

@section('sidebar')
@include('recruteur._sidebar')
@endsection

@section('content')
@php
  $candidat = $candidature->candidat;
  $profil   = $candidat->candidatProfil;
  $statut   = \App\Enums\StatutCandidature::tryFrom($candidature->statut);
@endphp

<div class="rec-topbar">
  <div class="rec-topbar__left">
    <a href="{{ route('recruteur.candidatures') }}" class="rec-btn rec-btn--outline rec-btn--sm" style="margin-bottom:8px">
      <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:-2px"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
      Retour
    </a>
    <h1>Candidature de {{ $candidat->nom_complet }}</h1>
    <p>Pour : <strong>{{ $candidature->offre->titre }}</strong> · <span class="rec-badge rec-badge--{{ $statut?->couleur() ?? 'gray' }}">{{ $statut?->label() ?? ucfirst($candidature->statut) }}</span></p>
  </div>
</div>

@if(session('success'))
<div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:10px;padding:12px 18px;margin-bottom:18px;color:#15803d;font-size:13.5px">{{ session('success') }}</div>
@endif

<div style="display:grid;grid-template-columns:1fr 310px;gap:20px;align-items:start">

  {{-- ── Colonne principale ──────────────────────────────────── --}}
  <div style="display:flex;flex-direction:column;gap:20px">

    {{-- Profil candidat complet --}}
    <div class="rec-card">
      <div class="rec-card__head">
        <span class="rec-card__title">Profil du candidat</span>
        @if($candidat->cvs->isNotEmpty())
        <a href="{{ route('cv.public.detail', $candidat->cvs->first()) }}" target="_blank"
           class="rec-btn rec-btn--outline rec-btn--sm">Voir le profil public</a>
        @endif
      </div>
      <div class="rec-card__body">

        {{-- Identité --}}
        <div style="display:flex;align-items:center;gap:16px;margin-bottom:20px;padding-bottom:18px;border-bottom:1px solid #f1f5f9">
          @if($candidat->avatar)
            <img src="{{ asset('storage/'.$candidat->avatar) }}" alt="Avatar" style="width:64px;height:64px;border-radius:50%;object-fit:cover;flex-shrink:0">
          @else
            <div style="width:64px;height:64px;border-radius:50%;background:rgba(55,138,221,.12);display:flex;align-items:center;justify-content:center;font-weight:800;font-size:1.4rem;color:#185FA5;flex-shrink:0">
              {{ strtoupper(substr($candidat->prenom ?? '?', 0, 1)) }}
            </div>
          @endif
          <div>
            <p style="font-weight:800;font-size:1.1rem;color:#042C53;margin:0 0 3px">{{ $candidat->nom_complet }}</p>
            @if($profil?->titre_professionnel)
              <p style="font-size:13.5px;color:#185FA5;font-weight:600;margin:0 0 4px">{{ $profil->titre_professionnel }}</p>
            @endif
            <div style="display:flex;flex-wrap:wrap;gap:10px;font-size:12.5px;color:#64748b">
              <span>{{ $candidat->email }}</span>
              @if($candidat->tel) <span>·</span><span>{{ $candidat->tel }}</span> @endif
              @if($profil?->ville) <span>·</span><span>{{ $profil->ville }}</span> @endif
              @if($candidat->pays) <span>·</span><span>{{ $candidat->pays }}</span> @endif
            </div>
          </div>
        </div>

        {{-- Bio / Résumé --}}
        @if($profil?->bio)
        <div style="margin-bottom:18px">
          <p style="font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;margin:0 0 8px">Résumé</p>
          <p style="font-size:14px;color:#374151;line-height:1.7;margin:0">{{ $profil->bio }}</p>
        </div>
        @endif

        {{-- Disponibilité + Contrats --}}
        <div style="display:flex;flex-wrap:wrap;gap:10px;margin-bottom:18px">
          @if($profil?->disponibilite)
          <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px;padding:6px 14px;font-size:12.5px;color:#15803d;font-weight:600">
            Disponible : {{ $profil->disponibilite }}
          </div>
          @endif
          @if($profil?->remote && $profil->remote !== 'non')
          <div style="background:#f0f7ff;border:1px solid #bfdbfe;border-radius:8px;padding:6px 14px;font-size:12.5px;color:#1e40af;font-weight:600">
            Remote : {{ $profil->remote }}
          </div>
          @endif
          @foreach($candidat->typesContrats as $tc)
          <div style="background:#fff7ed;border:1px solid #fed7aa;border-radius:8px;padding:6px 14px;font-size:12px;color:#9a3412;font-weight:600">
            {{ $tc->libelle }}
          </div>
          @endforeach
        </div>

        {{-- Compétences --}}
        @if($candidat->competences->isNotEmpty())
        <div style="margin-bottom:18px">
          <p style="font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;margin:0 0 8px">Compétences</p>
          <div style="display:flex;flex-wrap:wrap;gap:6px">
            @foreach($candidat->competences as $comp)
            <span style="background:#f1f5f9;border:1px solid #e2e8f0;border-radius:20px;padding:4px 12px;font-size:12.5px;color:#042C53;font-weight:500">
              {{ $comp->nom }}
              @if($comp->pivot->annees_experience)
                <span style="color:#94a3b8">· {{ $comp->pivot->annees_experience }} an{{ $comp->pivot->annees_experience > 1 ? 's' : '' }}</span>
              @endif
            </span>
            @endforeach
          </div>
        </div>
        @endif

        {{-- Expériences --}}
        @if($candidat->experiences->isNotEmpty())
        <div style="margin-bottom:18px">
          <p style="font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;margin:0 0 10px">Expériences professionnelles</p>
          <div style="display:flex;flex-direction:column;gap:12px">
            @foreach($candidat->experiences->sortByDesc('en_cours')->sortByDesc('date_debut') as $exp)
            <div style="padding:12px 14px;background:#f8fafc;border-radius:10px;border-left:3px solid #185FA5">
              <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:6px">
                <div>
                  <p style="font-weight:700;color:#042C53;margin:0 0 2px;font-size:14px">{{ $exp->poste }}</p>
                  @if($exp->entreprise)
                    <p style="font-size:13px;color:#185FA5;margin:0 0 2px;font-weight:600">{{ $exp->entreprise }}</p>
                  @endif
                  @if($exp->lieu)
                    <p style="font-size:12px;color:#64748b;margin:0">{{ $exp->lieu }}</p>
                  @endif
                </div>
                <div style="text-align:right;flex-shrink:0">
                  @php
                    $debut = $exp->date_debut ? \Carbon\Carbon::parse($exp->date_debut)->translatedFormat('M Y') : null;
                    $fin   = $exp->en_cours ? 'Présent' : ($exp->date_fin ? \Carbon\Carbon::parse($exp->date_fin)->translatedFormat('M Y') : null);
                  @endphp
                  @if($debut || $fin)
                    <span style="font-size:12px;color:#94a3b8">{{ $debut }}{{ ($debut && $fin) ? ' – ' : '' }}{{ $fin }}</span>
                  @endif
                  @if($exp->en_cours)
                    <span style="display:block;font-size:11px;background:#d1fae5;color:#15803d;border-radius:20px;padding:1px 8px;font-weight:700;margin-top:3px">En cours</span>
                  @endif
                </div>
              </div>
              @if($exp->description)
                <p style="font-size:13px;color:#374151;margin:8px 0 0;line-height:1.6">{{ $exp->description }}</p>
              @endif
            </div>
            @endforeach
          </div>
        </div>
        @endif

        {{-- Formations --}}
        @if($candidat->formations->isNotEmpty())
        <div style="margin-bottom:18px">
          <p style="font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;margin:0 0 10px">Formations</p>
          <div style="display:flex;flex-direction:column;gap:10px">
            @foreach($candidat->formations->sortByDesc('en_cours')->sortByDesc('date_debut') as $form)
            <div style="padding:12px 14px;background:#f8fafc;border-radius:10px;border-left:3px solid #7c3aed">
              <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:6px">
                <div>
                  <p style="font-weight:700;color:#042C53;margin:0 0 2px;font-size:14px">{{ $form->diplome }}</p>
                  @if($form->etablissement)
                    <p style="font-size:13px;color:#7c3aed;margin:0;font-weight:600">{{ $form->etablissement }}</p>
                  @endif
                </div>
                <div style="text-align:right;flex-shrink:0">
                  @php
                    $fd = $form->date_debut ? \Carbon\Carbon::parse($form->date_debut)->format('Y') : null;
                    $ff = $form->en_cours ? 'Présent' : ($form->date_fin ? \Carbon\Carbon::parse($form->date_fin)->format('Y') : null);
                  @endphp
                  @if($fd || $ff)
                    <span style="font-size:12px;color:#94a3b8">{{ $fd }}{{ ($fd && $ff) ? ' – ' : '' }}{{ $ff }}</span>
                  @endif
                </div>
              </div>
            </div>
            @endforeach
          </div>
        </div>
        @endif

        {{-- Langues --}}
        @if($candidat->languesCandidats->isNotEmpty())
        <div style="margin-bottom:18px">
          <p style="font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;margin:0 0 8px">Langues</p>
          <div style="display:flex;flex-wrap:wrap;gap:6px">
            @foreach($candidat->languesCandidats as $lc)
            <span style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:20px;padding:4px 12px;font-size:12.5px;color:#374151">
              {{ $lc->langue->nom }}
              @if($lc->niveau) <span style="color:#94a3b8">· {{ $lc->niveau->libelle ?? $lc->niveau->code }}</span> @endif
            </span>
            @endforeach
          </div>
        </div>
        @endif

        {{-- Niveau d'étude & expérience --}}
        @if($candidat->niveauEtude?->niveauEtude || $candidat->niveauExperience?->niveauExperience)
        <div style="display:flex;flex-wrap:wrap;gap:10px;margin-bottom:6px">
          @if($candidat->niveauEtude?->niveauEtude)
          <div style="font-size:12.5px;color:#374151"><span style="color:#94a3b8">Niveau d'études :</span> <strong>{{ $candidat->niveauEtude->niveauEtude->libelle }}</strong></div>
          @endif
          @if($candidat->niveauExperience?->niveauExperience)
          <div style="font-size:12.5px;color:#374151"><span style="color:#94a3b8">Expérience :</span> <strong>{{ $candidat->niveauExperience->niveauExperience->libelle }}</strong></div>
          @endif
        </div>
        @endif

      </div>
    </div>

    {{-- Message de motivation --}}
    <div class="rec-card">
      <div class="rec-card__head">
        <span class="rec-card__title">Message de motivation</span>
      </div>
      <div class="rec-card__body">
        @if($candidature->message_motivation)
          <p style="font-size:14.5px;color:#374151;line-height:1.7;margin:0;white-space:pre-line">{{ $candidature->message_motivation }}</p>
        @else
          <p style="color:#94a3b8;font-style:italic;margin:0">Aucun message de motivation fourni.</p>
        @endif
      </div>
    </div>

    {{-- Documents joints --}}
    @if($candidature->cv || $candidature->cv_path || $candidature->lettre_path)
    <div class="rec-card">
      <div class="rec-card__head">
        <span class="rec-card__title">Documents joints à la candidature</span>
      </div>
      <div class="rec-card__body" style="display:flex;flex-direction:column;gap:12px">

        {{-- CV profil --}}
        @if($candidature->cv)
        <div style="display:flex;align-items:center;gap:12px;padding:14px 16px;background:#f0f7ff;border:1.5px solid #bfdbfe;border-radius:10px">
          <div style="width:40px;height:40px;border-radius:8px;background:#dbeafe;display:flex;align-items:center;justify-content:center;flex-shrink:0">
            <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#185FA5" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
          </div>
          <div style="flex:1;min-width:0">
            <p style="font-weight:700;color:#042C53;margin:0 0 2px;font-size:13.5px">{{ $candidature->cv->metier ?? 'CV' }}</p>
            <p style="font-size:12px;color:#64748b;margin:0">{{ $candidature->cv->ville }}</p>
          </div>
          <div style="display:flex;gap:8px;flex-shrink:0">
            <span style="font-size:11px;background:#dbeafe;color:#1e40af;padding:2px 10px;border-radius:20px;font-weight:700">CV profil</span>
            @if($candidature->cv->fichier_path)
            <a href="{{ asset('storage/'.$candidature->cv->fichier_path) }}" target="_blank"
               style="font-size:12px;font-weight:700;color:#185FA5;text-decoration:none;padding:5px 12px;border:1.5px solid #bfdbfe;border-radius:6px;background:#fff;white-space:nowrap">
              Télécharger
            </a>
            @endif
          </div>
        </div>
        @endif

        {{-- CV fichier direct --}}
        @if($candidature->cv_path)
        <div style="display:flex;align-items:center;gap:12px;padding:14px 16px;background:#f0fdf4;border:1.5px solid #bbf7d0;border-radius:10px">
          <div style="width:40px;height:40px;border-radius:8px;background:#dcfce7;display:flex;align-items:center;justify-content:center;flex-shrink:0">
            <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#15803d" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
          </div>
          <div style="flex:1;min-width:0">
            <p style="font-weight:700;color:#042C53;margin:0 0 2px;font-size:13.5px">CV téléversé</p>
            <p style="font-size:12px;color:#64748b;margin:0">{{ basename($candidature->cv_path) }}</p>
          </div>
          <a href="{{ asset('storage/'.$candidature->cv_path) }}" target="_blank"
             style="font-size:12px;font-weight:700;color:#15803d;text-decoration:none;padding:5px 12px;border:1.5px solid #bbf7d0;border-radius:6px;background:#fff;white-space:nowrap;flex-shrink:0">
            Télécharger
          </a>
        </div>
        @endif

        {{-- Lettre de motivation --}}
        @if($candidature->lettre_path)
        <div style="display:flex;align-items:center;gap:12px;padding:14px 16px;background:#fdf4ff;border:1.5px solid #e9d5ff;border-radius:10px">
          <div style="width:40px;height:40px;border-radius:8px;background:#f3e8ff;display:flex;align-items:center;justify-content:center;flex-shrink:0">
            <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#7c3aed" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
          </div>
          <div style="flex:1;min-width:0">
            <p style="font-weight:700;color:#042C53;margin:0 0 2px;font-size:13.5px">Lettre de motivation</p>
            <p style="font-size:12px;color:#64748b;margin:0">{{ basename($candidature->lettre_path) }}</p>
          </div>
          <a href="{{ asset('storage/'.$candidature->lettre_path) }}" target="_blank"
             style="font-size:12px;font-weight:700;color:#7c3aed;text-decoration:none;padding:5px 12px;border:1.5px solid #e9d5ff;border-radius:6px;background:#fff;white-space:nowrap;flex-shrink:0">
            Télécharger
          </a>
        </div>
        @endif

      </div>
    </div>
    @else
    <div class="rec-card">
      <div class="rec-card__head"><span class="rec-card__title">Documents joints</span></div>
      <div class="rec-card__body">
        <p style="font-size:13.5px;color:#94a3b8;font-style:italic;margin:0">Aucun document joint à cette candidature.</p>
      </div>
    </div>
    @endif

    {{-- Mettre à jour le statut --}}
    <div class="rec-card">
      <div class="rec-card__head">
        <span class="rec-card__title">Mettre à jour le statut</span>
      </div>
      <div class="rec-card__body">
        <form method="POST" action="{{ route('recruteur.candidatures.statut', $candidature) }}" style="display:flex;flex-direction:column;gap:14px">
          @csrf @method('PATCH')
          <div>
            <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:5px">Nouveau statut</label>
            <select name="statut" class="rec-select" style="width:100%;padding:9px 12px;border:1.5px solid #d1d5db;border-radius:8px;font-size:14px">
              @foreach(\App\Enums\StatutCandidature::cases() as $case)
                <option value="{{ $case->value }}" {{ $candidature->statut === $case->value ? 'selected' : '' }}>{{ $case->label() }}</option>
              @endforeach
            </select>
          </div>
          <div>
            <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:5px">Note interne (visible par le candidat)</label>
            <textarea name="note_recruteur" rows="3" placeholder="Commentaire envoyé au candidat avec sa notification…"
                      style="width:100%;padding:10px 14px;border:1.5px solid #d1d5db;border-radius:8px;font-size:14px;resize:vertical;box-sizing:border-box">{{ $candidature->note_recruteur }}</textarea>
          </div>
          <button type="submit" class="rec-btn rec-btn--yellow">Enregistrer et notifier le candidat</button>
        </form>
      </div>
    </div>

  </div>

  {{-- ── Colonne latérale ────────────────────────────────────── --}}
  <div style="display:flex;flex-direction:column;gap:16px">

    {{-- Infos rapides candidat --}}
    <div class="rec-card">
      <div class="rec-card__head"><span class="rec-card__title">Infos rapides</span></div>
      <div class="rec-card__body" style="display:flex;flex-direction:column;gap:10px">
        <div>
          @if($candidat->avatar)
            <img src="{{ asset('storage/'.$candidat->avatar) }}" alt="" style="width:48px;height:48px;border-radius:50%;object-fit:cover;margin-bottom:8px">
          @else
            <div style="width:48px;height:48px;border-radius:50%;background:rgba(55,138,221,.12);display:flex;align-items:center;justify-content:center;font-weight:800;font-size:1.1rem;color:#185FA5;margin-bottom:8px">
              {{ strtoupper(substr($candidat->prenom ?? '?', 0, 1)) }}
            </div>
          @endif
          <p style="font-weight:700;color:#042C53;margin:0 0 2px;font-size:14px">{{ $candidat->nom_complet }}</p>
          <p style="font-size:12px;color:#94a3b8;margin:0">{{ $candidat->email }}</p>
          @if($candidat->tel)
          <p style="font-size:12px;color:#94a3b8;margin:3px 0 0">{{ $candidat->tel }}</p>
          @endif
        </div>

        <div style="border-top:1px solid #f1f5f9;padding-top:10px;font-size:12.5px;color:#374151;display:flex;flex-direction:column;gap:6px">
          @if($profil?->ville)
          <div><span style="color:#94a3b8">Ville :</span> {{ $profil->ville }}</div>
          @endif
          @if($candidat->pays)
          <div><span style="color:#94a3b8">Pays :</span> {{ $candidat->pays }}</div>
          @endif
          @if($profil?->disponibilite)
          <div><span style="color:#94a3b8">Disponibilité :</span> {{ $profil->disponibilite }}</div>
          @endif
          @if($profil?->linkedin)
          <div><a href="{{ $profil->linkedin }}" target="_blank" style="color:#185FA5;font-weight:600">LinkedIn</a></div>
          @endif
          @if($profil?->portfolio)
          <div><a href="{{ $profil->portfolio }}" target="_blank" style="color:#185FA5;font-weight:600">Portfolio</a></div>
          @endif
        </div>

        <div style="border-top:1px solid #f1f5f9;padding-top:10px;font-size:12px;color:#94a3b8">
          Candidature reçue le {{ $candidature->created_at->format('d/m/Y à H:i') }}
        </div>

        <form method="POST" action="{{ route('recruteur.messagerie.initier', $candidat) }}">
          @csrf
          <button type="submit" class="rec-btn rec-btn--outline" style="width:100%;justify-content:center">
            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:-2px;margin-right:5px"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
            Envoyer un message
          </button>
        </form>
      </div>
    </div>

    {{-- Documents du profil --}}
    @if($candidat->documents->isNotEmpty())
    <div class="rec-card">
      <div class="rec-card__head"><span class="rec-card__title">Documents du profil</span></div>
      <div class="rec-card__body" style="display:flex;flex-direction:column;gap:8px">
        @foreach($candidat->documents as $doc)
        <div style="display:flex;align-items:center;gap:10px;padding:10px 12px;background:#f8fafc;border-radius:8px;border:1px solid #e2e8f0">
          <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#64748b" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
          <div style="flex:1;min-width:0">
            <p style="font-size:12.5px;font-weight:600;color:#042C53;margin:0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $doc->nom }}</p>
            <p style="font-size:11px;color:#94a3b8;margin:0">{{ $doc->type?->nom ?? 'Document' }}</p>
          </div>
          <a href="{{ asset('storage/'.$doc->fichier) }}" target="_blank"
             style="font-size:11px;color:#185FA5;font-weight:700;text-decoration:none;padding:4px 10px;border:1px solid #bfdbfe;border-radius:5px;background:#fff;white-space:nowrap;flex-shrink:0">
            Voir
          </a>
        </div>
        @endforeach
      </div>
    </div>
    @endif

  </div>

</div>
@endsection
