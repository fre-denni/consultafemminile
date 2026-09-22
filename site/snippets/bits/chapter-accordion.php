<?php
/**
 * @var iterable<array{
 *   id: string,
 *   title: string,
 *   subtitle?: string,
 *   description?: string,
 *   images?: \Kirby\Cms\File[],
 *   ctaUrl?: string|null,
 * }> $items Elenco capitoli — un array associativo normalizzato per
 *   ognuno, non pagine/righe structure direttamente: tematiche (pagine,
 *   vedi site/templates/tematiche.php) e timeline (righe structure,
 *   vedi site/templates/timeline.php) hanno un'API diversa, e questo
 *   componente non deve conoscerla — solo la forma comune qui sopra.
 *
 * Accordion a scomparsa: chiuso, ogni capitolo è una riga in stile
 * "sezione" (bordo sopra, titolo centrato — vedi bits/section-divider
 * --chapter, stesso trattamento). Un click apre il capitolo rivelando
 * immagine, sottotitolo, descrizione e un link "Scopri di più" (se
 * $ctaUrl è dato); ne apre solo uno alla volta — aprirne un altro, o
 * cliccare fuori dall'accordion, richiude quello aperto (vedi
 * assets/js/chapter-accordion.js).
 *
 * Immagini e testo trattati come in bits/timeline-accordion (stessa
 * logica, duplicata apposta — componenti autonomi, vedi lì): il numero
 * di immagini decide da sé cosa mostrare, non un campo "tipo" a mano —
 * zero: niente media; una: foto singola; da due in su: un piccolo
 * carosello con le frecce sempre visibili, gestito da
 * assets/js/chapter-media-carousel.js.
 *
 * Gli "eventi collegati" del riferimento Figma non compaiono ancora:
 * il modello dati per gli eventi non è definito, verranno aggiunti in
 * un secondo momento.
 */
$items = is_array($items) ? $items : iterator_to_array($items);

if (count($items) === 0) return;
?>
<div class="chapter-accordion" data-accordion>
  <?php foreach ($items as $item): ?>
    <?php
    $subtitle    = trim((string) ($item['subtitle'] ?? ''));
    $description = trim((string) ($item['description'] ?? ''));
    $images      = array_values($item['images'] ?? []);
    $ctaUrl      = trim((string) ($item['ctaUrl'] ?? ''));
    $panelId     = 'chapter-panel-' . $item['id'];
    $isCarousel  = count($images) > 1;
    ?>
    <div class="chapter-accordion__item" data-accordion-item>
      <h3 class="chapter-accordion__heading">
        <button
          type="button"
          class="chapter-accordion__header"
          aria-expanded="false"
          aria-controls="<?= html($panelId) ?>"
          data-accordion-toggle
        >
          <?= html($item['title']) ?>
        </button>
      </h3>
      <div class="chapter-accordion__panel" id="<?= html($panelId) ?>" data-accordion-panel>
        <div class="chapter-accordion__panel-inner">
          <div class="chapter-accordion__content">
            <?php if (count($images) > 0): ?>
              <div class="chapter-accordion__media">
                <?php if ($isCarousel): ?>
                  <div class="chapter-accordion__media-controls">
                    <button type="button" class="chapter-accordion__media-button chapter-accordion__media-button--prev" aria-label="Foto precedente">←</button>
                    <button type="button" class="chapter-accordion__media-button chapter-accordion__media-button--next" aria-label="Foto successiva">→</button>
                  </div>
                <?php endif ?>
                <ul class="chapter-accordion__media-track<?= $isCarousel ? ' chapter-accordion__media-track--carousel' : '' ?>">
                  <?php foreach ($images as $image): ?>
                    <li class="chapter-accordion__media-item">
                      <?php snippet('atoms/picture', [
                        'image'     => $image,
                        'alt'       => '',
                        'sizes'     => [400, 700, 1000],
                        'sizesAttr' => '(min-width: 768px) 35vw, 90vw',
                        'class'     => 'chapter-accordion__img',
                      ]) ?>
                    </li>
                  <?php endforeach ?>
                </ul>
              </div>
            <?php endif ?>
            <div class="chapter-accordion__body">
              <div class="chapter-accordion__body-inner">
                <p class="chapter-accordion__body-title"><?= html($item['title']) ?></p>
                <?php if ($subtitle !== ''): ?>
                  <p class="chapter-accordion__subtitle"><?= html($subtitle) ?></p>
                <?php endif ?>
                <?php if ($description !== ''): ?>
                  <p class="chapter-accordion__description"><?= html($description) ?></p>
                <?php endif ?>
                <?php if ($ctaUrl !== ''): ?>
                  <a href="<?= html($ctaUrl) ?>" class="chapter-accordion__cta">Scopri di più ›</a>
                <?php endif ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  <?php endforeach ?>
</div>
<?= js('assets/js/chapter-accordion.js', ['defer' => true]) ?>
<?= js('assets/js/chapter-media-carousel.js', ['defer' => true]) ?>
