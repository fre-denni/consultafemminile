<?php
/**
 * @var \Kirby\Cms\Page $page Pagina di destinazione: usa ->file('cover.png'),
 *   ->title() e ->url()
 * @var string           $cta Testo del bottone mostrato in hover/focus
 */
$cta ??= 'Scopri di più';
$image = $page->file('cover.webp');
?>
<a href="<?= $page->url() ?>" class="header-thumbnail">
  <span class="header-thumbnail__media">
    <?php if ($image): ?>
      <?php snippet('atoms/picture', [
        'image'     => $image,
        'sizes'     => [320, 640],
        'sizesAttr' => '16rem',
        'class'     => 'header-thumbnail__img',
      ]) ?>
    <?php endif ?>
    <span class="header-thumbnail__overlay" aria-hidden="true"></span>
    <span class="header-thumbnail__cta" aria-hidden="true"><?= html($cta) ?> ›</span>
  </span>
  <span class="header-thumbnail__label"><?= html($page->title()) ?></span>
</a>
