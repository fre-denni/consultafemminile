<?php
/**
 * @var string                      $label   Etichetta breve sopra il titolo (opzionale)
 * @var \Kirby\Content\Field|string $heading Titolo (HTML inline, es. da un campo writer)
 * @var \Kirby\Content\Field|string $text    Corpo del testo (HTML, es. da un campo writer)
 *
 * Slot "heading" e "text": permettono di sostituire titolo/corpo con markup
 * personalizzato quando il componente è usato fuori dal block editor.
 */
$label ??= null;

// Un Field Kirby è sempre "truthy" come oggetto anche se vuoto: va
// controllato con isNotEmpty(), non con un semplice if/??.
$isEmpty = fn ($value) => $value instanceof \Kirby\Content\Field
  ? $value->isEmpty()
  : empty($value);

$headingSlot = $slots->heading ?? null;
$hasHeading  = $headingSlot !== null || !$isEmpty($heading);

$textSlot = $slots->text ?? null;
$hasText  = $textSlot !== null || !$isEmpty($text);
?>
<div class="paragraph">
  <?php if ($label): ?>
    <div class="paragraph__label">
      <?php snippet('atoms/section-label', ['text' => $label]) ?>
    </div>
  <?php endif ?>
  <div class="paragraph__body">
    <?php if ($hasHeading): ?>
      <h3 class="paragraph__heading"><?= $headingSlot ?? $heading ?></h3>
    <?php endif ?>
    <?php if ($hasText): ?>
      <div class="paragraph__text"><?= $textSlot ?? $text ?></div>
    <?php endif ?>
  </div>
</div>
