// Клиенттік тексеру: құпиясөздердің сәйкестігі (серверде де тексеріледі)
document.addEventListener('DOMContentLoaded', function () {
    var confirmInput = document.querySelector('input[name="confirm"]');
    if (!confirmInput) return;
    var form = confirmInput.form;
    form.addEventListener('submit', function (ev) {
        var p = form.querySelector('input[name="password"]').value;
        if (p !== confirmInput.value) {
            ev.preventDefault();
            alert('Құпиясөздер сәйкес емес!');
        }
    });
});

// Конфетти: рақмет бетінде (CSS анимациясымен)
document.addEventListener('DOMContentLoaded', function () {
    var host = document.querySelector('.confetti-host');
    if (!host) return;
    var icons = ['🎉', '✨', '🌸', '🍓', '💖', '🎀', '⭐'];
    var n = parseInt(host.getAttribute('data-confetti'), 10) || 30;
    for (var i = 0; i < n; i++) {
        var el = document.createElement('i');
        el.textContent = icons[Math.floor(Math.random() * icons.length)];
        el.style.left = Math.random() * 100 + '%';
        el.style.fontSize = (14 + Math.random() * 18) + 'px';
        el.style.animationDuration = (2.5 + Math.random() * 3) + 's';
        el.style.animationDelay = (Math.random() * 1.5) + 's';
        host.appendChild(el);
    }
});

// Жою алдында растау сұрау (серверде иелік пен CSRF бәрібір тексеріледі)
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('form[data-confirm]').forEach(function (form) {
        form.addEventListener('submit', function (ev) {
            if (!confirm(form.getAttribute('data-confirm'))) {
                ev.preventDefault();
            }
        });
    });
});
