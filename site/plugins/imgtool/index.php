<?php

/**
 * Strumento immagini dentro il Panel.
 *
 * I campi immagine del Panel che nel blueprint hanno la chiave
 * `imgtool: LARGHEZZAxALTEZZA` (es. `imgtool: 400x400`) mostrano, accanto a
 * "Carica" e "Seleziona", il pulsante "Genera": apre una finestra con lo
 * strumento ospitato dal sito (assets/imgtool), già impostato sulla misura
 * dello slot, e il suo pulsante "Aggiungi alla pagina" inserisce l'immagine
 * direttamente nel campo, segnata come "decorativa" (alt="" voluto: chi usa un
 * lettore di schermo la salta — vedi blueprints/files/default.yml).
 * Valgono solo i campi `type: files`, non le sezioni file.
 *
 * Tutto il lavoro sta in index.js: questo file serve solo a far caricare
 * il plugin a Kirby. Lo strumento sta sullo stesso dominio del Panel e parla
 * con la finestra solo da pagina a pagina (nessun messaggio tra siti
 * diversi); il caricamento passa dall'API del Panel, quindi richiede un
 * utente connesso.
 */
Kirby::plugin('consulta/imgtool', []);
