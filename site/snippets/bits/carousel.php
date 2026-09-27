<?php
/**
 * @var iterable  $items      Elementi da mostrare, in qualunque forma il closure $item sappia
 *   leggere — deve solo essere Countable (Pages, Files, un array...).
 * @var callable  $item       fn($entry): string — markup del contenuto di UN elemento (es. via
 *   snippet('bits/output-card', ['item' => $entry], return: true)): il carosello genera solo
 *   il <li> che lo contiene, non sa nulla della forma di $entry.
 * @var string    $heading    Etichetta nell'header sopra il carosello (opzionale).
 * @var string    $background Sfondo della sezione: 'default' (bianco), 'tinted' (panna) o
 *   'blue' (azzurro) — stesso vocabolario di blueprints/fields/background.yml. Ignorato
 *   quando $nested è true.
 * @var bool      $nested     Di default false: sezione a piena larghezza con gutter e bordo
 *   sopra. true quando incorporato dentro un altro componente che già gestisce il proprio
 *   contenitore (vedi bits/chapter-accordion, dentro .chapter-accordion__body-inner) — niente
 *   piena larghezza, gutter o bordo, il carosello resta largo quanto il suo contenitore.
 * @var string    $variant    'cards' (default) o 'posters': cambia solo dimensione e proporzione
 *   della card (vedi carousel.css) — scorrimento, frecce e header restano identici.
 * @var string    $prevLabel  aria-label del bottone indietro (es. "Articolo precedente").
 * @var string    $nextLabel  aria-label del bottone avanti (es. "Locandina successiva").
 * @var callable|null $after  Markup extra dopo il carosello, fuori da <section> (es.
 *   atoms/lightbox per bits/patrocinio-carousel).
 *
 * Meccanica di scorrimento condivisa da bits/output-carousel e
 * bits/patrocinio-carousel — nati come due componenti duplicati apposta
 * per restare autonomi, poi accorpati qui: striscia a scorrimento
 * nativo con snap, frecce sempre visibili sia su mobile che su desktop
 * (a differenza di quelle di bits/theme-carousel, solo mobile). Cosa
 * sia un "item" resta deciso da chi chiama, tramite $item — qui non
 * c'è nessuna assunzione sulla sua forma.
 */
$heading    ??= '';
$background ??= 'default';
$nested     ??= false;
$variant    ??= 'cards';
$prevLabel  ??= 'Elemento precedente';
$nextLabel  ??= 'Elemento successivo';
$after      ??= null;

if (count($items) === 0) return;

$hasControls = count($items) > 1;
?>
<section class="carousel-section<?= $nested ? ' carousel-section--nested' : ' block-full' ?><?= !$nested && $background !== 'default' ? ' carousel-section--' . html($background) : '' ?>">
  <?php if ($heading !== '' || $hasControls): ?>
    <div class="carousel-section__header">
      <?php if ($heading !== ''): ?>
        <?php snippet('atoms/section-label', ['text' => $heading]) ?>
      <?php endif ?>
      <?php if ($hasControls): ?>
        <div class="carousel__controls">
          <button type="button" class="carousel__button carousel__button--prev" aria-label="<?= html($prevLabel) ?>">←</button>
          <button type="button" class="carousel__button carousel__button--next" aria-label="<?= html($nextLabel) ?>">→</button>
        </div>
      <?php endif ?>
    </div>
  <?php endif ?>
  <div class="carousel carousel--<?= html($variant) ?>">
    <ul class="carousel__track">
      <?php foreach ($items as $entry): ?>
        <li class="carousel__item"><?= $item($entry) ?></li>
      <?php endforeach ?>
    </ul>
  </div>
</section>
<?= js('assets/js/carousel.js', ['defer' => true]) ?>
<?php if ($after): ?><?= $after() ?><?php endif ?>
