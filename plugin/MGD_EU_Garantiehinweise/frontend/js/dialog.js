/* Der normale Bildlink bleibt als Fallback erhalten; nur GARAN verwendet diesen Dialog. */
(function () {
    'use strict';
    document.addEventListener('click', function (event) {
        if (!(event.target instanceof Element) || event.ctrlKey || event.metaKey || event.shiftKey || event.altKey) {
            return;
        }
        var trigger = event.target.closest('[data-mgd-dialog-open]');
        if (!trigger) { return; }
        var dialog = document.getElementById(trigger.getAttribute('data-mgd-dialog-open'));
        if (!dialog || typeof dialog.showModal !== 'function' || dialog.open) { return; }
        dialog.showModal();
        event.preventDefault();
        trigger.setAttribute('aria-expanded', 'true');
        dialog.addEventListener('close', function onClose() {
            trigger.setAttribute('aria-expanded', 'false');
            trigger.focus();
            dialog.removeEventListener('close', onClose);
        });
    });
}());
