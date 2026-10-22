<?php
/** @var \Kirby\Cms\Block $block */
?>
<?php snippet('bits/contact-form', [
  // Etichetta, foto e credito sono globali: li legge il componente dalla
  // pagina Contatti (vedi bits/contact-form.php), qui non si ripetono.
  // Ancora univoca: la pagina può avere più form.
  'id' => 'contatti-' . $block->id(),
]) ?>
