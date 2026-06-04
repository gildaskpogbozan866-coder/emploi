﻿/* ── Lire les paramètres URL ── */
    (function() {
      var params = new URLSearchParams(window.location.search);
      var pack  = params.get('pack')  || '20';
      var prix  = params.get('prix')  || '70000';
      var usd   = params.get('usd')   || '123.97';

      var prixFmt = parseInt(prix).toLocaleString('fr-FR') + ' FCFA';
      var usdFmt  = parseFloat(usd).toFixed(2) + ' USD';

      document.getElementById('packName').textContent    = 'Pack ' + pack + ' CV';
      document.getElementById('packPrice').innerHTML     = prixFmt.replace(' FCFA', '') + ' <span>FCFA</span>';
      document.getElementById('recapPackName').textContent = pack + ' CV';
      document.getElementById('recapPrix').textContent   = prixFmt;
      document.getElementById('recapUsd').textContent    = usdFmt;
      document.getElementById('recapTotal').textContent  = prixFmt;
      document.getElementById('srPack').textContent      = pack + ' CV';
      document.getElementById('srPrix').textContent      = prixFmt;
      document.getElementById('srUsd').textContent       = usdFmt;
      document.getElementById('srTotal').textContent     = prixFmt;
    })();

    /* ── Sélection mode de paiement ── */
    var payLabels = { mobile: 'Mobile Money', carte: 'Carte bancaire', paypal: 'PayPal', virement: 'Virement bancaire' };
    function selectPay(el) {
      document.querySelectorAll('.pay-method').forEach(function(m) { m.classList.remove('selected'); });
      el.classList.add('selected');
      var val = el.querySelector('input').value;
      document.getElementById('srPaiement').textContent = payLabels[val] || val;
    }

    /* ── Validation et soumission ── */
    function showErr(msg) {
      var e = document.getElementById('cmdError');
      document.getElementById('cmdErrorMsg').textContent = msg;
      e.classList.add('show');
      setTimeout(function() { e.classList.remove('show'); }, 5000);
    }

    function soumettre() {
      var nom     = document.getElementById('fNom').value.trim();
      var prenom  = document.getElementById('fPrenom').value.trim();
      var email   = document.getElementById('fEmail').value.trim();
      var tel     = document.getElementById('fTel').value.trim();
      var secteur = document.getElementById('fSecteur').value;

      if (!nom)     { showErr('Veuillez saisir votre nom.'); document.getElementById('fNom').focus(); return; }
      if (!prenom)  { showErr('Veuillez saisir votre prénom.'); document.getElementById('fPrenom').focus(); return; }
      if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) { showErr('Veuillez saisir un email valide.'); document.getElementById('fEmail').focus(); return; }
      if (!tel)     { showErr('Veuillez saisir votre téléphone.'); document.getElementById('fTel').focus(); return; }
      if (!secteur) { showErr('Veuillez choisir votre secteur d\'activité.'); document.getElementById('fSecteur').focus(); return; }

      var btn = document.getElementById('submitBtn');
      btn.disabled = true;
      btn.innerHTML = '<svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" style="animation:spin 1s linear infinite"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg> Traitement…';

      /* Sauvegarder la commande */
      var params  = new URLSearchParams(window.location.search);
      var commande = {
        id: Date.now(),
        pack: params.get('pack') || '20',
        prix: params.get('prix') || '70000',
        usd:  params.get('usd')  || '123.97',
        paiement: document.querySelector('.pay-method.selected input').value,
        nom: nom, prenom: prenom, email: email, tel: tel, secteur: secteur,
        date: new Date().toLocaleDateString('fr-FR')
      };
      try {
        var cmds = JSON.parse(localStorage.getItem('commandes_cv') || '[]');
        cmds.unshift(commande);
        localStorage.setItem('commandes_cv', JSON.stringify(cmds));
      } catch(e) {}

      /* Sauvegarder les crédits CVthèque */
      var packQty = parseInt(params.get('pack') || '20');
      localStorage.setItem('cv_credits', JSON.stringify({ total: packQty, remaining: packQty }));

      /* Seed données démo si cv_deposes est vide */
      if (!localStorage.getItem('cv_deposes') || JSON.parse(localStorage.getItem('cv_deposes') || '[]').length === 0) {
        var demoCvs = [
          { id: 'demo1', prenom: 'Adeola', nom: 'Fassinou', tel: '+229 96 11 22 33', email: 'adeola.fassinou@gmail.com', age: 27, ville: 'Cotonou', quartier: 'Akpakpa', secteur: 'tech', secteurLabel: 'Technologie · Web', pays: 'Bénin', metier: 'Développeuse Web', metiers: ['Développeuse Web'], disponible: true, contrats: ['CDI', 'CDD'], experience: '3 ans', expLabel: '3 à 5 ans', niveau: 'Bac+3', competences: ['HTML/CSS', 'JavaScript', 'React', 'Node.js', 'Git'], langues: [{ nom: 'Français', niveau: 'Langue maternelle' }], formations: [{ titre: 'Licence Informatique', ecole: 'EPAC — UAC', date: '2019 – 2022' }], attestations: ['Licence Informatique — EPAC 2022', 'Certificat React 2023'], date: '2026-05-10' },
          { id: 'demo2', prenom: 'Kofi', nom: 'Agossou', tel: '+229 97 44 55 66', email: 'kofi.agossou@yahoo.fr', age: 31, ville: 'Porto-Novo', quartier: 'Ouando', secteur: 'finance', secteurLabel: 'Finance · Audit', pays: 'Bénin', metier: 'Comptable', metiers: ['Comptable'], disponible: true, contrats: ['CDI'], experience: '5 ans', expLabel: '5 à 10 ans', niveau: 'Bac+5', competences: ['OHADA', 'Sage Comptabilité', 'Excel', 'Audit interne'], langues: [{ nom: 'Français', niveau: 'Langue maternelle' }], formations: [{ titre: 'Master Comptabilité', ecole: 'ENEAM — UAC', date: '2018 – 2020' }], attestations: ['Master CCA — ENEAM 2020', 'Certification OHADA 2022'], date: '2026-05-08' }
        ];
        localStorage.setItem('cv_deposes', JSON.stringify(demoCvs));
      }

      setTimeout(function() {
        /* Mettre à jour les infos de confirmation */
        var packNb = parseInt(params.get('pack') || '20');
        var packNameEl = document.getElementById('confirmPackNom');
        var packQtyEl  = document.getElementById('confirmQty');
        if (packNameEl) packNameEl.textContent = 'Pack ' + packNb + ' CV';
        if (packQtyEl)  packQtyEl.textContent  = packNb;

        document.getElementById('cmdForm').style.display = 'none';
        document.getElementById('cmdSuccess').classList.add('show');
        window.scrollTo({ top: 0, behavior: 'smooth' });
      }, 1600);
    }

    /* Menu mobile */
    var hamburgerBtn = document.getElementById('hamburger');
    var mobileMenuEl = document.getElementById('mobileMenu');
    hamburgerBtn.addEventListener('click', function() {
      var isOpen = mobileMenuEl.classList.toggle('open');
      hamburgerBtn.classList.toggle('open', isOpen);
      hamburgerBtn.setAttribute('aria-expanded', isOpen);
    });

    function allerAuTableauDeBord() {
      window.location.href = '../recruteur/tableau-de-bord.html';
    }

    /* Les téléchargements se font profil par profil depuis la CVthèque */
    function telechargerPDF() { }
    function telechargerExcel() { }

    /* Ancienne implémentation PDF désactivée */
    function _oldPdfImpl_unused() {

      var jspdf_ref = window.jspdf;
      var doc = new jsPDF({ orientation: 'landscape', unit: 'mm', format: 'a4' });

      var couleurBleu   = [4,  44,  83];
      var couleurJaune  = [245, 200, 66];
      var couleurGris   = [100, 116, 139];
      var couleurBorder = [226, 232, 240];

      /* ── Page de garde ── */
      doc.setFillColor(couleurBleu[0], couleurBleu[1], couleurBleu[2]);
      doc.rect(0, 0, 297, 210, 'F');

      doc.setTextColor(245, 200, 66);
      doc.setFontSize(28); doc.setFont('helvetica', 'bold');
      doc.text('CVthèque — Emploi Bouge Bénin', 148.5, 80, { align: 'center' });

      doc.setTextColor(255, 255, 255);
      doc.setFontSize(14); doc.setFont('helvetica', 'normal');
      doc.text('Pack ' + cvs.length + ' CV — Export de profils candidats', 148.5, 96, { align: 'center' });

      var dateAujourd = new Date().toLocaleDateString('fr-FR', { day:'numeric', month:'long', year:'numeric' });
      doc.setFontSize(11); doc.setTextColor(180, 200, 220);
      doc.text('Généré le ' + dateAujourd, 148.5, 112, { align: 'center' });

      /* ── Tableau récapitulatif ── */
      doc.addPage();

      doc.setFillColor(couleurBleu[0], couleurBleu[1], couleurBleu[2]);
      doc.rect(0, 0, 297, 20, 'F');
      doc.setTextColor(245, 200, 66);
      doc.setFontSize(12); doc.setFont('helvetica', 'bold');
      doc.text('CVthèque — ' + cvs.length + ' profils', 14, 13);
      doc.setTextColor(200, 220, 240);
      doc.setFontSize(9); doc.setFont('helvetica', 'normal');
      doc.text('Emploi Bouge Bénin — ' + dateAujourd, 297 - 14, 13, { align: 'right' });

      var colonnes = [
        { header: 'N°',      dataKey: 'N°' },
        { header: 'Prénom',  dataKey: 'Prénom' },
        { header: 'Nom',     dataKey: 'Nom' },
        { header: 'Email',   dataKey: 'Email' },
        { header: 'Ville',   dataKey: 'Ville' },
        { header: 'Métier',  dataKey: 'Métier' },
        { header: 'Contrat', dataKey: 'Contrat' },
        { header: 'Dispo',   dataKey: 'Disponibilité' },
        { header: 'Formation',  dataKey: 'Formation' },
        { header: 'Compétences',dataKey: 'Compétences' },
      ];

      var lignes = preparerLignes(cvs);

      doc.autoTable({
        startY: 24,
        columns: colonnes,
        body: lignes,
        theme: 'grid',
        styles: {
          font: 'helvetica', fontSize: 8,
          cellPadding: 3,
          textColor: [30, 41, 59],
          lineColor: couleurBorder,
          lineWidth: 0.2,
          overflow: 'linebreak',
        },
        headStyles: {
          fillColor: couleurBleu,
          textColor: [245, 200, 66],
          fontStyle: 'bold',
          fontSize: 8.5,
        },
        alternateRowStyles: { fillColor: [248, 250, 255] },
        columnStyles: {
          0:  { cellWidth: 8,  halign: 'center' },
          1:  { cellWidth: 22 },
          2:  { cellWidth: 22 },
          3:  { cellWidth: 46 },
          4:  { cellWidth: 22 },
          5:  { cellWidth: 34 },
          6:  { cellWidth: 18 },
          7:  { cellWidth: 16 },
          8:  { cellWidth: 35 },
          9:  { cellWidth: 'auto' },
        },
        margin: { left: 14, right: 14 },
        didDrawPage: function(data) {
          /* En-tête sur chaque page */
          if (data.pageNumber > 1) {
            doc.setFillColor(couleurBleu[0], couleurBleu[1], couleurBleu[2]);
            doc.rect(0, 0, 297, 16, 'F');
            doc.setTextColor(245, 200, 66);
            doc.setFontSize(9); doc.setFont('helvetica', 'bold');
            doc.text('CVthèque — Emploi Bouge Bénin', 14, 10);
          }
          /* Pied de page */
          var nb = doc.internal.getNumberOfPages();
          doc.setFontSize(8); doc.setTextColor(couleurGris[0], couleurGris[1], couleurGris[2]);
          doc.text('Page ' + data.pageNumber + ' / ' + nb, 148.5, 205, { align: 'center' });
        }
      });

      /* ── Fiches individuelles ── */
      cvs.forEach(function(p, idx) {
        doc.addPage();

        /* Bandeau nom */
        doc.setFillColor(couleurBleu[0], couleurBleu[1], couleurBleu[2]);
        doc.rect(0, 0, 297, 24, 'F');

        doc.setTextColor(255, 255, 255);
        doc.setFontSize(16); doc.setFont('helvetica', 'bold');
        doc.text((p.prenom || '') + ' ' + (p.nom || ''), 14, 15);

        doc.setTextColor(couleurJaune[0], couleurJaune[1], couleurJaune[2]);
        doc.setFontSize(9);
        doc.text((p.metiers || [p.metier]).filter(Boolean).join(' · '), 14, 21);

        doc.setTextColor(180, 200, 220);
        doc.setFontSize(8);
        doc.text('Profil ' + (idx + 1) + ' / ' + cvs.length, 297 - 14, 15, { align: 'right' });

        var y = 32;
        function section(titre) {
          doc.setFillColor(240, 246, 255);
          doc.rect(14, y - 4, 269, 7, 'F');
          doc.setTextColor(couleurBleu[0], couleurBleu[1], couleurBleu[2]);
          doc.setFontSize(8); doc.setFont('helvetica', 'bold');
          doc.text(titre.toUpperCase(), 16, y + 0.5);
          y += 8;
          doc.setFont('helvetica', 'normal');
          doc.setTextColor(30, 41, 59);
        }

        function ligne(lbl, val) {
          if (!val) return;
          doc.setFontSize(8.5); doc.setFont('helvetica', 'bold'); doc.setTextColor(couleurGris[0], couleurGris[1], couleurGris[2]);
          doc.text(lbl + ' :', 16, y);
          doc.setFont('helvetica', 'normal'); doc.setTextColor(30, 41, 59);
          var lines = doc.splitTextToSize(String(val), 200);
          doc.text(lines, 58, y);
          y += lines.length * 5 + 1;
          if (y > 190) { doc.addPage(); y = 20; }
        }

        section('Informations personnelles');
        ligne('Email',       p.email || '—');
        ligne('Téléphone',   p.tel   || '—');
        ligne('Ville',       (p.ville || '') + ', ' + (p.pays || 'Bénin'));
        ligne('Disponibilité', p.disponible ? 'Immédiate' : 'À définir');
        y += 3;

        section('Profil professionnel');
        ligne('Métier(s)',   (p.metiers || [p.metier]).filter(Boolean).join(', '));
        ligne('Contrat',     (p.contrats || []).join(', '));
        ligne('Mobilité',    (p.mobilite || []).join(', ') || '—');
        y += 3;

        if ((p.competences || []).length) {
          section('Compétences');
          ligne('Compétences', p.competences.join(' · '));
          y += 3;
        }

        if ((p.formations || []).filter(function(f){ return f.titre && f.titre !== 'Non précisé'; }).length) {
          section('Formation');
          p.formations.forEach(function(f) {
            if (f.titre && f.titre !== 'Non précisé') {
              ligne(f.titre, (f.ecole ? f.ecole : '') + (f.date ? ' (' + f.date + ')' : ''));
            }
          });
          y += 3;
        }

        if ((p.langues || []).length) {
          section('Langues');
          ligne('Langues', p.langues.map(function(l){ return l.nom + (l.niveau ? ' — ' + l.niveau : ''); }).join(' · '));
        }

        /* Pied de page */
        doc.setFontSize(8); doc.setTextColor(couleurGris[0], couleurGris[1], couleurGris[2]);
        doc.text('CVthèque — Emploi Bouge Bénin — ' + dateAujourd, 148.5, 205, { align: 'center' });
      });

      doc.save('CVtheque_Pack' + cvs.length + '_' + dateAujourd.replace(/ /g,'-') + '.pdf');

      btn.disabled = false;
      btn.innerHTML = '<svg width="17" height="17" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg> Télécharger en PDF';
    } /* fin _oldPdfImpl_unused */
