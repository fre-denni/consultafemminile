<?php

/**
 * Solo le persone con il flag "Consigliera" attivo (vedi
 * site/collections/persone.php per l'elenco completo delle delegate):
 * sono quelle mostrate nel carosello in home. Ordine: Presidente prima,
 * poi chi ha il flag "Presidenza" (la Vicepresidente), poi il resto —
 * Collection::sort() aggiunge da sé l'indice originale come chiave di
 * ordinamento secondaria, quindi dentro ogni gruppo l'ordine relativo
 * della tabella resta invariato.
 */
return function ($site) {
  $consigliere = collection('persone')->filter(fn ($person) => $person->consigliera()->toBool());

  return $consigliere->sortBy(function ($person) {
    if ($person->ruolo()->value() === 'Presidente') return 0;
    if ($person->presidenza()->toBool()) return 1;
    return 2;
  }, 'asc');
};
