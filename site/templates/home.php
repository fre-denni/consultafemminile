<?php
/**
 * Le due "descrizioni" e il titolo sopra il carosello Tematiche sono tre
 * voci fisse di questa pagina (non un campo "blocks" aperto come nelle
 * altre — vedi home.yml): qui ricostruiamo a mano lo stesso identico
 * contenitore/comportamento di atoms/block-container.php (spaziatura
 * verticale tra i blocchi, sezione a piena larghezza con sfondo colorato
 * quando non è quello di default), visto che qui non serve un editor di
 * blocchi libero.
 */
$paragraphRows = function ($field) {
  $rows = [];
  foreach ($field->toStructure() as $row) {
    $rows[] = $row->text()->kirbytext()->value();
  }
  return $rows;
};

$wrapBackground = function (string $html, $background): string {
  $background = (string) $background;
  if ($background === '' || $background === 'default') {
    return $html;
  }
  return '<div class="block-container__section block-container__section--' . html($background) . '">' . $html . '</div>';
};
?>
<?php snippet('layout', slots: true) ?>

  <?php slot() ?>
    <?php snippet('bits/hero-video', [
      'video'            => $page->files()->filterBy('template', 'video')->first(),
      'headingPrimary'   => 'La Consulta Femminile',
      'headingSecondary' => 'di Milano dal 1963',
    ]) ?>
    <div class="block-container">
      <?= $wrapBackground(snippet('bits/paragraph', [
        'label'      => $page->descrizioneUnoLabel()->value(),
        'heading'    => $page->descrizioneUnoHeading(),
        'paragraphs' => $paragraphRows($page->descrizioneUnoTesto()),
      ], return: true), $page->descrizioneUnoBackground()) ?>
      <?= $wrapBackground(snippet('bits/paragraph', [
        'label'      => $page->descrizioneDueLabel()->value(),
        'heading'    => $page->descrizioneDueHeading(),
        'paragraphs' => $paragraphRows($page->descrizioneDueTesto()),
      ], return: true), $page->descrizioneDueBackground()) ?>
      <?php if ($page->tematicheHeading()->isNotEmpty()): ?>
        <?= $wrapBackground(snippet('bits/section-divider', [
          'heading' => $page->tematicheHeading()->value(),
          'style'   => 'chapter',
        ], return: true), $page->tematicheBackground()->or('tinted')->value()) ?>
      <?php endif ?>
    </div>
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
        'text' => 'Scopri tutte le delegate ›',
      ],
    ]) ?>
    <?php snippet('bits/logo-showreel', [
      'items'      => collection('associazioni'),
      'heading'    => 'Le associazioni della consulta',
      'background' => $page->associazioniBackground()->or('tinted')->value(),
    ]) ?>
  <?php endslot() ?>

<?php endsnippet() ?>
