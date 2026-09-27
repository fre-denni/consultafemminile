<?php
/**
 * @var iterable<array{
 *   id: string,
 *   anno: string,
 *   etichetta?: string,
 *   description?: string,
 *   images?: \Kirby\Cms\File[],
 * }> $items Capitoli della timeline (vedi site/templates/timeline.php).
 *
 * Adattatore sottile su bits/accordion (meccanica di apertura/chiusura e
 * blocco media condivisa con bits/chapter-accordion): qui vive solo ciò che
 * è specifico della timeline — anno, etichetta, descrizione, la riga chiusa
 * più compatta ($headerVariant: 'compact') e lo sfondo azzurro fisso (unico
 * tra i componenti della home/pagine: non scelto dal Panel come altrove, nel
 * riferimento Figma la timeline lo ha sempre). Il numero di immagini in
 * $item['images'] decide da sé cosa mostrare (vedi site/blueprints/pages/timeline.yml,
 * max 7): zero, niente media; una, una foto singola (una presidenza); da due
 * in su, un piccolo carosello (un evento).
 */
snippet('bits/accordion', [
  'items'          => $items,
  'panelPrefix'    => 'timeline-panel-',
  'headerVariant'  => 'compact',
  'imageSizesAttr' => '(min-width: 768px) 30vw, 90vw',
  'background'     => 'blue',
  'heading'        => fn ($item) => html($item['anno']),
  'body'           => function ($item) {
    $etichetta   = trim((string) ($item['etichetta'] ?? ''));
    $description = trim((string) ($item['description'] ?? ''));
    ob_start();
    ?>
    <?php if ($etichetta !== ''): ?>
      <p class="timeline-accordion__label"><?= html($etichetta) ?></p>
    <?php endif ?>
    <?php if ($description !== ''): ?>
      <p class="timeline-accordion__description"><?= html($description) ?></p>
    <?php endif ?>
    <?php
    return ob_get_clean();
  },
]) ?>
