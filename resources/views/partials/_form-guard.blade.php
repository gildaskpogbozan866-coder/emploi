<script>
document.addEventListener('submit', function (e) {
    var btns = e.target.querySelectorAll('[type="submit"]:not([data-no-guard])');
    btns.forEach(function (btn) {
        if (btn.disabled) return;
        btn.disabled = true;
        if (btn.tagName === 'BUTTON') {
            btn.dataset.originalText = btn.textContent;
            btn.textContent = 'En cours…';
        }
    });
}, true);
</script>
