<?php

use Consulta\Seo\Meta;
use Kirby\Cms\Response;

require_once __DIR__ . '/classes/Meta.php';

/**
 * SEO del sito, calcolato in modo dinamico (nessun metadato da scrivere a
 * mano per ogni pagina o articolo):
 *
 *  - snippet('seo/head')  → title, description, canonical, robots, Open
 *    Graph, Twitter card e JSON-LD (Organization, WebSite, WebPage,
 *    BreadcrumbList e, per gli output, Article) — vedi header.php;
 *  - /sitemap.xml         → tutte le pagine pubblicate e indicizzabili;
 *  - /robots.txt          → apre ai motori di ricerca solo online, mai in
 *    locale o su staging.
 *
 * Valori scritti a mano: sezione "SEO" in fondo a ogni pagina del Panel
 * (titolo, descrizione, immagine, nascondi dalla ricerca) e tab "SEO" di
 * site.yml (descrizione e immagine predefinite). Se vuoti, si ricavano dal
 * contenuto — vedi classes/Meta.php.
 *
 * Opzioni (config.php): 'consulta.seo.index' => true|false forza
 * l'indicizzazione a prescindere dall'ambiente;
 * 'consulta.seo.descriptionLength' => 155 (caratteri).
 */
Kirby::plugin('consulta/seo', [
  'snippets' => [
    'seo/head' => __DIR__ . '/snippets/head.php',
  ],
  'routes' => [
    [
      'pattern' => 'robots.txt',
      'action'  => function () {
        $lines = ['User-agent: *'];

        if (Meta::isProduction()) {
          $lines[] = 'Disallow: /' . trim((string) option('panel.slug', 'panel'), '/') . '/';
          $lines[] = 'Disallow: /invia-messaggio';
          $lines[] = '';
          $lines[] = 'Sitemap: ' . url('sitemap.xml');
        } else {
          $lines[] = 'Disallow: /';
        }

        return new Response(implode("\n", $lines) . "\n", 'text/plain', 200);
      },
    ],
    [
      'pattern' => 'sitemap.xml',
      'action'  => function () {
        $pages = site()->index()->filter(
          fn ($p) => $p->intendedTemplate()->name() !== 'error' && !$p->seoNoindex()->toBool()
        );

        $xml = ['<?xml version="1.0" encoding="UTF-8"?>',
                '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'];

        foreach ($pages as $p) {
          $xml[] = '  <url>';
          $xml[] = '    <loc>' . html($p->url(), false) . '</loc>';
          $xml[] = '    <lastmod>' . $p->modified('c', 'date') . '</lastmod>';
          $xml[] = '  </url>';
        }

        $xml[] = '</urlset>';

        return new Response(implode("\n", $xml) . "\n", 'application/xml', 200);
      },
    ],
    [
      'pattern' => 'sitemap',
      'action'  => fn () => go('sitemap.xml', 301),
    ],
  ],
]);
