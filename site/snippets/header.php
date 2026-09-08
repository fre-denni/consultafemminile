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

  //secondo livello
  $tematiche = $site->find('tematiche');
  $chiSiamo = $site->find(
    'chi-siamo/persone',
    'chi-siamo/associazioni',
    'chi-siamo/timeline',
    // aggiungi contatti
    'statuto',
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