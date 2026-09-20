<?php
/**
 * @var iterable $items      Associazioni (righe structure, vedi
 *   bits/association-card e bits/association-modal) da mostrare.
 * @var string   $label      Etichetta piccola sopra la griglia (es. "Le
 *   associazioni"), allineata a sinistra come nel riferimento Figma —
 *   a differenza delle altre sezioni della home, sempre centrate qui è
 *   voluto: la griglia stessa parte dallo stesso margine. Facoltativa.
 * @var string   $background Sfondo della sezione: 'default' (bianco) o 'tinted' (panna) —
 *   stesso vocabolario del campo condiviso blueprints/fields/background.yml. Facoltativo,
 *   di default 'default'.
 *
 * Una modale per associazione (vedi bits/association-modal) è resa qui
 * accanto alla griglia, non dentro ogni card: sono <dialog> nativi,
 * fuori dal flusso finché non vengono aperti — dove stanno nel markup
 * non ha effetto sul risultato visivo.
 */
$items = $items->filter(fn ($assoc) => $assoc->nome_breve()->isNotEmpty() || $assoc->nome_completo()->isNotEmpty());

if ($items->count() === 0) return;

$label      ??= '';
$background ??= 'default';
?>
<section class="associazioni-grid-section block-full<?= $background === 'tinted' ? ' associazioni-grid-section--tinted' : '' ?>">
  <?php if ($label !== ''): ?>
    <div class="associazioni-grid-section__header">
      <?php snippet('atoms/section-label', ['text' => $label]) ?>
    </div>
  <?php endif ?>
  <ul class="associazioni-grid">
    <?php foreach ($items as $assoc): ?>
      <li class="associazioni-grid__item">
        <?php snippet('bits/association-card', ['association' => $assoc]) ?>
      </li>
    <?php endforeach ?>
  </ul>
  <?php foreach ($items as $assoc): ?>
    <?php snippet('bits/association-modal', ['association' => $assoc]) ?>
  <?php endforeach ?>
</section>
<?= js('assets/js/association-modal.js', ['defer' => true]) ?>
