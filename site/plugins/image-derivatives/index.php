<?php

use Kirby\Cms\File;

/**
 * Scala unica delle larghezze dei derivati (px). Ogni `sizes` passato a
 * atoms/picture.php è un sottoinsieme di questa scala: così i derivati
 * che la pagina richiede sono esattamente quelli già generati al
 * caricamento (vedi gli hook in fondo), non "quasi" gli stessi.
 * Sostituibile con l'opzione 'consulta.imageWidths' in config.php.
 */
const IMAGE_DERIVATIVE_WIDTHS = [240, 320, 400, 640, 800, 1024, 1280, 1600, 2000];

/**
 * Larghezze effettive per un file: Kirby non ingrandisce mai oltre
 * l'originale, quindi una richiesta più larga coincide con l'originale
 * stesso. Usata sia qui sia da atoms/picture.php, così le due parti non
 * possono divergere.
 *
 * @param  int[] $widths
 * @return int[]
 */
function imageDerivativeWidths(File $file, array $widths): array
{
  $original = (int) $file->width();
  $result   = array_unique(array_map(fn ($w) => $original > 0 ? min($w, $original) : $w, $widths));
  sort($result);

  return array_values($result);
}

/**
 * Genera subito tutti i derivati WebP di un'immagine (uno per larghezza
 * della scala). Idempotente: se un derivato esiste già, Kirby lo lascia
 * com'è. Restituisce quanti ne ha (ri)creati.
 */
function imageDerivativesGenerate(File $file): int
{
  if (!$file->isResizable()) {
    return 0; // SVG, PDF, video: niente da ridimensionare
  }

  $widths = kirby()->option('consulta.imageWidths', IMAGE_DERIVATIVE_WIDTHS);
  $count  = 0;

  foreach (imageDerivativeWidths($file, $widths) as $width) {
    try {
      $file->thumb(['width' => $width])->save();
      $count++;
    } catch (Throwable $e) {
      // Un derivato fallito non deve far fallire il caricamento: alla
      // peggio verrà generato alla prima richiesta, come prima.
      error_log('image-derivatives: ' . $file->id() . " @{$width}px — " . $e->getMessage());
    }
  }

  return $count;
}

/**
 * Caricare una foto da fotocamera (anche 6000×4000px, ~100 MB da aperta)
 * richiede più memoria e tempo di una richiesta qualunque: qui si alzano i
 * limiti solo per l'operazione di caricamento, fatta da un admin nel Panel.
 * Se l'hosting non lo consente, restano quelli del server.
 */
function imageDerivativesRaiseLimits(): void
{
  @ini_set('memory_limit', '512M');
  @set_time_limit(180);
}

Kirby::plugin('consulta/image-derivatives', [
  'hooks' => [
    // Prima: il ridimensionamento dell'originale (opzione "create" del
    // blueprint files/default.yml) avviene durante la creazione.
    'file.create:before'  => function () {
      imageDerivativesRaiseLimits();
    },
    'file.replace:before' => function () {
      imageDerivativesRaiseLimits();
    },
    // Dopo: l'originale è già alla sua misura definitiva, i derivati si
    // generano da quello — mai da una foto da centinaia di MB in RAM.
    'file.create:after' => function (File $file) {
      imageDerivativesGenerate($file);
    },
    'file.replace:after' => function (File $newFile, File $oldFile) {
      imageDerivativesGenerate($newFile);
    },
    // Rinominare un file cambia il suo indirizzo in /media: i derivati vecchi
    // non servono più.
    'file.changeName:after' => function (File $newFile, File $oldFile) {
      imageDerivativesGenerate($newFile);
    },
  ],
]);
