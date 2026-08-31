<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="<?= $site->description() ?>">
  <meta name="keywords" content="<?= $site->keywords() ?>">
  <title><?= $page->title() ?> | <?= $site->title() ?></title>
  <?= css('assets/css/global.css') ?>
  <?= css('@auto') ?>   
  <?= js('@auto') ?>
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
  <header class="header">
    <a href="<?= $site->url() ?>" class="logo"><?= $site->title() ?></a>
    <nav class="navbar">
      <?php foreach($site->children()->listed() as $item): ?>
        <li><a href="<?= $item->url() ?>"><?= $item->title() ?></a></li>
      <?php endforeach ?>
      <!-- Add search -->
    </nav>
  </header>

<!-- andrebbe un if/else con versione mobile/desktop se non riesco a farlo da me -->