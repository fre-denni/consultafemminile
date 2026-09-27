<?php
/**
 * @var \Kirby\Cms\File|null $image     File immagine Kirby (es. $page->cover()->toFile())
 * @var array                $sizes     Larghezze da offrire al browser: valori della scala in
 *   site/plugins/image-derivatives (240, 320, 400, 640, 800, 1024, 1280, 1600, 2000), es. [400, 800, 1024].
 *   Sono già generate al caricamento dell'immagine nel Panel.
 * @var string                $alt       Testo alternativo — vuoto ("") se l'immagine è puramente decorativa
 * @var string                $sizesAttr Attributo "sizes" per il browser, es. "(min-width: 768px) 50vw, 100vw"
 * @var string                $class     Classi CSS opzionali sull'<img>
 * @var bool                   $lazy      Di default true (loading="lazy"). Passare false per immagini dentro
 *   contenuto che parte "display:none" e diventa visibile solo via JS (es. bits/association-modal,
 *   un <dialog>) — il lazy-load nativo del browser calcola la distanza dal viewport una sola volta,
 *   all'analisi iniziale della pagina: un'immagine mai layoutata a quel punto (perché dentro un
 *   antenato nascosto) può restare bloccata a naturalWidth 0 anche dopo che l'antenato diventa
 *   visibile. Ignorato quando $deferred è true.
 * @var bool                   $priority Di default false. true per l'immagine che il browser dipinge per
 *   prima nella pagina (hero, copertina dell'articolo): niente lazy-load né decodifica in differita, e
 *   fetchpriority="high". Un'immagine "lazy" già sopra la piega ritarda proprio il primo contenuto
 *   visibile, l'opposto di quello che si vuole.
 * @var bool                   $dimensions Di default false. true aggiunge width/height all'<img> perché il
 *   browser riservi lo spazio prima del caricamento (niente scatti di layout quando l'immagine
 *   arriva): utile per immagini nel flusso del testo (vedi blocks/image.php). Va usato solo dove
 *   il CSS del componente imposta già width e height — altrimenti gli attributi diventano
 *   dimensioni fisse in pixel.
 * @var bool                   $deferred Di default false. true non scrive affatto src/srcset
 *   sull'<img> (finiscono in data-src/data-srcset): niente viene scaricato finché uno script non
 *   li copia sui veri attributi. Va oltre "$lazy": serve per contenuto che potrebbe non venire mai
 *   mostrato (es. bits/association-modal, aperta solo se l'utente clicca "Scopri di più" — vedi
 *   assets/js/association-modal.js).
 *
 * Ogni derivato passa da $image->thumb() ed è WebP (opzione thumbs.format in
 * config.php): il browser scarica la larghezza che gli serve, mai l'originale.
 * Il <picture> resta come contenitore a cui si agganciano i CSS dei
 * componenti, ma non ha più <source>: c'è un solo formato.
 */
$sizes      ??= [400, 800, 1280, 1600];
$alt        ??= '';
$sizesAttr  ??= '100vw';
$class      ??= '';
$lazy       ??= true;
$priority   ??= false;
$dimensions ??= false;
$deferred   ??= false;

if (!$image) return;

$classAttr = $class !== '' ? ' class="' . html($class) . '"' : '';

// Larghezze effettive (vedi imageDerivativeWidths()): mai oltre l'originale.
// Per i file che Kirby non ridimensiona (SVG) si tengono così come sono.
$widths = $image->isResizable() ? imageDerivativeWidths($image, $sizes) : $sizes;
sort($widths);
$largest = max($widths);

$srcset = implode(', ', array_map(
  fn ($width) => $image->thumb(['width' => $width])->url() . ' ' . $width . 'w',
  $widths
));
$src = $image->thumb(['width' => $largest])->url();

$loadingAttrs = $deferred
  ? '' // niente src finché non è lo script ad assegnarlo: il "quando" lo decide lui, non il browser.
  : ($priority ? 'fetchpriority="high"' : ($lazy ? 'loading="lazy" decoding="async"' : 'decoding="async"'));

$srcAttrs = $deferred
  ? 'data-src="' . html($src) . '" data-srcset="' . html($srcset) . '"'
  : 'src="' . html($src) . '" srcset="' . html($srcset) . '"';

// Proporzioni dell'originale: nessun derivato da generare per conoscerle.
$dimensionAttrs = '';
if ($dimensions && $image->width() > 0) {
  $dimensionAttrs = 'width="' . $largest . '" height="' . round($largest * $image->height() / $image->width()) . '"';
}
?>
<picture>
  <img
    <?= $srcAttrs ?>
    sizes="<?= $sizesAttr ?>"
    <?= $dimensionAttrs ?>
    alt="<?= html($alt) ?>"
    <?= $loadingAttrs ?>
    <?= $classAttr ?>
  >
</picture>
