<div
    data-rc-share-notice
    class="pointer-events-none fixed bottom-5 left-1/2 z-[100] max-w-[calc(100vw-2rem)] -translate-x-1/2 rounded-lg bg-primary px-4 py-3 text-center text-sm font-semibold text-white shadow-xl"
    role="status"
    aria-live="polite"
    aria-atomic="true"
    hidden
></div>

{{--
    Zentraler Handler fuer die PageBuilder-Link-Eigenschaft `data-share`.
    Dadurch funktioniert dieselbe Link-Property auf allen oeffentlichen
    Seiten. Auf News-Detailseiten liest sie die artikelbezogenen Open-Graph-
    Metadaten mit Bild, Titel und Kurztext aus dem gemeinsamen Layout.

    Delegiert und idempotent; alte gespeicherte Teilen-Komponenten, die sich
    bereits selbst verdrahten (data-share-wired), werden nicht doppelt
    ausgeloest.
--}}
<script data-rc-share-init>
    (function () {
        if (window.rcShareInit) { return; }
        window.rcShareInit = true;

        function metaContent(selector) {
            var element = document.querySelector(selector);

            return element ? element.getAttribute('content') || '' : '';
        }

        function pageUrl() {
            var canonical = document.querySelector('link[rel="canonical"]');

            return canonical && canonical.href
                ? canonical.href
                : window.location.href.split('#')[0];
        }

        function pageTitle() {
            return metaContent('meta[property="og:title"]') || document.title || '';
        }

        function pageDescription() {
            return metaContent('meta[property="og:description"]')
                || metaContent('meta[name="description"]');
        }

        function showNotice(message) {
            var notice = document.querySelector('[data-rc-share-notice]');

            if (!notice) { return; }

            notice.textContent = message;
            notice.hidden = false;
            window.clearTimeout(window.rcShareNoticeTimer);
            window.rcShareNoticeTimer = window.setTimeout(function () {
                notice.hidden = true;
            }, 2600);
        }

        function legacyCopy(text) {
            var input = document.createElement('textarea');
            var copied = false;
            var previousFocus = document.activeElement;

            input.value = text;
            input.setAttribute('readonly', '');
            input.style.position = 'fixed';
            input.style.top = '0';
            input.style.left = '0';
            input.style.width = '1px';
            input.style.height = '1px';
            input.style.opacity = '0';
            input.style.pointerEvents = 'none';
            document.body.appendChild(input);
            input.focus();
            input.select();
            input.setSelectionRange(0, input.value.length);

            try {
                copied = document.execCommand('copy');
            } catch (error) {
                copied = false;
            }

            input.remove();

            if (previousFocus && typeof previousFocus.focus === 'function') {
                previousFocus.focus();
            }

            return copied;
        }

        function copyText(text) {
            if (window.isSecureContext && navigator.clipboard && navigator.clipboard.writeText) {
                return navigator.clipboard.writeText(text)
                    .then(function () { return true; })
                    .catch(function () { return legacyCopy(text); });
            }

            return Promise.resolve(legacyCopy(text));
        }

        function copyPageUrl() {
            return copyText(pageUrl()).then(function (copied) {
                if (copied) {
                    showNotice('Link wurde in die Zwischenablage kopiert.');

                    return;
                }

                window.prompt('Link kopieren:', pageUrl());
                showNotice('Bitte kopiere den Link aus dem geöffneten Feld.');
            });
        }

        function shouldCopyOnDesktop(event) {
            if (event && event.pointerType) {
                return event.pointerType === 'mouse';
            }

            return !!(window.matchMedia
                && window.matchMedia('(hover: hover) and (pointer: fine)').matches);
        }

        function targetFor(kind) {
            var u = encodeURIComponent(pageUrl());
            var t = encodeURIComponent(pageTitle());

            if (kind === 'facebook') { return 'https://www.facebook.com/sharer/sharer.php?u=' + u; }
            if (kind === 'x') { return 'https://twitter.com/intent/tweet?url=' + u + '&text=' + t; }
            if (kind === 'linkedin') { return 'https://www.linkedin.com/sharing/share-offsite/?url=' + u; }
            if (kind === 'whatsapp') { return 'https://api.whatsapp.com/send?text=' + t + '%20' + u; }
            if (kind === 'email') { return 'mailto:?subject=' + t + '&body=' + u; }

            return null;
        }

        document.addEventListener('click', function (event) {
            var trigger = event.target && event.target.closest
                ? event.target.closest('[data-share], [data-news-role="meta"] a[aria-label="Artikel teilen"]')
                : null;

            if (!trigger || trigger.getAttribute('data-share-wired')) { return; }

            var kind = trigger.getAttribute('data-share') || 'native';

            if (kind === 'native') {
                event.preventDefault();

                if (shouldCopyOnDesktop(event)) {
                    copyPageUrl();
                } else if (navigator.share) {
                    navigator.share({
                        title: pageTitle(),
                        text: pageDescription(),
                        url: pageUrl(),
                    })['catch'](function (error) {
                        if (!error || error.name !== 'AbortError') {
                            copyPageUrl();
                        }
                    });
                } else {
                    copyPageUrl();
                }

                return;
            }

            var target = targetFor(kind);

            if (!target) { return; }

            event.preventDefault();

            if (kind === 'email') {
                window.location.href = target;
            } else {
                window.open(target, '_blank', 'noopener,noreferrer,width=640,height=560');
            }
        });
    })();
</script>
