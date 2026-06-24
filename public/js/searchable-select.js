/**
 * Searchable select — transforms <select data-searchable> into a
 * type-to-filter dropdown. Drop is portalled to <body> to bypass
 * overflow:hidden containers.
 */
(function () {
  'use strict';

  var activeClose = null;

  function esc(str) {
    var d = document.createElement('div');
    d.appendChild(document.createTextNode(String(str)));
    return d.innerHTML;
  }

  function build(select) {
    if (select.dataset.ssInit) return;
    select.dataset.ssInit = '1';

    var autoSubmit = /form\.submit/.test(select.getAttribute('onchange') || '');
    select.removeAttribute('onchange');
    select.style.display = 'none';

    // Wrapper replaces the select in the flow
    var wrap = document.createElement('div');
    wrap.className = 'ss-wrap';
    select.parentNode.insertBefore(wrap, select);
    wrap.appendChild(select);

    // Trigger button
    var btn = document.createElement('div');
    btn.className = 'ss-btn';
    btn.setAttribute('tabindex', '0');
    btn.setAttribute('role', 'combobox');
    btn.setAttribute('aria-haspopup', 'listbox');
    btn.setAttribute('aria-expanded', 'false');
    wrap.appendChild(btn);

    // Drop portalled to body
    var drop = document.createElement('div');
    drop.className = 'ss-drop';
    document.body.appendChild(drop);

    var srch = document.createElement('input');
    srch.type = 'text';
    srch.className = 'ss-search';
    srch.placeholder = 'Rechercher…';
    srch.setAttribute('autocomplete', 'off');
    drop.appendChild(srch);

    var ul = document.createElement('ul');
    ul.className = 'ss-list';
    ul.setAttribute('role', 'listbox');
    drop.appendChild(ul);

    var isOpen = false;

    function renderBtn() {
      var opt = select.options[select.selectedIndex];
      var hasVal = !!(opt && opt.value);
      var label = hasVal ? opt.textContent.trim()
                         : (select.options[0] ? select.options[0].textContent.trim() : '—');
      btn.innerHTML =
        '<span class="ss-btn__label' + (hasVal ? ' ss-btn__label--val' : '') + '">' +
        esc(label) +
        '</span><svg class="ss-chevron" width="11" height="11" fill="none" viewBox="0 0 24 24"' +
        ' stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round"' +
        ' stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>';
    }

    function buildList(q) {
      ul.innerHTML = '';
      q = (q || '').toLowerCase().trim();
      var count = 0;
      Array.from(select.options).forEach(function (opt) {
        var txt = opt.textContent.trim();
        if (q && txt.toLowerCase().indexOf(q) === -1) return;
        var li = document.createElement('li');
        li.className = 'ss-option' + (opt.selected ? ' ss-option--sel' : '');
        li.setAttribute('role', 'option');
        if (q) {
          var i = txt.toLowerCase().indexOf(q);
          li.innerHTML =
            esc(txt.slice(0, i)) +
            '<mark>' + esc(txt.slice(i, i + q.length)) + '</mark>' +
            esc(txt.slice(i + q.length));
        } else {
          li.textContent = txt;
        }
        li.addEventListener('mousedown', function (e) {
          e.preventDefault();
          select.value = opt.value;
          renderBtn();
          close();
          if (autoSubmit && select.form) select.form.submit();
        });
        ul.appendChild(li);
        count++;
      });
      if (!count) {
        var emp = document.createElement('li');
        emp.className = 'ss-empty';
        emp.textContent = 'Aucun résultat';
        ul.appendChild(emp);
      }
    }

    function positionDrop() {
      var r = wrap.getBoundingClientRect();
      drop.style.position = 'absolute';
      drop.style.zIndex   = '9999';
      drop.style.top      = (window.scrollY + r.bottom + 4) + 'px';
      drop.style.left     = (window.scrollX + r.left) + 'px';
      drop.style.width    = r.width + 'px';
      drop.style.minWidth = '200px';
    }

    function open() {
      if (isOpen) return;
      if (activeClose && activeClose !== close) activeClose();
      activeClose = close;
      isOpen = true;
      positionDrop();
      drop.classList.add('ss-drop--open');
      btn.classList.add('ss-btn--open');
      btn.setAttribute('aria-expanded', 'true');
      srch.value = '';
      buildList('');
      setTimeout(function () {
        srch.focus();
        var sel = ul.querySelector('.ss-option--sel');
        if (sel) sel.scrollIntoView({ block: 'nearest' });
      }, 0);
    }

    function close() {
      if (!isOpen) return;
      isOpen = false;
      drop.classList.remove('ss-drop--open');
      btn.classList.remove('ss-btn--open');
      btn.setAttribute('aria-expanded', 'false');
      if (activeClose === close) activeClose = null;
    }

    btn.addEventListener('click', function (e) {
      e.stopPropagation();
      isOpen ? close() : open();
    });

    btn.addEventListener('keydown', function (e) {
      if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); open(); }
      if (e.key === 'Escape') close();
      if (e.key === 'ArrowDown' && !isOpen) open();
    });

    srch.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') { close(); btn.focus(); }
    });

    srch.addEventListener('input', function () { buildList(this.value); });

    document.addEventListener('click', function (e) {
      if (!wrap.contains(e.target) && !drop.contains(e.target)) close();
    });

    window.addEventListener('scroll', function () { if (isOpen) { positionDrop(); } }, { passive: true });
    window.addEventListener('resize', function () { if (isOpen) positionDrop(); });

    renderBtn();
  }

  function init() {
    document.querySelectorAll('select[data-searchable]').forEach(build);
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
