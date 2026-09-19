<?php

use Kirby\Cms\Pages;

/**
 * Tutte le tematiche mostrabili nel carosello: sottopagine di "tematiche"
 * (pubblicate come "unlisted", niente ->listed() qui — stesso pattern già
 * usato in header.php per il menu) con una cover.png, nell'ordine del
 * menu, fino a un massimo di 8.
 */
return function ($site) {
  $themes = $site->find('tematiche')?->children() ?? new Pages([]);

  return $themes
    ->filter(fn ($theme) => $theme->file('cover.png') !== null)
    ->limit(8);
};
