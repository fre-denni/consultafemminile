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
    // Qualità dei derivati generati da thumb()/srcset(): il default di
    // Kirby è 90, un livello che su foto e loghi web non si distingue da
    // 80 ma pesa circa un quarto in più.
    'thumbs' => [
        'quality' => 80,
    ],
    'routes' => [
        [
        'pattern' => 'sitemap.xml',
        'action'  => function() {
            $pages = site()->pages()->index();

            // fetch the pages to ignore from the config settings,
            // if nothing is set, we ignore the error page
            $ignore = kirby()->option('sitemap.ignore', ['error']);

            $content = snippet('sitemap', compact('pages', 'ignore'), true);

            // return response with correct header type
            return new Kirby\Cms\Response($content, 'application/xml');
        }
        ],
        [
        'pattern' => 'sitemap',
        'action'  => function() {
            return go('sitemap.xml', 301);
        }
        ]
    ],
    'sitemap.ignore' => ['error'],
];