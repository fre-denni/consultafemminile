<?php
/**
 * @var iterable $items Elementi con ->title() e ->url(); se un elemento
 *   espone anche ->icon() (nome file in assets/icons), l'icona sostituisce
 *   il testo del link. Se espone ->target() (es. '_blank'), il link lo usa
 *   e aggiunge rel="noopener noreferrer" — pensato per le voci che puntano
 *   fuori dal sito (vedi footer.php, link "Carbon Footprint").
 */
?>
<nav class="list">
  <menu>
    <?php foreach($items as $item): ?>
      <?php
        $icon = $item->icon();
        $icon = $icon instanceof \Kirby\Content\Field ? $icon->value() : $icon;

        $target = $item->target();
        $target = $target instanceof \Kirby\Content\Field ? $target->value() : $target;
      ?>
      <li>
        <a
          href="<?= $item->url() ?>"
          <?= $icon ? 'aria-label="' . html($item->title()) . '"' : '' ?>
          <?= $target ? 'target="' . html($target) . '" rel="noopener noreferrer"' : '' ?>
        >
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