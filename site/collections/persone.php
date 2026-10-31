<?php

use Kirby\Toolkit\Collection;

/**
 * Tutte le persone della Consulta: non sono sottopagine, ma righe delle due
 * tabelle della pagina Chi Siamo > Le Persone (content/4_chi-siamo/persone,
 * vedi site/blueprints/pages/persone.yml): "Consiglio" (campo "consiglio") e
 * "Delegate" (campo "persone"). Aggiungere/togliere una persona è solo
 * compilare/rimuovere una riga dal Panel, niente da toccare qui.
 *
 * Le righe del Consiglio risultano "consigliera" per definizione: da qui in
 * poi collections/consigliere.php e collections/delegate.php le distinguono
 * dal flag come sempre. Le righe di "Delegate" con il vecchio flag
 * "consigliera" (quando c'era una tabella sola) restano consigliere finché
 * non vengono spostate; una persona presente in entrambe le tabelle conta
 * una volta, come "Consiglio".
 */
return function ($site) {
  $page = $site->find('chi-siamo/persone');

  if (!$page) {
    return new Collection([]);
  }

  $key    = fn ($person) => mb_strtolower(trim($person->nome() . ' ' . $person->cognome()));
  $people = [];

  foreach ($page->consiglio()->toStructure() as $i => $person) {
    $person->content()->update(['consigliera' => 'true']);
    $people[$key($person)] = $person;
  }

  foreach ($page->persone()->toStructure() as $i => $person) {
    $people[$key($person)] ??= $person;
  }

  return new Collection(array_values($people));
};
