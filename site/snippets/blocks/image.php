<?php
/**
 * Blocco "image" di Kirby (blueprint stock, vedi kirby/config/blocks/image):
 * stessa struttura del suo snippet originale — <figure data-ratio
 * data-crop> con <img> e <figcaption>, a cui si aggancia il CSS di
 * atoms/block-container — ma con un derivato ridimensionato e lazy-load
 * al posto dell'originale a piena risoluzione (una foto di qualche MB
 * caricata dal Panel arrivava intera al browser).
 *
 * @var \Kirby\Cms\Block $block
 */
$alt     = $block->alt();
$caption = $block->caption();
$crop    = $block->crop()->isTrue();
$link    = $block->link();
$ratio   = $block->ratio()->or('auto');
$image   = null;
$src     = null;

if ($block->location() == 'web') {
  $src = $block->src()->esc();
} elseif ($image = $block->image()->toFile()) {
  $alt = $alt->or($image->alt());
}

// Colonna di lettura: 46rem su desktop (vedi atoms/block-container.css),
// tutta la larghezza meno il gutter su mobile.
$picture = fn () => $image
  ? snippet('atoms/picture', [
      'image'     => $image,
      'alt'       => $alt->value(),
      'sizes'     => [640, 800, 1280, 1600],
      'sizesAttr' => '(min-width: 768px) 46rem, calc(100vw - 2rem)',
      // Nel flusso del testo: lo spazio va riservato prima che l'immagine
      // arrivi. Il CSS di block-container.css fissa width:100% e height:auto.
      'dimensions' => true,
    ], return: true)
  : '<img src="' . $src . '" alt="' . $alt->esc() . '" loading="lazy" decoding="async">';
?>
<?php if ($image || $src): ?>
<figure<?= Html::attr(['data-ratio' => $ratio, 'data-crop' => $crop], null, ' ') ?>>
  <?php if ($link->isNotEmpty()): ?>
  <a href="<?= Str::esc($link->toUrl()) ?>">
    <?= $picture() ?>
  </a>
  <?php else: ?>
  <?= $picture() ?>
  <?php endif ?>

  <?php if ($caption->isNotEmpty()): ?>
  <figcaption>
    <?= $caption ?>
  </figcaption>
  <?php endif ?>
</figure>
<?php endif ?>
