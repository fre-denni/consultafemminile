<?php
/**
 * @var \Kirby\Cms\File $image Locandina (file immagine, vedi site/collections/patrocinio.php).
 *
 * Un elemento di bits/patrocinio-carousel: l'immagine, cliccabile per
 * aprirla in primo piano nel lightbox condiviso (vedi atoms/lightbox) —
 * un derivato entro 1600px, non l'originale (anche di qualche MB),
 * basta per schermi di ogni dimensione, portrait o landscape.
 */
?>
<a
  href="<?= $image->thumb(['width' => 1600, 'height' => 1600])->url() ?>"
  class="patrocinio-carousel__link"
  data-lightbox
>
  <?php snippet('atoms/picture', [
    'image'     => $image,
    'alt'       => $image->name(),
    'sizes'     => [400, 640, 800],
    'sizesAttr' => '(min-width: 768px) 20vw, 60vw',
    'class'     => 'patrocinio-carousel__img',
  ]) ?>
</a>
