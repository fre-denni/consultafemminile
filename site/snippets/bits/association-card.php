<?php
/**
 * @var \Kirby\Cms\StructureObject $association Riga del campo structure
 *   "associazioni" (vedi site/blueprints/pages/associazioni.yml): ->logo()->toFile(),
 *   ->nome_breve(), ->nome_completo().
 *
 * Logo, nome breve/completo e un bottone che apre la modale con i dettagli
 * (vedi bits/association-modal — stesso id, costruito da $association->id(),
 * a collegare i due componenti).
 */
$logo = $association->logo()->toFile();
$modalId = 'association-' . $association->id();
?>
<article class="association-card">
  <div class="association-card__logo">
    <?php if ($logo): ?>
      <?php snippet('atoms/picture', [
        'image'     => $logo,
        'alt'       => '',
        'sizes'     => [160, 240],
        'sizesAttr' => '160px',
        'class'     => 'association-card__logo-img',
      ]) ?>
    <?php endif ?>
  </div>
  <div class="association-card__content">
    <p class="association-card__name"><?= html($association->nome_breve()) ?></p>
    <p class="association-card__full-name"><?= html($association->nome_completo()) ?></p>
  </div>
  <button
    type="button"
    class="association-card__cta"
    data-modal-open
    data-modal-target="<?= $modalId ?>"
    aria-haspopup="dialog"
  >
    Scopri di più
  </button>
</article>
