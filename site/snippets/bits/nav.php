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
      <?php $panelId = 'navbar-panel-' . $item->slug() ?>

      <li class="navbar__item<?= $panel ? ' navbar__item--expandable' : '' ?>">

        <a
          href="<?= $item->url() ?>"
          class="navbar__link"
          <?= $panel ? 'aria-expanded="false" aria-controls="' . $panelId . '"' : '' ?>
          <?= $item->isOpen() ? 'aria-current="page"' : '' ?>
        >
          <?= html($item->title()) ?>
          <?php if ($panel): ?>
            <span class="navbar__chevron" aria-hidden="true"></span>
          <?php endif ?>
        </a>

        <?php if ($panel): ?>
          <div id="<?= $panelId ?>" class="navbar-panel" data-open="false">
            <?php if (($panel['description'] ?? null)?->isNotEmpty()): ?>
              <p class="navbar-panel__description"><?= $panel['description'] ?></p>
            <?php endif ?>

            <?php if (($panel['items'] ?? null)?->isNotEmpty()): ?>
              <ul class="navbar-panel__gallery">
                <?php foreach ($panel['items'] as $sub): ?>
                  <li>
                    <a href="<?= $sub->url() ?>" class="navbar-panel__card">
                      <?php if ($image = $sub->file('cover.png')): ?>
                        <img src="<?= $image->url() ?>" alt="" loading="lazy">
                      <?php endif ?>
                      <span><?= html($sub->title()) ?></span>
                    </a>
                  </li>
                <?php endforeach ?>
              </ul>
            <?php endif ?>

            <?php if ($panel['cta'] ?? null): ?>
              <a href="<?= $panel['cta']->url() ?>" class="navbar-panel__cta">
                Scopri di più ›
              </a>
            <?php endif ?>
          </div>
        <?php endif ?>

      </li>
    <?php endforeach ?>

  </ul>
</nav>