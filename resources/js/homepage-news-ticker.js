const TICKER_SELECTOR = '[data-homepage-news-ticker]';
const TRACK_SELECTOR = '.homepage-news-ticker__track';
const DRAG_THRESHOLD = 8;
const CLICK_SUPPRESSION_MS = 800;
const AUTOPLAY_RESTART_DELAY_MS = 2000;
const MOMENTUM_FRAME_MS = 1000 / 60;
const MOMENTUM_FRICTION_PER_FRAME = 0.94;
const MOMENTUM_STOP_VELOCITY = 0.02;
const MAX_MOMENTUM_VELOCITY = 2.4;
const MAX_MOMENTUM_DURATION_MS = 1800;
const RELEASE_VELOCITY_IDLE_MS = 120;
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
    state.suppressionTimer = null;

    if (!state.dragging) {
        ticker.classList.remove('is-drag-suppressing');
    }
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
        axis: null,
        startX: 0,
        startY: 0,
        originX: 0,
        currentX: 0,
        lastPointerX: 0,
        lastPointerTime: 0,
        velocityX: 0,
        dragging: false,
        momentumFrame: null,
        restartTimer: null,
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
        window.removeEventListener('touchmove', blockLockedTouchScroll);
    };

    const setMotionState = (motion) => {
        ticker.dataset.homepageNewsTickerMotion = motion;
    };

    const stopManualMotion = () => {
        if (state.momentumFrame !== null) {
            window.cancelAnimationFrame(state.momentumFrame);
            state.momentumFrame = null;
        }

        if (state.restartTimer !== null) {
            window.clearTimeout(state.restartTimer);
            state.restartTimer = null;
        }

        ticker.classList.remove('is-coasting', 'is-restart-pending');
    };

    const setTrackPosition = (value) => {
        state.currentX = normalizeX(value);
        track.style.transform = `translate3d(${state.currentX}px, 0, 0)`;
    };

    const resumeTicker = () => {
        stopManualMotion();

        const width = cycleWidth();

        if (reducedMotion.matches || !width) {
            track.style.removeProperty('animation-delay');
            track.style.removeProperty('animation-name');
            track.style.removeProperty('animation-play-state');
            track.style.removeProperty('transform');
            setMotionState('native-scroll');

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
        setMotionState('autoplay');
    };

    const queueAutoplayRestart = () => {
        if (state.momentumFrame !== null) {
            window.cancelAnimationFrame(state.momentumFrame);
            state.momentumFrame = null;
        }

        state.velocityX = 0;
        ticker.classList.remove('is-coasting');
        ticker.classList.add('is-restart-pending');
        setMotionState('waiting');

        if (state.restartTimer !== null) {
            window.clearTimeout(state.restartTimer);
        }

        // Erst wenn der Auslauf wirklich beendet ist, beginnt die
        // zweisekuendige Ruhephase. Danach uebernimmt die lineare
        // CSS-Laufschrift wieder.
        state.restartTimer = window.setTimeout(() => {
            state.restartTimer = null;
            resumeTicker();
        }, AUTOPLAY_RESTART_DELAY_MS);
    };

    const startMomentum = () => {
        const startedAt = window.performance.now();
        const idleTime = Math.max(0, startedAt - state.lastPointerTime);
        const idleFactor = Math.max(0, 1 - (idleTime / RELEASE_VELOCITY_IDLE_MS));
        let velocity = Math.max(
            -MAX_MOMENTUM_VELOCITY,
            Math.min(MAX_MOMENTUM_VELOCITY, state.velocityX * idleFactor)
        );

        if (Math.abs(velocity) <= MOMENTUM_STOP_VELOCITY) {
            queueAutoplayRestart();

            return;
        }

        ticker.classList.add('is-coasting');
        setMotionState('coasting');

        let previousFrame = startedAt;
        const step = (timestamp) => {
            const elapsed = Math.min(34, Math.max(0, timestamp - previousFrame));
            const decay = Math.pow(
                MOMENTUM_FRICTION_PER_FRAME,
                elapsed / MOMENTUM_FRAME_MS
            );
            const nextVelocity = velocity * decay;
            const averageVelocity = (velocity + nextVelocity) / 2;

            setTrackPosition(state.currentX + (averageVelocity * elapsed));
            velocity = nextVelocity;
            state.velocityX = velocity;
            previousFrame = timestamp;

            if (
                Math.abs(velocity) <= MOMENTUM_STOP_VELOCITY
                || timestamp - startedAt >= MAX_MOMENTUM_DURATION_MS
            ) {
                state.momentumFrame = null;
                queueAutoplayRestart();

                return;
            }

            state.momentumFrame = window.requestAnimationFrame(step);
        };

        state.momentumFrame = window.requestAnimationFrame(step);
    };

    function finishDrag(event) {
        if (
            state.pointerId === null
            || (event?.pointerId !== undefined && event.pointerId !== state.pointerId)
        ) {
            return;
        }

        const wasDragging = state.dragging;
        const shouldCoast = event?.type === 'pointerup';

        state.pointerId = null;
        state.axis = null;
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
                state.suppressionTimer = null;
                state.suppressNextClick = false;

                if (!state.dragging) {
                    ticker.classList.remove('is-drag-suppressing');
                }
            }, CLICK_SUPPRESSION_MS);

            if (shouldCoast) {
                startMomentum();
            } else {
                queueAutoplayRestart();
            }

            return;
        }

        resumeTicker();
    }

    function blockLockedTouchScroll(event) {
        if (
            state.axis !== 'x'
            || !state.dragging
            || (event.touches?.length ?? 0) > 1
        ) {
            return;
        }

        if (event.cancelable) {
            event.preventDefault();
        }
    }

    function moveDrag(event) {
        if (state.pointerId === null || event.pointerId !== state.pointerId) {
            return;
        }

        const now = window.performance.now();
        const deltaX = event.clientX - state.startX;
        const deltaY = event.clientY - state.startY;

        if (!state.dragging) {
            if (Math.max(Math.abs(deltaX), Math.abs(deltaY)) < DRAG_THRESHOLD) {
                return;
            }

            if (Math.abs(deltaY) >= Math.abs(deltaX)) {
                state.axis = 'y';
                finishDrag(event);

                return;
            }

            state.axis = 'x';
            state.dragging = true;
            window.clearTimeout(state.suppressionTimer);
            state.suppressionTimer = null;
            ticker.classList.add('is-dragging');
            ticker.classList.add('is-drag-suppressing');
            setMotionState('dragging');

            // Ein Link kann beim Pointerdown Fokus erhalten. Nach einem Drag
            // darf :focus-within den spaeteren Autostart nicht festhalten.
            const activeElement = document.activeElement;

            if (activeElement instanceof HTMLElement && ticker.contains(activeElement)) {
                activeElement.blur();
            }

            window.addEventListener('touchmove', blockLockedTouchScroll, { passive: false });
        }

        if (event.cancelable) {
            event.preventDefault();
        }

        const elapsed = Math.max(1, now - state.lastPointerTime);
        const instantVelocity = Math.max(
            -MAX_MOMENTUM_VELOCITY,
            Math.min(
                MAX_MOMENTUM_VELOCITY,
                (event.clientX - state.lastPointerX) / elapsed
            )
        );

        state.velocityX = state.velocityX === 0 || elapsed >= RELEASE_VELOCITY_IDLE_MS
            ? instantVelocity
            : (state.velocityX * 0.35) + (instantVelocity * 0.65);
        state.lastPointerX = event.clientX;
        state.lastPointerTime = now;

        setTrackPosition(state.originX + deltaX);
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

        stopManualMotion();
        state.pointerId = event.pointerId;
        state.axis = null;
        state.startX = event.clientX;
        state.startY = event.clientY;
        state.lastPointerX = event.clientX;
        state.lastPointerTime = window.performance.now();
        state.velocityX = 0;
        track.style.animationPlayState = 'paused';
        state.originX = translateX(track);
        setTrackPosition(state.originX);
        track.style.animationName = 'none';
        setMotionState('pointer-down');

        window.addEventListener('pointermove', moveDrag, { passive: false });
        window.addEventListener('pointerup', finishDrag);
        window.addEventListener('pointercancel', finishDrag);
        window.addEventListener('blur', finishDrag);
    };

    ticker.addEventListener('pointerdown', startDrag);
    ticker.addEventListener('dragstart', (event) => event.preventDefault());
    ticker.dataset.homepageNewsTickerReady = 'true';
    setMotionState(reducedMotion.matches ? 'native-scroll' : 'autoplay');
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
