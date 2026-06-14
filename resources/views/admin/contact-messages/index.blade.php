@extends('layouts.admin')
@section('title', 'Messages de contact')

@section('content')
<div class="adm-topbar">
  <div class="adm-topbar__left">
    <h1>Messages de contact</h1>
    <p>Messages reçus via le formulaire de contact public</p>
  </div>
</div>

@if(session('success'))
  <div class="alert alert--success" style="margin-bottom:20px">{{ session('success') }}</div>
@endif

@if($messages->isEmpty())
  <div class="adm-card">
    <div class="adm-empty">
      <svg width="40" height="40" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
      <h3>Aucun message pour l'instant</h3>
      <p>Les messages du formulaire de contact apparaîtront ici.</p>
    </div>
  </div>
@else
  <div class="adm-card">
    <div class="adm-table-wrap">
      <table class="adm-table">
        <thead>
          <tr>
            <th>Expéditeur</th>
            <th>Sujet</th>
            <th>Aperçu</th>
            <th>Date</th>
            <th>Statut</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          @foreach($messages as $msg)
          <tr style="{{ !$msg->lu ? 'background:#fffbeb' : '' }}">
            <td>
              <div style="font-weight:{{ $msg->lu ? '500' : '700' }};color:#042C53">{{ $msg->prenom }} {{ $msg->nom }}</div>
              <div style="font-size:12px;color:#94a3b8;margin-top:2px">{{ $msg->email }}</div>
            </td>
            <td style="font-size:13px;font-weight:500;color:#374151">{{ $msg->sujet_label }}</td>
            <td style="max-width:260px">
              <p style="font-size:13px;color:#64748b;margin:0;overflow:hidden;white-space:nowrap;text-overflow:ellipsis">{{ Str::limit($msg->message, 80) }}</p>
            </td>
            <td style="color:#94a3b8;font-size:12px;white-space:nowrap">{{ $msg->created_at->format('d/m/Y H:i') }}</td>
            <td>
              @if($msg->lu)
                <span class="adm-badge adm-badge--gray">Lu</span>
              @else
                <span class="adm-badge adm-badge--yellow">Nouveau</span>
              @endif
            </td>
            <td>
              <a href="{{ route('admin.contact-messages.show', $msg) }}" class="adm-btn adm-btn--outline adm-btn--sm">
                Voir
                <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" style="display:inline-block;vertical-align:-1px;margin-left:4px"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
              </a>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    @if($messages->hasPages())
      <div style="padding:16px 22px">{{ $messages->links() }}</div>
    @endif
  </div>
@endif

@endsection
