document.addEventListener("DOMContentLoaded", () => {
  const videos = document.querySelectorAll(".hero-video__media");
  if (!videos.length) return;

  // Chi preferisce meno movimento non deve subire l'autoplay: mettiamo
  // in pausa e togliamo l'attributo, mostrando il fotogramma fermo.
  const reducedMotion = window.matchMedia("(prefers-reduced-motion: reduce)");

  const applyMotionPreference = () => {
    videos.forEach((video) => {
      if (reducedMotion.matches) {
        video.removeAttribute("autoplay");
        video.pause();
      } else if (video.paused) {
        video.play().catch(() => {});
      }
    });
  };

  applyMotionPreference();
  reducedMotion.addEventListener("change", applyMotionPreference);
});
