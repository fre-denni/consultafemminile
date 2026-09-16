<?php
/**
 * @var \Kirby\Content\Field $blocks Campo Kirby di tipo "blocks" da renderizzare
 *   (es. $page->text()).
 *
 * Qualunque blocco può opzionalmente esporre un campo "background" nel
 * proprio blueprint (valore diverso da "default", es. "tinted"): se
 * presente, il blocco viene avvolto in una sezione a piena larghezza con
 * sfondo colorato — vedi .block-container__section in block-container.css.
 * Non serve altro codice qui per farlo funzionare su un nuovo blocco.
 */
?>
<div class="block-container">
  <?php foreach ($blocks->toBlocks() as $block): ?>
    <?php $background = $block->background()->value(); ?>
    <?php if ($background && $background !== 'default'): ?>
      <div class="block-container__section block-container__section--<?= html($background) ?>">
        <?= $block->toHtml() ?>
      </div>
    <?php else: ?>
      <?= $block->toHtml() ?>
    <?php endif ?>
  <?php endforeach ?>
</div>
