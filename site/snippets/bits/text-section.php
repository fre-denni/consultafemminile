<?php
/**
 * @var string $title    Prima riga del titolo (es. "Art.1")
 * @var string $subtitle Seconda riga, opzionale (es. "Denominazione sede")
 * @var string $text     HTML già pronto del corpo (campo writer: paragrafi, elenchi, grassetti)
 * @var string $style    'article' (default: titolo su due righe) | 'large' (un solo titolo grande,
 *   per aprire una sezione)
 *
 * Colonna di lettura stretta e centrata, testo allineato a sinistra: è la
 * forma di uno statuto o di un regolamento, articolo dopo articolo — vedi
 * text-section.css per il ritmo verticale tra gli articoli.
 */
$subtitle ??= '';
$text     ??= '';
$style    ??= 'article';
?>
<section class="text-section text-section--<?= html($style) ?>">
  <h3 class="text-section__heading">
    <span class="text-section__title"><?= html($title) ?></span>
    <?php if ($subtitle !== ''): ?>
      <span class="text-section__subtitle"><?= html($subtitle) ?></span>
    <?php endif ?>
  </h3>
  <?php if (trim($text) !== ''): ?>
    <div class="text-section__text"><?= $text ?></div>
  <?php endif ?>
</section>
