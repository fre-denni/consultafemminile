<?php
/**
 * @var \Kirby\Content\Field|string      $heading Titolo (HTML inline, es. da un campo writer)
 * @var \Kirby\Content\Field|string|null $text    Testo esplicativo opzionale sotto il titolo
 *
 * Header di apertura pagina: titolo grande + separatore, sempre presente.
 *
 * Slot "text": permette di sostituire il testo esplicativo con markup
 * personalizzato quando il componente è usato fuori dal block editor,
 * es.:
 *   <?php snippet('bits/title-page', ['heading' => '...'], slots: true) ?>
 *     <?php slot('text') ?><p>Testo su misura</p><?php endslot() ?>
 *   <?php endsnippet() ?>
 */
$text ??= null;

// Un Field Kirby è sempre "truthy" come oggetto anche se vuoto: va
// controllato con isNotEmpty(), non con un semplice if/??.
$textSlot = $slots->text ?? null;
$hasText  = $textSlot !== null
  || ($text instanceof \Kirby\Content\Field ? $text->isNotEmpty() : !empty($text));
?>
<header class="title-page block-full">
  <p class="title-page__heading"><?= $heading ?></p>
  <?php if ($hasText): ?>
    <div class="title-page__text"><?= $textSlot ?? $text ?></div>
  <?php endif ?>
</header>
