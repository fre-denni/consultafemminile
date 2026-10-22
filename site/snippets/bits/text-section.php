<?php
/**
 * @var string $title    Prima riga del titolo (es. "Art.1")
 * @var string $subtitle Seconda riga, opzionale (es. "Denominazione sede")
 * @var string $text     HTML già pronto del corpo (campo writer: paragrafi, elenchi, grassetti)
 *
 * Colonna di lettura stretta e centrata, testo allineato a sinistra: è la
 * forma di uno statuto o di un regolamento, articolo dopo articolo — vedi
 * text-section.css per il ritmo verticale tra gli articoli.
 */
$subtitle ??= '';
$text     ??= '';
?>
<section class="text-section">
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
