<?php snippet('layout', slots: true) ?>

  <?php slot() ?>
    <?php snippet('bits/title-page', [
      'heading' => $page->payoff(),
      'text'    => $page->mission(),
    ]) ?>
    <?php snippet('bits/associazioni-grid', [
      'items' => collection('associazioni'),
      'label' => 'Le associazioni',
    ]) ?>
  <?php endslot() ?>

<?php endsnippet() ?>
