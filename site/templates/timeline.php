<?php
/**
 * I capitoli ("Capitoli" nel Panel, vedi site/blueprints/pages/timeline.yml)
 * non sono ancora stati compilati — l'accordion (vedi bits/timeline-accordion)
 * semplicemente non mostra nulla finché non ce n'è almeno uno.
 */
$capitoli = [];
foreach (collection('timeline') as $capitolo) {
  $capitoli[] = [
    'id'          => $capitolo->id(),
    'anno'        => trim((string) $capitolo->anno()),
    'etichetta'   => trim((string) $capitolo->etichetta()),
    'description' => trim((string) $capitolo->descrizione()),
    'images'      => $capitolo->immagini()->toFiles()->values(),
  ];
}
?>
<?php snippet('layout', slots: true) ?>

  <?php slot() ?>
    <?php snippet('bits/title-page', [
      'heading' => $page->payoff()->or($site->payoff()),
      'text'    => $page->mission(),
    ]) ?>
    <?php snippet('bits/timeline-accordion', ['items' => $capitoli]) ?>
  <?php endslot() ?>

<?php endsnippet() ?>
