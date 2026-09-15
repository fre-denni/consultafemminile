<?php
/**
 * @var \Kirby\Cms\File|null $video            File video Kirby (mp4), es. $page->file('presentazione.mp4')
 * @var string|null          $headingPrimary   Prima riga del titolo, in semibold (es. "La Consulta Femminile")
 * @var string|null          $headingSecondary Seconda riga del titolo, in regular (es. "di Milano dal 1963")
 *
 * Video di sfondo autoplay/loop, puramente decorativo (aria-hidden — il
 * contenuto informativo della sezione è tutto nel testo sovrapposto).
 * Va sempre silenziato: è l'unico modo per cui gli autoplay funzionano
 * in modo affidabile nei browser, ed evita audio non richiesto.
 * Rispetta prefers-reduced-motion via assets/js/hero-video.js.
 */
$headingPrimary   ??= null;
$headingSecondary ??= null;

if (!$video) return;
?>
<section class="hero-video">
  <div class="hero-video__frame">
    <video
      class="hero-video__media"
      src="<?= $video->url() ?>"
      autoplay
      muted
      loop
      playsinline
      preload="auto"
      aria-hidden="true"
    >
      Il tuo browser non supporta la riproduzione video HTML5.
    </video>
    <div class="hero-video__scrim" aria-hidden="true"></div>
    <?php if ($headingPrimary || $headingSecondary): ?>
      <h1 class="hero-video__heading">
        <?php if ($headingPrimary): ?>
          <span class="hero-video__heading-primary"><?= html($headingPrimary) ?></span>
        <?php endif ?>
        <?php if ($headingSecondary): ?>
          <span class="hero-video__heading-secondary"><?= html($headingSecondary) ?></span>
        <?php endif ?>
      </h1>
    <?php endif ?>
  </div>
</section>
<?= js('assets/js/hero-video.js', ['defer' => true]) ?>
