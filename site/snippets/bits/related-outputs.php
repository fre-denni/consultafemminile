<?php
/**
 * @var \Kirby\Cms\Page $page Pagina "output" (vedi
 *   site/blueprints/pages/output.yml): un articolo.
 *
 * Carosello "Correlati" in fondo all'articolo (vedi bits/output-carousel,
 * lo stesso della pagina di una tematica): di default gli altri output
 * della sua tematica, poi — se l'editor li ha scelti — quelli aggiunti a
 * mano dopo di essi. Spegnendo "Output della stessa tematica" restano
 * solo quelli scelti a mano.
 */
$tematica = $page->parent();
$related  = new Kirby\Cms\Pages([]);

// toBool(true): sugli articoli che non hanno mai salvato il campo vale il
// default del blueprint (acceso), non "spento".
if ($tematica && $page->correlati_auto()->toBool(true)) {
  $related = $tematica->children()->filterBy('intendedTemplate', 'output');
}

$related = $related->add($page->correlati_extra()->toPages())->not($page);
?>
<?php snippet('bits/output-carousel', [
  'items'   => $related,
  'heading' => 'Correlati',
]) ?>
