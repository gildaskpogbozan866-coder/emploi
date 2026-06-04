/* ── Shared Admin JS ── */

document.addEventListener('DOMContentLoaded', function() {
  initSidebarUser();
  var ham = document.getElementById('admHamburger');
  var sidebar = document.getElementById('admSidebar');
  if (ham && sidebar) {
    ham.addEventListener('click', function() { sidebar.classList.toggle('open'); });
    document.addEventListener('click', function(e) {
      if (sidebar.classList.contains('open') && !sidebar.contains(e.target) && e.target !== ham) {
        sidebar.classList.remove('open');
      }
    });
  }
});

function initSidebarUser() {
  var user = {};
  try { user = JSON.parse(localStorage.getItem('current_admin') || localStorage.getItem('current_user') || '{}'); } catch(e) {}
  var el = document.getElementById('admSidebarName');
  var av = document.getElementById('admSidebarAvatar');
  if (el && user.prenom) el.textContent = (user.prenom + ' ' + (user.nom || '')).trim();
  if (av && user.prenom) av.textContent = (user.prenom[0] + (user.nom ? user.nom[0] : '')).toUpperCase();
}

function deconnexion(e) {
  if (e) e.preventDefault();
  localStorage.removeItem('current_admin');
  localStorage.removeItem('current_user');
  window.location.href = 'connexion.html';
}

function showToast(msg, type) {
  var t = document.getElementById('admToast');
  if (!t) return;
  t.textContent = msg;
  t.className = 'adm-toast' + (type ? ' ' + type : '');
  void t.offsetWidth;
  t.classList.add('show');
  clearTimeout(t._timer);
  t._timer = setTimeout(function() { t.classList.remove('show'); }, 3200);
}

function escHtml(str) {
  return String(str||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

function formatDate(d) {
  if (!d) return '—';
  var dt = new Date(d);
  return isNaN(dt) ? d : dt.toLocaleDateString('fr-FR');
}
