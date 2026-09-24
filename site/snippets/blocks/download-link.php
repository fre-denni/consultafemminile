<?php
/** @var \Kirby\Cms\Block $block */
?>
<?php snippet('bits/download-link', [
  'label' => $block->label()->value(),
  'href'  => $block->link()->toUrl(),
]) ?>
