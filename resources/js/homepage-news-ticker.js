export function createHomepageNewsTickers(scope = document) {

  function createTicker(root) {
    const track = root.querySelector('[data-carousel-track]');
    const originals = track ? [...track.querySelectorAll('[data-carousel-item]')] : [];
    if (!track || !originals.length) return null;
    const previous = root.querySelector('[data-carousel-prev]');
    const next = root.querySelector('[data-carousel-next]');
    const range = root.querySelector('[data-carousel-progress]');
    const status = root.querySelector('[data-carousel-status]');
    const toggle = root.querySelector('[data-news-toggle]');
    const motion = window.matchMedia('(prefers-reduced-motion: reduce)');
    const announcer = document.createElement('span');
    announcer.className = 'sr-only';
    announcer.setAttribute('role', 'status');
    announcer.setAttribute('aria-live', 'polite');
    root.append(announcer);
    status?.setAttribute('aria-live', 'off');

    const cleanups = [];
    let clones = [];
    let cycle = 0, starts = [], position = 0;
    let frame = 0, lastTime = 0, lastUI = 0;
    let resumeTimer = 0, resizeFrame = 0, clickTimer = 0, focusTimer = 0;
    let alive = true, userPaused = false, hovered = false, focused = false;
    let pointer = null, pointerHeld = false, cooling = false, suppressClick = false;
    let inView = typeof IntersectionObserver !== 'function', windowFocused = true;
    const speed = 36;
    const modulo = value => cycle > 0 ? ((value % cycle) + cycle) % cycle : 0;
    const listen = (target, event, handler, options) => {
      target?.addEventListener(event, handler, options);
      cleanups.push(() => target?.removeEventListener(event, handler, options));
    };

    function running() {
      return alive && originals.length > 1 && cycle > 0 && !userPaused && !motion.matches &&
        !hovered && !focused && !pointerHeld && !cooling && inView && windowFocused && !document.hidden;
    }
    function paintPosition(value) {
      position = modulo(value);
      track.scrollLeft = position;
    }
    function currentIndex() {
      return Math.max(0, starts.findLastIndex(start => start <= position + 2));
    }
    function updateUI(announce = false) {
      const index = currentIndex();
      if (status) status.textContent = `${index + 1} / ${originals.length}`;
      if (range) {
        const lastStart = starts.at(-1) || 1;
        range.disabled = originals.length < 2 || !cycle;
        range.value = String(Math.round(Math.min(position / lastStart, 1) * 1000));
        range.setAttribute('aria-valuetext', `Nachricht ${index + 1} von ${originals.length}`);
        range.style.setProperty('--carousel-thumb-size', `${Math.max(28, range.clientWidth * Math.min(1, track.clientWidth / Math.max(1, cycle)))}px`);
      }
      if (previous) previous.disabled = originals.length < 2;
      if (next) next.disabled = originals.length < 2;
      if (announce) announcer.textContent = `Nachricht ${index + 1} von ${originals.length}`;
    }
    function updateToggle() {
      if (!toggle) return;
      toggle.disabled = motion.matches || originals.length < 2;
      toggle.setAttribute('aria-pressed', String(userPaused || motion.matches));
      toggle.setAttribute('aria-label', motion.matches ? 'Automatischer Newslauf bei reduzierter Bewegung deaktiviert' : userPaused ? 'News automatisch abspielen' : 'Automatischen Newslauf pausieren');
      toggle.textContent = motion.matches ? 'Manuell' : userPaused ? 'Abspielen' : 'Pausieren';
    }
    function tick(time) {
      frame = 0;
      if (!running()) return;
      if (lastTime) paintPosition(position + Math.min(64, time - lastTime) * speed / 1000);
      lastTime = time;
      if (time - lastUI >= 100) { updateUI(); lastUI = time; }
      frame = requestAnimationFrame(tick);
    }
    function sync() {
      if (!alive) return;
      root.classList.toggle('news-is-playing', running());
      if (running()) {
        if (!frame) { lastTime = 0; frame = requestAnimationFrame(tick); }
      } else {
        cancelAnimationFrame(frame);
        frame = 0;
        lastTime = 0;
        position = modulo(track.scrollLeft);
      }
    }
    function manualPause() {
      cooling = true;
      clearTimeout(resumeTimer);
      sync();
      resumeTimer = setTimeout(() => { cooling = false; sync(); }, 5000);
    }
    function rebuild() {
      if (!alive) return;
      const fraction = cycle ? modulo(track.scrollLeft) / cycle : 0;
      clones.forEach(clone => clone.remove());
      clones = [];
      const appendSequence = () => {
        originals.forEach((item, index) => {
          const clone = item.cloneNode(true);
          clone.removeAttribute('id');
          clone.removeAttribute('data-carousel-item');
          clone.setAttribute('data-news-clone', String(index));
          clone.setAttribute('aria-hidden', 'true');
          clone.querySelectorAll('[id]').forEach(element => element.removeAttribute('id'));
          clone.querySelectorAll('a, button, input, select, textarea, [tabindex]').forEach(element => element.setAttribute('tabindex', '-1'));
          track.append(clone);
          clones.push(clone);
        });
      };
      appendSequence();
      const origin = originals[0].offsetLeft;
      cycle = clones[0].offsetLeft - origin;
      starts = originals.map(item => item.offsetLeft - origin);
      if (cycle > 0) {
        // Enough repeated content to cover the widest viewport throughout a loop.
        const additional = Math.max(0, Math.ceil(track.clientWidth / cycle) - 1);
        for (let index = 0; index < additional; index++) appendSequence();
      }
      paintPosition(fraction * cycle);
      updateUI();
      updateToggle();
      sync();
    }
    function resize() {
      if (resizeFrame) return;
      resizeFrame = requestAnimationFrame(() => { resizeFrame = 0; rebuild(); });
    }
    function navigate(direction) {
      manualPause();
      let index = currentIndex();
      if (direction === 'first') index = 0;
      else if (direction === 'last') index = originals.length - 1;
      else if (direction < 0 && position > starts[index] + 2) { /* Return to the visible card's start. */ }
      else index = (index + direction + originals.length) % originals.length;
      paintPosition(starts[index] || 0);
      updateUI(true);
    }
    function onKeydown(event) {
      if (event.altKey || event.ctrlKey || event.metaKey || event.shiftKey) return;
      if (event.target !== track && event.target !== range) return;
      const direction = { ArrowLeft: -1, ArrowRight: 1, ArrowUp: -1, ArrowDown: 1, Home: 'first', End: 'last', PageUp: -1, PageDown: 1 }[event.key];
      if (direction === undefined) return;
      event.preventDefault();
      navigate(direction);
    }
    function onFocus(event) {
      const clone = event.target.closest?.('[data-news-clone]');
      if (clone) {
        // Allow a native pointer click to finish before relocating its target.
        // Mouse focus is prevented below; this also covers touch/programmatic focus.
        clearTimeout(focusTimer);
        focusTimer = setTimeout(() => {
          if (!alive || !clone.contains(document.activeElement)) return;
          const index = Number(clone.getAttribute('data-news-clone'));
          manualPause();
          paintPosition(starts[index] || 0);
          originals[index]?.querySelector('a[href]')?.focus({ preventScroll: true });
          updateUI();
        }, 0);
      }
      focused = root.contains(document.activeElement) && document.activeElement !== toggle;
      sync();
    }
    function endPointer() {
      if (!pointerHeld && !pointer) return;
      const ended = pointer;
      pointer = null;
      pointerHeld = false;
      if (ended?.dragging) {
        suppressClick = true;
        clearTimeout(clickTimer);
        clickTimer = setTimeout(() => { suppressClick = false; }, 350);
        try { if (track.hasPointerCapture?.(ended.id)) track.releasePointerCapture(ended.id); } catch (_) { /* Already released. */ }
      }
      root.classList.remove('is-dragging');
      manualPause();
    }

    listen(previous, 'click', () => navigate(-1));
    listen(next, 'click', () => navigate(1));
    listen(toggle, 'click', () => { userPaused = !userPaused; updateToggle(); sync(); });
    listen(root, 'pointerenter', event => { if (event.pointerType !== 'touch') { hovered = true; sync(); } });
    listen(root, 'pointerleave', () => { hovered = false; sync(); });
    listen(root, 'focusin', onFocus);
    listen(root, 'focusout', () => queueMicrotask(() => { if (!alive) return; focused = root.contains(document.activeElement) && document.activeElement !== toggle; sync(); }));
    listen(track, 'keydown', onKeydown);
    listen(range, 'keydown', onKeydown);
    listen(range, 'input', () => {
      manualPause();
      paintPosition((Number(range.value) || 0) / 1000 * (starts.at(-1) || 0));
      updateUI();
    });
    listen(range, 'change', () => updateUI(true));
    listen(track, 'wheel', manualPause, { passive: true });
    listen(track, 'scroll', () => {
      if (running()) return;
      const left = track.scrollLeft;
      position = modulo(left);
      if (left >= cycle && cycle > 0) track.scrollLeft = position;
      updateUI();
    }, { passive: true });
    listen(track, 'mousedown', event => {
      // Cancel only duplicate-link focus, never its normal click/navigation.
      if (event.button === 0 && event.target.closest?.('[data-news-clone]')) event.preventDefault();
    });
    listen(track, 'pointerdown', event => {
      if (event.button !== 0 && event.pointerType === 'mouse') return;
      pointerHeld = true;
      sync();
      if (event.pointerType === 'mouse') pointer = { id: event.pointerId, x: event.clientX, y: event.clientY, left: position, dragging: false };
    }, { passive: true });
    listen(track, 'pointermove', event => {
      if (!pointer || pointer.id !== event.pointerId) return;
      const dx = event.clientX - pointer.x, dy = event.clientY - pointer.y;
      if (!pointer.dragging) {
        if (Math.abs(dy) > 6 && Math.abs(dy) >= Math.abs(dx)) { pointer = null; return; }
        if (Math.abs(dx) < 7) return;
        pointer.dragging = true;
        root.classList.add('is-dragging');
        try { track.setPointerCapture?.(pointer.id); } catch (_) { /* Pointer was already released. */ }
      }
      event.preventDefault();
      paintPosition(pointer.left - dx);
      updateUI();
    });
    listen(track, 'dragstart', event => { if (pointer) event.preventDefault(); });
    listen(track, 'click', event => {
      if (!suppressClick) return;
      suppressClick = false;
      event.preventDefault();
      event.stopPropagation();
    }, true);
    listen(window, 'pointerup', endPointer);
    listen(window, 'pointercancel', endPointer);
    listen(track, 'lostpointercapture', endPointer);
    listen(window, 'blur', () => { windowFocused = false; endPointer(); sync(); });
    listen(window, 'focus', () => { windowFocused = true; sync(); });
    listen(document, 'visibilitychange', sync);
    listen(window, 'resize', resize);
    const onMotion = () => { updateToggle(); sync(); };
    if (motion.addEventListener) listen(motion, 'change', onMotion);
    else { motion.addListener(onMotion); cleanups.push(() => motion.removeListener(onMotion)); }
    const visibility = typeof IntersectionObserver === 'function' ? new IntersectionObserver(entries => {
      inView = entries.some(entry => entry.isIntersecting);
      sync();
    }, { threshold: .05 }) : null;
    visibility?.observe(root);
    const observer = typeof ResizeObserver === 'function' ? new ResizeObserver(resize) : null;
    observer?.observe(track);
    observer?.observe(originals[0]);
    document.fonts?.ready.then(() => { if (alive) resize(); });
    root.classList.add('news-ticker-ready');
    rebuild();

    return {
      destroy() {
        alive = false;
        cancelAnimationFrame(frame);
        cancelAnimationFrame(resizeFrame);
        clearTimeout(resumeTimer);
        clearTimeout(clickTimer);
        clearTimeout(focusTimer);
        visibility?.disconnect();
        observer?.disconnect();
        cleanups.forEach(cleanup => cleanup());
        clones.forEach(clone => clone.remove());
        announcer.remove();
        root.classList.remove('news-ticker-ready', 'news-is-playing', 'is-dragging');
        if (range) range.disabled = true;
      }
    };
  }

  const roots = scope.matches?.('[data-news-ticker]') ? [scope] : [...scope.querySelectorAll('[data-news-ticker]')];
  const controllers = roots.map(createTicker).filter(Boolean);
  return () => controllers.forEach(controller => controller.destroy());
}
