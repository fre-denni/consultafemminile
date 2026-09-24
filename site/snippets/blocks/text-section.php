<?php
/** @var \Kirby\Cms\Block $block */
?>
<?php snippet('bits/text-section', [
  'title'    => $block->title()->value(),
  'subtitle' => $block->subtitle()->value(),
  'text'     => $block->text()->value(),
  'style'    => $block->style()->or('article')->value(),
]) ?>
