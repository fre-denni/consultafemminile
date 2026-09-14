<?php
// Script temporaneo di verifica — caricalo via FTP nella root del sito,
// aprilo dal browser (es. https://consultafemminile.org/check-webp.php),
// leggi il risultato, POI CANCELLALO. Non fa parte del sito.

header('Content-Type: text/plain; charset=utf-8');

echo "PHP version: " . PHP_VERSION . "\n\n";

if (extension_loaded('gd')) {
    $info = gd_info();
    echo "GD attivo — versione: " . $info['GD Version'] . "\n";
    echo "GD supporta WebP: " . (!empty($info['WebP Support']) ? "SÌ" : "NO") . "\n\n";
} else {
    echo "GD non disponibile.\n\n";
}

if (extension_loaded('imagick')) {
    $formats = (new Imagick())->queryFormats('WEBP');
    echo "Imagick attivo.\n";
    echo "Imagick supporta WebP: " . (in_array('WEBP', $formats) ? "SÌ" : "NO") . "\n";
} else {
    echo "Imagick non disponibile.\n";
}