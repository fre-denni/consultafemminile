<?php snippet('layout', slots: true) ?>

  <?php slot() ?>
      <?php snippet('bits/title-page', [
      'heading' => $site->payoff(),
      'text'    => $page->mission(),
      'borderBottom' => true,
    ]) ?>
    <?php snippet('atoms/block-container', ['blocks' => $page->text()]) ?>
    <?php snippet('bits/people-carousel', [
      'items' => collection('consigliere'),
      'label' => 'Consiglio 2026-2027',
      'background' => 'tinted',
    ]) ?>
    <?php snippet('bits/people-carousel', [
      'items' => collection('delegate'),
      'label' => 'Le delegate delle associazioni',
      'background' => 'tinted',
    ]) ?>
    <?php snippet('bits/associazioni-grid', [
      'items' => collection('associazioni'),
      'label' => 'Le associazioni',
    ]) ?>
  <?php endslot() ?>

<?php endsnippet() ?>
