@extends('layouts.recruteur')
@section('title', 'Mon profil recruteur')

@section('sidebar')
@include('recruteur._sidebar')
@endsection

@section('content')
<div class="rec-topbar">
  <div class="rec-topbar__left">
    <h1>Mon profil recruteur</h1>
    <p>Informations de votre compte et de votre entreprise</p>
  </div>
</div>

<div class="rec-card" style="max-width:680px">
  <div class="rec-card__body">
    <form method="POST" action="{{ route('recruteur.profil.update') }}" enctype="multipart/form-data">
      @csrf @method('PUT')

      {{-- Avatar entreprise --}}
      <div class="rec-avatar-upload">
        <div class="rec-avatar-preview" id="avatar-preview-wrap">
          @if($user->avatar)
            <img id="avatar-preview-img" src="{{ asset('storage/'.$user->avatar) }}">
          @else
            <span id="avatar-preview-initiale">{{ $user->initiale }}</span>
            <img id="avatar-preview-img" src="" style="display:none">
          @endif
        </div>
        <div>
          <p style="font-size:13px;font-weight:600;color:#042C53;margin:0 0 6px">Logo / Photo</p>
          <label for="avatar-input" style="display:inline-flex;align-items:center;gap:7px;cursor:pointer;padding:7px 14px;border:1.5px solid #e2e8f0;border-radius:8px;font-size:13px;font-weight:600;color:#042C53;background:#f8fafc;transition:border-color .15s">
            <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            <span id="avatar-btn-label">Choisir une image</span>
          </label>
          <input type="file" id="avatar-input" name="avatar" accept="image/jpeg,image/png,image/webp,image/gif" style="display:none">
          <p style="font-size:11.5px;color:#94a3b8;margin:6px 0 0">JPG, PNG ou WebP · max 2 Mo</p>
          @error('avatar')
            <p style="font-size:12px;color:#dc2626;font-weight:600;margin:4px 0 0">{{ $message }}</p>
          @enderror
        </div>
      </div>

      <div class="rec-form-grid">
        <div class="rec-form-group">
          <label>Prénom <span style="color:#e53e3e">*</span></label>
          <input type="text" name="prenom" value="{{ old('prenom', $user->prenom) }}" required>
          @error('prenom')<small style="color:#e53e3e">{{ $message }}</small>@enderror
        </div>
        <div class="rec-form-group">
          <label>Nom <span style="color:#e53e3e">*</span></label>
          <input type="text" name="nom" value="{{ old('nom', $user->nom) }}" required>
          @error('nom')<small style="color:#e53e3e">{{ $message }}</small>@enderror
        </div>

        <div class="rec-form-group full">
          <label>Nom de l'entreprise</label>
          <input type="text" name="entreprise" value="{{ old('entreprise', $user->entreprise) }}" placeholder="Ex : TechBénin SARL">
        </div>

        <div class="rec-form-group">
          <label>Pays</label>
          <select name="pays" id="rec-pays">
            <option value="">-- Sélectionnez --</option>
            @foreach($paysList as $p)
              <option value="{{ $p }}" {{ old('pays', $user->pays) === $p ? 'selected' : '' }}>{{ $p }}</option>
            @endforeach
          </select>
        </div>
        <div class="rec-form-group">
          <label>Téléphone</label>
          <div style="display:flex;align-items:stretch">
            <span id="rec-tel-prefix" style="display:flex;align-items:center;justify-content:center;padding:0 12px;background:#f1f5f9;border:1.5px solid #e2e8f0;border-right:none;border-radius:8px 0 0 8px;font-size:13.5px;font-weight:700;color:#042C53;white-space:nowrap;min-width:60px;user-select:none">+229</span>
            <input type="tel" name="tel" id="rec-tel-input" style="border-radius:0 8px 8px 0!important;flex:1;min-width:0"
                   value="{{ old('tel', $user->tel) ? preg_replace('/^\+\d+\s*/', '', old('tel', $user->tel)) : '' }}"
                   placeholder="01 00 00 00">
          </div>
        </div>
      </div>

      <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;padding:12px 16px;margin-bottom:18px;font-size:13px;color:#64748b">
        <strong style="color:#374151">Email :</strong> {{ $user->email }}
        <span style="margin-left:6px;font-size:11px;background:#e2e8f0;color:#64748b;padding:2px 8px;border-radius:20px">non modifiable</span>
      </div>

      <div style="display:flex;gap:10px">
        <button type="submit" class="rec-btn rec-btn--yellow">Mettre à jour mon profil</button>
      </div>
    </form>
  </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/tel-field.js') }}"></script>
<script>initTelField('rec-pays', 'rec-tel-prefix', 'rec-tel-input');</script>
<script>
(function () {
  var input     = document.getElementById('avatar-input');
  var img       = document.getElementById('avatar-preview-img');
  var initiale  = document.getElementById('avatar-preview-initiale');
  var btnLabel  = document.getElementById('avatar-btn-label');

  if (!input || !img) return;

  input.addEventListener('change', function () {
    var file = this.files[0];
    if (!file) return;

    if (file.size > 2 * 1024 * 1024) {
      alert('L\'image est trop lourde. Taille maximale : 2 Mo.');
      this.value = '';
      return;
    }

    var reader = new FileReader();
    reader.onload = function (e) {
      img.src = e.target.result;
      img.style.display = 'block';
      if (initiale) initiale.style.display = 'none';
      btnLabel.textContent = file.name.length > 22
        ? file.name.substring(0, 20) + '…'
        : file.name;
    };
    reader.readAsDataURL(file);
  });
})();
</script>
@endsection
