document.addEventListener("DOMContentLoaded", () => {
  // Stesso pattern di assets/js/patrocinio-carousel.js: frecce sempre
  // visibili (desktop compreso), niente hover-intent da gestire. Un
  // capitolo alla volta può avere il suo carosello — anche più di uno
  // nella stessa pagina, se più capitoli hanno 2+ immagini — quindi
  // operiamo su tutti quelli trovati, non solo il primo.
  document.querySelectorAll(".timeline-accordion__media-track--carousel").forEach((track) => {
    const media = track.closest(".timeline-accordion__media");
    const prev = media?.querySelector(".timeline-accordion__media-button--prev");
    const next = media?.querySelector(".timeline-accordion__media-button--next");
    if (!prev || !next) return;

    const scrollByOneItem = (direction) => {
      const item = track.querySelector(".timeline-accordion__media-item");
      if (!item) return;

      const gap = parseFloat(getComputedStyle(track).columnGap || "0");
      const amount = item.getBoundingClientRect().width + gap;
      track.scrollBy({ left: amount * direction, behavior: "smooth" });
    };

    prev.addEventListener("click", () => scrollByOneItem(-1));
    next.addEventListener("click", () => scrollByOneItem(1));

    // Disabilita la freccia quando non c'è più modo di scorrere in
    // quella direzione (inizio/fine).
    const updateButtonStates = () => {
      const maxScroll = track.scrollWidth - track.clientWidth;
      prev.disabled = track.scrollLeft <= 1;
      next.disabled = track.scrollLeft >= maxScroll - 1;
    };

    updateButtonStates();
    track.addEventListener("scroll", updateButtonStates, { passive: true });
    window.addEventListener("resize", updateButtonStates);
  });
});
