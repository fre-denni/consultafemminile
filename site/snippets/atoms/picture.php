<?php
/**
 * @var \Kirby\Cms\File|null $image     File immagine Kirby (es. $page->cover()->toFile())
 * @var array                $sizes     Larghezze da generare, es. [400, 800, 1200]
 * @var string                $alt       Testo alternativo — vuoto ("") se l'immagine è puramente decorativa
 * @var string                $sizesAttr Attributo "sizes" per il browser, es. "(min-width: 768px) 50vw, 100vw"
 * @var string                $class     Classi CSS opzionali sull'<img>
 * @var bool                   $webp      Genera anche una sorgente WebP (richiede supporto server, vedi note in coda al file)
 */
$sizes     ??= [400, 800, 1200, 1600];
$alt       ??= '';
$sizesAttr ??= '100vw';
$class     ??= '';
$webp      ??= true;

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
    loading="lazy"
    <?= $class !== '' ? 'class="' . html($class) . '"' : '' ?>
  >
</picture>
