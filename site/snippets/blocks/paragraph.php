<?php
/** @var \Kirby\Cms\Block $block */

$paragraphs = [];
foreach ($block->paragraphs()->toStructure() as $row) {
  $paragraphs[] = $row->text()->value();
}
?>
<?php snippet('bits/paragraph', [
  'label'      => $block->label()->value(),
  'heading'    => $block->heading(),
  'paragraphs' => $paragraphs,
]) ?>
