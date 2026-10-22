<?php
/** @var \Kirby\Cms\Block $block */

// Il campo "text" è un writer: salva già HTML con uno o più <p> (e titoli,
// elenchi...), da stampare così com'è — vedi .paragraph__text > * + * in
// paragraph.css per la spaziatura tra i blocchi di testo.
$text = trim((string) $block->text()->value());
?>
<?php snippet('bits/paragraph', [
  'heading'    => $block->heading()->escape(),
  'paragraphs' => $text !== '' ? [$text] : [],
]) ?>
