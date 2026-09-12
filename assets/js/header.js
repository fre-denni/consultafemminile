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
  // Logica "Intent Hover" Avanzata per Desktop
  // ——————————————————————————————————————————————————

  // Selezioniamo TUTTI i link della navbar, non solo quelli espandibili
  const allNavItems = header.querySelectorAll(".navbar__item");
  const navList = header.querySelector(".navbar__list");
  let desktopHoverTimeout;

  allNavItems.forEach((item) => {
    // Quando entri in un link qualsiasi (es. sia "Chi siamo" che "La nostra storia")
    item.addEventListener("mouseenter", () => {
      if (isMobile()) return;

      // 1. Ferma subito la chiusura ritardata
      clearTimeout(desktopHoverTimeout);

      // 2. Chiudi tutti i pannelli (fondamentale quando passi su "La nostra storia")
      closeAllPanels();

      // 3. Se questo specifico item ha un sottomenu, aprilo
      const link = item.querySelector(".navbar__link");
      if (link && link.hasAttribute("aria-controls")) {
        const panel = document.getElementById(
          link.getAttribute("aria-controls"),
        );
        if (panel) {
          panel.dataset.open = "true";
          link.setAttribute("aria-expanded", "true");
        }
      }
    });
  });

  // Il mouseleave non lo facciamo più sul singolo link, ma sull'INTERO menu (ul).
  // Finché il mouse viaggia tra un link e l'altro, o scende nel pannello,
  // non fai mai mouseleave dalla navbar__list.
  if (navList) {
    navList.addEventListener("mouseleave", () => {
      if (isMobile()) return;

      // Parte il timer solo se esci completamente dall'ecosistema della navbar
      desktopHoverTimeout = setTimeout(() => {
        closeAllPanels();
      }, 150);
    });
  }

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
