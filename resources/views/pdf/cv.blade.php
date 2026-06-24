<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<style>
* { margin: 0; padding: 0; box-sizing: border-box; }
body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #1e293b; background: #fff; }

/* ── HEADER ── */
.header {
  background-color: #042C53;
  padding: 0;
}
.header-inner {
  padding: 22px 32px;
}
.header-table {
  width: 100%;
  border-collapse: collapse;
}
.header-logo-cell {
  width: 52px;
  vertical-align: middle;
}
.header-logo-box {
  width: 44px;
  height: 44px;
  background-color: #F5C842;
  border-radius: 8px;
  text-align: center;
  line-height: 44px;
  font-size: 22px;
  font-weight: 900;
  color: #042C53;
  display: block;
}
.header-brand-cell {
  vertical-align: middle;
  padding-left: 12px;
}
.header-brand-name {
  font-size: 15px;
  font-weight: 700;
  color: #ffffff;
  letter-spacing: 0.01em;
}
.header-brand-sub {
  font-size: 9.5px;
  color: rgba(255,255,255,0.55);
  margin-top: 2px;
}
.header-meta-cell {
  text-align: right;
  vertical-align: middle;
}
.header-meta-label {
  font-size: 9px;
  color: #F5C842;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.1em;
}
.header-meta-value {
  font-size: 9px;
  color: rgba(255,255,255,0.55);
  margin-top: 3px;
}

/* ── BANDE ACCENT ── */
.accent-bar {
  height: 4px;
  background-color: #F5C842;
}

