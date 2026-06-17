/**
 * initTelField(paysId, prefixId, telId)
 * Gère l'indicatif dynamique, les chiffres uniquement,
 * le maxlength par pays et le préfixe "01" obligatoire pour le Bénin.
 */
function initTelField(paysId, prefixId, telId) {

  var dialCodes = {
    'Bénin':'+229','Côte d\'Ivoire':'+225','Sénégal':'+221','Cameroun':'+237',
    'Togo':'+228','Mali':'+223','Burkina Faso':'+226','Niger':'+227',
    'Guinée':'+224','Congo':'+242','République Démocratique du Congo':'+243',
    'Gabon':'+241','Tchad':'+235','Mauritanie':'+222','Ghana':'+233',
    'Nigeria':'+234','Madagascar':'+261','Maroc':'+212','Algérie':'+213',
    'Tunisie':'+216','Libye':'+218','Égypte':'+20','Éthiopie':'+251',
    'Kenya':'+254','Tanzanie':'+255','Ouganda':'+256','Rwanda':'+250',
    'Burundi':'+257','Angola':'+244','Mozambique':'+258','Zambie':'+260',
    'Zimbabwe':'+263','Botswana':'+267','Namibie':'+264','Afrique du Sud':'+27',
    'Djibouti':'+253','Somalie':'+252','Comores':'+269','Maurice':'+230',
    'France':'+33','Belgique':'+32','Suisse':'+41','Portugal':'+351',
    'Espagne':'+34','Italie':'+39','Allemagne':'+49','Royaume-Uni':'+44',
    'Canada':'+1','États-Unis':'+1',
  };

  // len = nb chiffres du numéro LOCAL (sans indicatif)
  // fixed = chiffres pré-remplis et verrouillés
  // ph = placeholder affiché
  var phoneRules = {
    'Bénin':                          { len:10, fixed:'01', ph:'01 XX XX XX XX' },
    'Côte d\'Ivoire':                 { len:10, fixed:'',   ph:'XX XX XX XX XX' },
    'Sénégal':                        { len:9,  fixed:'',   ph:'XX XXX XX XX' },
    'Cameroun':                       { len:9,  fixed:'',   ph:'6 XX XX XX XX' },
    'Togo':                           { len:8,  fixed:'',   ph:'XX XX XX XX' },
    'Mali':                           { len:8,  fixed:'',   ph:'XX XX XX XX' },
    'Burkina Faso':                   { len:8,  fixed:'',   ph:'XX XX XX XX' },
    'Niger':                          { len:8,  fixed:'',   ph:'XX XX XX XX' },
    'Guinée':                         { len:9,  fixed:'',   ph:'XXX XX XX XX' },
    'Congo':                          { len:9,  fixed:'',   ph:'XX XXX XX XX' },
    'République Démocratique du Congo':{ len:9, fixed:'',   ph:'XXX XXX XXX' },
    'Gabon':                          { len:8,  fixed:'',   ph:'XX XX XX XX' },
    'Tchad':                          { len:8,  fixed:'',   ph:'XX XX XX XX' },
    'Mauritanie':                     { len:8,  fixed:'',   ph:'XX XX XX XX' },
    'Ghana':                          { len:9,  fixed:'',   ph:'XX XXX XXXX' },
    'Nigeria':                        { len:10, fixed:'',   ph:'XXX XXX XXXX' },
    'Madagascar':                     { len:9,  fixed:'',   ph:'XX XX XXX XX' },
    'Maroc':                          { len:9,  fixed:'',   ph:'6 XX XX XX XX' },
    'Algérie':                        { len:9,  fixed:'',   ph:'7 XX XX XX XX' },
    'Tunisie':                        { len:8,  fixed:'',   ph:'XX XXX XXX' },
    'France':                         { len:9,  fixed:'',   ph:'6 XX XX XX XX' },
    'Belgique':                       { len:9,  fixed:'',   ph:'4XX XX XX XX' },
    'Suisse':                         { len:9,  fixed:'',   ph:'7X XXX XX XX' },
    'Portugal':                       { len:9,  fixed:'',   ph:'9XX XXX XXX' },
    'Afrique du Sud':                 { len:9,  fixed:'',   ph:'7X XXX XXXX' },
    'Kenya':                          { len:9,  fixed:'',   ph:'7XX XXX XXX' },
    'Éthiopie':                       { len:9,  fixed:'',   ph:'9X XXX XXXX' },
  };

  var paysEl   = document.getElementById(paysId);
  var prefixEl = document.getElementById(prefixId);
  var telEl    = document.getElementById(telId);
  if (!paysEl || !prefixEl || !telEl) return;

  function getRule(pays) {
    return phoneRules[pays] || { len:15, fixed:'', ph:'Numéro de téléphone' };
  }

  function applyRule(pays) {
    var code = dialCodes[pays] || '';
    var rule = getRule(pays);

    // Mise à jour indicatif
    prefixEl.textContent   = code || '—';
    prefixEl.style.opacity = code ? '1' : '0.5';

    // Mise à jour maxlength et placeholder
    telEl.maxLength   = rule.len;
    telEl.placeholder = rule.ph;

    // Pré-remplir le préfixe fixe (ex: "01" pour Bénin)
    if (rule.fixed) {
      var cur = telEl.value.replace(/\D/g, '');
      if (!cur.startsWith(rule.fixed)) {
        telEl.value = rule.fixed;
      }
      // Positionner le curseur après le préfixe fixe
      setTimeout(function () {
        telEl.setSelectionRange(rule.fixed.length, rule.fixed.length);
      }, 0);
    }
  }

  // ── Saisie : chiffres uniquement + respect préfixe fixe ──
  telEl.addEventListener('input', function () {
    var pays = paysEl.value;
    var rule = getRule(pays);

    // Ne garder que les chiffres
    var val = this.value.replace(/\D/g, '');

    // Imposer le préfixe fixe
    if (rule.fixed) {
      if (val.length < rule.fixed.length) {
        val = rule.fixed;
      } else if (!val.startsWith(rule.fixed)) {
        val = rule.fixed + val.slice(rule.fixed.length);
      }
    }

    // Limiter à la longueur max
    if (val.length > rule.len) val = val.substring(0, rule.len);

    this.value = val;
  });

  // ── Bloquer Backspace/Delete sur le préfixe fixe ──
  telEl.addEventListener('keydown', function (e) {
    var pays = paysEl.value;
    var rule = getRule(pays);
    if (!rule.fixed) return;

    var fl  = rule.fixed.length;
    var ss  = this.selectionStart;
    var se  = this.selectionEnd;

    if (e.key === 'Backspace' && ss <= fl && se <= fl) { e.preventDefault(); return; }
    if (e.key === 'Delete'    && ss < fl)               { e.preventDefault(); return; }
  });

  // ── Changement de pays ──
  paysEl.addEventListener('change', function () { applyRule(this.value); });

  // ── Init au chargement ──
  applyRule(paysEl.value);

  // ── Soumission : combine indicatif + numéro ──
  var form = paysEl.closest('form');
  if (form) {
    form.addEventListener('submit', function () {
      var code = prefixEl.textContent.trim();
      var num  = telEl.value.trim();
      if (code && code !== '—' && num && !num.startsWith('+')) {
        telEl.value = code + ' ' + num;
      }
    });
  }
}
