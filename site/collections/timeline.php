<?php

use Kirby\Toolkit\Collection;

/**
 * I capitoli della timeline ("La nostra storia"): righe del campo
 * structure "capitoli" nella pagina content/3_chi-siamo/timeline — vedi
 * site/blueprints/pages/timeline.yml. Aggiungere/togliere un capitolo è
 * solo compilare/rimuovere una riga dal Panel, niente da toccare qui.
 */
return function ($site) {
  $page = $site->find('chi-siamo/timeline');

  return $page ? $page->capitoli()->toStructure() : new Collection([]);
};
