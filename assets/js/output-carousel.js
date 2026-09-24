document.addEventListener("DOMContentLoaded", () => {
  // Stesso pattern di assets/js/patrocinio-carousel.js: frecce sempre
  // visibili (desktop compreso), niente hover-intent da gestire.
  document.querySelectorAll(".output-carousel-section").forEach((section) => {
    const track = section.querySelector(".output-carousel__track");
    const prev = section.querySelector(".output-carousel__button--prev");
    const next = section.querySelector(".output-carousel__button--next");
    if (!track || !prev || !next) return;

    const scrollByOneItem = (direction) => {
      const item = track.querySelector(".output-carousel__item");
      if (!item) return;

      const gap = parseFloat(getComputedStyle(track).columnGap || "0");
      const amount = item.getBoundingClientRect().width + gap;
      track.scrollBy({ left: amount * direction, behavior: "smooth" });
    };

    prev.addEventListener("click", () => scrollByOneItem(-1));
    next.addEventListener("click", () => scrollByOneItem(1));

    // Disabilita la freccia quando non c'è più modo di scorrere in
    // quella direzione (inizio/fine riga).
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
