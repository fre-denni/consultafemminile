<?php
/**
 * @var \Kirby\Cms\File|null $image   Immagine di copertina della tematica (es. $page->images()->first())
 * @var string               $heading Titolo mostrato sopra l'immagine (es. $page->title())
 *
 * Fascia a piena larghezza in cima alla pagina di una tematica: la
 * copertina come sfondo, una tinta scura piatta sopra per leggibilità
 * (nel riferimento Figma è un overlay uniforme, non un gradiente come
 * bits/theme-card) e il titolo centrato, enorme, in bianco.
 */
if (!$image) return;
?>
<section class="tema-hero">
  <?php snippet('atoms/picture', [
    'image'     => $image,
    'alt'       => '',
    'sizes'     => [800, 1200, 1600, 2000],
    'sizesAttr' => '100vw',
    'class'     => 'tema-hero__img',
  ]) ?>
  <div class="tema-hero__overlay" aria-hidden="true"></div>
  <h1 class="tema-hero__heading"><?= html($heading) ?></h1>
</section>
