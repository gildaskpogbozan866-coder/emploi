@extends('layouts.admin')
@section('title', 'Nouveau plan de publication — Administration')

@section('content')
<div class="adm-topbar">
  <div class="adm-topbar__left">
    <a href="{{ route('admin.publication-plans.index') }}"
       style="display:inline-flex;align-items:center;gap:6px;font-size:13px;color:#64748b;text-decoration:none;margin-bottom:6px">
      <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
      Plans de publication
    </a>
    <h1>Nouveau plan</h1>
    <p>Définissez un tarif de mise en ligne pour les annonceurs</p>
  </div>
</div>

<div style="max-width:560px">
  <div class="adm-card">
    <div style="padding:20px 24px;border-bottom:1px solid #f1f5f9">
      <h3 style="font-size:14px;font-weight:700;color:#042C53;margin:0">Informations du plan</h3>
    </div>
    <div style="padding:24px">
      <form method="POST" action="{{ route('admin.publication-plans.store') }}"
            style="display:flex;flex-direction:column;gap:18px">
        @csrf
        @include('admin.publication-plans._form-fields')

        @if($errors->any())
          <div class="adm-alert adm-alert--danger">
            <ul style="margin:0;padding-left:16px">
              @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <div style="display:flex;gap:12px;padding-top:4px">
          <button type="submit" class="adm-btn adm-btn--yellow" style="flex:1">Créer le plan</button>
          <a href="{{ route('admin.publication-plans.index') }}" class="adm-btn adm-btn--outline">Annuler</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
