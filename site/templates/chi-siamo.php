<?php
/**
 * La sezione timeline qui sotto riusa la stessa pagina "La nostra
 * storia" (introduzione + capitoli) di site/templates/timeline.php —
 * vedi bits/timeline-section: contenuto vive in un solo posto.
 */
$timelinePage = $site->find('chi-siamo/timeline');

$capitoliStoria = [];
foreach (collection('timeline') as $capitolo) {
  $capitoliStoria[] = [
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
      'heading' => $site->payoff(),
      'text'    => $page->mission(),
      'borderBottom' => true,
    ]) ?>
    <?php snippet('atoms/block-container', ['blocks' => $page->text()]) ?>
    <?php snippet('bits/timeline-section', [
      'intro' => $timelinePage?->introduzione(),
      'items' => $capitoliStoria,
    ]) ?>
    <?php snippet('bits/people-carousel', [
      'items'      => collection('consigliere'),
      'label'      => 'Consiglio 2026-2027',
      'background' => 'tinted',
      'cta'        => [
        'href' => $site->find('chi-siamo/persone')->url(),
        'text' => 'Scopri tutte le delegate ›',
      ],
    ]) ?>
    <?php snippet('bits/associazioni-grid', [
      'items' => collection('associazioni'),
      'label' => 'Le associazioni',
    ]) ?>
    <?php snippet('bits/contact-form', [
      'label'      => $page->etichetta()->or('Contattaci')->value(),
      'image'      => $page->foto()->toFile(),
      'credit'     => $page->credit()->value(),
      'background' => 'tinted',
    ]) ?>
  <?php endslot() ?>

<?php endsnippet() ?>
