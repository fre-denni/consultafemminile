<?php
/**
 * Blocco "video" (YouTube o Vimeo): vedi blueprints/blocks/video.yml.
 *
 * Il riquadro nero è un segnaposto: l'iframe di YouTube/Vimeo non esiste
 * nella pagina finché non si preme "Riproduci" (assets/js/video-embed.js),
 * così chi legge non contatta quei servizi — né riceve i loro cookie — senza
 * volerlo. Senza JavaScript il pulsante è un normale link al video originale.
 * L'indirizzo dell'iframe è sempre quello "senza cookie" costruito da
 * site/plugins/video-embed.
 *
 * @var \Kirby\Cms\Block $block
 */
$video = VideoEmbed::parse($block->url()->value());
if (!$video) return;

$caption  = $block->caption();
?>
<figure class="video-block block-full">
  <div class="video-block__frame" data-video-src="<?= esc(VideoEmbed::src($video, autoplay: true)) ?>">
    <a
      class="video-block__play"
      href="<?= esc($block->url()->value()) ?>"
      target="_blank"
      rel="noopener noreferrer"
      aria-label="Riproduci il video"
    >
      <span class="video-block__play-icon" aria-hidden="true"></span>
    </a>
  </div>
  <?php if ($caption->isNotEmpty()): ?>
    <figcaption class="video-block__caption"><?= $caption ?></figcaption>
  <?php endif ?>
<?php
// Lo script è uno solo anche con più video nella stessa pagina. Sta dentro il
// <figure> (non dopo) così il blocco resta l'ultimo figlio del contenitore e
// il CSS può togliere lo spazio finale quando è l'ultimo dell'articolo.
static $scriptLoaded = false;
if (!$scriptLoaded) {
  $scriptLoaded = true;
  echo js('assets/js/video-embed.js', ['defer' => true]);
}
?>
</figure>
