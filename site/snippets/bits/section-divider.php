<?php
/**
 * @var string $heading Testo del divisore
 * @var string $style   'chapter' (default) | 'section' | 'banner'
 *
 * Slot "heading": permette di sostituire il testo con markup personalizzato
 * (es. un link) quando usato fuori dal block editor.
 */
$style ??= 'chapter';
?>
<div class="section-divider section-divider--<?= $style ?>">
  <?php if ($style === 'section'): ?>
    <?php snippet('atoms/section-label', ['text' => $slots->heading ?? $heading]) ?>
  <?php else: ?>
    <h2 class="section-divider__heading"><?= $slots->heading ?? html($heading) ?></h2>
  <?php endif ?>
</div>
