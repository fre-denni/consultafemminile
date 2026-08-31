<?php snippet('header') ?>
  <?php snippet('main', slots: true) ?>
    <?php slot() ?>
    <h1><?= $site->Payoff() ?></h1>
    <?php endslot() ?>
  <?php endsnippet() ?>
<?php snippet('footer') ?>