document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll("[data-copy-link]").forEach((button) => {
    const label = button.querySelector("[data-copy-label]");
    const defaultLabel = label?.textContent ?? "";
    let resetTimer;

    const copyToClipboard = async (text) => {
      if (navigator.clipboard?.writeText) {
        try {
          await navigator.clipboard.writeText(text);
          return true;
        } catch {
          // Contesti non sicuri (es. http in sviluppo) non hanno accesso
          // alla Clipboard API: si ripiega sul metodo classico sotto.
        }
      }

      const helper = document.createElement("textarea");
      helper.value = text;
      helper.style.position = "fixed";
      helper.style.opacity = "0";
      document.body.appendChild(helper);
      helper.select();
      const copied = document.execCommand("copy");
      helper.remove();
      return copied;
    };

    // data-copied sostituisce la freccia col check (vedi article-header.css).
    const setState = (state) => {
      clearTimeout(resetTimer);
      button.toggleAttribute("data-copied", state === "copied");
      if (label) {
        label.textContent =
          { copied: "Link copiato", failed: "Copia non riuscita" }[state] ??
          defaultLabel;
      }
      if (state !== "idle") {
        resetTimer = setTimeout(() => setState("idle"), 2000);
      }
    };

    button.addEventListener("click", async () => {
      // La conferma è immediata e non aspetta l'esito della Clipboard API
      // (che può metterci un attimo): solo se la copia fallisce viene
      // sostituita da un messaggio d'errore.
      setState("copied");
      if (!(await copyToClipboard(button.dataset.copyLink))) {
        setState("failed");
      }
    });
  });
});
