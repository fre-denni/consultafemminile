<?php

namespace Consulta\Seo;

use Kirby\Cms\File;
use Kirby\Cms\Page;
use Kirby\Cms\Site;

/**
 * Metadati SEO di una pagina, calcolati "a cascata": il valore scritto a
 * mano nel Panel (sezione SEO della pagina) vince; se manca, si ricava
 * dal contenuto della pagina stessa (es. la descrizione di un articolo);
 * se manca anche quello, valgono i valori del sito (tab SEO di site.yml).
 * Niente da ricompilare per ogni pagina o articolo nuovo: basta
 * compilare le pagine come al solito e i metadati si generano da soli.
 */
final class Meta
{
  public function __construct(private Page $page)
  {
  }

  private function site(): Site
  {
    return $this->page->site();
  }

  private function template(): string
  {
    return $this->page->intendedTemplate()->name();
  }

  /* ── utilità ─────────────────────────────────────────────────── */

  /** HTML (anche da un editor con paragrafi) → testo semplice su una riga. */
  public static function plain(?string $html): string
  {
    $html = preg_replace('/<\/(p|li|h[1-6]|blockquote|div)>|<br\s*\/?>/i', ' ', (string) $html);
    $text = html_entity_decode(strip_tags((string) $html), ENT_QUOTES | ENT_HTML5, 'UTF-8');

    return trim((string) preg_replace('/\s+/u', ' ', $text));
  }

  /** Taglia a fine parola, con "…" solo se ha davvero tagliato. */
  public static function excerpt(string $text, int $max = 155): string
  {
    if (mb_strlen($text) <= $max) {
      return $text;
    }

    $cut   = mb_substr($text, 0, $max);
    $space = mb_strrpos($cut, ' ');

    if ($space !== false && $space > $max * 0.6) {
      $cut = mb_substr($cut, 0, $space);
    }

    return rtrim($cut, " ,;:.-–—") . '…';
  }

  /** Online (non locale/staging): solo lì si chiede ai motori di indicizzare. */
  public static function isProduction(): bool
  {
    $forced = option('consulta.seo.index');

    if ($forced !== null) {
      return (bool) $forced;
    }

    if (option('debug')) {
      return false; // il config attiva debug solo su staging
    }

    $host = (string) kirby()->environment()->host();

    return preg_match('/^(localhost|127\.|\[?::1)|\.(test|local|localhost)$/i', $host) !== 1;
  }

  /* ── cascata ─────────────────────────────────────────────────── */

  /** Titolo "pulito" della pagina (senza il nome del sito). */
  public function name(): string
  {
    $custom = trim((string) $this->page->seoTitle());

    if ($custom !== '') {
      return $custom;
    }

    // La home si chiama "Home" nel Panel, ma per i motori di ricerca è il sito.
    return $this->page->isHomePage()
      ? trim((string) $this->site()->title())
      : trim((string) $this->page->title());
  }

  /** Contenuto di <title>: "Pagina | Consulta Femminile di Milano". */
  public function title(): string
  {
    $site = trim((string) $this->site()->title());

    if ($this->page->isHomePage()) {
      $custom = trim((string) $this->page->seoTitle());
      return $custom !== '' ? $custom : $site;
    }

    $name = $this->name();
    $full = $name . ' | ' . $site;

    // Il nome del sito si aggiunge solo se il titolo resta di una lunghezza
    // che Google mostra intera (~60 caratteri): meglio il titolo della
    // pagina da solo che un titolo tagliato a metà.
    if ($site === '' || mb_stripos($name, $site) !== false || mb_strlen($full) > (int) option('consulta.seo.titleLength', 65)) {
      return $name;
    }

    return $full;
  }

  private function contentDescription(): string
  {
    $p = $this->page;

    $raw = match ($this->template()) {
      'output'   => $p->intro(),
      'tematica' => $p->description(),
      'home'     => $p->descrizioneUnoTesto(),
      'tematiche', 'patrocinio', 'timeline', 'chi-siamo', 'error', 'default' => $p->mission(),
      default    => null,
    };

    return self::plain((string) $raw);
  }

  public function description(): string
  {
    foreach ([
      (string) $this->page->seoDescription(),
      $this->contentDescription(),
      (string) $this->site()->seoDescription(),
      (string) $this->site()->payoff(),
    ] as $candidate) {
      $text = self::plain($candidate);

      if ($text !== '') {
        return self::excerpt($text, (int) option('consulta.seo.descriptionLength', 155));
      }
    }

    return '';
  }

  public function canonical(): string
  {
    return $this->page->url();
  }

  public function robots(): string
  {
    if (!self::isProduction()) {
      return 'noindex, nofollow';
    }

    if ($this->template() === 'error' || $this->page->seoNoindex()->toBool()) {
      return 'noindex, follow';
    }

    return 'index, follow, max-image-preview:large, max-snippet:-1';
  }

  public function isIndexable(): bool
  {
    return $this->template() !== 'error' && !$this->page->seoNoindex()->toBool();
  }

  private function sourceImage(): ?File
  {
    $p = $this->page;

    $file = $p->seoImage()->toFile();

    $file ??= match ($this->template()) {
      'output'   => $p->images()->first(),
      'tematica' => $p->file('cover.webp') ?? $p->images()->first(),
      default    => null,
    };

    return $file ?? $this->site()->seoImage()->toFile();
  }

