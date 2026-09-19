<?php
/**
 * @var iterable $items Persone (righe structure, vedi bits/person-card)
 *   da mostrare — per policy editoriale non sono mai più di 6, quindi
 *   niente scroll/paginazione qui, solo una riga che si adatta.
 * @var string   $label Etichetta piccola sopra il carosello (es.
 *   "Consiglio 2026-2027"), come nel riferimento Figma. Facoltativa.
 */
$items = $items->filter(fn ($person) => $person->foto()->toFile() !== null);

if ($items->count() === 0) return;

$label ??= '';
?>
<section class="people-carousel-section block-full">
  <?php if ($label !== ''): ?>
    <div class="people-carousel-section__label">
      <?php snippet('atoms/section-label', ['text' => $label]) ?>
    </div>
  <?php endif ?>
  <ul class="people-carousel">
    <?php foreach ($items as $person): ?>
      <li class="people-carousel__item">
        <?php snippet('bits/person-card', ['person' => $person]) ?>
      </li>
    <?php endforeach ?>
  </ul>
</section>
