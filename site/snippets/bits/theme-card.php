<?php
/**
 * @var \Kirby\Cms\Page $page   Pagina tematica: usa ->title(), ->subtitle(),
 *   ->description() e ->file('cover.png')
 * @var bool             $active Stato "attivo": testo e CTA visibili sopra
 *   l'immagine. Da fermo (non attivo) la card mostra solo un bordo
 *   sfumato — pensata per l'uso dentro bits/theme-carousel.
 *
 * Sottotitolo e descrizione (troncata a 35 parole, vedi
 * site/config/methods.php) restano due elementi separati: su mobile la
 * descrizione si nasconde e resta solo il sottotitolo (vedi theme-card.css).
 *
 * Il link porta solo sul bottone CTA, non su tutta la card: il
 * contenitore non è più un <a> (l'hover/focus che apre la card su
 * desktop resta gestito su di esso via classe, vedi theme-carousel.js).
 */
$active ??= false;

$image = $page->file('cover.webp');
if (!$image) return;

$subtitle    = trim((string) $page->subtitle());
$description = trim($page->description()->truncateWords(35));
?>
<div class="theme-card<?= $active ? ' theme-card--active' : '' ?>">
  <span class="theme-card__media">
    <?php snippet('atoms/picture', [
      'image'     => $image,
      'sizes'     => [640, 1080, 1600],
      'sizesAttr' => '(min-width: 768px) 70vw, 100vw',
      'class'     => 'theme-card__img',
    ]) ?>
  </span>
  <span class="theme-card__scrim" aria-hidden="true"></span>
  <span class="theme-card__content">
    <span class="theme-card__title"><?= html($page->title()) ?></span>
    <?php if ($subtitle !== ''): ?>
      <span class="theme-card__subtitle"><?= html($subtitle) ?></span>
    <?php endif ?>
    <?php if ($description !== ''): ?>
      <span class="theme-card__description"><?= html($description) ?></span>
    <?php endif ?>
    <a href="<?= $page->url() ?>" class="theme-card__cta" aria-label="<?= html($page->title()) ?> — Scopri di più">
      Scopri di più ›
    </a>
  </span>
</div>
