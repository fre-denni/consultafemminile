<?php
$capitoli = [];
foreach (collection('tematiche') as $tema) {
  $capitoli[] = [
    'id'          => $tema->slug(),
    'title'       => $tema->title()->value(),
    'subtitle'    => trim((string) $tema->subtitle()),
    'description' => trim((string) $tema->description()),
    'image'       => $tema->file('cover.webp'),
    'ctaUrl'      => $tema->url(),
  ];
}
?>
<?php snippet('layout', slots: true) ?>

  <?php slot() ?>
    <?php snippet('bits/title-page', [
      'heading' => $page->payoff()->or($site->payoff()),
      'text'    => $page->mission(),
    ]) ?>
    <?php snippet('bits/chapter-accordion', ['items' => $capitoli]) ?>
  <?php endslot() ?>

<?php endsnippet() ?>
