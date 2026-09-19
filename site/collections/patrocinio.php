<?php

use Kirby\Cms\Files;

/**
 * Le locandine dei progetti patrocinati dalla Consulta: per ora sono
 * semplicemente i file immagine caricati sulla pagina "Con il
 * patrocinio" (content/2_patrocinio) — i dati dei progetti non sono
 * ancora definiti oltre l'immagine stessa (niente titolo, data,
 * link...), da rivedere quando saranno pronti.
 */
return function ($site) {
  $page = $site->find('patrocinio');

  return $page ? $page->images() : new Files([]);
};
