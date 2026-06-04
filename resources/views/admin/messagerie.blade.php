@extends('layouts.admin')
@section('title', 'Messagerie — Administration')

@section('content')
<div class="adm-topbar">
  <div class="adm-topbar__left">
    <h1>Messagerie</h1>
    <p>Surveillance des conversations de la plateforme</p>
  </div>
  <div style="display:flex;gap:12px">
    <div style="background:#f0f9ff;border:1px solid #bae6fd;border-radius:10px;padding:10px 18px;text-align:center">
      <p style="font-size:11.5px;color:#0369a1;font-weight:600;text-transform:uppercase;margin:0 0 2px">Total messages</p>
      <p style="font-size:1.3rem;font-weight:800;color:#0369a1;margin:0">{{ $stats['total_messages'] }}</p>
    </div>
    <div style="background:#fef9c3;border:1px solid #fde68a;border-radius:10px;padding:10px 18px;text-align:center">
      <p style="font-size:11.5px;color:#92400e;font-weight:600;text-transform:uppercase;margin:0 0 2px">Non lus</p>
      <p style="font-size:1.3rem;font-weight:800;color:#92400e;margin:0">{{ $stats['non_lus'] }}</p>
    </div>
  </div>
</div>

<div class="adm-card">
  <div class="adm-table-wrap">
    <table class="adm-table">
      <thead>
        <tr><th>Participant 1</th><th>Participant 2</th><th>Dernier message</th><th>Date</th></tr>
      </thead>
      <tbody>
        @forelse($conversations as $conv)
        <tr>
          <td>
            <div style="font-weight:600;color:#042C53">{{ $conv->user1->nom_complet }}</div>
            <div style="font-size:12px;color:#94a3b8">{{ ucfirst($conv->user1->role) }}</div>
          </td>
          <td>
            <div style="font-weight:600;color:#042C53">{{ $conv->user2->nom_complet }}</div>
            <div style="font-size:12px;color:#94a3b8">{{ ucfirst($conv->user2->role) }}</div>
          </td>
          <td style="max-width:300px">
            @if($conv->dernierMessage)
              <p style="color:#374151;font-size:13px;margin:0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">
                {{ Str::limit($conv->dernierMessage->contenu, 80) }}
              </p>
            @else
              <span style="color:#94a3b8;font-size:13px">—</span>
            @endif
          </td>
          <td style="color:#94a3b8;font-size:12px">{{ $conv->dernier_message_at?->format('d/m/Y H:i') ?? '—' }}</td>
        </tr>
        @empty
        <tr>
          <td colspan="4">
            <div class="adm-empty">
              <svg width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
              <h3>Aucune conversation</h3>
            </div>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($conversations->hasPages())
    <div style="padding:16px 22px">{{ $conversations->links() }}</div>
  @endif
</div>
@endsection
