<?php

use Kirby\Toolkit\Collection;

/**
 * Tutte le persone della Consulta ("le delegate"): non sono sottopagine,
 * ma righe del campo structure "persone" nella pagina Chi Siamo > Le
 * Persone (content/4_chi-siamo/persone) — vedi site/blueprints/pages/persone.yml.
 * Aggiungere/togliere una persona è solo compilare/rimuovere una riga
 * dal Panel, niente da toccare qui.
 */
return function ($site) {
  $page = $site->find('chi-siamo/persone');

  return $page ? $page->persone()->toStructure() : new Collection([]);
};
