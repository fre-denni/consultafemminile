<?php snippet('layout', slots: true) ?>

  <?php slot() ?>
    <?php snippet('bits/article-header') ?>
    <?php snippet('atoms/block-container', ['blocks' => $page->text()]) ?>
    <?php snippet('bits/related-outputs') ?>
    <?php snippet('bits/article-footer') ?>
  <?php endslot() ?>

<?php endsnippet() ?>
