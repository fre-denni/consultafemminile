document.addEventListener("DOMContentLoaded", () => {
  const MAX_WORDS = 500;
  const countWords = (text) => text.trim().split(/\s+/).filter(Boolean).length;

  document.querySelectorAll("[data-contact-form]").forEach((form) => {
    // Lo script viene incluso una volta per ogni form nella pagina.
    if (form.dataset.bound) return;
    form.dataset.bound = "true";

    const textarea = form.querySelector("textarea");
    const counter = form.querySelector("[data-contact-counter]");
    const status = form.querySelector("[data-contact-status]");
    const submit = form.querySelector('[type="submit"]');

    const updateCounter = () => {
      const words = countWords(textarea.value);
      const isOver = words > MAX_WORDS;
      counter.textContent = `${words}/${MAX_WORDS} parole`;
      counter.toggleAttribute("data-over", isOver);
      // Blocca l'invio con il messaggio nativo del browser, senza
      // tagliare il testo che l'utente sta scrivendo.
      textarea.setCustomValidity(
        isOver ? `Il messaggio può avere al massimo ${MAX_WORDS} parole.` : "",
      );
    };

    const showStatus = (message, kind) => {
      status.textContent = message;
      status.dataset.kind = kind;
      status.hidden = message === "";
    };

    textarea.addEventListener("input", updateCounter);
    updateCounter();

    // Il browser ha già validato i campi (required, email, parole) prima
    // di arrivare qui: se non fosse valido, "submit" non partirebbe.
    form.addEventListener("submit", async (event) => {
      event.preventDefault();
      submit.disabled = true;
      form.setAttribute("aria-busy", "true");
      showStatus("Invio in corso…", "pending");

      try {
        const response = await fetch(form.action, {
          method: "POST",
          body: new FormData(form),
          headers: { Accept: "application/json" },
        });
        const result = await response.json();

        showStatus(result.message, result.ok ? "ok" : "error");
        if (result.ok) {
          form.reset();
          updateCounter();
        }
      } catch {
        showStatus(
          "Non è stato possibile inviare il messaggio: controlla la connessione e riprova.",
          "error",
        );
      } finally {
        submit.disabled = false;
        form.removeAttribute("aria-busy");
      }
    });
  });
});
