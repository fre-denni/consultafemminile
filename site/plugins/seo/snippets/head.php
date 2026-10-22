<?php
/**
 * Tutti i meta tag SEO/social della pagina corrente: titolo, descrizione,
 * canonical, robots, Open Graph, Twitter card e dati strutturati
 * (JSON-LD). Da includere una volta sola nel <head> (vedi header.php).
 * I valori si calcolano da soli: vedi classes/Meta.php per la cascata.
 *
 * @var \Kirby\Cms\Page $page
 */

use Consulta\Seo\Meta;

$meta        = new Meta($page);
$title       = $meta->title();
$description = $meta->description();
$image       = $meta->image();
$type        = $meta->type();

// htmlspecialchars e non $e(): lascia le lettere accentate come sono
// ($e() le trasforma in entità, valide ma illeggibili nel sorgente).
$e = fn ($value) => htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
?>
<title><?= $e($title) ?></title>
<?php if ($description !== ''): ?>
<meta name="description" content="<?= $e($description) ?>">
<?php endif ?>
<meta name="robots" content="<?= $e($meta->robots()) ?>">
<link rel="canonical" href="<?= $e($meta->canonical()) ?>">
<link rel="sitemap" type="application/xml" href="<?= $e(url('sitemap.xml')) ?>">

<meta property="og:locale" content="it_IT">
<meta property="og:site_name" content="<?= $e($site->title()->value()) ?>">
<meta property="og:type" content="<?= $e($type) ?>">
<meta property="og:title" content="<?= $e($meta->name()) ?>">
<?php if ($description !== ''): ?>
<meta property="og:description" content="<?= $e($description) ?>">
<?php endif ?>
<meta property="og:url" content="<?= $e($meta->canonical()) ?>">
<?php if ($image): ?>
<meta property="og:image" content="<?= $e($image['url']) ?>">
<?php if ($image['width'] && $image['height']): ?>
<meta property="og:image:width" content="<?= (int) $image['width'] ?>">
<meta property="og:image:height" content="<?= (int) $image['height'] ?>">
<?php endif ?>
<meta property="og:image:alt" content="<?= $e($image['alt']) ?>">
<?php endif ?>
<?php if ($type === 'article'): ?>
<?php if ($page->data()->isNotEmpty()): ?>
<meta property="article:published_time" content="<?= $e($page->data()->toDate('c')) ?>">
<?php endif ?>
<meta property="article:modified_time" content="<?= $e($page->modified('c')) ?>">
<?php if ($page->parent()): ?>
<meta property="article:section" content="<?= $e($page->parent()->title()->value()) ?>">
<?php endif ?>
<?php endif ?>

<meta name="twitter:card" content="<?= $image ? 'summary_large_image' : 'summary' ?>">
<meta name="twitter:title" content="<?= $e($meta->name()) ?>">
<?php if ($description !== ''): ?>
<meta name="twitter:description" content="<?= $e($description) ?>">
<?php endif ?>
<?php if ($image): ?>
<meta name="twitter:image" content="<?= $e($image['url']) ?>">
<meta name="twitter:image:alt" content="<?= $e($image['alt']) ?>">
<?php endif ?>

<script type="application/ld+json"><?= json_encode($meta->jsonLd(), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP) ?></script>
