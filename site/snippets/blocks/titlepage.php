<?php
/** @var \Kirby\Cms\Block $block */
?>
<?php snippet('bits/title-page', [
  'heading'      => $block->heading(),
  'text'         => $block->text(),
  'borderBottom' => $block->borderBottom()->toBool(),
]) ?>
