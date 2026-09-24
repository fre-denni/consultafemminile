<?php
/**
 * @var string                      $label      Etichetta breve sopra il titolo (opzionale)
 * @var \Kirby\Content\Field|string $heading    Titolo (HTML inline, es. da un campo writer)
 * @var iterable<string>            $paragraphs Elenco di HTML già pronto (kirbytext), uno o più <p> ciascuno
 *
 * A piena larghezza (così un eventuale sfondo colorato applicato da
 * atoms/block-container.php arriva ai bordi) con dentro una griglia a
 * larghezza di lettura, centrata — vedi .paragraph__grid in paragraph.css.
 *
 * Slot "heading" e "text": permettono di sostituire titolo/corpo con markup
 * personalizzato quando il componente è usato fuori dal block editor, es.:
 *   <?php snippet('bits/paragraph', ['label' => '...'], slots: true) ?>
 *     <?php slot('text') ?><p>Testo su misura</p><?php endslot() ?>
 *   <?php endsnippet() ?>
 */
$label      ??= null;
$heading    ??= null;
$paragraphs ??= [];

// Un Field Kirby è sempre "truthy" come oggetto anche se vuoto: va
// controllato con isNotEmpty(), non con un semplice if/??.
$isEmpty = fn ($value) => $value instanceof \Kirby\Content\Field
  ? $value->isEmpty()
  : empty($value);

$headingSlot = $slots->heading ?? null;
$hasHeading  = $headingSlot !== null || !$isEmpty($heading);

$textSlot      = $slots->text ?? null;
$hasParagraphs = $textSlot !== null || count($paragraphs) > 0;
?>
<div class="paragraph block-full">
  <div class="paragraph__grid<?= !$label ? ' paragraph__grid--centered' : '' ?>">
    <?php if ($label): ?>
      <div class="paragraph__label">
        <?php snippet('atoms/section-label', ['text' => $label]) ?>
      </div>
    <?php endif ?>
    <div class="paragraph__body">
      <?php if ($hasHeading): ?>
        <h3 class="paragraph__heading"><?= $headingSlot ?? $heading ?></h3>
      <?php endif ?>
      <?php if ($hasParagraphs): ?>
        <div class="paragraph__text">
          <?php if ($textSlot !== null): ?>
            <?= $textSlot ?>
          <?php else: ?>
            <?php foreach ($paragraphs as $paragraph): ?>
              <?= $paragraph ?>
            <?php endforeach ?>
          <?php endif ?>
        </div>
      <?php endif ?>
    </div>
  </div>
</div>
