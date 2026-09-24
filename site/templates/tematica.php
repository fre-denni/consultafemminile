<?php
$outputs = $page->children()->filterBy('intendedTemplate', 'output');
?>
<?php snippet('layout', slots: true) ?>

  <?php slot() ?>
    <?php snippet('bits/tema-hero', [
      'image'   => $page->images()->first(),
      'heading' => $page->title(),
    ]) ?>
    <?php snippet('bits/paragraph', ['paragraphs' => [(string) $page->description()]]) ?>
    <?php snippet('bits/output-carousel', [
      'items'   => $outputs,
      'heading' => 'Output',
    ]) ?>
  <?php endslot() ?>

<?php endsnippet() ?>
