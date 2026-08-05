const TICKER_SELECTOR = '[data-homepage-news-ticker]';
const TRACK_SELECTOR = '.homepage-news-ticker__track';
const DRAG_THRESHOLD = 8;
const CLICK_SUPPRESSION_MS = 800;
const tickerStates = new WeakMap();

// Muss vor Livewires wire:navigate-Listener laufen: Einige Touch-Browser
// erzeugen den Click erst deutlich nach pointerup. In der Capture-Phase am
// Fenster kann genau dieser Drag-Click gestoppt werden, bevor Livewire ihn als
// Navigation verarbeitet.
const suppressDraggedClick = (event) => {
    const ticker = event.target?.closest?.(TICKER_SELECTOR);
    const state = ticker ? tickerStates.get(ticker) : null;

    if (!state?.suppressNextClick) {
        return;
    }

    event.preventDefault();
    event.stopImmediatePropagation();
    state.suppressNextClick = false;
    window.clearTimeout(state.suppressionTimer);
    ticker.classList.remove('is-drag-suppressing');
};

window.addEventListener('click', suppressDraggedClick, true);

const translateX = (track) => {
    const transform = window.getComputedStyle(track).transform;

    if (!transform || transform === 'none') {
        return 0;
    }

    const is3d = transform.startsWith('matrix3d(');
    const values = transform
        .slice(is3d ? 9 : 7, -1)
        .split(',')
        .map(Number);
    const value = values[is3d ? 12 : 4];

    return Number.isFinite(value) ? value : 0;
};

const initializeTicker = (ticker) => {
    if (ticker.dataset.homepageNewsTickerReady === 'true') {
        return;
    }

    const track = ticker.querySelector(TRACK_SELECTOR);

    if (!track) {
        return;
    }

    const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
    const state = {
        pointerId: null,
        startX: 0,
        startY: 0,
        originX: 0,
        currentX: 0,
        dragging: false,
        suppressNextClick: false,
        suppressionTimer: null,
    };
    tickerStates.set(ticker, state);

    const cycleWidth = () => track.scrollWidth / 2;
    const normalizeX = (value) => {
        const width = cycleWidth();

        if (!width) {
            return 0;
        }

        let normalized = value % width;

        if (normalized > 0) {
            normalized -= width;
        }

        return normalized;
    };

    const removeWindowListeners = () => {
        window.removeEventListener('pointermove', moveDrag);
        window.removeEventListener('pointerup', finishDrag);
        window.removeEventListener('pointercancel', finishDrag);
        window.removeEventListener('blur', finishDrag);
    };

    const resumeTicker = () => {
        const width = cycleWidth();

        if (reducedMotion.matches || !width) {
            track.style.removeProperty('animation-delay');
            track.style.removeProperty('animation-name');
            track.style.removeProperty('animation-play-state');
            track.style.removeProperty('transform');

            return;
        }

        state.currentX = normalizeX(state.currentX);

        const duration = Number.parseFloat(
            window.getComputedStyle(ticker)
                .getPropertyValue('--homepage-news-ticker-duration')
        ) || 60;
        const progress = Math.min(1, Math.max(0, -state.currentX / width));

        // Der negative Delay setzt die CSS-Animation auf die gezogene
        // Position, bevor die automatische Bewegung nahtlos weiterlaeuft.
        track.style.animationDelay = `${-(duration * progress)}s`;
        track.style.removeProperty('animation-name');
        track.style.removeProperty('animation-play-state');
        track.style.removeProperty('transform');
    };

    function finishDrag(event) {
        if (
            state.pointerId === null
            || (event?.pointerId !== undefined && event.pointerId !== state.pointerId)
        ) {
            return;
        }

        const wasDragging = state.dragging;

        resumeTicker();
        state.pointerId = null;
        state.dragging = false;
        ticker.classList.remove('is-dragging');
        removeWindowListeners();

        if (wasDragging) {
            // Touch-Browser erzeugen den Click teilweise deutlich verzoegert
            // nach pointerup. Genau dieser naechste Click wird abgefangen.
            // Bleibt er aus, endet die Sperre nach einem kurzen Fenster.
            state.suppressNextClick = true;
            window.clearTimeout(state.suppressionTimer);
            state.suppressionTimer = window.setTimeout(() => {
                state.suppressNextClick = false;
                ticker.classList.remove('is-drag-suppressing');
            }, CLICK_SUPPRESSION_MS);
        }
    }

    function moveDrag(event) {
        if (state.pointerId === null || event.pointerId !== state.pointerId) {
            return;
        }

        const deltaX = event.clientX - state.startX;
        const deltaY = event.clientY - state.startY;

        if (!state.dragging) {
            if (Math.abs(deltaX) < DRAG_THRESHOLD) {
                return;
            }

            if (Math.abs(deltaY) > Math.abs(deltaX)) {
                finishDrag(event);

                return;
            }

            state.dragging = true;
            ticker.classList.add('is-dragging');
            ticker.classList.add('is-drag-suppressing');
        }

        if (event.cancelable) {
            event.preventDefault();
        }

        state.currentX = normalizeX(state.originX + deltaX);
        track.style.transform = `translate3d(${state.currentX}px, 0, 0)`;
    }

    const startDrag = (event) => {
        if (
            event.isPrimary === false
            || (event.pointerType === 'mouse' && event.button !== 0)
        ) {
            return;
        }

        if (state.pointerId !== null || reducedMotion.matches) {
            return;
        }

        state.pointerId = event.pointerId;
        state.startX = event.clientX;
        state.startY = event.clientY;
        track.style.animationPlayState = 'paused';
        state.originX = translateX(track);
        state.currentX = state.originX;
        track.style.transform = `translate3d(${state.originX}px, 0, 0)`;
        track.style.animationName = 'none';

        window.addEventListener('pointermove', moveDrag, { passive: false });
        window.addEventListener('pointerup', finishDrag);
        window.addEventListener('pointercancel', finishDrag);
        window.addEventListener('blur', finishDrag);
    };

    ticker.addEventListener('pointerdown', startDrag);
    ticker.addEventListener('dragstart', (event) => event.preventDefault());
    ticker.dataset.homepageNewsTickerReady = 'true';
};

export const initializeHomepageNewsTickers = (root = document) => {
    const tickers = [];

    if (root instanceof Element && root.matches(TICKER_SELECTOR)) {
        tickers.push(root);
    }

    root.querySelectorAll?.(TICKER_SELECTOR).forEach((ticker) => tickers.push(ticker));
    tickers.forEach(initializeTicker);
};

const bootHomepageNewsTickers = () => initializeHomepageNewsTickers(document);

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', bootHomepageNewsTickers, { once: true });
} else {
    bootHomepageNewsTickers();
}

document.addEventListener('livewire:initialized', bootHomepageNewsTickers);
document.addEventListener('livewire:navigated', bootHomepageNewsTickers);
