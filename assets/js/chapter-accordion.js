document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll("[data-accordion]").forEach((accordion) => {
    const items = [...accordion.querySelectorAll("[data-accordion-item]")];

    const closeItem = (item) => {
      item.dataset.open = "false";
      item.querySelector("[data-accordion-toggle]")?.setAttribute("aria-expanded", "false");
    };

    const openItem = (item) => {
      // Un solo capitolo aperto alla volta: aprirne uno richiude gli altri.
      items.forEach((other) => {
        if (other !== item) closeItem(other);
      });
      item.dataset.open = "true";
      item.querySelector("[data-accordion-toggle]")?.setAttribute("aria-expanded", "true");
    };

    items.forEach((item) => {
      item.dataset.open = "false";
      item.querySelector("[data-accordion-toggle]")?.addEventListener("click", () => {
        item.dataset.open === "true" ? closeItem(item) : openItem(item);
      });
    });

    // Click fuori dall'accordion: richiude qualunque capitolo aperto.
    document.addEventListener("click", (event) => {
      if (accordion.contains(event.target)) return;
      items.forEach(closeItem);
    });
  });
});
