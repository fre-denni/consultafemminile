<?php

use Kirby\Toolkit\Collection;

/**
 * Tutte le associazioni della Consulta: righe del campo structure
 * "associazioni" nella pagina Chi Siamo > Le Associazioni
 * (content/4_chi-siamo/associazioni) — vedi
 * site/blueprints/pages/associazioni.yml. Aggiungere/togliere
 * un'associazione è solo compilare/rimuovere una riga dal Panel.
 */
return function ($site) {
  $page = $site->find('chi-siamo/associazioni');

  return $page ? $page->associazioni()->toStructure() : new Collection([]);
};
