<?php
/**
 * @var \Kirby\Cms\File|null $image     File immagine Kirby (es. $page->cover()->toFile())
 * @var array                $sizes     Larghezze da generare, es. [400, 800, 1200]
 * @var string                $alt       Testo alternativo — vuoto ("") se l'immagine è puramente decorativa
 * @var string                $sizesAttr Attributo "sizes" per il browser, es. "(min-width: 768px) 50vw, 100vw"
 * @var string                $class     Classi CSS opzionali sull'<img>
 * @var bool                   $webp      Genera anche una sorgente WebP (richiede supporto server, vedi note in coda al file)
 * @var bool                   $lazy      Di default true (loading="lazy"). Passare false per immagini dentro
 *   contenuto che parte "display:none" e diventa visibile solo via JS (es. bits/association-modal,
 *   un <dialog>) — il lazy-load nativo del browser calcola la distanza dal viewport una sola volta,
 *   all'analisi iniziale della pagina: un'immagine mai layoutata a quel punto (perché dentro un
 *   antenato nascosto) può restare bloccata a naturalWidth 0 anche dopo che l'antenato diventa
 *   visibile — stesso problema già risolto per bits/logo-showreel, qui va evitato a monte.
 */
$sizes     ??= [400, 800, 1200, 1600];
$alt       ??= '';
$sizesAttr ??= '100vw';
$class     ??= '';
$webp      ??= true;
$lazy      ??= true;

if (!$image) return;

// Chiave esplicita "{width}w": srcset() usa la CHIAVE dell'array come
// descrittore quando il valore è un array di opzioni (necessario qui per
// passare `format`), a differenza del caso di un array di soli interi.
$webpSizes = [];
foreach ($sizes as $width) {
  $webpSizes[$width . 'w'] = ['width' => $width, 'format' => 'webp'];
}
?>
<picture>
  <?php if ($webp): ?>
    <source
      type="image/webp"
      srcset="<?= $image->srcset($webpSizes) ?>"
      sizes="<?= $sizesAttr ?>"
    >
  <?php endif ?>
  <img
    src="<?= $image->resize(max($sizes))->url() ?>"
    srcset="<?= $image->srcset($sizes) ?>"
    sizes="<?= $sizesAttr ?>"
    alt="<?= html($alt) ?>"
    <?= $lazy ? 'loading="lazy"' : '' ?>
    <?= $class !== '' ? 'class="' . html($class) . '"' : '' ?>
  >
</picture>
