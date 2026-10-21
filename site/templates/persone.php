<?php snippet('layout', slots: true) ?>

  <?php slot() ?>
    <?php snippet('bits/title-page', [
      'heading' => $site->payoff(),
      'borderBottom' => true,
    ]) ?>
    <?php snippet('bits/people-carousel', [
      'items' => collection('consigliere'),
      'label' => 'Consiglio 2026-2027',
      'background' => 'tinted',
    ]) ?>
    <?php snippet('bits/people-carousel', [
      'items' => collection('delegate'),
      'label' => 'Le delegate delle associazioni',
    ]) ?>
    <?php snippet('bits/contact-form', [
      'label'      => $page->etichetta()->or('Contattaci')->value(),
      'image'      => $page->foto()->toFile(),
      'alt'        => $page->fotoAlt()->value(),
      'credit'     => $page->credit()->value(),
      'background' => 'tinted',
    ]) ?>
  <?php endslot() ?>

<?php endsnippet() ?>
