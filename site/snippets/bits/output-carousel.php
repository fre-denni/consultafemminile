<?php
/**
 * @var \Kirby\Cms\Pages $items      Sottopagine "output" da mostrare
 *   (vedi bits/output-card e site/blueprints/pages/output.yml) — già
 *   filtrate dal chiamante: site/templates/tematica.php (solo gli
 *   output di quella tematica) o site/templates/tematiche.php /
 *   site/collections/outputs.php (gli ultimi output di tutte le
 *   tematiche insieme).
 * @var string            $heading    Etichetta nell'header sopra il carosello (opzionale).
 * @var string            $background Sfondo della sezione: 'default' (bianco), 'tinted' (panna) o
 *   'blue' (azzurro) — stesso vocabolario di blueprints/fields/background.yml.
 *   Ignorato quando $nested è true (vedi sotto).
 * @var bool               $nested Di default false: sezione a piena larghezza con gutter e bordo
 *   sopra (vedi site/templates/tematica.php). true quando incorporato dentro un altro
 *   componente che già gestisce il proprio contenitore (vedi bits/chapter-accordion,
 *   dentro .chapter-accordion__body-inner) — niente piena larghezza, gutter o bordo,
 *   il carosello resta semplicemente largo quanto il suo contenitore.
 *
 * Adattatore sottile su bits/carousel (meccanica di scorrimento
 * condivisa con bits/patrocinio-carousel): qui vive solo ciò che è
 * specifico degli output — il filtro su chi ha una copertina e la card
 * da usare per ogni elemento.
 */
$items = $items->filter(fn ($item) => $item->images()->first() !== null);

$heading    ??= '';
$background ??= 'default';
$nested     ??= false;

snippet('bits/carousel', [
  'items'      => $items,
  'item'       => fn ($item) => snippet('bits/output-card', ['item' => $item], return: true),
  'heading'    => $heading,
  'background' => $background,
  'nested'     => $nested,
  'variant'    => 'cards',
  'prevLabel'  => 'Articolo precedente',
  'nextLabel'  => 'Articolo successivo',
]) ?>
