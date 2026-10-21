<?php

use Kirby\Toolkit\Obj;

/**
 * Opzioni per il campo "Icona" della struttura Social (vedi site.yml):
 * semplicemente i file presenti in assets/icons/social — nessuna
 * convenzione sul nome richiesta. Chi gestisce il progetto aggiunge o
 * toglie un'icona caricando/eliminando il file in quella cartella, senza
 * toccare blueprint o codice.
 */
Kirby::plugin('consulta/social-icons', [
  'siteMethods' => [
    'socialIconOptions' => function () {
      $dir   = 'social';
      $files = glob(kirby()->roots()->assets() . '/icons/' . $dir . '/*.svg') ?: [];
      sort($files);

      return array_map(function ($path) use ($dir) {
        $name = pathinfo($path, PATHINFO_FILENAME);

        return new Obj(['value' => $dir . '/' . $name, 'text' => $name]);
      }, $files);
    },
  ],
]);
