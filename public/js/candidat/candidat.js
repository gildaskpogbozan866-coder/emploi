/* Shared JS for all candidat pages */

function initSidebarUser() {
  var user = JSON.parse(localStorage.getItem('current_user') || '{}');
  var fullName = ((user.prenom || '') + ' ' + (user.nom || '')).trim();
  var nameEl = document.getElementById('sidebarName');
  var avEl   = document.getElementById('sidebarAvatar');
  var badge  = document.getElementById('sidebarBadge');
  if (nameEl && fullName) nameEl.textContent = fullName;
  if (avEl) {
    var initials = ((user.prenom || 'C')[0] + (user.nom || 'V')[0]).toUpperCase();
    avEl.textContent = initials;
  }
  if (badge) {
    var sub = null;
    try { sub = JSON.parse(localStorage.getItem('cv_subscription') || 'null'); } catch(e) {}
    var isPrem = !!(user.premium || (sub && sub.plan === 'Premium'));
    if (isPrem) {
      badge.textContent = 'Premium ✓';
      badge.className = 'cand-sidebar__badge cand-sidebar__badge--premium';
    } else {
      badge.textContent = 'Plan Gratuit';
      badge.className = 'cand-sidebar__badge cand-sidebar__badge--free';
    }
  }
}

function toggleSidebar() {
  var nav = document.getElementById('sidebarNav');
  if (nav) nav.classList.toggle('open');
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

document.addEventListener('DOMContentLoaded', function () {
  initSidebarUser();
  var ham  = document.getElementById('hamburger');
  var menu = document.getElementById('mobileMenu');
  if (ham && menu) {
    ham.addEventListener('click', function () {
      var open = menu.classList.toggle('open');
      ham.setAttribute('aria-expanded', String(open));
    });
  }
});
