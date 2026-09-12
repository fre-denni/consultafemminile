<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="<?= $site->description() ?>">
  <meta name="keywords" content="<?= $site->keywords() ?>">
  <title><?= $page->title() ?> | <?= $site->title() ?></title>
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

  // Voci di primo livello (menu principale)
  $navItems = $site->children()->listed();

  // 1. Peschiamo la timeline (che fisicamente è una sottopagina) 
  // e l'aggiungiamo alla collection del menu principale
  $timeline = $site->find('chi-siamo/timeline');
  if ($timeline) {
      $navItems = $navItems->add($timeline);
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
    <a href="<?= $site->url() ?>" class="header__logo"><?= $site->title() ?></a>

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