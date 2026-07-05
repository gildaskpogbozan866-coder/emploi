<style>
/* ── Champ en erreur (validation client + serveur) ── */
.field--invalid,
input.field--invalid,
select.field--invalid,
textarea.field--invalid {
  border-color: #e53e3e !important;
  box-shadow: 0 0 0 3px rgba(229,62,62,.1) !important;
}
.field__client-error,
.field__server-error {
  display: flex;
  align-items: center;
  gap: 5px;
  margin-top: 5px;
  font-size: 12.5px;
  color: #dc2626;
  font-weight: 500;
  line-height: 1.4;
}
.field__client-error::before,
.field__server-error::before {
  content: '';
  display: inline-block;
  width: 14px;
  height: 14px;
  flex-shrink: 0;
  background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23dc2626' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Ccircle cx='12' cy='12' r='10'/%3E%3Cline x1='12' y1='8' x2='12' y2='12'/%3E%3Cline x1='12' y1='16' x2='12.01' y2='16'/%3E%3C/svg%3E") center/contain no-repeat;
}
</style>
<script>
/* ── Validateur de champs obligatoires (client-side) ── */
(function () {
  function getLabel(field) {
    if (field.dataset.label) return field.dataset.label;
    if (field.id) {
      var lbl = document.querySelector('label[for="' + field.id + '"]');
      if (lbl) return lbl.textContent.replace(/\s*\*\s*$/, '').trim();
    }
    var wrap = field.closest('.aform__field, .cand-form-group, .form-row > div, .field, fieldset');
    if (wrap) {
      var lbl = wrap.querySelector('.field__label, .aform__label, .cand-form-label, label');
      if (lbl) return lbl.textContent.replace(/\s*\*\s*$/, '').trim();
    }
    return null;
  }

  function clearErrors(form) {
    form.querySelectorAll('.field__client-error').forEach(function (e) { e.remove(); });
    form.querySelectorAll('.field--invalid').forEach(function (e) { e.classList.remove('field--invalid'); });
  }

  function addError(field, msg) {
    field.classList.add('field--invalid');
    var p = document.createElement('p');
    p.className = 'field__client-error';
    p.textContent = msg;
    var target = field.closest('.langue-row') || field;
    target.insertAdjacentElement('afterend', p);
  }

  document.addEventListener('submit', function (e) {
    var form = e.target;
    if (form.dataset.noValidate !== undefined) return;
    clearErrors(form);

    var required = form.querySelectorAll('input[required], select[required], textarea[required]');
    var firstErr = null;

    required.forEach(function (field) {
      if (field.type === 'checkbox') {
        if (!field.checked) {
          addError(field, 'Veuillez cocher cette case.');
          if (!firstErr) firstErr = field;
        }
        return;
      }
      if (!field.value || !field.value.trim()) {
        var label = getLabel(field);
        var msg = label
          ? 'Veuillez remplir le champ « ' + label.replace(/\s*:$/, '') + ' ».'
          : 'Ce champ est obligatoire.';
        addError(field, msg);
        if (!firstErr) firstErr = field;
      }
    });

    if (firstErr) {
      e.preventDefault();
      e.stopImmediatePropagation();
      var scrollTarget = firstErr.closest('.aform__field, .cand-form-group, .form-row, .depot-card, .cp-modal__body') || firstErr;
      scrollTarget.scrollIntoView({ behavior: 'smooth', block: 'center' });
      firstErr.focus({ preventScroll: true });
    }
  }, true);

  /* Efface l'erreur dès que l'utilisateur commence à remplir le champ */
  document.addEventListener('input', function (e) {
    var field = e.target;
    if (field.classList.contains('field--invalid') && field.value.trim()) {
      field.classList.remove('field--invalid');
      var next = field.nextElementSibling;
      if (next && next.classList.contains('field__client-error')) next.remove();
    }
  }, true);

  document.addEventListener('change', function (e) {
    var field = e.target;
    if (field.classList.contains('field--invalid') && field.value) {
      field.classList.remove('field--invalid');
      var next = field.nextElementSibling;
      if (next && next.classList.contains('field__client-error')) next.remove();
    }
  }, true);
})();

/* ── Désactivation bouton soumettre après validation OK ──
   Écouteur non-capturant (bulle) : il se déclenche APRÈS les validations
   custom attachées directement au formulaire (ex: dépôt de CV), qui peuvent
   annuler la soumission via e.preventDefault() sans stopPropagation(). Sans
   ce délai, le bouton restait bloqué sur "En cours…" alors que le formulaire
   n'était en réalité jamais soumis.
   Les formulaires avec data-confirm (components/flash-swal.blade.php) sont
   exclus ici : leur propre écouteur, lui aussi sur `document` en phase bulle
   mais enregistré APRÈS celui-ci (ordre des <script> dans les layouts), ne
   peut pas encore avoir appelé preventDefault() à ce stade — le bouton serait
   désactivé avant même l'affichage de la boîte de confirmation, et resterait
   bloqué sur "En cours…" si l'utilisateur clique "Annuler". flash-swal gère
   lui-même la désactivation du bouton une fois la confirmation acceptée. */
document.addEventListener('submit', function (e) {
  if (e.defaultPrevented) return;
  if (e.target.dataset.confirm !== undefined) return;
  var btns = e.target.querySelectorAll('[type="submit"]:not([data-no-guard])');
  btns.forEach(function (btn) {
    if (btn.disabled) return;
    btn.disabled = true;
    if (btn.tagName === 'BUTTON') {
      btn.dataset.originalText = btn.textContent;
      btn.textContent = 'En cours…';
    }
  });
});
</script>
