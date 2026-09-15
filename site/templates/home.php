<?php snippet('layout', slots: true) ?>

  <?php slot() ?>
    <?php snippet('bits/hero-video', [
      'video'            => $page->files()->filterBy('template', 'video')->first(),
      'headingPrimary'   => 'La Consulta Femminile',
      'headingSecondary' => 'di Milano dal 1963',
    ]) ?>
    <?php snippet('atoms/block-container', ['blocks' => $page->text()]) ?>
  <?php endslot() ?>

<?php endsnippet() ?>
