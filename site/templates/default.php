<?php snippet('layout', slots: true) ?>

  <?php slot() ?>
    <h1><?= $site->payoff() ?></h1>
  <?php endslot() ?>

<?php endsnippet() ?>