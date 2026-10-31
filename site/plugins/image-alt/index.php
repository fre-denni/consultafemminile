<?php

use Kirby\Cms\ModelWithContent;
use Kirby\Cms\Page;
use Kirby\Cms\Site;
use Kirby\Exception\InvalidArgumentException;

/**
 * Testo alternativo (Alt) obbligatorio per le immagini NUOVE.
 *
 * Il campo "Alt" è nel blueprint dei file (blueprints/files/default.yml,
 * required: il Panel non salva il file senza). Ma Kirby non chiede i campi
 * del file mentre si carica: l'upload va sempre a buon fine, l'immagine
 * arriva senza Alt. Per non lasciarla così, qui si blocca il salvataggio
 * della pagina (o del sito) che contiene immagini nuove senza Alt, con un
 * messaggio che elenca quali sono.
 *
 * Eccezione: le pagine con template in 'consulta.alt.exempt' (default:
 * "persone" e "associazioni") non bloccano nulla, perché il loro Alt si
 * genera da codice: per le persone "Foto di profilo di Nome Cognome ..."
 * (bits/person-card.php), per le associazioni "logo-Nome" e
 * "foto-significativa-Nome" (associationAlt() qui sotto).
 *
 * Anche le immagini segnate "decorative" (campo `decorative` del file, vedi
 * blueprints/files/default.yml) non hanno bisogno di Alt: escono con alt=""
 * e il lettore di schermo le salta. Il metodo `altText` dà il valore da
 * usare nel codice (vuoto se decorativa), `altInfo` il testo per il Panel.
 *
 * "Nuove" = caricate (o sostituite) dopo la data 'consulta.alt.since'
 * (config.php, default sotto): le immagini già presenti prima non bloccano
 * nulla, si completano quando serve — nelle liste del Panel sono segnate
 * "⚠ Alt mancante".
 */

if (!function_exists('associationAlt')) {
  /**
   * Alt programmatico delle immagini di un'associazione (riga della
   * struttura "associazioni"): "logo-{nome}" per il logo,
   * "foto-significativa-{nome}" per la foto. Il nome è quello completo, o
   * quello breve se manca.
   *
   * @param string $kind 'logo' | 'foto'
   */
  function associationAlt($association, string $kind): string
  {
    $name = trim((string) $association->nome_completo()) ?: trim((string) $association->nome_breve());

    if ($name === '') {
      return '';
    }

    return ($kind === 'foto' ? 'foto-significativa-' : 'logo-') . $name;
  }
}

if (!function_exists('imageAltMissing')) {
  /** @return string[] nomi dei file immagine nuovi senza Alt */
  function imageAltMissing(ModelWithContent $model, ?int $since = null): array
  {
    $since ??= (int) strtotime((string) option('consulta.alt.since', '2026-10-22 21:00'));
    $missing = [];

    foreach ($model->images() as $file) {
      if ($file->alt()->isEmpty() && $file->decorative()->isFalse() && $file->modified() >= $since) {
        $missing[] = $file->filename();
      }
    }

    return $missing;
  }
}

$assertAlt = function (ModelWithContent $model): void {
  if (
    $model instanceof Page &&
    in_array($model->intendedTemplate()->name(), (array) option('consulta.alt.exempt', ['persone', 'associazioni']), true)
  ) {
    return;
  }

  $missing = imageAltMissing($model);

  if ($missing === []) {
    return;
  }

  $list = implode(', ', array_slice($missing, 0, 5)) . (count($missing) > 5 ? '…' : '');

  throw new InvalidArgumentException(
    message: (count($missing) === 1 ? 'Manca il testo alternativo (Alt) di questa immagine: ' : 'Manca il testo alternativo (Alt) di queste immagini: ')
      . $list
      . '. Aprila (clic sull\'immagine), compila il campo "Testo alternativo (Alt)" e salva, poi salva di nuovo la pagina.'
  );
};

Kirby::plugin('consulta/image-alt', [
  'fileMethods' => [
    /** Alt da mettere nell'HTML: vuoto se l'immagine è decorativa. */
    'altText' => fn (): string => $this->decorative()->isTrue() ? '' : trim((string) $this->alt()->value()),
    /** Riga informativa nelle liste del Panel. */
    'altInfo' => fn (): string => $this->decorative()->isTrue()
      ? 'Decorativa (senza Alt)'
      : (trim((string) $this->alt()->value()) ?: '⚠ Alt mancante'),
  ],
  'hooks' => [
    'page.update:before' => function (Page $page) use ($assertAlt) {
      $assertAlt($page);
    },
    'site.update:before' => function (Site $site) use ($assertAlt) {
      $assertAlt($site);
    },
  ],
]);
