<?php snippet('layout', slots: true) ?>

  <?php slot() ?>
    <?php snippet('bits/hero-video', [
      'video'            => $page->files()->filterBy('template', 'video')->first(),
      'headingPrimary'   => 'La Consulta Femminile',
      'headingSecondary' => 'di Milano dal 1963',
    ]) ?>
    <?php snippet('atoms/block-container', ['blocks' => $page->text()]) ?>
    <?php snippet('bits/theme-carousel', [
      'items'      => collection('tematiche'),
      'background' => $page->tematicheBackground()->or('tinted')->value(),
    ]) ?>
    <?php snippet('bits/patrocinio-carousel', [
      'items'      => collection('patrocinio'),
      'heading'    => 'Con il patrocinio della Consulta',
      'background' => $page->patrocinioBackground()->or('tinted')->value(),
    ]) ?>
    <?php snippet('bits/people-carousel', [
      'items'      => collection('consigliere'),
      'label'      => 'Consiglio 2026-2027',
      'background' => $page->personeBackground()->or('default')->value(),
      'cta'        => [
        'href' => $site->find('chi-siamo/persone')->url(),
        'text' => 'Scopri tutte le persone ›',
      ],
    ]) ?>
    <?php snippet('bits/logo-showreel', [
      'items'      => collection('associazioni'),
      'heading'    => 'Le associazioni della consulta',
      'background' => $page->associazioniBackground()->or('tinted')->value(),
    ]) ?>
  <?php endslot() ?>

<?php endsnippet() ?>
