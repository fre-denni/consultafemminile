<?php
/**
 * @var \Kirby\Cms\Pages $items      Sottopagine "output" da mostrare
 *   (vedi bits/output-card e site/blueprints/pages/output.yml) — già
 *   filtrate dal chiamante: site/templates/tematica.php (solo gli
 *   output di quella tematica) o site/templates/tematiche.php /
 *   site/collections/outputs.php (gli ultimi output di tutte le
 *   tematiche insieme).
 * @var string            $heading    Etichetta nell'header sopra il carosello (opzionale).
 * @var string            $background Sfondo della sezione: 'default' (bianco), 'tinted' (panna) o
 *   'blue' (azzurro) — stesso vocabolario di blueprints/fields/background.yml.
 *   Ignorato quando $nested è true (vedi sotto).
 * @var bool               $nested Di default false: sezione a piena larghezza con gutter e bordo
 *   sopra (vedi site/templates/tematica.php). true quando incorporato dentro un altro
 *   componente che già gestisce il proprio contenitore (vedi bits/chapter-accordion,
 *   dentro .chapter-accordion__body-inner) — niente piena larghezza, gutter o bordo,
 *   il carosello resta semplicemente largo quanto il suo contenitore.
 *
 * Stesso comportamento di bits/patrocinio-carousel (duplicato apposta,
 * ogni componente resta autonomo): striscia a scorrimento nativo con
 * snap, frecce sempre visibili sia su mobile che su desktop — a
 * differenza di quelle di bits/theme-carousel, solo mobile.
 */
$items = $items->filter(fn ($item) => $item->images()->first() !== null);

if ($items->count() === 0) return;

$heading     ??= '';
$background  ??= 'default';
$nested      ??= false;
$hasControls = $items->count() > 1;
?>
<section class="output-carousel-section<?= $nested ? ' output-carousel-section--nested' : ' block-full' ?><?= !$nested && $background !== 'default' ? ' output-carousel-section--' . html($background) : '' ?>">
  <?php if ($heading !== '' || $hasControls): ?>
    <div class="output-carousel-section__header">
      <?php if ($heading !== ''): ?>
        <?php snippet('atoms/section-label', ['text' => $heading]) ?>
      <?php endif ?>
      <?php if ($hasControls): ?>
        <div class="output-carousel__controls">
          <button type="button" class="output-carousel__button output-carousel__button--prev" aria-label="Articolo precedente">←</button>
          <button type="button" class="output-carousel__button output-carousel__button--next" aria-label="Articolo successivo">→</button>
        </div>
      <?php endif ?>
    </div>
  <?php endif ?>
  <div class="output-carousel">
    <ul class="output-carousel__track">
      <?php foreach ($items as $item): ?>
        <li class="output-carousel__item">
          <?php snippet('bits/output-card', ['item' => $item]) ?>
        </li>
      <?php endforeach ?>
    </ul>
  </div>
</section>
<?= js('assets/js/output-carousel.js', ['defer' => true]) ?>
