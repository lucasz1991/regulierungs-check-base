import Swal from 'sweetalert2';

const iconMap = (type) => {
    switch (type) {
        case 'success':
            return 'success';
        case 'warning':
            return 'warning';
        case 'error':
            return 'error';
        case 'info':
        default:
            return 'info';
    }
};

window.addEventListener('swal:toast', (e) => {
    const d = e.detail || {};
    const type = d.type || 'info';
    const title = d.title ?? ({
        success: 'Erfolg!',
        warning: 'Warnung!',
        error: 'Fehler!',
        info: 'Hinweis!',
    }[type] || 'Hinweis!');

    const showConfirm = d.showConfirm ?? !!d.redirectTo;

    Swal.fire({
        toast: true,
        position: d.position || 'top-end',
        icon: iconMap(type),
        title,
        text: d.text ?? undefined,
        html: d.html ?? undefined,
        timer: showConfirm ? undefined : (d.timer ?? 4000),
        timerProgressBar: !showConfirm,
        showConfirmButton: showConfirm,
        confirmButtonText: d.confirmText || 'OK',
    }).then((result) => {
        if (d.onConfirm && result.isConfirmed) {
            window.dispatchEvent(
                new CustomEvent(d.onConfirm.name || 'swal:confirmed', {
                    detail: d.onConfirm.detail || {},
                })
            );
        }

        const redirectOn = d.redirectOn || (showConfirm ? 'confirm' : 'close');
        const shouldRedirect = d.redirectTo
            && (
                (redirectOn === 'confirm' && result.isConfirmed)
                || (redirectOn === 'close' && (result.dismiss === Swal.DismissReason.timer || result.isDismissed))
            );

        if (shouldRedirect) {
            window.location.assign(d.redirectTo);
        }
    });
});

window.addEventListener('swal:alert', (e) => {
    window.dispatchEvent(new CustomEvent('swal:toast', { detail: e.detail || {} }));
});
