<?php

use Kirby\Cms\File;

/**
 * Immagine di copertina di una pagina: $page->coverImage().
 *
 * Per gli articoli (template "output") è quella scelta nel campo "Immagine"
 * del Panel (blueprints/pages/output.yml). Se il campo è vuoto — o per ogni
 * altra pagina — si ripiega sul primo file immagine della cartella, come
 * prima: gli articoli già pubblicati senza scelta restano uguali.
 *
 * Card, intestazione dell'articolo, carosello e anteprima social usano tutti
 * questo metodo, così "l'immagine dell'articolo" è sempre la stessa.
 */
Kirby::plugin('consulta/cover-image', [
  'pageMethods' => [
    'coverImage' => function (): ?File {
      return $this->intendedTemplate()->name() === 'output'
        ? ($this->immagine()->toFile() ?? $this->images()->first())
        : $this->images()->first();
    },
  ],
]);
