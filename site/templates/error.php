<?php snippet('layout', slots: true) ?>

  <?php slot() ?>
    <?php snippet('bits/title-page', [
      'heading' => $page->payoff()->or($site->payoff()),
      'text'    => $page->mission(),
      'borderBottom' => true,
    ]) ?>
    <?php snippet('bits/contact-form', [
      'background' => 'tinted',
    ]) ?>
  <?php endslot() ?>

<?php endsnippet() ?>