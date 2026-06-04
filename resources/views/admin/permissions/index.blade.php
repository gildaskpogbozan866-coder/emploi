@extends('layouts.admin')
@section('title', 'Rôles & Permissions')

@section('content')
<div class="adm-topbar">
  <div class="adm-topbar__left">
    <h1>Rôles &amp; Permissions</h1>
    <p>Contrôle granulaire des accès par rôle et par utilisateur</p>
  </div>
</div>

{{-- Onglets --}}
<div class="adm-tabs" style="margin-bottom:24px">
  <button class="adm-tab active" id="btn-tab-roles" onclick="showTab('tab-roles')">
    Matrice Rôles × Permissions
  </button>
  <button class="adm-tab" id="btn-tab-users" onclick="showTab('tab-users')">
    Permissions individuelles
  </button>
</div>

{{-- TAB 1 : MATRICE RÔLES × PERMISSIONS --}}
<div id="tab-roles" class="adm-tab-panel active">
  @foreach($roles as $role)
  <div class="adm-card" style="margin-bottom:18px">
    <div class="adm-card__header">
      <div style="display:flex;align-items:center;gap:12px">
        <span class="badge-role badge-role--{{ $role->name }}" style="font-size:13px;padding:5px 14px">
          {{ ucfirst($role->name) }}
        </span>
        <span style="color:#94a3b8;font-size:13px">
          {{ $role->permissions->count() }} permission(s) active(s)
        </span>
      </div>
      @if($role->name !== 'admin')
        <button type="submit" form="form-role-{{ $role->id }}" class="adm-btn adm-btn--primary adm-btn--sm">
          Sauvegarder
        </button>
      @else
        <span style="font-size:12.5px;color:#94a3b8;font-style:italic">Admin = toutes les permissions</span>
      @endif
    </div>
    <div class="adm-card__body">
      <form id="form-role-{{ $role->id }}" method="POST" action="{{ route('admin.permissions.role.update', $role) }}">
        @csrf @method('PUT')

        @foreach($permissions as $groupe => $perms)
        <div style="margin-bottom:16px">
          <p class="adm-section-label" style="margin-bottom:10px">{{ $groupe }}</p>
          <div style="display:flex;flex-wrap:wrap;gap:8px">
            @foreach($perms as $perm)
            <label style="display:flex;align-items:center;gap:6px;padding:5px 12px;border:1.5px solid {{ $role->permissions->contains('name', $perm->name) ? '#378ADD' : '#e2e8f0' }};border-radius:8px;cursor:pointer;font-size:12.5px;font-weight:500;background:{{ $role->permissions->contains('name', $perm->name) ? 'rgba(55,138,221,.08)' : '#fff' }};color:{{ $role->permissions->contains('name', $perm->name) ? '#185FA5' : '#475569' }};{{ $role->name === 'admin' ? 'opacity:.6;pointer-events:none;' : '' }}transition:all .15s">
              <input type="checkbox"
                     name="permissions[]"
                     value="{{ $perm->name }}"
                     {{ $role->permissions->contains('name', $perm->name) ? 'checked' : '' }}
                     {{ $role->name === 'admin' ? 'disabled' : '' }}
                     style="accent-color:#185FA5">
              {{ $perm->name }}
            </label>
            @endforeach
          </div>
        </div>
        @endforeach
      </form>
    </div>
  </div>
  @endforeach
</div>

{{-- TAB 2 : PERMISSIONS INDIVIDUELLES --}}
<div id="tab-users" class="adm-tab-panel">
  <div class="adm-card">
    <div class="adm-card__header">
      <h2>Gestion individuelle</h2>
    </div>
    <div class="adm-card__body">
      <p style="font-size:13.5px;color:#64748b;margin:0 0 20px;line-height:1.6">
        Accordez ou retirez des permissions supplémentaires à un utilisateur spécifique, en plus de celles de son rôle.
      </p>

      <div class="adm-table-wrap">
        <table class="adm-table">
          <thead>
            <tr>
              <th>Utilisateur</th>
              <th>Rôle</th>
              <th>Permissions directes</th>
              <th>Changer le rôle</th>
              <th>Ajouter une permission</th>
            </tr>
          </thead>
          <tbody>
            @foreach($users as $user)
            <tr>
              <td>
                <div style="font-weight:600;color:#042C53">{{ $user->nom_complet }}</div>
                <div style="font-size:11.5px;color:#94a3b8">{{ $user->email }}</div>
              </td>
              <td>
                <span class="badge-role badge-role--{{ $user->role }}">{{ ucfirst($user->role) }}</span>
              </td>
              <td style="max-width:240px">
                @forelse($user->getDirectPermissions() as $dp)
                  <form method="POST" action="{{ route('admin.permissions.user.revoke', $user) }}" style="display:inline">
                    @csrf @method('DELETE')
                    <input type="hidden" name="permission" value="{{ $dp->name }}">
                    <button type="submit" class="adm-badge adm-badge--red" style="cursor:pointer;border:none;margin:2px" title="Retirer cette permission">
                      {{ $dp->name }} ✕
                    </button>
                  </form>
                @empty
                  <span style="color:#94a3b8;font-size:12.5px">Aucune permission directe</span>
                @endforelse
              </td>
              <td>
                @if($user->role !== 'admin')
                <form method="POST" action="{{ route('admin.permissions.user.role', $user) }}" style="display:flex;gap:6px;align-items:center">
                  @csrf @method('PUT')
                  <select name="role" class="adm-select" style="padding:5px 8px;font-size:12.5px">
                    @foreach(['admin','recruteur','candidat','talent'] as $r)
                      <option value="{{ $r }}" {{ $user->role === $r ? 'selected' : '' }}>{{ ucfirst($r) }}</option>
                    @endforeach
                  </select>
                  <button type="submit" class="adm-btn adm-btn--primary adm-btn--sm">OK</button>
                </form>
                @else
                  <span style="font-size:12.5px;color:#94a3b8;font-style:italic">Super Admin</span>
                @endif
              </td>
              <td>
                <form method="POST" action="{{ route('admin.permissions.user.give', $user) }}" style="display:flex;gap:6px;align-items:center">
                  @csrf
                  <select name="permission" class="adm-select" style="padding:5px 8px;font-size:12px;max-width:200px">
                    @foreach(\App\Enums\Permission::all() as $p)
                      <option value="{{ $p }}">{{ $p }}</option>
                    @endforeach
                  </select>
                  <button type="submit" class="adm-btn adm-btn--yellow adm-btn--sm">+ Donner</button>
                </form>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
function showTab(id) {
  document.querySelectorAll('.adm-tab-panel').forEach(p => p.classList.remove('active'));
  document.querySelectorAll('.adm-tab').forEach(b => b.classList.remove('active'));
  document.getElementById(id).classList.add('active');
  document.getElementById('btn-' + id).classList.add('active');
}
</script>
@endsection
