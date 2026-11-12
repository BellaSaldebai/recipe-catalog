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
