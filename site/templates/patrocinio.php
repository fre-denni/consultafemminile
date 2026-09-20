<?php snippet('layout', slots: true) ?>

  <?php slot() ?>
    <?php snippet('bits/title-page', [
      'heading' => $page->payoff(),
      'text'    => $page->mission(),
    ]) ?>
    <?php snippet('bits/patrocinio-carousel', [
      'items'   => collection('patrocinio'),
      'heading' => 'Progetti patrocinati dalla consulta',
    ]) ?>
  <?php endslot() ?>

<?php endsnippet() ?>
