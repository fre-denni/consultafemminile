<?php
/**
 * @var iterable $items   Locandine (file immagine, vedi
 *   site/collections/patrocinio.php) — nessun dato oltre l'immagine
 *   per ora, il modello dei progetti patrocinati non è ancora definito.
 * @var string   $heading    Etichetta nell'header sopra il carosello
 *   (opzionale) — stile "sezione" (vedi bits/section-divider), una
 *   riga sola con le frecce di navigazione.
 * @var string   $background Sfondo della sezione: 'default' (bianco) o 'tinted' (panna) —
 *   stesso vocabolario del campo condiviso blueprints/fields/background.yml. Facoltativo,
 *   di default 'default'.
 *
 * Striscia orizzontale "classica": scroll nativo (drag/trackpad/touch)
 * con snap, mai a capo — a differenza di bits/people-carousel non c'è
 * nessuna interazione hover, un click apre semplicemente la locandina
 * in primo piano (vedi atoms/lightbox). Stessa larghezza/proporzione
 * carta di bits/person-card, per restare coerente con l'altro
 * carosello della home. Le frecce (assets/js/patrocinio-carousel.js)
 * sono visibili e usabili sia su mobile che su desktop — a differenza
 * di quelle di bits/theme-carousel, che esistono solo su mobile.
 */
if ($items->count() === 0) return;

$heading     ??= '';
$background  ??= 'default';
$hasControls = $items->count() > 1;
?>
<section class="patrocinio-carousel-section block-full<?= $background === 'tinted' ? ' patrocinio-carousel-section--tinted' : '' ?>">
  <?php if ($heading !== '' || $hasControls): ?>
    <div class="patrocinio-carousel-section__header">
      <?php if ($heading !== ''): ?>
        <?php snippet('atoms/section-label', ['text' => $heading]) ?>
      <?php endif ?>
      <?php if ($hasControls): ?>
        <div class="patrocinio-carousel__controls">
          <button type="button" class="patrocinio-carousel__button patrocinio-carousel__button--prev" aria-label="Locandina precedente">←</button>
          <button type="button" class="patrocinio-carousel__button patrocinio-carousel__button--next" aria-label="Locandina successiva">→</button>
        </div>
      <?php endif ?>
    </div>
  <?php endif ?>
  <div class="patrocinio-carousel">
    <ul class="patrocinio-carousel__track">
      <?php foreach ($items as $image): ?>
        <li class="patrocinio-carousel__item">
          <a
            href="<?= $image->url() ?>"
            class="patrocinio-carousel__link"
            data-lightbox
          >
            <?php snippet('atoms/picture', [
              'image'     => $image,
              'alt'       => $image->name(),
              'sizes'     => [320, 500, 700],
              'sizesAttr' => '(min-width: 768px) 20vw, 60vw',
              'class'     => 'patrocinio-carousel__img',
            ]) ?>
          </a>
        </li>
      <?php endforeach ?>
    </ul>
  </div>
</section>
<?= js('assets/js/patrocinio-carousel.js', ['defer' => true]) ?>
<?php snippet('atoms/lightbox') ?>
