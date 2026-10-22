<?php

$isStaging = strpos($_SERVER['SCRIPT_NAME'] ?? '', '/staging/') === 0;

return [
    'debug' => $isStaging,
    'panel' => [
        'install' => $isStaging,
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