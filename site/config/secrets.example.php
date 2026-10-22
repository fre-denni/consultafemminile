<?php

/**
 * Segreti di questa installazione — COPIA questo file in secrets.php (che git
 * ignora) e sostituisci i due valori con stringhe casuali e lunghe, diverse
 * per ogni server (locale, staging, produzione). NON scrivere mai i valori
 * veri in questo file di esempio e non committare secrets.php.
 *
 * Genera un valore con:  php -r 'echo bin2hex(random_bytes(32)), "\n";'
 *
 * - content.salt: rende imprevedibili gli indirizzi segreti delle anteprime
 *   delle bozze. Cambiarlo invalida i link di anteprima già condivisi.
 * - cookie.key:   firma i cookie (tra cui quello di sessione del Panel).
 *   Cambiarlo disconnette tutti gli utenti una volta sola.
 */
return [
    'content.salt' => 'SOSTITUISCI-CON-UN-VALORE-CASUALE',
    'cookie.key'   => 'SOSTITUISCI-CON-UN-ALTRO-VALORE-CASUALE',
];
