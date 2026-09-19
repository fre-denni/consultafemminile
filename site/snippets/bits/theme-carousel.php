<?php
/**
 * @var \Kirby\Cms\Pages $items Pagine tematica da mostrare (già filtrate/limitate a monte)
 */

// bits/theme-card non renderizza nulla senza cover.png: scartiamo quelle
// pagine qui, altrimenti il carosello si ritroverebbe slide vuote.
$items = $items->filter(fn ($item) => $item->file('cover.webp') !== null);

if ($items->count() === 0) return;
?>
<div class="theme-carousel block-full">
  <?php if ($items->count() > 1): ?>
    <div class="theme-carousel__header">
      <div class="theme-carousel__controls">
        <button type="button" class="theme-carousel__button theme-carousel__button--prev" aria-label="Tema precedente">←</button>
        <button type="button" class="theme-carousel__button theme-carousel__button--next" aria-label="Tema successivo">→</button>
      </div>
    </div>
  <?php endif ?>
  <ul class="theme-carousel__track">
    <?php $i = 0 ?>
    <?php foreach ($items as $item): ?>
      <?php $isFirst = $i++ === 0 ?>
      <li class="theme-carousel__slide<?= $isFirst ? ' theme-carousel__slide--active' : '' ?>">
        <?php snippet('bits/theme-card', ['page' => $item, 'active' => $isFirst]) ?>
      </li>
    <?php endforeach ?>
  </ul>
</div>
<?= js('assets/js/theme-carousel.js', ['defer' => true]) ?>
