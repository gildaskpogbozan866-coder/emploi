@php
    $flashSuccess = session('success');
    $flashError   = session('error');
    $flashWarning = session('warning');
    $flashInfo    = session('info');
    $flashCvPublie = session('cv_publie');
    $flashAbonnementRequis = session('abonnement_requis');
    $flashAbonnementRequisUrl = session('abonnement_requis_url');
    $sessionError = $errors->first('session');

    $clesIgnorees = ['session','credentials','email','password','mot_de_passe_actuel',
        'password_confirmation','prenom','nom','tel','pays','role','entreprise','metier',
        'admin_notification_email','note_admin','token','carte_biometrique','cip',
        'ifu_numero','ifu_fichier','rccm_numero','rccm_fichier'];
    $autresErreurs = collect($errors->toArray())->except($clesIgnorees)->flatten()->all();
@endphp

{{-- Confirmation SweetAlert2 pour tous les formulaires avec data-confirm --}}
<script>
document.addEventListener('submit', function(e) {
    var form = e.target;
    var msg = form.dataset.confirm;
    if (!msg) return;
    e.preventDefault();
    Swal.fire({
        title: 'Confirmation',
        text: msg,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: form.dataset.confirmColor || '#ef4444',
        cancelButtonColor: '#64748b',
        confirmButtonText: form.dataset.confirmBtn || 'Confirmer',
        cancelButtonText: 'Annuler',
        reverseButtons: true
    }).then(function(result) {
        if (result.isConfirmed) {
            form.removeAttribute('data-confirm');
            var btn = form.querySelector('[type="submit"]:not([data-no-guard])');
            if (btn && !btn.disabled) {
                btn.disabled = true;
                if (btn.tagName === 'BUTTON') {
                    btn.dataset.originalText = btn.textContent;
                    btn.textContent = 'En cours…';
                }
            }
            form.submit();
        }
    });
});
</script>

@if($flashSuccess || $flashError || $flashWarning || $flashInfo || $flashCvPublie || $flashAbonnementRequis || $sessionError || !empty($autresErreurs))
<script>
document.addEventListener('DOMContentLoaded', function () {
    @if($flashAbonnementRequis)
    Swal.fire({
        icon: 'info',
        title: 'Abonnement requis',
        text: {{ Js::from($flashAbonnementRequis) }},
        confirmButtonText: 'Voir les plans',
        confirmButtonColor: '#185FA5',
        showCancelButton: true,
        cancelButtonText: 'Fermer',
        cancelButtonColor: '#94a3b8',
        reverseButtons: true,
    }).then(function(r) {
        @if($flashAbonnementRequisUrl)
        if (r.isConfirmed) window.location.href = {{ Js::from($flashAbonnementRequisUrl) }};
        @endif
    });
    @endif
    @if($flashCvPublie)
    Swal.fire({
        icon: 'success',
        title: 'Profil enregistré !',
        html: '<p style="font-size:15px;color:#374151;margin:0 0 12px">Votre CV est maintenant <strong style="color:#16a34a">visible dans la CVthèque</strong>.<br>Les recruteurs peuvent vous trouver et vous contacter.</p>'
            + '<p style="font-size:13px;color:#6b7280;margin:0">Complétez votre profil à 100&nbsp;% pour apparaître en tête des résultats.</p>',
        confirmButtonText: 'Voir mon CV',
        confirmButtonColor: '#185FA5',
        showCancelButton: true,
        cancelButtonText: 'Continuer',
        cancelButtonColor: '#6b7280',
        reverseButtons: true,
    }).then(function(r) {
        if (r.isConfirmed) window.location.href = '{{ route("candidat.cvs") }}';
    });
    @elseif($flashSuccess)
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
