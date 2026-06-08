@if(session('success'))
  <div class="flash flash--success" role="alert">
    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
    {{ session('success') }}
  </div>
@endif

@if(session('error'))
  <div class="flash flash--error" role="alert">
    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
    {{ session('error') }}
  </div>
@endif

@if(session('warning'))
  <div class="flash flash--warning" role="alert">
    {{ session('warning') }}
  </div>
@endif

@error('session')
  <div class="flash flash--warning" role="alert" style="display:flex;align-items:center;gap:8px">
    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
    {{ $message }}
  </div>
@enderror

@php
  $clesFormulaire = ['session', 'credentials', 'email', 'password',
      'mot_de_passe_actuel', 'password_confirmation', 'prenom', 'nom', 'tel', 'pays',
      'role', 'entreprise', 'metier', 'admin_notification_email', 'note_admin',
      'token', 'carte_biometrique', 'cip', 'ifu_numero', 'ifu_fichier',
      'rccm_numero', 'rccm_fichier'];
  $autresMessages = collect($errors->toArray())
      ->except($clesFormulaire)
      ->flatten()
      ->all();
@endphp
@if(!empty($autresMessages))
  <div class="flash flash--error" role="alert">
    <ul style="margin:0;padding-left:1.2em">
      @foreach($autresMessages as $msg)
        <li>{{ $msg }}</li>
      @endforeach
    </ul>
  </div>
@endif
