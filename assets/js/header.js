document.addEventListener("DOMContentLoaded", () => {
  const header = document.querySelector(".header");
  if (!header) return;

  const menuToggle = header.querySelector(".header__menu-toggle");
  const nav = header.querySelector(".navbar");
  const panelToggles = header.querySelectorAll(".navbar__toggle");

  const closeAllPanels = () => {
    panelToggles.forEach((toggle) => {
      const panel = document.getElementById(
        toggle.getAttribute("aria-controls"),
      );
      toggle.setAttribute("aria-expanded", "false");
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

  // Pannelli: click per aprire/chiudere (oltre all'hover via CSS su desktop)
  panelToggles.forEach((toggle) => {
    toggle.addEventListener("click", () => {
      const panel = document.getElementById(
        toggle.getAttribute("aria-controls"),
      );
      if (!panel) return;
      const isOpen = panel.dataset.open === "true";

      closeAllPanels();

      panel.dataset.open = String(!isOpen);
      toggle.setAttribute("aria-expanded", String(!isOpen));
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
