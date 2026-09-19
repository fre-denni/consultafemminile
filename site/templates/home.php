<?php snippet('layout', slots: true) ?>

  <?php slot() ?>
    <?php snippet('bits/hero-video', [
      'video'            => $page->files()->filterBy('template', 'video')->first(),
      'headingPrimary'   => 'La Consulta Femminile',
      'headingSecondary' => 'di Milano dal 1963',
    ]) ?>
    <?php snippet('atoms/block-container', ['blocks' => $page->text()]) ?>
    <div class="block-container__section block-container__section--tinted">
      <?php snippet('bits/theme-carousel', ['items' => collection('tematiche')]) ?>
      <?php //inserisci qui il carosello patrocini ?>
    </div>
    <?php snippet('bits/people-carousel', [
      'items' => collection('consigliere'),
      'label' => 'Consiglio 2026-2027',
    ]) ?>
    <?php //inserisci qui i loghi della consulta ?>
  <?php endslot() ?>

<?php endsnippet() ?>
