<?php
/**
 * @var string                       $intro Testo introduttivo su sfondo azzurro, opzionale
 *   (vedi content/3_chi-siamo/timeline/timeline.txt, campo "Introduzione").
 * @var iterable<array{
 *   id: string,
 *   anno: string,
 *   etichetta?: string,
 *   description?: string,
 *   images?: \Kirby\Cms\File[],
 * }> $items Capitoli della timeline (vedi bits/timeline-accordion).
 *
 * Elemento riutilizzabile da più pagine (vedi site/templates/timeline.php
 * e site/templates/chi-siamo.php): introduzione e capitoli vivono in un
 * solo posto, la pagina "La nostra storia" (vedi
 * site/collections/timeline.php e site/blueprints/pages/timeline.yml) —
 * richiamare questo snippet basta, non c'è nulla da ricompilare altrove.
 */
$intro = trim((string) ($intro ?? ''));
?>
<?php if ($intro !== ''): ?>
  <div class="block-container__section block-container__section--blue">
    <?php snippet('bits/paragraph', ['paragraphs' => [$intro]]) ?>
  </div>
<?php endif ?>
<?php snippet('bits/timeline-accordion', ['items' => $items]) ?>
