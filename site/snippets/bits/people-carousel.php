<?php
/**
 * @var iterable $items      Persone (righe structure, vedi bits/person-card) da mostrare —
 *   sulla home sono sempre ≤ 6 (il consiglio), ma il componente va a capo da sé oltre le 6
 *   colonne per liste più lunghe (vedi la pagina "Le Persone", che ce ne passa fino a decine).
 * @var string   $label      Etichetta piccola sopra il carosello (es.
 *   "Consiglio 2026-2027"), come nel riferimento Figma. Facoltativa.
 * @var string   $background Sfondo della sezione: 'default' (bianco), 'tinted' (panna) o
 *   'blue' (azzurro) — stesso vocabolario del campo condiviso blueprints/fields/background.yml.
 *   Facoltativo, di default 'default'.
 * @var array{href: string, text: string}|null $cta Bottone opzionale sotto la griglia (es.
 *   "Scopri tutte le persone ›" verso la pagina dedicata, usato sulla home). Facoltativo.
 */
$items = $items->filter(fn ($person) => $person->foto()->toFile() !== null);

if ($items->count() === 0) return;

$label      ??= '';
$background ??= 'default';
$cta        ??= null;
?>
<section class="people-carousel-section block-full<?= $background !== 'default' ? ' people-carousel-section--' . html($background) : '' ?>">
  <?php if ($label !== ''): ?>
    <div class="people-carousel-section__header">
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
  <?php if ($cta !== null): ?>
    <div class="people-carousel-section__footer">
      <a href="<?= html($cta['href']) ?>" class="people-carousel-section__cta"><?= html($cta['text']) ?></a>
    </div>
  <?php endif ?>
</section>
