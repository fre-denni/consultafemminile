<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $page->title() ?> | <?= $site->title() ?></title>
  <?= css('assets/css/global.css') ?>
  <!-- Aggiungi slot per SEO + per stili e javascript personalizzato per template -->
</head>
<body>
  <header class="header">
    <a href="<?= $site->url() ?>" class="logo"><?= $site->title() ?></a>
    <nav class="navbar">
      <?php foreach($site->children()->listed() as $item): ?>
        <li><a href="<?= $item->url() ?>"><?= $item->title() ?></a></li>
      <?php endforeach ?>
    </nav>
  </header>