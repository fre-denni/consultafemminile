<?php
/**
 * @var \Kirby\Cms\Pages $items  Voci di primo livello (site->children()->listed())
 * @var array             $panels Config pannelli espandibili, indicizzata per slug pagina
 */
?>
<nav class="navbar" id="main-nav" data-open="false">
  <ul class="navbar__list">

    <?php foreach ($items as $item): ?>
      <?php $panel = $panels[$item->slug()] ?? null ?>

      <li class="navbar__item<?= $panel ? ' navbar__item--expandable' : '' ?>">

        <a
          href="<?= $item->url() ?>"
          class="navbar__link"
          <?= $item->isOpen() ? 'aria-current="page"' : '' ?>
        ><?= html($item->title()) ?></a>

        <?php if ($panel): ?>

          <button
            type="button"
            class="navbar__toggle"
            aria-expanded="false"
            aria-controls="navbar-panel-<?= $item->slug() ?>"
          >
            <span class="sr-only">Mostra <?= html($item->title()) ?></span>
          </button>

          <div
            id="navbar-panel-<?= $item->slug() ?>"
            class="navbar-panel"
            data-open="false"
          >
            <?php if (($panel['description'] ?? null)?->isNotEmpty()): ?>
              <p class="navbar-panel__description"><?= $panel['description'] ?></p>
            <?php endif ?>

            <?php if (($panel['items'] ?? null)?->isNotEmpty()): ?>
              <ul class="navbar-panel__gallery">
                <?php foreach ($panel['items'] as $sub): ?>
                  <li>
                    <a href="<?= $sub->url() ?>" class="navbar-panel__card">
                      <?php if ($image = $sub->file('heroshot.png')): ?>
                        <img src="<?= $image->url() ?>" alt="" loading="lazy">
                      <?php endif ?>
                      <span><?= html($sub->title()) ?></span>
                    </a>
                  </li>
                <?php endforeach ?>
              </ul>
            <?php endif ?>

            <?php if ($panel['cta'] ?? null): ?>
              <a href="<?= $panel['cta']->url() ?>" class="navbar-panel__cta button">
                Scopri di più ›
              </a>
            <?php endif ?>
          </div>

        <?php endif ?>

      </li>
    <?php endforeach ?>

    <li class="navbar__item navbar__item--search">
      <button type="button" class="navbar__search-toggle">
        <span class="sr-only">Cerca</span>
      </button>
      <!-- TODO: form di ricerca, fuori scope per questo passaggio -->
    </li>

    <li class="navbar__item navbar__item--lang">
      <a href="#" class="navbar__lang">IT</a>
      <!-- TODO: switcher lingua, da collegare quando il multilang sarà attivo -->
    </li>

  </ul>
</nav>