/* recruteur.js — shared JS for all recruteur pages */

function initSidebarUser() {
  var user = JSON.parse(localStorage.getItem('current_user') || '{}');
  var fullName = ((user.prenom || '') + ' ' + (user.nom || '')).trim() || 'Recruteur';
  var entreprise = user.entreprise || 'Mon entreprise';
  var el = document.getElementById('sidebarName');
  if (el) el.textContent = fullName;
  var elEnt = document.getElementById('sidebarEntreprise');
  if (elEnt) elEnt.textContent = entreprise;
  var avatar = document.getElementById('sidebarAvatar');
  if (avatar) {
    var initials = ((user.prenom || '?')[0] + (user.nom || '?')[0]).toUpperCase();
    if (user.logoUrl) {
      avatar.innerHTML = '<img src="' + user.logoUrl + '" alt="logo">';
    } else {
      avatar.textContent = initials;
    }
  }
  var badge = document.getElementById('sidebarBadge');
  if (badge) {
    /* Lire le plan depuis rec_subscription (plus précis que user.premium) */
    var recSub = null;
    try {
      recSub = JSON.parse(localStorage.getItem('rec_subscription') || 'null')
            || JSON.parse(localStorage.getItem('recruteur_subscription') || 'null');
    } catch(e) {}
    var plan30 = recSub && recSub.plan === 'premium_30';
    var plan50 = recSub && (recSub.plan === 'premium_50' || recSub.plan === 'illimite');
    var isPrem = !!(user.premium || plan30 || plan50 || (recSub && recSub.plan === 'premium'));

    var planLabel = 'Plan Gratuit';
    if (plan50)        planLabel = 'Premium 50 500 F';
    else if (plan30)   planLabel = 'Premium 30 300 F';
    else if (isPrem)   planLabel = 'Premium';

    badge.textContent = planLabel;
    badge.className   = isPrem ? 'rec-sidebar__badge' : 'rec-sidebar__badge rec-sidebar__badge--free';
  }
  // update nav badges
  var notifBadge = document.getElementById('navNotifBadge');
  if (notifBadge) {
    var notifs = JSON.parse(localStorage.getItem('notifications_rec') || '[]');
    var unread = notifs.filter(function(n) { return !n.lu; }).length;
    notifBadge.textContent = unread;
    notifBadge.style.display = unread ? '' : 'none';
  }
  var msgBadge = document.getElementById('navMsgBadge');
  if (msgBadge) {
    var convs = JSON.parse(localStorage.getItem('conversations_rec') || '[]');
    var unreadMsg = convs.reduce(function(acc, c) { return acc + (c.nonLus || 0); }, 0);
    msgBadge.textContent = unreadMsg;
    msgBadge.style.display = unreadMsg ? '' : 'none';
  }
}

function toggleSidebar() {
  var sidebar = document.getElementById('recSidebar');
  if (sidebar) sidebar.classList.toggle('open');
}

function deconnexion(e) {
  if (e) e.preventDefault();
  if (confirm('Voulez-vous vraiment vous déconnecter ?')) {
    if (typeof App !== 'undefined') { App.logout(); } else {
      localStorage.removeItem('current_user');
      window.location.href = '../auth/connexion.html';
    }
  }
}

function showToast(msg, type) {
  var toast = document.getElementById('recToast');
  if (!toast) return;
  toast.textContent = msg;
  toast.className = 'rec-toast' + (type ? ' rec-toast--' + type : '') + ' show';
  setTimeout(function() { toast.classList.remove('show'); }, 3000);
}

function formatMontant(n) {
  return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ') + ' FCFA';
}

function formatDate(d) {
  if (!d) return '';
  var date = new Date(d);
  return date.toLocaleDateString('fr-FR', { day: '2-digit', month: 'short', year: 'numeric' });
}

document.addEventListener('DOMContentLoaded', function () {
  initSidebarUser();
  var ham = document.getElementById('recHamburger');
  if (ham) ham.addEventListener('click', toggleSidebar);
  // close sidebar on outside click (mobile)
  document.addEventListener('click', function(e) {
    var sidebar = document.getElementById('recSidebar');
    if (!sidebar) return;
    if (sidebar.classList.contains('open') && !sidebar.contains(e.target) && e.target !== ham) {
      sidebar.classList.remove('open');
    }
  });
});
