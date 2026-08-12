(function () {
    'use strict';

    document.addEventListener('click', function (event) {
        var trigger = event.target.closest('[data-mgd-dialog-open]');
        if (!trigger) {
            return;
        }
        var dialog = document.getElementById(trigger.getAttribute('data-mgd-dialog-open'));
        if (dialog && typeof dialog.showModal === 'function') {
            trigger.setAttribute('aria-expanded', 'true');
            dialog.showModal();
            dialog.addEventListener('close', function onClose() {
                trigger.setAttribute('aria-expanded', 'false');
                trigger.focus();
                dialog.removeEventListener('close', onClose);
            });
        }
    });
}());
