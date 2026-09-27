document.addEventListener("DOMContentLoaded", () => {
  // Condiviso da bits/output-carousel e bits/patrocinio-carousel (vedi
  // bits/carousel): frecce sempre visibili anche su desktop, niente
  // hover-intent da gestire — a differenza di quelle di
  // assets/js/theme-carousel.js, solo mobile.
  document.querySelectorAll(".carousel-section").forEach((section) => {
    const track = section.querySelector(".carousel__track");
    const prev = section.querySelector(".carousel__button--prev");
    const next = section.querySelector(".carousel__button--next");
    if (!track || !prev || !next) return;

    const scrollByOneItem = (direction) => {
      const item = track.querySelector(".carousel__item");
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
