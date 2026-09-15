<?php snippet('layout', slots: true) ?>

  <?php slot() ?>
    <?php snippet('bits/title-page', ['heading' => $site->payoff()]) ?>
    <?php snippet('atoms/block-container', ['blocks' => $page->text()]) ?>
  <?php endslot() ?>

<?php endsnippet() ?>