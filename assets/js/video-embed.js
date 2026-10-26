// Il video di YouTube/Vimeo si carica solo al clic su "Riproduci": prima non
// c'è nessun iframe, quindi nessuna richiesta né cookie di terze parti.
// L'indirizzo "senza cookie" arriva già pronto da snippets/blocks/video.php.
document.addEventListener("click", (event) => {
  const play = event.target.closest(".video-block__play");
  if (!play) return;

  const frame = play.closest(".video-block__frame");
  const src = frame?.dataset.videoSrc;
  if (!src) return; // senza indirizzo resta il link al video originale

  event.preventDefault();

  const iframe = document.createElement("iframe");
  iframe.src = src;
  iframe.title = "Video";
  iframe.allow = "autoplay; fullscreen; picture-in-picture";
  iframe.allowFullscreen = true;
  // YouTube rifiuta di riprodurre l'embed senza un referrer.
  iframe.referrerPolicy = "strict-origin-when-cross-origin";

  play.replaceWith(iframe);
  iframe.focus();
});
