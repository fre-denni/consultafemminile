<?php
/** @var \Kirby\Cms\Block $block */

// kirbytext() (non ->value()) così una riga vuota dentro un singolo
// paragrafo del textarea produce comunque due <p> distinti e spaziati
// (vedi .paragraph__text > * + * in paragraph.css), non un unico blocco
// di testo con gli a-capo appiattiti dal browser.
$paragraphs = [];
foreach ($block->paragraphs()->toStructure() as $row) {
  $paragraphs[] = $row->text()->kirbytext()->value();
}
?>
<?php snippet('bits/paragraph', [
  'label'      => $block->label()->value(),
  'heading'    => $block->heading(),
  'paragraphs' => $paragraphs,
]) ?>
