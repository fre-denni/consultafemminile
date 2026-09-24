<?php
/**
 * @var \Kirby\Cms\Page $page Pagina "output" (vedi
 *   site/blueprints/pages/output.yml): un articolo.
 *
 * Chiude l'articolo: banner blu (bits/section-divider, stile "banner")
 * col titolo della tematica di cui fa parte, la sua descrizione e un
 * pulsante che porta alla pagina della tematica. Titolo e descrizione
 * vengono dalla tematica (vedi site/blueprints/pages/tematica.yml), non
 * sono campi dell'articolo.
 */
$tematica = $page->parent();

if (!$tematica || $tematica->intendedTemplate()->name() !== 'tematica') return;
?>
<section class="article-footer">
  <?php snippet('bits/section-divider', [
    'heading' => $tematica->title()->value(),
    'style'   => 'banner',
  ]) ?>
  <div class="article-footer__body">
    <?php if ($tematica->description()->isNotEmpty()): ?>
      <div class="article-footer__text">
        <?= $tematica->description()->kirbytext() ?>
      </div>
    <?php endif ?>
    <a href="<?= html($tematica->url()) ?>" class="article-footer__cta">
      Scopri di più
      <?php snippet('atoms/icon', ['name' => 'chevron-right']) ?>
    </a>
  </div>
</section>
