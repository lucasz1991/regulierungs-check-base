import './bootstrap';
import { createHomepageNewsTickers } from './homepage-news-ticker';
import './aos';

import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
import mask from '@alpinejs/mask';
import resize from '@alpinejs/resize';
import intersect from '@alpinejs/intersect';
import anchor from '@alpinejs/anchor';
import masonry from 'alpinejs-masonry';
import persist from '@alpinejs/persist'
import 'sweetalert2/dist/sweetalert2.min.css';
import './assets/sweetalerts/sweetalerts';



// Alpine Plugins
Alpine.plugin(collapse);
Alpine.plugin(mask);
Alpine.plugin(resize);
Alpine.plugin(intersect);
Alpine.plugin(anchor);
Alpine.plugin(masonry);
Alpine.plugin(persist);

(function () {
    const isMobile = window.matchMedia('(max-width: 768px)').matches;
    if (!isMobile) return;

    let tries = 0;
    const maxTries = 50; 

    const interval = setInterval(() => {
        tries++;

        const ucRoot = document.querySelector('#usercentrics-cmp-ui');
        if (!ucRoot || !ucRoot.shadowRoot) {
            if (tries >= maxTries) clearInterval(interval);
            return;
        }

        const dialog = ucRoot.shadowRoot.querySelector('#uc-main-dialog');
        if (!dialog) {
            if (tries >= maxTries) clearInterval(interval);
            return;
        }

        dialog.style.bottom = '80px';
        dialog.style.top = 'auto';

        clearInterval(interval);
    }, 250);
})();


