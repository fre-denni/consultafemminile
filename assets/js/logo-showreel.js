document.addEventListener("DOMContentLoaded", () => {
  const prefersReducedMotion = window.matchMedia(
    "(prefers-reduced-motion: reduce)",
  ).matches;

  document.querySelectorAll(".logo-showreel").forEach((showreel) => {
    const track = showreel.querySelector(".logo-showreel__track");
    const firstGroup = showreel.querySelector(".logo-showreel__group");
    if (!track || !firstGroup) return;

    // Chi preferisce meno movimento in pagina: niente scorrimento,
    // i loghi restano semplicemente fermi (il markup li mostra già
    // tutti, non serve altro).
    if (prefersReducedMotion) return;

    const speed = 40; // px al secondo — "lentamente", come richiesto
    let groupWidth = firstGroup.getBoundingClientRect().width;
    let offset = 0;
    let paused = false;

    // La larghezza del gruppo è nota per certo solo dopo che ogni
    // immagine ha finito di caricare (i loghi sono lazy e di
    // dimensioni diverse tra loro) — senza questo ricalcolo il loop
    // scatta ogni volta che un logo cambia la larghezza del gruppo
    // dopo che l'animazione era già partita.
    const recalcGroupWidth = () => {
      groupWidth = firstGroup.getBoundingClientRect().width;
    };

    showreel.querySelectorAll("img").forEach((img) => {
      if (img.complete) return;
      img.addEventListener("load", recalcGroupWidth, { once: true });
    });

    window.addEventListener("resize", recalcGroupWidth);

    // In pausa al passaggio del mouse, per poter leggere un logo.
    showreel.addEventListener("mouseenter", () => {
      paused = true;
    });
    showreel.addEventListener("mouseleave", () => {
      paused = false;
    });

    let lastTimestamp = null;

    const step = (timestamp) => {
      if (lastTimestamp === null) lastTimestamp = timestamp;
      const deltaSeconds = (timestamp - lastTimestamp) / 1000;
      lastTimestamp = timestamp;

      if (!paused && groupWidth > 0) {
        offset -= speed * deltaSeconds;
        // Il gruppo è duplicato una volta (vedi bits/logo-showreel.php):
        // appena si è scorso esattamente della sua larghezza, la copia
        // successiva è già visivamente identica al punto di partenza —
        // sottrarre invece di azzerare evita qualunque scatto, anche a
        // frame-rate irregolare.
        if (offset <= -groupWidth) offset += groupWidth;
        track.style.transform = `translateX(${offset}px)`;
      }

      requestAnimationFrame(step);
    };

    requestAnimationFrame(step);
  });
});
