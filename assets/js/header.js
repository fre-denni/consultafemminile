document.addEventListener("DOMContentLoaded", () => {
  const header = document.querySelector(".header");
  if (!header) return;

  const menuToggle = header.querySelector(".header__menu-toggle");
  const nav = header.querySelector(".navbar");
  const expandableLinks = header.querySelectorAll(
    ".navbar__link[aria-controls]",
  );
  const isMobile = () => window.matchMedia("(max-width: 768px)").matches;

  // Espone l'altezza reale dell'header come variabile CSS: il menu mobile
  // si aggancia esattamente sotto di essa, senza valori stimati a mano.
  const setHeaderHeightVar = () => {
    document.documentElement.style.setProperty(
      "--header-height",
      `${header.offsetHeight}px`,
    );
  };
  setHeaderHeightVar();
  window.addEventListener("resize", setHeaderHeightVar);

  const closeAllPanels = () => {
    expandableLinks.forEach((link) => {
      const panel = document.getElementById(link.getAttribute("aria-controls"));
      link.setAttribute("aria-expanded", "false");
      if (panel) panel.dataset.open = "false";
    });
  };

  // Hamburger: apre/chiude il menu su mobile
  menuToggle?.addEventListener("click", () => {
    const isOpen = nav?.dataset.open === "true";
    if (!nav) return;
    nav.dataset.open = String(!isOpen);
    menuToggle.setAttribute("aria-expanded", String(!isOpen));
    if (isOpen) closeAllPanels();
  });

  // Voci con pannello: su mobile il tap apre/chiude il pannello invece di
  // navigare; su desktop restano link normali (lì l'apertura è via hover CSS).
  expandableLinks.forEach((link) => {
    link.addEventListener("click", (event) => {
      if (!isMobile()) return;

      event.preventDefault();

      const panel = document.getElementById(link.getAttribute("aria-controls"));
      if (!panel) return;
      const isOpen = panel.dataset.open === "true";

      closeAllPanels();

      panel.dataset.open = String(!isOpen);
      link.setAttribute("aria-expanded", String(!isOpen));
    });
  });

  // ——————————————————————————————————————————————————
  // Logica "Intent Hover" per Desktop
  // ——————————————————————————————————————————————————
  const expandableItems = header.querySelectorAll(".navbar__item--expandable");
  let desktopHoverTimeout;

  expandableItems.forEach((item) => {
    const link = item.querySelector(".navbar__link");
    const panel = document.getElementById(link.getAttribute("aria-controls"));

    if (!link || !panel) return;

    // Quando il mouse entra nel container (link + pannello)
    item.addEventListener("mouseenter", () => {
      if (isMobile()) return;

      // 1. Cancella la chiusura in corso (se stavi uscendo ma ci hai ripensato)
      clearTimeout(desktopHoverTimeout);

      // 2. Chiudi immediatamente tutti gli altri sottomenu
      closeAllPanels();

      // 3. Apri questo sottomenu
      panel.dataset.open = "true";
      link.setAttribute("aria-expanded", "true");
    });

    // Quando il mouse esce dal container
    item.addEventListener("mouseleave", () => {
      if (isMobile()) return;

      // Ritardiamo la chiusura di 150ms.
      // Se vai su un altro link, il mouseenter dell'altro link annullerà questo timer.
      // Se stai solo muovendo il mouse verso il basso, ti perdona le sbavature.
      desktopHoverTimeout = setTimeout(() => {
        panel.dataset.open = "false";
        link.setAttribute("aria-expanded", "false");
      }, 150);
    });
  });

  // Esc chiude tutto
  document.addEventListener("keydown", (event) => {
    if (event.key !== "Escape") return;

    closeAllPanels();

    if (nav?.dataset.open === "true") {
      nav.dataset.open = "false";
      menuToggle?.setAttribute("aria-expanded", "false");
    }
  });
});
