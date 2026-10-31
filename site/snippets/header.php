<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php snippet('seo/head') ?>
  <link rel="icon" href="<?= url('favicon.ico') ?>" sizes="32x32">
  <link rel="icon" href="<?= url('favicon.svg') ?>" type="image/svg+xml">
  <link rel="apple-touch-icon" href="<?= url('apple-touch-icon.png') ?>">
  <?= css('assets/css/global.css') ?>
  <?= js('assets/js/header.js', ['defer' => true]) ?>
  <?= css('@auto') ?>
  <?= js('@auto') ?>
  <link rel="stylesheet" href="<?= bundledAsset('main', [
    'snippets/atoms/*.css',
    'snippets/bits/*.css',
    'snippets/blocks/*.css',
    'snippets/*.css',
  ]) ?>">
  <style>
    <?php if ($stylesheet = $slots->stylesheet()): ?>
      <?= $stylesheet ?>
    <?php endif ?>
  </style>
  <script>
    <?php if ($scripts = $slots->scripts()): ?>
      <?= $scripts ?>
    <?php endif ?>
  </script>
</head>
<body>

  <?php
  // ————————————————————————————————————————————————————— DATI NAV

  // Voci di primo livello (menu principale): l'elenco "Pagine di navigazione"
  // del Panel (Sito → Pagine sito), nell'ordine scelto lì. La home può
  // starci per comodità del Panel ma nel menu non compare: il logo già porta
  // lì. Se l'elenco è vuoto (installazione nuova o contenuto non ancora
  // impostato) si usano le pagine "listed" con "La nostra storia" in fondo.
  $navItems = $site->navigazione()->toPages()->not('home');

  if ($navItems->isEmpty()) {
      $navItems = $site->children()->listed()->not('home');

      $timeline = $site->find('chi-siamo/timeline');
      if ($timeline) {
          $navItems = $navItems->add($timeline);
      }
  }

  //secondo livello
  $tematiche = $site->find('tematiche');
  $chiSiamoPage = $site->find('chi-siamo');

  $chiSiamo = $site->find(
    'chi-siamo/persone',
    'chi-siamo/associazioni',
    'statuto',
    'chi-siamo/contatti'
  );

  // Config dei pannelli, indicizzata per slug della pagina di primo livello.
  // Una voce senza pannello resta un semplice link.
  $navPanels = [
    'tematiche' => [
      'description' => $tematiche?->description(),
      'items'       => $tematiche?->children()->limit(4),
      'cta'         => $tematiche,
    ],
    'chi-siamo' => [
      'items' => $chiSiamo,
      'cta'   => $chiSiamoPage,
    ],
  ];
  ?>

  <header class="header">
    <?php snippet('atoms/logo', ['type' => 'esteso']) ?>

    <button
      type="button"
      class="header__menu-toggle"
      aria-expanded="false"
      aria-controls="main-nav"
    >
      <span class="header__menu-icon" aria-hidden="true"></span>
      <span class="sr-only">Apri il menu</span>
    </button>

    <?php snippet('bits/nav', [
      'items'  => $navItems,
      'panels' => $navPanels,
    ]) ?>
  </header>