// Homepage navigation, carousels and Livewire lifecycle.
{
// Keep the breakpoint aligned with app.css.
const desktopNavigationWidth = 1180;

function registerHomepageNavigation(Alpine) {
    Alpine.data('homepageNavigation', () => ({
        mobile: window.innerWidth < desktopNavigationWidth,
        menuOpen: false,
        searchOpen: false,
        scrolled: false,
        hidden: false,
        theme: document.documentElement.dataset.theme === 'dark' ? 'dark' : 'light',
        previousScroll: window.scrollY,
        frame: 0,

        init() {
            this.$root.querySelectorAll('.nav-submenu').forEach((dropdown, index) => {
                const summary = dropdown.querySelector('summary');
                const panel = dropdown.querySelector('.nav-submenu-list');
                panel.id = `homepage-navigation-panel-${index + 1}`;
                summary.setAttribute('aria-controls', panel.id);
                summary.setAttribute('aria-expanded', String(dropdown.open));
            });
            this.onScroll = () => {
                if (this.frame) return;
                this.frame = requestAnimationFrame(() => {
                    this.frame = 0;
                    this.updateScroll();
                });
            };
            this.onResize = () => {
                const mobile = window.innerWidth < desktopNavigationWidth;
                if (mobile !== this.mobile) {
                    const focused = document.activeElement;
                    const focusMovesToMenu = mobile && this.$refs.navigation.contains(focused);
                    this.closeMenu(false);
                    this.closeDropdowns();
                    this.mobile = mobile;
                    this.$nextTick(() => {
                        if (focusMovesToMenu) {
                            this.$refs.menuToggle?.focus({ preventScroll: true });
                        } else if (this.$root.contains(focused) && !focused.getClientRects().length) {
                            this.$root.querySelector('.brand')?.focus({ preventScroll: true });
                        }
                    });
                }
                this.hidden = false;
                this.syncNavigation();
            };
            this.onFocus = () => { this.hidden = false; this.syncNavigation(); };
            this.onFocusOut = () => queueMicrotask(() => {
                if (this.$root.isConnected) this.closeDropdowns(document.activeElement?.closest('.nav-submenu'));
            });
            this.onLinkClick = event => {
                if (event.target.closest('a[href]')) { this.closeMenu(false); this.closeDropdowns(); }
            };
            this.onStorage = event => {
                if (event.key !== 'rc-appearance' || !['light', 'dark'].includes(event.newValue)) return;
                this.applyTheme(event.newValue);
            };
            window.addEventListener('scroll', this.onScroll, { passive: true });
            window.addEventListener('resize', this.onResize, { passive: true });
            window.addEventListener('storage', this.onStorage);
            this.$root.addEventListener('focusin', this.onFocus);
            this.$root.addEventListener('focusout', this.onFocusOut);
            this.$root.addEventListener('click', this.onLinkClick);
            this.applyTheme(this.theme);
            this.updateScroll();
            this.$nextTick(() => this.syncNavigation());
        },

        destroy() {
            cancelAnimationFrame(this.frame);
            window.removeEventListener('scroll', this.onScroll);
            window.removeEventListener('resize', this.onResize);
            window.removeEventListener('storage', this.onStorage);
            this.$root.removeEventListener('focusin', this.onFocus);
            this.$root.removeEventListener('focusout', this.onFocusOut);
            this.$root.removeEventListener('click', this.onLinkClick);
            if (this.$store.nav) {
                this.$store.nav.isMobileMenuOpen = false;
                this.$store.nav.showNav = true;
            }
        },

        syncNavigation() {
            this.$root.style.setProperty('--homepage-header-bottom', `${Math.max(this.$root.offsetHeight, this.$root.getBoundingClientRect().bottom)}px`);
            document.querySelector('.homepage-search-layer')?.style.setProperty('--homepage-header-bottom', this.$root.style.getPropertyValue('--homepage-header-bottom'));
            const nav = this.$store.nav;
            if (!nav) return;
            nav.height = this.$root.offsetHeight;
            nav.isMobile = this.mobile;
            nav.isScreenXl = !this.mobile;
            nav.isMobileMenuOpen = this.menuOpen;
            nav.showNav = !this.hidden;
        },

        updateScroll() {
            const current = Math.max(0, window.scrollY);
            const delta = current - this.previousScroll;
            this.scrolled = current > 20;
            const keepVisible = current <= 120 || this.menuOpen || this.searchOpen ||
                this.$root.contains(document.activeElement) ||
                this.$root.querySelector('.nav-submenu[open]') || document.querySelector('dialog[open]');
            if (keepVisible) this.hidden = false;
            else if (delta > 5) this.hidden = true;
            else if (delta < -10) this.hidden = false;
            if (Math.abs(delta) >= 2 || current === 0) this.previousScroll = current;
            this.syncNavigation();
        },

        toggleMenu() {
            if (!this.mobile) return;
            if (this.menuOpen) { this.closeMenu(true); return; }
            this.closeDropdowns();
            this.hidden = false;
            this.menuOpen = true;
            this.syncNavigation();
            this.$nextTick(() => this.$refs.navigation.querySelector('.menu-close')?.focus());
        },

        closeMenu(restoreFocus = false) {
            const wasOpen = this.menuOpen;
            this.menuOpen = false;
            this.syncNavigation();
            if (wasOpen && restoreFocus) this.$nextTick(() => this.$refs.menuToggle?.focus({ preventScroll: true }));
        },

        closeDropdowns(except = null) {
            this.$root.querySelectorAll('.nav-submenu[open]').forEach(dropdown => {
                if (dropdown !== except) dropdown.open = false;
            });
        },

        submenuChanged(event) {
            const dropdown = event.target;
            dropdown.querySelector('summary')?.setAttribute('aria-expanded', String(dropdown.open));
            if (!dropdown.open) return;
            this.closeDropdowns(dropdown);
            this.hidden = false;
            this.syncNavigation();
        },

        focusSubmenu(event, last) {
            const dropdown = event.target.closest('.nav-submenu');
            this.closeDropdowns(dropdown);
            dropdown.open = true;
            this.$nextTick(() => {
                const controls = [...dropdown.querySelectorAll('.nav-submenu-list a[href], .nav-submenu-list button')];
                (last ? controls.at(-1) : controls[0])?.focus();
            });
        },

        escape(event) {
            if (this.searchOpen) return;
            const dropdown = this.$root.querySelector('.nav-submenu[open]');
            if (dropdown) {
                this.closeDropdowns();
                dropdown.querySelector('summary')?.focus({ preventScroll: true });
            } else if (this.menuOpen) this.closeMenu(true);
            else return;
            event.preventDefault();
            event.stopPropagation();
        },

        openSearch() {
            this.closeMenu(false);
            this.closeDropdowns();
            this.hidden = false;
            this.syncNavigation();
            this.$nextTick(() => { this.syncNavigation(); this.$dispatch('homepage-search-open'); });
        },

        searchState(open) {
            const wasOpen = this.searchOpen;
            this.searchOpen = Boolean(open);
            this.hidden = false;
            this.syncNavigation();
            if (wasOpen && !open) this.$nextTick(() => this.$refs.searchButton?.focus({ preventScroll: true }));
        },

        applyTheme(theme) {
            this.theme = theme;
            document.documentElement.dataset.theme = theme;
            document.documentElement.style.colorScheme = theme;
        },

        toggleTheme() {
            this.applyTheme(this.theme === 'dark' ? 'light' : 'dark');
            try { localStorage.setItem('rc-appearance', this.theme); } catch (_) { /* Storage can be unavailable. */ }
        },
    }));
}

if (window.Alpine) registerHomepageNavigation(window.Alpine);
else document.addEventListener('alpine:init', () => registerHomepageNavigation(window.Alpine), { once: true });

function createHomepageCarousels(scope = document) {

  const EPSILON = 2;
  const clamp = (value, minimum, maximum) => Math.min(maximum, Math.max(minimum, value));

  function createCarousel(root) {
    const track = root.querySelector('[data-carousel-track]');
    if (!track) return null;

    const previous = root.querySelector('[data-carousel-prev]');
    const next = root.querySelector('[data-carousel-next]');
    const status = root.querySelector('[data-carousel-status]');
    const progress = root.querySelector('[data-carousel-progress]');
    const scrollControl = progress?.tagName === 'INPUT' && progress.type === 'range' ? progress : null;
    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
    const originalLive = status?.getAttribute('aria-live');
    const announcer = document.createElement('span');
    announcer.className = 'sr-only';
    announcer.setAttribute('role', 'status');
    announcer.setAttribute('aria-live', 'polite');
    announcer.setAttribute('aria-atomic', 'true');
    // Keep the live region accessible even when this script is used without
    // the site's utility stylesheet. Visible position text is updated silently.
    announcer.style.cssText = 'position:absolute;width:1px;height:1px;padding:0;margin:-1px;overflow:hidden;clip:rect(0,0,0,0);white-space:nowrap;border:0';
    root.append(announcer);
    status?.setAttribute('aria-live', 'off');

    let frame = 0;
    let settleTimer = 0;
    let announcePending = false;
    let alive = true;
    let drag = null;
    let suppressClick = false;
    let clickTimer = 0;
    let scrubbing = false;

    function measure() {
      const items = [...track.querySelectorAll('[data-carousel-item]')]
        .filter(item => !item.hidden && item.getClientRects().length > 0);
      const origin = items[0]?.offsetLeft || 0;
      const maximum = Math.max(0, track.scrollWidth - track.clientWidth);
      const left = clamp(track.scrollLeft, 0, maximum);
      return {
        items,
        maximum,
        left,
        width: track.clientWidth,
        starts: items.map(item => item.offsetLeft - origin),
        widths: items.map(item => item.offsetWidth)
      };
    }

    function render() {
      if (!alive) return null;
      const state = measure();
      const visible = state.items.map((_, index) => index).filter(index =>
        state.starts[index] + state.widths[index] > state.left + EPSILON &&
        state.starts[index] < state.left + state.width - EPSILON
      );
      const first = visible.length ? visible[0] + 1 : 0;
      const last = visible.length ? visible[visible.length - 1] + 1 : 0;
      const total = state.items.length;
      const range = first === last ? `${first}` : `${first}–${last}`;
      if (status) status.textContent = total ? `${range} / ${total}` : 'Keine Ergebnisse';
      if (previous) previous.disabled = !total || state.left <= EPSILON;
      if (next) next.disabled = !total || state.left >= state.maximum - EPSILON;
      if (progress) {
        const fraction = !total ? 0 : state.maximum > EPSILON ? state.left / state.maximum : 1;
        progress.style.setProperty('--carousel-progress', `${clamp(fraction * 100, 0, 100)}%`);
        if (scrollControl) {
          scrollControl.disabled = !total || state.maximum <= EPSILON;
          scrollControl.value = String(Math.round((state.maximum > EPSILON ? fraction : 0) * 1000));
          scrollControl.setAttribute('aria-valuetext', total
            ? `${first === last ? first : `${first} bis ${last}`} von ${total}`
            : 'Keine Ergebnisse');
          const controlWidth = scrollControl.clientWidth || 0;
          const thumbWidth = Math.min(controlWidth, Math.max(28, controlWidth * state.width / Math.max(1, track.scrollWidth)));
          scrollControl.style.setProperty('--carousel-thumb-size', `${thumbWidth}px`);
        }
      }
      return { ...state, first, last, total };
    }

    function scheduleRender() {
      if (!alive || frame) return;
      frame = requestAnimationFrame(() => {
        frame = 0;
        render();
      });
    }

    function finishNavigation() {
      clearTimeout(settleTimer);
      settleTimer = 0;
      const state = render();
      if (!announcePending || !state) return;
      announcePending = false;
      announcer.textContent = state.total
        ? `${state.first === state.last ? state.first : `${state.first} bis ${state.last}`} von ${state.total}`
        : 'Keine Ergebnisse';
    }

    function scheduleAnnouncement() {
      clearTimeout(settleTimer);
      settleTimer = setTimeout(finishNavigation, 180);
    }

    function jumpTo(left) {
      // Override CSS scroll-behavior as well as the API option. Filtering and
      // reduced-motion changes must never leave a smooth scroll in progress.
      const originalBehavior = track.style.scrollBehavior;
      track.style.scrollBehavior = 'auto';
      track.scrollTo({ left, behavior: 'auto' });
      track.style.scrollBehavior = originalBehavior;
    }

    function navigate(direction) {
      const state = measure();
      if (!state.items.length) return;
      let destination = state.left;
      if (direction === 'first') destination = 0;
      else if (direction === 'last') destination = state.maximum;
      else if (direction > 0) destination = state.starts.find(start => start > state.left + EPSILON) ?? state.maximum;
      else destination = [...state.starts].reverse().find(start => start < state.left - EPSILON) ?? 0;
      destination = clamp(destination, 0, state.maximum);
      if (Math.abs(destination - state.left) <= EPSILON) return;
      announcePending = true;
      if (reduceMotion.matches) jumpTo(destination);
      else track.scrollTo({ left: destination, behavior: 'smooth' });
      scheduleRender();
      scheduleAnnouncement();
    }

    const onPrevious = () => navigate(-1);
    const onNext = () => navigate(1);
    const onKeydown = event => {
      // Links and controls inside cards keep their native keyboard behavior.
      if (event.target !== track || event.altKey || event.ctrlKey || event.metaKey || event.shiftKey) return;
      const directions = { ArrowLeft: -1, ArrowRight: 1, Home: 'first', End: 'last' };
      if (!(event.key in directions)) return;
      event.preventDefault();
      navigate(directions[event.key]);
    };
    const onScroll = () => {
      scheduleRender();
      if (announcePending) scheduleAnnouncement();
    };
    const cancelAnnouncement = () => {
      announcePending = false;
      clearTimeout(settleTimer);
      settleTimer = 0;
    };
    function nearestPosition(state) {
      const positions = [...state.starts, state.maximum].map(value => clamp(value, 0, state.maximum));
      return positions.reduce((nearest, value) =>
        Math.abs(value - state.left) < Math.abs(nearest - state.left) ? value : nearest, 0);
    }
    function stopScrubbing({ settle = true } = {}) {
      if (!scrubbing) return;
      scrubbing = false;
      root.classList.remove('is-scrubbing');
      if (!settle || !alive) return;
      const state = measure();
      // Commit to a complete card after dragging the lower scrollbar.
      jumpTo(nearestPosition(state));
      scheduleRender();
      scheduleAnnouncement();
    }
    const onScrubberInput = () => {
      if (!scrollControl || scrollControl.disabled) return;
      stopDrag({ settle: false, suppress: false });
      cancelAnnouncement();
      scrubbing = true;
      root.classList.add('is-scrubbing');
      const state = measure();
      const fraction = clamp(Number(scrollControl.value) || 0, 0, 1000) / 1000;
      announcePending = true;
      // The native range supplies touch, pointer and assistive-technology input.
      // Direct scrolling follows its thumb without a competing smooth tween.
      jumpTo(fraction * state.maximum);
      scheduleRender();
    };
    const onScrubberChange = () => stopScrubbing();
    const onScrubberKeydown = event => {
      if (event.altKey || event.ctrlKey || event.metaKey || event.shiftKey) return;
      const directions = { ArrowLeft: -1, ArrowDown: -1, ArrowRight: 1, ArrowUp: 1, PageDown: 1, PageUp: -1, Home: 'first', End: 'last' };
      if (!(event.key in directions) || scrollControl.disabled) return;
      event.preventDefault();
      stopScrubbing({ settle: false });
      navigate(directions[event.key]);
    };
    function stopDrag({ settle = true, suppress = true } = {}) {
      if (!drag) return;
      const ended = drag;
      drag = null;
      if (!ended.active) return;
      root.classList.remove('is-dragging');
      track.style.scrollBehavior = ended.behavior;
      try {
        if (track.hasPointerCapture?.(ended.id)) track.releasePointerCapture(ended.id);
      } catch (_) { /* A pointer can already have been released by the browser. */ }
      if (suppress) {
        suppressClick = true;
        clearTimeout(clickTimer);
        clickTimer = setTimeout(() => { suppressClick = false; }, 350);
      }
      if (settle && alive) {
        const state = measure();
        const destination = nearestPosition(state);
        if (reduceMotion.matches) jumpTo(destination);
        else track.scrollTo({ left: destination, behavior: 'smooth' });
        scheduleRender();
      }
    }
    const onPointerDown = event => {
      // Native touch/pen scrolling, link activation and vertical selection are
      // untouched. Capture starts only after a real horizontal mouse drag.
      cancelAnnouncement();
      stopScrubbing({ settle: false });
      if (event.pointerType !== 'mouse' || event.button !== 0 || event.defaultPrevented) return;
      if (event.target.closest?.('a, button, input, select, textarea, summary, label, [role="button"], [contenteditable]')) return;
      if (track.scrollWidth <= track.clientWidth + EPSILON) return;
      stopDrag({ settle: false, suppress: false });
      drag = { id: event.pointerId, x: event.clientX, y: event.clientY, left: track.scrollLeft, active: false };
    };
    const onPointerMove = event => {
      if (!drag || event.pointerId !== drag.id) return;
      const dx = event.clientX - drag.x;
      const dy = event.clientY - drag.y;
      if (!drag.active) {
        if (Math.abs(dy) > 6 && Math.abs(dy) >= Math.abs(dx)) { drag = null; return; }
        if (Math.abs(dx) <= 6 || Math.abs(dx) <= Math.abs(dy)) return;
        drag.active = true;
        drag.behavior = track.style.scrollBehavior;
        track.style.scrollBehavior = 'auto';
        root.classList.add('is-dragging');
        try { track.setPointerCapture?.(drag.id); } catch (_) { /* Pointer ended between events. */ }
      }
      if (event.cancelable !== false) event.preventDefault();
      track.scrollLeft = clamp(drag.left - dx, 0, Math.max(0, track.scrollWidth - track.clientWidth));
      scheduleRender();
    };
    const onPointerEnd = event => {
      if (drag && event.pointerId === drag.id) stopDrag();
      stopScrubbing();
    };
    const onPointerLost = event => {
      if (drag && event.pointerId === drag.id) stopDrag();
    };
    const onWindowBlur = () => { stopDrag(); stopScrubbing(); };
    const onClick = event => {
      if (!suppressClick) return;
      suppressClick = false;
      clearTimeout(clickTimer);
      event.preventDefault();
      event.stopPropagation();
    };
    const onNativeDrag = event => { if (drag) event.preventDefault(); };
    const onFilter = event => {
      const requested = event.detail?.id || event.detail?.track;
      if (requested && requested !== track && requested !== track.id) return;
      stopDrag({ settle: false, suppress: false });
      stopScrubbing({ settle: false });
      cancelAnnouncement();
      announcer.textContent = '';
      jumpTo(0);
      scheduleRender();
    };
    const onMotionChange = () => {
      if (reduceMotion.matches) jumpTo(track.scrollLeft);
      scheduleRender();
    };

    previous?.addEventListener('click', onPrevious);
    next?.addEventListener('click', onNext);
    scrollControl?.addEventListener('input', onScrubberInput);
    scrollControl?.addEventListener('change', onScrubberChange);
    scrollControl?.addEventListener('keydown', onScrubberKeydown);
    scrollControl?.addEventListener('blur', onScrubberChange);
    track.addEventListener('keydown', onKeydown);
    track.addEventListener('scroll', onScroll, { passive: true });
    track.addEventListener('scrollend', finishNavigation);
    track.addEventListener('pointerdown', onPointerDown, { passive: true });
    track.addEventListener('pointermove', onPointerMove);
    track.addEventListener('lostpointercapture', onPointerLost);
    track.addEventListener('click', onClick, true);
    track.addEventListener('dragstart', onNativeDrag);
    track.addEventListener('wheel', cancelAnnouncement, { passive: true });
    document.addEventListener('rc:carousel-filter', onFilter);
    window.addEventListener('resize', scheduleRender);
    window.addEventListener('pointerup', onPointerEnd);
    window.addEventListener('pointercancel', onPointerEnd);
    window.addEventListener('blur', onWindowBlur);
    if (reduceMotion.addEventListener) reduceMotion.addEventListener('change', onMotionChange);
    else reduceMotion.addListener?.(onMotionChange);

    const resize = typeof ResizeObserver === 'function' ? new ResizeObserver(scheduleRender) : null;
    resize?.observe(track);
    if (scrollControl) resize?.observe(scrollControl);
    track.querySelectorAll('[data-carousel-item]').forEach(item => resize?.observe(item));
    document.fonts?.ready.then(scheduleRender);
    render();
    if (scrollControl) {
      root.classList.add('carousel-enhanced');
      // The range was hidden in the no-JS layout; measure its width once shown.
      scheduleRender();
    }

    return {
      destroy() {
        alive = false;
        stopDrag({ settle: false, suppress: false });
        stopScrubbing({ settle: false });
        root.classList.remove('carousel-enhanced');
        if (scrollControl) scrollControl.disabled = true;
        cancelAnimationFrame(frame);
        clearTimeout(settleTimer);
        clearTimeout(clickTimer);
        resize?.disconnect();
        previous?.removeEventListener('click', onPrevious);
        next?.removeEventListener('click', onNext);
        scrollControl?.removeEventListener('input', onScrubberInput);
        scrollControl?.removeEventListener('change', onScrubberChange);
        scrollControl?.removeEventListener('keydown', onScrubberKeydown);
        scrollControl?.removeEventListener('blur', onScrubberChange);
        track.removeEventListener('keydown', onKeydown);
        track.removeEventListener('scroll', onScroll);
        track.removeEventListener('scrollend', finishNavigation);
        track.removeEventListener('pointerdown', onPointerDown);
        track.removeEventListener('pointermove', onPointerMove);
        track.removeEventListener('lostpointercapture', onPointerLost);
        track.removeEventListener('click', onClick, true);
        track.removeEventListener('dragstart', onNativeDrag);
        track.removeEventListener('wheel', cancelAnnouncement);
        document.removeEventListener('rc:carousel-filter', onFilter);
        window.removeEventListener('resize', scheduleRender);
        window.removeEventListener('pointerup', onPointerEnd);
        window.removeEventListener('pointercancel', onPointerEnd);
        window.removeEventListener('blur', onWindowBlur);
        if (reduceMotion.removeEventListener) reduceMotion.removeEventListener('change', onMotionChange);
        else reduceMotion.removeListener?.(onMotionChange);
        announcer.remove();
        if (status) {
          if (originalLive === null) status.removeAttribute('aria-live');
          else status.setAttribute('aria-live', originalLive);
        }
      }
    };
  }

  const roots = scope.matches?.('[data-carousel]') ? [scope] : [...scope.querySelectorAll('[data-carousel]')];
  const controllers = roots.map(createCarousel).filter(Boolean);
  return () => controllers.forEach(controller => controller.destroy());
}

let revealed = new WeakSet();
const revealedKeys = new Set();
const active = new Map();
const pendingReveals = new Set();
const heroMotion = new Map();
let observer;
let motionRoot;
const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');

function stopHomepageMotion(resetHistory = false) {
    observer?.disconnect();
    observer = undefined;
    pendingReveals.clear();
    active.forEach((element, animation) => animation.cancel());
    active.clear();
    heroMotion.forEach(stop => stop());
    heroMotion.clear();
    if (resetHistory) {
        revealed = new WeakSet();
        revealedKeys.clear();
        motionRoot = undefined;
    }
}

function revealKey(element) {
    return element.matches('.insurer-card, .review-card, .filter') ? element.getAttribute('wire:key') : null;
}

function revealDelay(element) {
    const heroParts = ['.eyebrow', '.hero-title-line:not(.hero-title-accent)', '.hero-title-accent', 'h1', '.hero-subtitle', '.hero-lead', '.hero-actions', '.hero-visual', '.hero-count', '.button-icon'];
    if (element.closest('.hero')) {
        const index = heroParts.findIndex(selector => element.matches(selector));
        return [0, 90, 190, 90, 285, 355, 440, 120, 520, 620][index] || 0;
    }
    if (element.matches('.insurer-card, .review-card, #ablauf .steps article, .filter')) {
        const siblings = [...element.parentElement.children].filter(sibling => sibling.matches('.insurer-card, .review-card, article, .filter'));
        return (siblings.indexOf(element) % 4) * 100;
    }
    if (element.matches('#wissen, .insurance-toolbar')) return 100;
    return 0;
}

function revealHomepageElement(element) {
    const key = revealKey(element);
    if (revealed.has(element) || (key && revealedKeys.has(key))) return;
    revealed.add(element);
    if (key) revealedKeys.add(key);
    // A responsive headline changes its animation target, not its reveal history.
    if (element.matches('.hero h1')) element.querySelectorAll('.hero-title-line').forEach(line => revealed.add(line));
    if (element.matches('.hero-title-line')) {
        const title = element.closest('h1');
        if (title) revealed.add(title);
    }
    if (element.contains(document.activeElement)) return;
    const delay = revealDelay(element);
    const background = element.matches('.homepage-background');
    const header = element.matches('.site-header .header-inner');
    const icon = element.matches('.hero-actions .button-icon');
    const visual = element.matches('.hero-visual');
    const cards = element.matches('.insurer-card, .review-card, #ablauf .steps article');
    const targetOpacity = background ? Number.parseFloat(getComputedStyle(element).opacity) : 1;
    const duration = (background ? 1500 : header ? 800 : element.closest('.hero') ? 940 : 880) + delay;
    let transform = 'translate3d(0, 32px, 0)';
    if (background) transform = 'translate3d(0, 44px, 0)';
    else if (header) transform = 'translate3d(0, -14px, 0)';
    else if (icon) transform = 'translate3d(-4px, 4px, 0) rotate(-12deg) scale(.86)';
    else if (visual) transform = 'translate3d(30px, 18px, 0) scale(.97)';
    else if (cards) transform = 'translate3d(0, 32px, 0) scale(.975)';
    else if (element.matches('.filter')) transform = 'translate3d(0, 16px, 0)';
    const start = {
        opacity: background ? 0 : icon ? .6 : visual ? 1 : .08,
        transform,
    };
    const frames = [{ ...start, offset: 0 }];
    // Hold the opening frame inside the animation, rather than hiding content
    // with CSS or backwards fill while a delayed animation waits to start.
    if (delay) frames.push({ ...start, offset: delay / duration });
    frames.push({ opacity: targetOpacity, transform: 'translate3d(0, 0, 0)', offset: 1 });
    const animation = element.animate(frames, {
        duration,
        easing: 'cubic-bezier(.22,1,.36,1)',
        fill: 'none',
    });
    animation.id = 'homepage-reveal';
    active.set(animation, element);
    animation.finished.then(() => {
        active.delete(animation);
        animation.cancel();
    }).catch(() => active.delete(animation));
}

function createHeroMotion(stage) {
    const art = stage.querySelector('.hero-art');
    if (!art) return () => {};
    const finePointer = window.matchMedia('(hover: hover) and (pointer: fine)');
    const originalTransform = stage.style.transform;
    const originalTransition = stage.style.transition;
    const originalWillChange = stage.style.willChange;
    let destroyed = false;
    let visible = false;
    let bounds;
    let pointerFrame = 0;
    let settleTimer = 0;
    let tiltX = 0;
    let tiltY = 0;
    const float = art.animate([
        { transform: 'translate3d(0, 0, 0)', offset: 0 },
        { transform: 'translate3d(0, -7px, 0)', offset: .5 },
        { transform: 'translate3d(0, 0, 0)', offset: 1 },
    ], { duration: 6400, iterations: Infinity, easing: 'cubic-bezier(.45,0,.55,1)', fill: 'none' });
    float.id = 'homepage-hero-float';
    float.pause();

    const center = (immediate = false) => {
        cancelAnimationFrame(pointerFrame);
        pointerFrame = 0;
        clearTimeout(settleTimer);
        stage.style.transform = originalTransform;
        if (immediate) {
            stage.style.transition = originalTransition;
            stage.style.willChange = originalWillChange;
        } else {
            settleTimer = window.setTimeout(() => {
                stage.style.transition = originalTransition;
                stage.style.willChange = originalWillChange;
            }, 500);
        }
    };
    const syncVisibility = () => {
        if (destroyed) return;
        const running = visible && !document.hidden && !reducedMotion.matches;
        if (running) float.play();
        else {
            float.pause();
            center(true);
        }
    };
    const visibilityObserver = new IntersectionObserver(entries => {
        visible = entries.some(entry => entry.isIntersecting);
        syncVisibility();
    }, { threshold: .05 });
    visibilityObserver.observe(stage);
    const enter = event => {
        if (!finePointer.matches || event.pointerType === 'touch' || reducedMotion.matches) return;
        bounds = stage.getBoundingClientRect();
        clearTimeout(settleTimer);
        stage.style.transition = 'transform 500ms cubic-bezier(.22,1,.36,1)';
        stage.style.willChange = 'transform';
    };
    const move = event => {
        if (!bounds || !finePointer.matches || reducedMotion.matches || event.pointerType === 'touch') return;
        const x = Math.max(-1, Math.min(1, ((event.clientX - bounds.left) / bounds.width - .5) * 2));
        const y = Math.max(-1, Math.min(1, ((event.clientY - bounds.top) / bounds.height - .5) * 2));
        tiltX = -y * 3;
        tiltY = x * 3;
        if (pointerFrame) return;
        pointerFrame = requestAnimationFrame(() => {
            pointerFrame = 0;
            stage.style.transform = `perspective(900px) rotateX(${tiltX.toFixed(2)}deg) rotateY(${tiltY.toFixed(2)}deg)`;
        });
    };
    const leave = () => { bounds = undefined; center(); };
    const onPointerChange = () => { bounds = undefined; center(true); };
    stage.addEventListener('pointerenter', enter);
    stage.addEventListener('pointermove', move);
    stage.addEventListener('pointerleave', leave);
    stage.addEventListener('pointercancel', leave);
    window.addEventListener('resize', onPointerChange);
    document.addEventListener('visibilitychange', syncVisibility);
    finePointer.addEventListener('change', onPointerChange);
    return () => {
        destroyed = true;
        visibilityObserver.disconnect();
        float.cancel();
        center(true);
        stage.removeEventListener('pointerenter', enter);
        stage.removeEventListener('pointermove', move);
        stage.removeEventListener('pointerleave', leave);
        stage.removeEventListener('pointercancel', leave);
        window.removeEventListener('resize', onPointerChange);
        document.removeEventListener('visibilitychange', syncVisibility);
        finePointer.removeEventListener('change', onPointerChange);
    };
}

function refreshHomepageMotion() {
    if (!document.body.classList.contains('rc-home') || reducedMotion.matches ||
        !window.IntersectionObserver || !window.Element?.prototype.animate) {
        stopHomepageMotion();
        return;
    }
    const root = document.querySelector('[data-homepage-content]');
    if (motionRoot && motionRoot !== root) stopHomepageMotion(true);
    motionRoot = root;
    for (const [stage, stop] of heroMotion) {
        if (!stage.isConnected) { stop(); heroMotion.delete(stage); }
    }
    for (const [animation, element] of active) {
        if (!element.isConnected) { animation.cancel(); active.delete(animation); }
    }
    for (const element of pendingReveals) {
        if (!element.isConnected) { observer?.unobserve(element); pendingReveals.delete(element); }
    }
    if (!observer) {
        const revealObserver = new IntersectionObserver(entries => {
            if (observer !== revealObserver) return;
            entries.forEach(entry => {
                if (!entry.isIntersecting) return;
                const element = entry.target;
                revealObserver.unobserve(element);
                pendingReveals.delete(element);
                if (reducedMotion.matches || !element.isConnected || !document.body.classList.contains('rc-home')) return;
                revealHomepageElement(element);
            });
        }, { threshold: .08 });
        observer = revealObserver;
    }
    const headline = document.querySelector('.hero h1');
    const headlineLines = headline ? [...headline.querySelectorAll('.hero-title-line')] : [];
    const splitHeadline = headlineLines.length > 1 && headlineLines.every(line => getComputedStyle(line).display === 'block');
    document.querySelectorAll('.homepage-background, .site-header .header-inner, .hero .eyebrow, .hero h1, .hero-title-line, .hero-subtitle, .hero-lead, .hero-actions, .hero-actions .button-icon, .hero-visual, .hero-count, #wissen, .explorer-heading, #versicherer .filter, .insurance-toolbar, .insurer-card, .experience-intro, .review-card, #ablauf .process-intro, #ablauf .steps article, #plattform > div, #newsletter, .homepage-cms-content, .homepage-cms-footer').forEach(element => {
        if (element === headline && splitHeadline) return;
        if (headlineLines.includes(element) && !splitHeadline) return;
        const key = revealKey(element);
        if (!revealed.has(element) && !(key && revealedKeys.has(key)) && !pendingReveals.has(element)) {
            pendingReveals.add(element);
            observer.observe(element);
        }
    });
    document.querySelectorAll('.hero-art-stage').forEach(stage => {
        if (!heroMotion.has(stage)) heroMotion.set(stage, createHeroMotion(stage));
    });
}

const onHomepageMotionChange = () => {
    stopHomepageMotion();
    refreshHomepageMotion();
};
reducedMotion.addEventListener('change', onHomepageMotionChange);

// Keyboard focus always takes precedence over an entrance animation.
document.addEventListener('focusin', event => {
    for (const [animation, element] of active) {
        if (element.contains(event.target)) {
            animation.cancel();
            active.delete(animation);
        }
    }
});

const sliders = new Map();
let stopSearch = () => {};
let updateFrame = 0;

function stopSliders() {
    sliders.forEach(stop => stop());
    sliders.clear();
}

function refreshSliders() {
    for (const [root, stop] of sliders) {
        if (!root.isConnected) { stop(); sliders.delete(root); }
    }
    document.querySelectorAll('[data-carousel], [data-news-ticker]').forEach(root => {
        if (sliders.has(root)) return;
        sliders.set(root, root.matches('[data-news-ticker]') ? createHomepageNewsTickers(root) : createHomepageCarousels(root));
    });
}

function initializeSearch() {
    const input = document.getElementById('insurer-search');
    const section = document.getElementById('versicherer');
    if (!input || !section) return () => {};
    const rows = [...section.querySelectorAll('[data-insurer]')];
    const count = section.querySelector('#result-count');
    const empty = section.querySelector('#insurer-empty');
    const discover = section.querySelector('[data-carousel-discover]');
    const apply = () => {
        const query = input.value.trim().toLocaleLowerCase('de');
        let visible = 0;
        rows.forEach(row => {
            const matches = (row.dataset.insurer || row.textContent).toLocaleLowerCase('de').includes(query);
            row.hidden = !matches;
            if (matches) visible++;
        });
        if (count) count.textContent = `${visible} Versicherer`;
        if (empty) empty.hidden = visible > 0;
        if (discover) discover.hidden = query.length > 0;
        document.dispatchEvent(new CustomEvent('rc:carousel-filter', { detail: { id: 'insurance-track' } }));
    };
    const reset = section.querySelector('[data-reset-insurer-search], #reset-filters');
    const clear = () => { input.value = ''; apply(); input.focus(); };
    input.addEventListener('input', apply);
    reset?.addEventListener('click', clear);
    apply();
    return () => { input.removeEventListener('input', apply); reset?.removeEventListener('click', clear); };
}

function synchronizeHomepage() {
    stopSearch();
    const home = document.body.classList.contains('rc-home');
    document.documentElement.toggleAttribute('data-homepage', home);
    stopSearch = () => {};
    if (!home) {
        stopSliders();
        stopHomepageMotion(true);
        document.documentElement.style.colorScheme = '';
        return;
    }
    refreshSliders();
    stopSearch = initializeSearch();
    refreshHomepageMotion();
}

function scheduleRefresh() {
    cancelAnimationFrame(updateFrame);
    updateFrame = requestAnimationFrame(synchronizeHomepage);
}

function installMorphHooks() {
    const containsSlider = el => el.matches?.('[data-carousel], [data-news-ticker]') ||
        el.querySelector?.('[data-carousel], [data-news-ticker]');
    window.Livewire.hook('morph', ({ el }) => {
        if (!containsSlider(el)) return;
        for (const [root, stop] of sliders) {
            if (el === root || el.contains(root)) { stop(); sliders.delete(root); }
        }
        if (el.id === 'versicherer' || el.querySelector('#versicherer')) {
            stopSearch();
            stopSearch = () => {};
        }
    });
    window.Livewire.hook('morphed', ({ el }) => {
        if (containsSlider(el)) scheduleRefresh();
    });
}

document.addEventListener('livewire:init', installMorphHooks, { once: true });
document.addEventListener('livewire:navigated', scheduleRefresh);
document.addEventListener('livewire:navigating', () => {
    cancelAnimationFrame(updateFrame);
    stopSearch();
    stopSliders();
    stopHomepageMotion(true);
});
window.addEventListener('pagehide', () => { stopSearch(); stopSliders(); stopHomepageMotion(true); });
window.addEventListener('pageshow', event => { if (event.persisted) scheduleRefresh(); });
if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', scheduleRefresh, { once: true });
else scheduleRefresh();
}
