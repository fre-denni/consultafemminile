<?php
/** @var \Kirby\Cms\Block $block */

// Blocco "Testo" (blueprints/blocks/text.yml): stesso involucro del blocco
// "Paragrafo con titolo" (blocks/paragraph.php), solo senza titolo — così i
// due hanno identici margini, padding e larghezza di lettura, e il testo
// resta allineato anche quando si alternano. Sostituisce lo snippet di
// default di Kirby, che stampava l'HTML nudo nella colonna del
// block-container (vedi .paragraph in bits/paragraph.css).
// Il campo "text" è un writer: salva già HTML con uno o più <p> (e titoli,
// elenchi...), da stampare così com'è.
$text = trim((string) $block->text()->value());
?>
<?php snippet('bits/paragraph', [
  'paragraphs' => $text !== '' ? [$text] : [],
]) ?>
