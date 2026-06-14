@extends('layouts.app')
@section('title', 'Soumettre votre dossier')

@section('content')
<div style="min-height:80vh;background:#f8fafc;display:flex;align-items:center;justify-content:center;padding:40px 20px">
  <div style="width:100%;max-width:620px">

    <div style="text-align:center;margin-bottom:32px">
      <div style="width:60px;height:60px;border-radius:16px;background:linear-gradient(135deg,#042C53,#185FA5);display:flex;align-items:center;justify-content:center;margin:0 auto 16px">
        <svg width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="#fff" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
      </div>
      <h1 style="font-size:1.5rem;font-weight:800;color:#042C53;margin:0 0 8px">Compléter votre dossier</h1>
      <p style="font-size:14px;color:#64748b;margin:0 auto;max-width:420px">
        Votre compte est créé. Soumettez les documents requis pour que l'équipe valide votre accès.
      </p>
    </div>

    @if($verification?->estRejete())
      <div style="background:#fef2f2;border:1.5px solid #fecaca;border-radius:12px;padding:16px 20px;margin-bottom:24px">
        <p style="font-size:13.5px;font-weight:700;color:#dc2626;margin:0 0 6px">Dossier rejeté</p>
        <p style="font-size:13px;color:#7f1d1d;margin:0">{{ $verification->note_admin }}</p>
        <p style="font-size:12px;color:#94a3b8;margin:8px 0 0">Corrigez les points ci-dessus et re-soumettez votre dossier.</p>
      </div>
    @endif

    @if($errors->any())
      <div style="background:#fef2f2;border:1.5px solid #fecaca;border-radius:10px;padding:12px 18px;margin-bottom:20px">
        <ul style="margin:0;padding-left:18px;font-size:13px;color:#dc2626">
          @foreach($errors->all() as $err)
            <li>{{ $err }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    @if($types->isEmpty())
      <div style="background:#fff;border:1.5px solid #e2e8f0;border-radius:14px;padding:40px;text-align:center;color:#94a3b8">
        <p style="font-size:14px;margin:0">Aucun document requis pour le moment.</p>
        <p style="font-size:13px;margin:8px 0 0">Contactez l'administrateur si vous pensez que c'est une erreur.</p>
      </div>
    @else
    <form method="POST" action="{{ route('recruteur.verification.store') }}" enctype="multipart/form-data">
      @csrf
      <div style="display:flex;flex-direction:column;gap:16px;margin-bottom:24px">

        @foreach($types as $type)
        @php $existant = $existants[$type->id] ?? null; @endphp
        <div style="background:#fff;border:1.5px solid #e2e8f0;border-radius:14px;padding:20px 22px">
          <div style="display:flex;align-items:flex-start;gap:12px;margin-bottom:14px">
            <div style="width:36px;height:36px;border-radius:8px;background:#f0f4ff;display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:2px">
              <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="#185FA5" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
            </div>
            <div style="flex:1">
              <p style="font-size:14.5px;font-weight:700;color:#042C53;margin:0">
                {{ $type->nom }}
                @if($type->est_requis)<span style="color:#dc2626"> *</span>@endif
              </p>
              @if($type->description)
                <p style="font-size:12.5px;color:#64748b;margin:3px 0 0">{{ $type->description }}</p>
              @endif
            </div>
            @if(!$type->est_requis)
              <span style="font-size:11px;padding:2px 8px;border-radius:20px;background:#f1f5f9;color:#94a3b8;font-weight:600;flex-shrink:0">Optionnel</span>
            @endif
          </div>

          @if($type->accepte_fichier)
            <div style="margin-bottom:{{ $type->accepte_texte ? '12px' : '0' }}">
              <label style="display:block;font-size:12px;font-weight:600;color:#374151;margin-bottom:6px">
                Fichier (PDF, JPG, PNG — max 5 Mo)
              </label>
              @if($existant?->fichier)
                <p style="font-size:12px;color:#16a34a;margin:0 0 6px;display:flex;align-items:center;gap:6px">
                  <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                  Fichier déjà soumis — laisser vide pour conserver
                </p>
              @endif
              <input type="file" name="fichier_{{ $type->id }}" accept=".pdf,.jpg,.jpeg,.png"
                     style="width:100%;padding:8px 12px;border:1.5px solid #d1d5db;border-radius:8px;font-size:13px;box-sizing:border-box;background:#fafafa">
            </div>
          @endif

          @if($type->accepte_texte)
            <div>
              <label style="display:block;font-size:12px;font-weight:600;color:#374151;margin-bottom:6px">
                Numéro / Référence
              </label>
              <input type="text" name="texte_{{ $type->id }}"
                     value="{{ old("texte_{$type->id}", $existant?->texte) }}"
                     placeholder="Entrez le numéro ou la référence"
                     style="width:100%;padding:9px 12px;border:1.5px solid #d1d5db;border-radius:8px;font-size:13px;box-sizing:border-box">
            </div>
          @endif
        </div>
        @endforeach

      </div>

      <button type="submit" style="width:100%;padding:14px;font-size:15px;font-weight:700;border-radius:10px;background:linear-gradient(135deg,#042C53,#185FA5);color:#fff;border:none;cursor:pointer">
        Soumettre mon dossier
      </button>
      <p style="text-align:center;font-size:12px;color:#94a3b8;margin-top:12px">
        Votre dossier sera examiné par l'équipe dans les plus brefs délais.
      </p>
    </form>
    @endif

  </div>
</div>
@endsection
