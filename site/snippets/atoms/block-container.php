<?php
/**
 * @var \Kirby\Content\Field $blocks Campo Kirby di tipo "blocks" da renderizzare
 *   (es. $page->text()). Applica il ritmo verticale tra i blocchi tramite
 *   la classe .block-container — vedi block-container.css.
 */
?>
<div class="block-container">
  <?= $blocks->toBlocks() ?>
</div>
