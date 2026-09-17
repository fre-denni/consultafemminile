document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll(".theme-carousel").forEach((carousel) => {
    const track = carousel.querySelector(".theme-carousel__track");
    if (!track) return;

    const slides = [...track.querySelectorAll(".theme-carousel__slide")];
    if (slides.length === 0) return;

    const isMobile = () => window.matchMedia("(max-width: 768px)").matches;
    let resetTimeout;

    // Stessa logica "intent hover" dell'header (assets/js/header.js): lo
    // stato attivo si sposta subito con mouseenter, e torna al default
    // (prima card) solo se il mouse esce per davvero dall'intero
    // carosello — non quando passa semplicemente da una card all'altra.
    const setActive = (target) => {
      clearTimeout(resetTimeout);
      slides.forEach((slide) => {
        const isActive = slide === target;
        slide.classList.toggle("theme-carousel__slide--active", isActive);
        slide
          .querySelector(".theme-card")
          ?.classList.toggle("theme-card--active", isActive);
      });
    };

    slides.forEach((slide) => {
      slide.addEventListener("mouseenter", () => {
        if (isMobile()) return;
        setActive(slide);
      });

      slide.addEventListener("focusin", () => {
        if (isMobile()) return;
        setActive(slide);
      });
    });

    track.addEventListener("mouseleave", () => {
      if (isMobile()) return;
      resetTimeout = setTimeout(() => setActive(slides[0]), 150);
    });

    track.addEventListener("focusout", (event) => {
      if (isMobile()) return;
      if (track.contains(event.relatedTarget)) return;
      resetTimeout = setTimeout(() => setActive(slides[0]), 150);
    });

    // ── Frecce (solo mobile: su desktop la navigazione è via hover) ──
    const prev = carousel.querySelector(".theme-carousel__button--prev");
    const next = carousel.querySelector(".theme-carousel__button--next");

    const scrollByOneSlide = (direction) => {
      const slide = track.querySelector(".theme-carousel__slide");
      if (!slide) return;

      const gap = parseFloat(getComputedStyle(track).columnGap || "0");
      const amount = slide.getBoundingClientRect().width + gap;
      track.scrollBy({ left: amount * direction, behavior: "smooth" });
    };

    prev?.addEventListener("click", () => scrollByOneSlide(-1));
    next?.addEventListener("click", () => scrollByOneSlide(1));

    // Disabilita la freccia quando non c'è più modo di scorrere in quella
    // direzione (inizio/fine riga).
    const updateButtonStates = () => {
      if (!prev || !next) return;

      const maxScroll = track.scrollWidth - track.clientWidth;
      prev.disabled = track.scrollLeft <= 1;
      next.disabled = track.scrollLeft >= maxScroll - 1;
    };

    updateButtonStates();
    track.addEventListener("scroll", updateButtonStates, { passive: true });
    window.addEventListener("resize", updateButtonStates);
  });
});
