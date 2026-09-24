<?php
/** @var \Kirby\Cms\Block $block */
?>
<?php snippet('bits/contact-form', [
  'label'  => $block->label()->or('Contattaci')->value(),
  'image'  => $block->image()->toFile(),
  'credit' => $block->credit()->value(),
  // Ancora univoca: la pagina può avere più form.
  'id'     => 'contatti-' . $block->id(),
]) ?>
