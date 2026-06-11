@php
    $flashSuccess = session('success');
    $flashError   = session('error');
    $flashWarning = session('warning');
    $flashInfo    = session('info');
    $sessionError = $errors->first('session');

    $clesIgnorees = ['session','credentials','email','password','mot_de_passe_actuel',
        'password_confirmation','prenom','nom','tel','pays','role','entreprise','metier',
        'admin_notification_email','note_admin','token','carte_biometrique','cip',
        'ifu_numero','ifu_fichier','rccm_numero','rccm_fichier'];
    $autresErreurs = collect($errors->toArray())->except($clesIgnorees)->flatten()->all();
@endphp

@if($flashSuccess || $flashError || $flashWarning || $flashInfo || $sessionError || !empty($autresErreurs))
<script>
document.addEventListener('DOMContentLoaded', function () {
    @if($flashSuccess)
    Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: {{ Js::from($flashSuccess) }}, showConfirmButton: false, timer: 3000, timerProgressBar: true });
    @endif
    @if($flashWarning)
    Swal.fire({ toast: true, position: 'top-end', icon: 'warning', title: {{ Js::from($flashWarning) }}, showConfirmButton: false, timer: 4000, timerProgressBar: true });
    @endif
    @if($flashInfo)
    Swal.fire({ toast: true, position: 'top-end', icon: 'info', title: {{ Js::from($flashInfo) }}, showConfirmButton: false, timer: 4000, timerProgressBar: true });
    @endif
    @if($flashError)
    Swal.fire({ icon: 'error', title: 'Erreur', text: {{ Js::from($flashError) }}, confirmButtonColor: '#ef4444' });
    @endif
    @if($sessionError)
    Swal.fire({ toast: true, position: 'top-end', icon: 'warning', title: {{ Js::from($sessionError) }}, showConfirmButton: false, timer: 4000, timerProgressBar: true });
    @endif
    @if(!empty($autresErreurs))
    Swal.fire({ icon: 'error', title: 'Erreur de validation', html: {{ Js::from('<ul style="text-align:left;margin:0;padding-left:1.2em">'.implode('', array_map(fn($m) => '<li>'.e($m).'</li>', $autresErreurs)).'</ul>') }}, confirmButtonColor: '#ef4444' });
    @endif
});
</script>
@endif
