document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll(".patrocinio-carousel-section").forEach((section) => {
    const track = section.querySelector(".patrocinio-carousel__track");
    const prev = section.querySelector(".patrocinio-carousel__button--prev");
    const next = section.querySelector(".patrocinio-carousel__button--next");
    if (!track || !prev || !next) return;

    // A differenza di bits/theme-carousel.js queste frecce restano
    // sempre visibili (desktop compreso, vedi patrocinio-carousel.css)
    // — qui basta scorrere di una locandina alla volta, niente
    // hover-intent da gestire.
    const scrollByOneItem = (direction) => {
      const item = track.querySelector(".patrocinio-carousel__item");
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
