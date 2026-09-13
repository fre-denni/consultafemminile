<?php
/**
 * @var iterable $items Elementi con ->title() e ->url(); se un elemento
 *   espone anche ->icon() (nome file in assets/icons), l'icona sostituisce
 *   il testo del link.
 */
?>
<nav class="list">
  <menu>
    <?php foreach($items as $item): ?>
      <?php
        $icon = $item->icon();
        $icon = $icon instanceof \Kirby\Content\Field ? $icon->value() : $icon;
      ?>
      <li>
        <a href="<?= $item->url() ?>" <?= $icon ? 'aria-label="' . html($item->title()) . '"' : '' ?>>
          <?php if ($icon): ?>
            <?php snippet('atoms/icon', ['name' => $icon]) ?>
          <?php else: ?>
            <?= $item->title() ?>
          <?php endif ?>
        </a>
      </li>
    <?php endforeach ?>
  </menu>
</nav>