  /** @return array{url: string, width: int|null, height: int|null, alt: string}|null */
  public function image(): ?array
  {
    $file = $this->sourceImage();

    if (!$file) {
      return null;
    }

    $alt = trim((string) $file->alt()->value()) ?: $this->name();

    // 1200×630 (rapporto 1,91:1 di Facebook/LinkedIn/X), in JPG: il
    // formato che ogni anteprima social legge senza sorprese.
    if ($file->isResizable()) {
      $thumb = $file->thumb([
        'width'   => 1200,
        'height'  => 630,
        'crop'    => true,
        'quality' => 82,
        'format'  => 'jpg',
      ]);

      return ['url' => $thumb->url(), 'width' => 1200, 'height' => 630, 'alt' => $alt];
    }

    return [
      'url'    => $file->url(),
      'width'  => $file->width() ?: null,
      'height' => $file->height() ?: null,
      'alt'    => $alt,
    ];
  }

  public function type(): string
  {
    return $this->template() === 'output' ? 'article' : 'website';
  }

  /* ── dati strutturati (JSON-LD) ──────────────────────────────── */

  private function organization(): array
  {
    $site = $this->site();

    $sameAs = [];
    foreach ($site->social()->toStructure() as $row) {
      $url = trim((string) $row->url());
      if ($url !== '') {
        $sameAs[] = $url;
      }
    }

    $org = [
      '@type' => 'Organization',
      '@id'   => $site->url() . '/#organization',
      'name'  => (string) $site->title(),
      'url'   => $site->url(),
      'logo'  => [
        '@type' => 'ImageObject',
        'url'   => url('assets/logo.svg'),
      ],
    ];

    if ($sameAs !== []) {
      $org['sameAs'] = $sameAs;
    }

    $contatti = $site->find('chi-siamo/contatti');
    $email    = $contatti?->contact_email()->value();
    $email    = $email ?: (defined('CONTACT_FORM_FALLBACK_TO') ? CONTACT_FORM_FALLBACK_TO : null);

    if ($email) {
      $org['contactPoint'] = [
        '@type'             => 'ContactPoint',
        'contactType'       => 'customer support',
        'email'             => $email,
        'availableLanguage' => 'it',
      ];
    }

    return $org;
  }

  private function breadcrumb(): ?array
  {
    if ($this->page->isHomePage()) {
      return null;
    }

    $items = [['name' => (string) $this->site()->title(), 'url' => $this->site()->url()]];

    foreach ($this->page->parents()->flip() as $parent) {
      $items[] = ['name' => (string) $parent->title(), 'url' => $parent->url()];
    }

    $items[] = ['name' => $this->name(), 'url' => $this->canonical()];

    return [
      '@type'           => 'BreadcrumbList',
      '@id'             => $this->canonical() . '#breadcrumb',
      'itemListElement' => array_map(fn ($item, $i) => [
        '@type'    => 'ListItem',
        'position' => $i + 1,
        'name'     => $item['name'],
        'item'     => $item['url'],
      ], $items, array_keys($items)),
    ];
  }

  private function article(?array $image): array
  {
    $p     = $this->page;
    $site  = $this->site();
    $persone = $site->find('chi-siamo/persone')?->url();

    $authors = [];
    foreach ($p->autori()->toStructure() as $row) {
      $name = trim((string) $row->nome());
      if ($name === '') {
        continue;
      }

      $author = ['@type' => 'Person', 'name' => $name];
      $link   = $row->membro_consulta()->toBool() ? $persone : trim((string) $row->link());

      if ($link) {
        $author['url'] = $link;
      }

      $authors[] = $author;
    }

    $article = [
      '@type'            => 'Article',
      '@id'              => $this->canonical() . '#article',
      'headline'         => mb_substr($this->name(), 0, 110),
      'description'      => $this->description(),
      'mainEntityOfPage' => ['@id' => $this->canonical() . '#webpage'],
      'inLanguage'       => 'it-IT',
      'publisher'        => ['@id' => $site->url() . '/#organization'],
      'dateModified'     => $p->modified('c'),
    ];

    if ($p->data()->isNotEmpty()) {
      $article['datePublished'] = $p->data()->toDate('c');
    }

    if ($authors !== []) {
      $article['author'] = $authors;
    }

    if ($image) {
      $article['image'] = [$image['url']];
    }

    if ($p->categoria()->isNotEmpty()) {
      $article['articleSection'] = (string) $p->categoria();
    }

    return $article;
  }

  public function jsonLd(): array
  {
    $site  = $this->site();
    $image = $this->image();

    $graph = [$this->organization()];

    if ($this->page->isHomePage()) {
      $graph[] = [
        '@type'      => 'WebSite',
        '@id'        => $site->url() . '/#website',
        'url'        => $site->url(),
        'name'       => (string) $site->title(),
        'inLanguage' => 'it-IT',
        'publisher'  => ['@id' => $site->url() . '/#organization'],
      ];
    }

    $webpage = [
      '@type'       => 'WebPage',
      '@id'         => $this->canonical() . '#webpage',
      'url'         => $this->canonical(),
      'name'        => $this->title(),
      'inLanguage'  => 'it-IT',
      'isPartOf'    => ['@id' => $site->url() . '/#website'],
    ];

    if (($description = $this->description()) !== '') {
      $webpage['description'] = $description;
    }

    if ($image) {
      $webpage['primaryImageOfPage'] = ['@type' => 'ImageObject', 'url' => $image['url']];
    }

    if ($breadcrumb = $this->breadcrumb()) {
      $webpage['breadcrumb'] = ['@id' => $breadcrumb['@id']];
    }

    $graph[] = $webpage;

    if ($breadcrumb) {
      $graph[] = $breadcrumb;
    }

    if ($this->template() === 'output') {
      $graph[] = $this->article($image);
    }

    return ['@context' => 'https://schema.org', '@graph' => $graph];
  }
}
