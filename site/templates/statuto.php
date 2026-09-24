<?php snippet('layout', slots: true) ?>

  <?php slot() ?>
    <?php snippet('bits/title-page', [
      'heading' => $site->payoff(),
      'borderBottom' => true,
    ]) ?>
    <?php snippet('atoms/block-container', ['blocks' => $page->text()]) ?>
    <?php snippet('bits/contact-form', [
      'label'      => $page->etichetta()->or('Contattaci')->value(),
      'image'      => $page->foto()->toFile(),
      'credit'     => $page->credit()->value(),
      'background' => 'tinted',
    ]) ?>
  <?php endslot() ?>

<?php endsnippet() ?>
