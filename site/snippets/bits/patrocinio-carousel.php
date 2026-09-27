<?php
/**
 * @var iterable $items   Locandine (file immagine, vedi
 *   site/collections/patrocinio.php) — nessun dato oltre l'immagine
 *   per ora, il modello dei progetti patrocinati non è ancora definito.
 * @var string   $heading    Etichetta nell'header sopra il carosello
 *   (opzionale) — stile "sezione" (vedi bits/section-divider), una
 *   riga sola con le frecce di navigazione.
 * @var string   $background Sfondo della sezione: 'default' (bianco), 'tinted' (panna) o
 *   'blue' (azzurro) — stesso vocabolario del campo condiviso blueprints/fields/background.yml.
 *   Facoltativo, di default 'default'.
 *
 * Adattatore sottile su bits/carousel (meccanica di scorrimento
 * condivisa con bits/output-carousel): qui vive solo ciò che è
 * specifico delle locandine — l'elemento (bits/patrocinio-poster) e il
 * lightbox in cui si aprono al click.
 */
$heading    ??= '';
$background ??= 'default';

snippet('bits/carousel', [
  'items'      => $items,
  'item'       => fn ($image) => snippet('bits/patrocinio-poster', ['image' => $image], return: true),
  'heading'    => $heading,
  'background' => $background,
  'variant'    => 'posters',
  'prevLabel'  => 'Locandina precedente',
  'nextLabel'  => 'Locandina successiva',
  'after'      => fn () => snippet('atoms/lightbox', [], return: true),
]) ?>
