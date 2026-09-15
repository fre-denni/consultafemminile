<?php
/** @var \Kirby\Cms\Block $block */
?>
<?php snippet('bits/section-divider', [
  'heading' => $block->heading()->value(),
  'style'   => $block->style()->or('chapter')->value(),
]) ?>
