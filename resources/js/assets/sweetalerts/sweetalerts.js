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
        if ((result.isConfirmed || result.dismiss === Swal.DismissReason.timer) && d.redirectTo) {
            window.location.assign(d.redirectTo);
        }
    });
});

window.addEventListener('swal:alert', async (e) => {
    const d = e.detail || {};
    const type = d.type || 'info';

    const res = await Swal.fire({
        icon: iconMap(type),
        title: d.title || 'Hinweis',
        text: d.text ?? undefined,
        html: d.html ?? undefined,
        confirmButtonText: d.confirmText || 'OK',
        showCancelButton: !!d.showCancel,
        cancelButtonText: d.cancelText || 'Abbrechen',
        allowOutsideClick: d.allowOutsideClick ?? true,
    });

    if (d.onConfirm && res.isConfirmed) {
        window.dispatchEvent(
            new CustomEvent(d.onConfirm.name || 'swal:confirmed', {
                detail: d.onConfirm.detail || {},
            })
        );
    }

    const redirectOn = d.redirectOn || 'confirm';
    const shouldRedirect = d.redirectTo
        && (
            (redirectOn === 'confirm' && res.isConfirmed)
            || (redirectOn === 'close' && (res.isDismissed || res.isDenied))
        );

    if (shouldRedirect) {
        window.location.assign(d.redirectTo);
    }
});