/* ── HERO CANDIDAT ── */
.hero {
  background-color: #f8fafc;
  border-bottom: 1px solid #e2e8f0;
  padding: 20px 32px;
}
.hero-table { width: 100%; border-collapse: collapse; }
.hero-avatar-cell { width: 68px; vertical-align: middle; }
.hero-avatar {
  width: 62px;
  height: 62px;
  border-radius: 50%;
  background-color: #042C53;
  text-align: center;
  line-height: 62px;
  font-size: 22px;
  font-weight: 800;
  color: #ffffff;
  display: block;
  border: 3px solid #185FA5;
}
.hero-info-cell { vertical-align: middle; padding-left: 16px; }
.hero-name { font-size: 18px; font-weight: 800; color: #042C53; }
.hero-titre { font-size: 12px; color: #185FA5; font-weight: 600; margin-top: 3px; }
.hero-dispo {
  display: inline-block;
  margin-top: 7px;
  padding: 3px 12px;
  border-radius: 99px;
  font-size: 9.5px;
  font-weight: 700;
  border: 1px solid #bbf7d0;
  background-color: #dcfce7;
  color: #15803d;
}
.hero-dispo.ouvert { background-color: #fef9c3; border-color: #fde68a; color: #92400e; }
.hero-dispo.indisponible { background-color: #fee2e2; border-color: #fecaca; color: #991b1b; }

/* ── CONTENU ── */
.content { padding: 24px 32px; }

/* ── SECTION ── */
.section { margin-bottom: 20px; }
.section-header {
  margin-bottom: 10px;
  padding-bottom: 6px;
  border-bottom: 1.5px solid #e2e8f0;
}
.section-header-table { width: 100%; border-collapse: collapse; }
.section-bar-cell { width: 4px; }
.section-bar {
  display: block;
  width: 4px;
  height: 16px;
  background-color: #185FA5;
  border-radius: 2px;
}
.section-title-cell { padding-left: 8px; vertical-align: middle; }
.section-title {
  font-size: 9px;
  font-weight: 800;
  text-transform: uppercase;
  letter-spacing: 0.12em;
  color: #185FA5;
}

/* ── CONTACT GRID ── */
.contact-table { width: 100%; border-collapse: collapse; }
.contact-item { vertical-align: top; padding: 6px 8px 6px 0; width: 50%; }
.contact-label {
  font-size: 8.5px;
  font-weight: 700;
  color: #94a3b8;
  text-transform: uppercase;
  letter-spacing: 0.07em;
  margin-bottom: 2px;
}
.contact-value { font-size: 11.5px; color: #042C53; font-weight: 600; }

/* ── TEXTE ── */
.text-block { font-size: 11px; color: #374151; line-height: 1.8; white-space: pre-wrap; }

/* ── TAGS ── */
.tags-wrap { line-height: 2; }
.tag {
  display: inline-block;
  background-color: #eff6ff;
  color: #1d4ed8;
  border: 1px solid #bfdbfe;
  padding: 2px 10px;
  border-radius: 99px;
  font-size: 10px;
  font-weight: 600;
  margin-right: 4px;
}

/* ── FOOTER ── */
.footer {
  background-color: #042C53;
  padding: 13px 32px;
  margin-top: 24px;
}
.footer-table { width: 100%; border-collapse: collapse; }
.footer-left-cell { vertical-align: middle; }
.footer-left { font-size: 9px; color: rgba(255,255,255,0.5); line-height: 1.7; }
.footer-left strong { color: rgba(255,255,255,0.85); }
.footer-right-cell { text-align: right; vertical-align: middle; }
.footer-right { font-size: 9px; color: rgba(255,255,255,0.4); line-height: 1.7; }

/* ── CONFIDENTIALITÉ ── */
.confidentiel {
  text-align: center;
  font-size: 8.5px;
  color: #cbd5e1;
  padding: 8px 32px 12px;
  font-style: italic;
}
</style>
</head>
<body>

{{-- ═══ EN-TÊTE ═══ --}}
<div class="header">
  <div class="header-inner">
    <table class="header-table">
      <tr>
        <td class="header-logo-cell">
          <span class="header-logo-box">E</span>
        </td>
        <td class="header-brand-cell">
          <div class="header-brand-name">Emploi Bouge Bénin</div>
          <div class="header-brand-sub">La plateforme de l'emploi au Bénin</div>
        </td>
        <td class="header-meta-cell">
          <div class="header-meta-label">Fiche Candidat</div>
          <div class="header-meta-value">
            Généré le {{ now()->format('d/m/Y à H:i') }}<br>
            Réf. CV #{{ $cv->id }}
          </div>
        </td>
      </tr>
    </table>
  </div>
</div>
<div class="accent-bar"></div>

{{-- ═══ HERO ═══ --}}
<div class="hero">
  <table class="hero-table">
    <tr>
      <td class="hero-avatar-cell">
        <span class="hero-avatar">{{ mb_strtoupper(mb_substr($cv->candidat?->prenom ?? '?', 0, 1)) }}{{ mb_strtoupper(mb_substr($cv->candidat?->nom ?? '', 0, 1)) }}</span>
      </td>
      <td class="hero-info-cell">
        <div class="hero-name">{{ $cv->candidat?->prenom }} {{ $cv->candidat?->nom }}</div>
        <div class="hero-titre">{{ $cv->metier }}</div>
      </td>
    </tr>
  </table>
</div>

{{-- ═══ CONTENU ═══ --}}
<div class="content">

  {{-- Contact --}}
  <div class="section">
    <div class="section-header">
      <table class="section-header-table"><tr>
        <td class="section-bar-cell"><span class="section-bar"></span></td>
        <td class="section-title-cell"><span class="section-title">Informations de contact</span></td>
      </tr></table>
    </div>
    <table class="contact-table">
      <tr>
        @if($cv->candidat?->prenom || $cv->candidat?->nom)
        <td class="contact-item">
          <div class="contact-label">Nom complet</div>
          <div class="contact-value">{{ $cv->candidat->prenom }} {{ $cv->candidat->nom }}</div>
        </td>
        @endif
        @if($cv->candidat?->email)
        <td class="contact-item">
          <div class="contact-label">Email</div>
          <div class="contact-value">{{ $cv->candidat->email }}</div>
        </td>
        @endif
      </tr>
      <tr>
        @if($cv->candidat?->telephone ?? $cv->candidat?->tel ?? null)
        <td class="contact-item">
          <div class="contact-label">Téléphone</div>
          <div class="contact-value">{{ $cv->candidat->telephone ?? $cv->candidat->tel }}</div>
        </td>
        @endif
        @if($cv->ville)
        <td class="contact-item">
          <div class="contact-label">Ville</div>
          <div class="contact-value">{{ $cv->ville }}</div>
        </td>
        @endif
      </tr>
    </table>
  </div>

  @if($cv->resume)
  <div class="section">
    <div class="section-header">
      <table class="section-header-table"><tr>
        <td class="section-bar-cell"><span class="section-bar"></span></td>
        <td class="section-title-cell"><span class="section-title">Résumé / Présentation</span></td>
      </tr></table>
    </div>
    <p class="text-block">{{ $cv->resume }}</p>
  </div>
  @endif

  @if($cv->competences)
  <div class="section">
    <div class="section-header">
      <table class="section-header-table"><tr>
        <td class="section-bar-cell"><span class="section-bar"></span></td>
        <td class="section-title-cell"><span class="section-title">Compétences</span></td>
      </tr></table>
    </div>
    @php $comps = array_values(array_filter(array_map('trim', preg_split('/[,;\n]+/', $cv->competences)))); @endphp
    @if(count($comps) > 1)
      <div class="tags-wrap">@foreach($comps as $c)<span class="tag">{{ $c }}</span>@endforeach</div>
    @else
      <p class="text-block">{{ $cv->competences }}</p>
    @endif
  </div>
  @endif

  @if($cv->experience)
  <div class="section">
    <div class="section-header">
      <table class="section-header-table"><tr>
        <td class="section-bar-cell"><span class="section-bar"></span></td>
        <td class="section-title-cell"><span class="section-title">Expériences professionnelles</span></td>
      </tr></table>
    </div>
    <p class="text-block">{{ $cv->experience }}</p>
  </div>
  @endif

  @if($cv->formation)
  <div class="section">
    <div class="section-header">
      <table class="section-header-table"><tr>
        <td class="section-bar-cell"><span class="section-bar"></span></td>
        <td class="section-title-cell"><span class="section-title">Formation</span></td>
      </tr></table>
    </div>
    <p class="text-block">{{ $cv->formation }}</p>
  </div>
  @endif

  @if($cv->langues)
  <div class="section">
    <div class="section-header">
      <table class="section-header-table"><tr>
        <td class="section-bar-cell"><span class="section-bar"></span></td>
        <td class="section-title-cell"><span class="section-title">Langues</span></td>
      </tr></table>
    </div>
    <p class="text-block">{{ $cv->langues }}</p>
  </div>
  @endif

</div>

{{-- ═══ FOOTER ═══ --}}
<div class="footer">
  <table class="footer-table">
    <tr>
      <td class="footer-left-cell">
        <div class="footer-left">Document généré par <strong>Emploi Bouge Bénin</strong> · Le {{ now()->format('d/m/Y à H:i') }}</div>
      </td>
      <td class="footer-right-cell">
        <div class="footer-right">emploibouge.bj · contact@emploibouge.bj</div>
      </td>
    </tr>
  </table>
</div>
<div class="confidentiel">Document confidentiel, réservé au recruteur ayant débloqué ce profil.</div>

</body>
</html>
