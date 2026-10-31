<?php

$isStaging = strpos($_SERVER['SCRIPT_NAME'] ?? '', '/staging/') === 0;

$options = [
    'debug' => $isStaging,
    'panel' => [
        'install' => $isStaging,
        // Il Panel non compila template Vue a runtime (meno superficie
        // d'attacco, vedi getkirby.com/security/vue-compiler): i plugin del
        // Panel devono usare funzioni render, non `template:` — vedi
        // site/plugins/panel-registration/index.js.
        'vue' => ['compiler' => false],
        // Menu laterale: le voci di sempre + il link allo strumento per
        // generare le immagini in linea col brand (si apre in una nuova
        // scheda, nella versione originale su GitHub: le immagini si
        // scaricano). Nei campi immagine c'è anche il pulsante "Genera", che
        // apre la copia ospitata dal sito (assets/imgtool) e inserisce
        // l'immagine direttamente nello slot.
        // Visibile a tutti i ruoli che entrano nel Panel (admin ed editor):
        // una voce personalizzata non ha un permesso suo. Con 'panel.menu'
        // definito, le aree vanno elencate tutte a mano.
        'menu' => [
            'site',
            'languages',
            'users',
            'system',
            '-',
            'imgtool' => [
                'label'  => 'Strumento immagini',
                'title'  => 'Genera un\'immagine in linea col brand (si apre in una nuova scheda)',
                'icon'   => 'image',
                'link'   => 'https://fre-denni.github.io/consultafemminile_imgtool/',
                'target' => '_blank',
            ],
        ],
    ],
    'extensions' => [
        'fieldMethods' => require __DIR__ . '/methods.php',
    ],
    'thumbs' => [
        'format'  => 'webp',
        'quality' => 80,
    ],
    // robots.txt e sitemap.xml sono nel plugin site/plugins/seo.
];

// Segreti di questa installazione (salt dei contenuti, chiave dei cookie):
// diversi su ogni server e NON nel repository — stanno in
// site/config/secrets.php, ignorato da git. Su un server nuovo si crea
// partendo da secrets.example.php; senza, Kirby usa i valori di default e il
// Panel (Sistema) segnala l'avviso.
$secrets = is_file(__DIR__ . '/secrets.php') ? require __DIR__ . '/secrets.php' : [];

if (!empty($secrets['content.salt'])) {
    $options['content'] = ['salt' => $secrets['content.salt']];
}

if (!empty($secrets['cookie.key'])) {
    $options['cookie'] = ['key' => $secrets['cookie.key']];
}

return $options;
