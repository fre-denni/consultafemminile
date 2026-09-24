<?php
/**
 * @var \Kirby\Cms\Page $item Sottopagina "output" (un articolo, vedi
 *   site/blueprints/pages/output.yml) — figlia di una tematica, apre
 *   sempre la pagina dell'articolo stesso (mai un link esterno).
 *
 * Stessa "forma" delle altre card del sito (bordo + radius, niente
 * ombra — vedi bits/association-card): qui però la foto copre tutta
 * la card e il riquadro di testo (titolo + link "Leggi l'articolo") è
 * sovrapposto in basso, non affiancato — vedi il riferimento Figma.
 * Tutta la card è cliccabile.
 */
$image = $item->images()->first();

if (!$image) return;
?>
<a href="<?= html($item->url()) ?>" class="output-card">
  <?php snippet('atoms/picture', [
    'image'     => $image,
    'alt'       => '',
    'sizes'     => [400, 700, 1000],
    'sizesAttr' => '(min-width: 768px) 20vw, 75vw',
    'class'     => 'output-card__img',
  ]) ?>
  <span class="output-card__content">
    <span class="output-card__title"><?= html($item->title()) ?></span>
    <span class="output-card__link">Leggi l'articolo</span>
  </span>
</a>
