<?php snippet('layout', slots: true) ?>

  <?php slot() ?>
    <?php snippet('bits/title-page', [
      'heading' => $site->payoff(),
      'borderBottom' => true,
    ]) ?>
    <?php snippet('bits/associazioni-grid', [
      'items' => collection('associazioni'),
      'label' => 'Le associazioni',
    ]) ?>
    <?php snippet('bits/contact-form', [
      'label'      => $page->etichetta()->or('Contattaci')->value(),
      'image'      => $page->foto()->toFile(),
      'credit'     => $page->credit()->value(),
      'background' => 'tinted',
    ]) ?>
  <?php endslot() ?>

<?php endsnippet() ?>
