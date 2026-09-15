<?php
/** @var \Kirby\Cms\Block $block */
?>
<?php snippet('bits/paragraph', [
  'label'   => $block->label()->value(),
  'heading' => $block->heading(),
  'text'    => $block->text(),
]) ?>
