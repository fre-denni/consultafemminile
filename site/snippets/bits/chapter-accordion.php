<?php
/**
 * @var iterable<array{
 *   id: string,
 *   title: string,
 *   subtitle?: string,
 *   description?: string,
 *   images?: \Kirby\Cms\File[],
 *   ctaUrl?: string|null,
 *   outputs?: \Kirby\Cms\Pages|null,
 * }> $items Elenco capitoli — un array associativo normalizzato per
 *   ognuno, non pagine direttamente: chi chiama (site/templates/tematiche.php)
 *   costruisce questa forma, il componente non conosce le pagine "tematica".
 *
 * Adattatore sottile su bits/accordion (meccanica di apertura/chiusura e
 * blocco media condivisa con bits/timeline-accordion): qui vive solo ciò
 * che è specifico dei capitoli-tematica — titolo, sottotitolo, descrizione,
 * CTA e gli output della tematica (mostrati con bits/output-carousel dentro
 * lo stesso div del testo, non come sezione a piena larghezza a parte, vedi
 * il parametro $nested di bits/output-carousel).
 *
 * Gli "eventi collegati" del riferimento Figma non compaiono ancora: il
 * modello dati per gli eventi non è definito, verranno aggiunti in un
 * secondo momento.
 */
snippet('bits/accordion', [
  'items'          => $items,
  'panelPrefix'    => 'chapter-panel-',
  'headerVariant'  => 'large',
  'imageSizesAttr' => '(min-width: 768px) 35vw, 90vw',
  'heading'        => fn ($item) => html($item['title']),
  'body'           => function ($item) {
    $subtitle    = trim((string) ($item['subtitle'] ?? ''));
    $description = trim((string) ($item['description'] ?? ''));
    $ctaUrl      = trim((string) ($item['ctaUrl'] ?? ''));
    ob_start();
    ?>
    <p class="chapter-accordion__body-title"><?= html($item['title']) ?></p>
    <?php if ($subtitle !== ''): ?>
      <p class="chapter-accordion__subtitle"><?= html($subtitle) ?></p>
    <?php endif ?>
    <?php if ($description !== ''): ?>
      <p class="chapter-accordion__description"><?= html($description) ?></p>
    <?php endif ?>
    <?php if ($ctaUrl !== ''): ?>
      <a href="<?= html($ctaUrl) ?>" class="chapter-accordion__cta">Scopri di più ›</a>
    <?php endif ?>
    <?php if (($item['outputs'] ?? null) !== null): ?>
      <?php snippet('bits/output-carousel', [
        'items'   => $item['outputs'],
        'heading' => 'Output',
        'nested'  => true,
      ]) ?>
    <?php endif ?>
    <?php
    return ob_get_clean();
  },
]) ?>
