<?php
/**
 * @var string      $label Testo del link (es. "Scarica la brochure (.pdf)")
 * @var string|null $href  Indirizzo del file o della pagina; senza, il
 *   componente non mostra nulla (niente link morti).
 *
 * Link centrato in maiuscolo e sottolineato, a piena larghezza come un
 * banner ma senza sfondo né bordi — vedi il riferimento Figma dello
 * statuto ("scarica la brochure").
 */
$href ??= null;

if (!$href) return;
?>
<div class="download-link block-full">
  <a class="download-link__link" href="<?= html($href) ?>" download><?= html($label) ?></a>
</div>
