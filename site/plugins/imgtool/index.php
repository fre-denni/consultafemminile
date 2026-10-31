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
 * Per le *sezioni* file (l'elenco dei file di una pagina, non un campo) c'è il
 * tipo `imgfiles`: come `files` di Kirby, con in più la stessa chiave
 * `imgtool` e lo stesso pulsante "Genera" accanto a "Aggiungi".
 *
 * Il lavoro sta in index.js: questo file porta solo la misura fino al Panel
 * (il tipo `imgfiles` qui sotto) e fa caricare il plugin a Kirby. Lo strumento sta sullo stesso dominio del Panel e parla
 * con la finestra solo da pagina a pagina (nessun messaggio tra siti
 * diversi); il caricamento passa dall'API del Panel, quindi richiede un
 * utente connesso.
 */
use Kirby\Cms\Section;

Kirby::plugin('consulta/imgtool', [
  'sections' => [
    'imgfiles' => [
      'extends' => 'files',
      'props' => [
        /** Misura da impostare nello strumento, es. "500x310" */
        'imgtool' => fn (string|null $size = null) => $size,
      ],
      // Il tipo "files" di Kirby manda al Panel solo le opzioni che conosce:
      // si parte dalle sue e si aggiunge la misura.
      'toArray' => function () {
        $array = Section::$types['files']['toArray']->call($this);
        $array['options']['imgtool'] = $this->imgtool;

        return $array;
      },
    ],
  ],
]);
