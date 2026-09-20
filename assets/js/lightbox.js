document.addEventListener("DOMContentLoaded", () => {
  const overlay = document.querySelector("[data-lightbox-overlay]");
  if (!overlay) return;

  const image = overlay.querySelector("[data-lightbox-image]");
  image.draggable = false;
  let lastFocused = null;

  // ── Zoom/pan fatto in casa (niente librerie): rotellina/trackpad,
  // doppio click/tap e pinch a due dita per ingrandire, trascinamento
  // per spostarsi quando ingrandita. Tutto via CSS transform
  // (translate + scale) sull'immagine. ──
  const MIN_SCALE = 1;
  const MAX_SCALE = 4;
  const DOUBLE_TAP_SCALE = 2.5;
  let scale = MIN_SCALE;
  let translateX = 0;
  let translateY = 0;

  const applyTransform = () => {
    image.style.transform = `translate(${translateX}px, ${translateY}px) scale(${scale})`;
    image.classList.toggle("lightbox__img--zoomed", scale > MIN_SCALE);
  };

  const resetZoom = () => {
    scale = MIN_SCALE;
    translateX = 0;
    translateY = 0;
    image.classList.remove("lightbox__img--transition");
    applyTransform();
  };

  // image.offsetWidth/Height non risentono del transform (solo del
  // layout): restano sempre la dimensione "adattata allo schermo" di
  // base su cui applicare lo scale, comoda per calcolare quanto può
  // sporgere l'immagine ingrandita oltre il viewport.
  const clampTranslate = () => {
    const maxX = Math.max(0, (image.offsetWidth * scale - window.innerWidth) / 2);
    const maxY = Math.max(0, (image.offsetHeight * scale - window.innerHeight) / 2);
    translateX = Math.min(maxX, Math.max(-maxX, translateX));
    translateY = Math.min(maxY, Math.max(-maxY, translateY));
  };

  // Ingrandisce/rimpicciolisce mantenendo fermo sotto il cursore/dito
  // il punto toccato, invece di zoomare sempre dal centro.
  const zoomAt = (clientX, clientY, nextScale) => {
    nextScale = Math.min(MAX_SCALE, Math.max(MIN_SCALE, nextScale));
    const rect = image.getBoundingClientRect();
    const relX = (clientX - rect.left) / rect.width;
    const relY = (clientY - rect.top) / rect.height;

    const newWidth = image.offsetWidth * nextScale;
    const newHeight = image.offsetHeight * nextScale;
    translateX = clientX - relX * newWidth - (window.innerWidth - newWidth) / 2;
    translateY = clientY - relY * newHeight - (window.innerHeight - newHeight) / 2;
    scale = nextScale;
    clampTranslate();
    applyTransform();
  };

  const toggleZoom = (clientX, clientY) => {
    image.classList.add("lightbox__img--transition");
    if (scale > MIN_SCALE) {
      resetZoom();
    } else {
      zoomAt(clientX, clientY, DOUBLE_TAP_SCALE);
    }
    window.setTimeout(() => image.classList.remove("lightbox__img--transition"), 200);
  };

  const open = (src, alt) => {
    lastFocused = document.activeElement;
    image.src = src;
    image.alt = alt || "";
    resetZoom();
    overlay.hidden = false;
    document.body.style.overflow = "hidden";
    overlay.querySelector("[data-lightbox-close]")?.focus();
  };

  const close = () => {
    overlay.hidden = true;
    image.src = "";
    resetZoom();
    document.body.style.overflow = "";
    lastFocused?.focus();
  };

  // ── Rotellina/trackpad: zoom continuo centrato sul cursore ──
  image.addEventListener("wheel", (event) => {
    event.preventDefault();
    const direction = event.deltaY < 0 ? 1 : -1;
    zoomAt(event.clientX, event.clientY, scale + direction * scale * 0.25);
  }, { passive: false });

  // ── Doppio click (desktop): alterna tra "adattata" e ingrandita ──
  image.addEventListener("dblclick", (event) => {
    event.preventDefault();
    toggleZoom(event.clientX, event.clientY);
  });

  // ── Trascinamento con il mouse quando ingrandita ──
  let dragging = false;
  let dragStart = { x: 0, y: 0, translateX: 0, translateY: 0 };

  image.addEventListener("pointerdown", (event) => {
    if (event.pointerType !== "mouse" || scale <= MIN_SCALE) return;
    dragging = true;
    image.setPointerCapture(event.pointerId);
    dragStart = { x: event.clientX, y: event.clientY, translateX, translateY };
  });

  image.addEventListener("pointermove", (event) => {
    if (!dragging || event.pointerType !== "mouse") return;
    translateX = dragStart.translateX + (event.clientX - dragStart.x);
    translateY = dragStart.translateY + (event.clientY - dragStart.y);
    clampTranslate();
    applyTransform();
  });

  const stopDragging = () => { dragging = false; };
  image.addEventListener("pointerup", stopDragging);
  image.addEventListener("pointercancel", stopDragging);

  // ── Touch: doppio tap per zoomare, pinch a due dita, trascinamento
  // a un dito quando ingrandita — gestiti qui invece che via Pointer
  // Events per non far collidere il pan a un dito con l'inizio di un
  // pinch a due (vedi touch-action:none in lightbox.css, che lascia
  // tutta la gestione del gesto a questo script). ──
  let activePinch = false;
  let pinchStartDist = 0;
  let pinchStartScale = 1;
  let touchPanStart = null;
  let lastTap = 0;
  let lastTapPos = { x: 0, y: 0 };

  const touchDistance = ([a, b]) => Math.hypot(a.clientX - b.clientX, a.clientY - b.clientY);
  const touchMidpoint = ([a, b]) => ({ x: (a.clientX + b.clientX) / 2, y: (a.clientY + b.clientY) / 2 });

  image.addEventListener("touchstart", (event) => {
    if (event.touches.length === 2) {
      activePinch = true;
      touchPanStart = null;
      pinchStartDist = touchDistance(event.touches);
      pinchStartScale = scale;
      return;
    }

    if (event.touches.length === 1) {
      activePinch = false;
      const touch = event.touches[0];

      const now = Date.now();
      const tapDistance = Math.hypot(touch.clientX - lastTapPos.x, touch.clientY - lastTapPos.y);
      if (now - lastTap < 300 && tapDistance < 30) {
        toggleZoom(touch.clientX, touch.clientY);
      }
      lastTap = now;
      lastTapPos = { x: touch.clientX, y: touch.clientY };

      if (scale > MIN_SCALE) {
        touchPanStart = { x: touch.clientX, y: touch.clientY, translateX, translateY };
      }
    }
  });

  image.addEventListener("touchmove", (event) => {
    if (event.touches.length === 2) {
      event.preventDefault();
      const dist = touchDistance(event.touches);
      const mid = touchMidpoint(event.touches);
      zoomAt(mid.x, mid.y, pinchStartScale * (dist / pinchStartDist));
      return;
    }

    if (event.touches.length === 1 && touchPanStart) {
      event.preventDefault();
      const touch = event.touches[0];
      translateX = touchPanStart.translateX + (touch.clientX - touchPanStart.x);
      translateY = touchPanStart.translateY + (touch.clientY - touchPanStart.y);
      clampTranslate();
      applyTransform();
    }
  }, { passive: false });

  image.addEventListener("touchend", (event) => {
    if (event.touches.length < 2) activePinch = false;
    if (event.touches.length === 0) touchPanStart = null;
  });

  // Delegato sul documento (non sui singoli link): funziona anche per
  // trigger aggiunti altrove in futuro, senza dover ripetere questo
  // script per ogni nuovo carosello/galleria.
  document.addEventListener("click", (event) => {
    const trigger = event.target.closest("[data-lightbox]");
    if (trigger) {
      event.preventDefault();
      const altSource = trigger.matches("img") ? trigger : trigger.querySelector("img");
      open(trigger.href || trigger.dataset.lightbox, altSource?.alt);
      return;
    }

    if (!overlay.hidden && (event.target === overlay || event.target.closest("[data-lightbox-close]"))) {
      close();
    }
  });

  document.addEventListener("keydown", (event) => {
    if (event.key === "Escape" && !overlay.hidden) close();
  });
});
