<?php snippet('layout', slots: true) ?>

  <?php slot() ?>
    <?php snippet('bits/title-page', [
      'heading' => $page->payoff()->or($site->payoff()),
      'text'    => $page->mission(),
    ]) ?>
    <?php snippet('atoms/block-container', ['blocks' => $page->text()]) ?>
  <?php endslot() ?>

<?php endsnippet() ?>