document.addEventListener("DOMContentLoaded", () => {
  // Le immagini della modale sono "deferred" (vedi atoms/picture.php e
  // bits/association-modal.php): niente src/srcset finché non si apre,
  // per non scaricare foto e loghi di associazioni che quasi nessuno
  // clicca. Idempotente: riaprire la stessa modale non ridownloada nulla,
  // src/srcset sono già impostati dalla prima apertura.
  const activateImages = (dialog) => {
    dialog.querySelectorAll("img[data-src]").forEach((img) => {
      img.src = img.dataset.src;
      if (img.dataset.srcset) img.srcset = img.dataset.srcset;
      delete img.dataset.src;
      delete img.dataset.srcset;
    });
  };

  // Delegato sul documento: funziona per qualunque coppia bottone/dialog
  // aggiunta in pagina (vedi bits/association-card e bits/association-modal),
  // niente da ripetere per ogni nuova card.
  document.addEventListener("click", (event) => {
    const opener = event.target.closest("[data-modal-open]");
    if (opener) {
      const dialog = document.getElementById(opener.dataset.modalTarget);
      if (dialog) activateImages(dialog);
      dialog?.showModal();
      return;
    }

    const closer = event.target.closest("[data-modal-close]");
    if (closer) {
      closer.closest("dialog")?.close();
      return;
    }

    // Click sullo sfondo del <dialog> stesso (non su un suo discendente,
    // altrimenti chiuderebbe anche cliccando dentro la modale): l'unico
    // caso in cui event.target è il <dialog> è quando il click cade
    // fuori dal riquadro di contenuto, sulla zona "backdrop" del
    // top-layer — showModal() copre l'intero elemento, non solo la sua
    // porzione visibile.
    if (event.target.tagName === "DIALOG" && event.target.classList.contains("association-modal")) {
      event.target.close();
    }
  });
});
