<?php

/**
 * Le persone che NON sono in consiglio (vedi site/collections/consigliere.php
 * per quelle): mostrate nella pagina "Le Persone" in un'unica griglia (vedi
 * bits/people-carousel, che va già a capo da sé oltre le 6 colonne — non
 * serve raggrupparle a mano in righe separate), ordinate alfabeticamente per
 * associazione così le delegate della stessa associazione compaiono vicine.
 */
return function ($site) {
  $delegate = collection('persone')->filter(fn ($person) => !$person->consigliera()->toBool());

  return $delegate->sortBy(function ($person) {
    return mb_strtolower(trim((string) $person->associazione()) . '|' . trim((string) $person->cognome()));
  }, 'asc');
};
