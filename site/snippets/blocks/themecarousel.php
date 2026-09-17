<?php
/** @var \Kirby\Cms\Block $block */

// Dentro uno snippet di blocco $site/$page non sono garantiti (a
// differenza dei normali snippet/template): usiamo l'helper globale
// site(), sempre sicuro ovunque.
$selected = $block->pages()->toPages();

// Vuoto = "tutte": ripieghiamo sulle sottopagine di Tematiche. Sono
// pubblicate come "unlisted" (stesso pattern già usato in header.php per
// il menu), quindi niente ->listed() qui: ->children() basta, esclude
// già le bozze di suo.
$themes = $selected->count() > 0
  ? $selected
  : (site()->find('tematiche')?->children() ?? new Kirby\Cms\Pages([]));

// Scartiamo prima le pagine senza cover (bits/theme-card non renderizza
// nulla senza immagine) e solo dopo applichiamo il limite di 8, così il
// tetto riguarda le tematiche davvero mostrabili.
$themes = $themes->filter(fn ($theme) => $theme->file('cover.png') !== null)->limit(8);
?>
<?php snippet('bits/theme-carousel', ['items' => $themes]) ?>
