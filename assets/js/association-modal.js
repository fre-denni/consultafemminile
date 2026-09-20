document.addEventListener("DOMContentLoaded", () => {
  // Delegato sul documento: funziona per qualunque coppia bottone/dialog
  // aggiunta in pagina (vedi bits/association-card e bits/association-modal),
  // niente da ripetere per ogni nuova card.
  document.addEventListener("click", (event) => {
    const opener = event.target.closest("[data-modal-open]");
    if (opener) {
      const dialog = document.getElementById(opener.dataset.modalTarget);
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
