document.addEventListener("DOMContentLoaded", () => {
  const overlay = document.querySelector("[data-lightbox-overlay]");
  if (!overlay) return;

  const image = overlay.querySelector("[data-lightbox-image]");
  let lastFocused = null;

  const open = (src, alt) => {
    lastFocused = document.activeElement;
    image.src = src;
    image.alt = alt || "";
    overlay.hidden = false;
    document.body.style.overflow = "hidden";
    overlay.querySelector("[data-lightbox-close]")?.focus();
  };

  const close = () => {
    overlay.hidden = true;
    image.src = "";
    document.body.style.overflow = "";
    lastFocused?.focus();
  };

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
