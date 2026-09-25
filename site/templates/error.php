<?php snippet('layout', slots: true) ?>

  <?php slot() ?>
    <?php snippet('bits/title-page', [
      'heading' => $page->payoff()->or($site->payoff()),
      'text'    => $page->mission(),
      'borderBottom' => true,
    ]) ?>
    <?php snippet('bits/contact-form', [
      'label'      => $page->etichetta()->or('Contattaci')->value(),
      'image'      => $page->foto()->toFile(),
      'credit'     => $page->credit()->value(),
      'background' => 'tinted',
    ]) ?>
  <?php endslot() ?>

<?php endsnippet() ?>