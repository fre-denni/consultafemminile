<?php
/**
 * @var iterable<array{id: string, images?: \Kirby\Cms\File[]}> $items Un array
 *   associativo per capitolo — la sola forma che questo componente conosce è
 *   'id' e 'images' (per il blocco media, gestito qui); tutto il resto del
 *   contenuto lo decide chi chiama tramite $heading/$body qui sotto. Non
 *   pagine/righe structure direttamente: tematiche e timeline hanno un'API
 *   diversa, normalizzata a monte da bits/chapter-accordion e
 *   bits/timeline-accordion.
 * @var callable $heading fn($item): string — testo del bottone quando il
 *   capitolo è chiuso (già escapato).
 * @var callable $body    fn($item): string — markup del corpo del pannello
 *   (già pronto, es. via output buffering): titolo/sottotitolo/CTA per un
 *   uso, etichetta/descrizione per un altro — il componente non lo sa.
 * @var string   $panelPrefix    Prefisso per l'id del pannello (deve essere
 *   univoco per pagina): 'chapter-panel-' o 'timeline-panel-'.
 * @var string   $headerVariant  'large' (default) o 'compact' — dimensione
 *   del bottone quando il capitolo è chiuso (vedi accordion.css).
 * @var string   $imageSizesAttr Attributo "sizes" per le immagini del blocco
 *   media (vedi atoms/picture) — l'unica differenza reale tra i due usi
 *   attuali oltre al contenuto del corpo.
 * @var string   $background     'default' (nessuno) o 'blue' — sfondo fisso
 *   dell'intero accordion, non scelto dal Panel (a differenza del
 *   $background di bits/carousel): solo bits/timeline-accordion lo usa.
 *
 * Accordion a scomparsa: chiuso, ogni capitolo è una riga in stile "sezione"
 * (bordo sopra, titolo centrato — vedi bits/section-divider--chapter, stesso
 * trattamento). Un click apre il capitolo rivelando immagine e corpo,
 * richiudendo qualunque altro già aperto — l'apertura/chiusura vive in
 * assets/js/accordion.js, agnostico al contenuto (solo attributi
 * data-accordion*). Immagini: zero, niente media; una, foto singola; da due
 * in su, un piccolo carosello con le frecce sempre visibili, gestito da
 * assets/js/accordion-media-carousel.js.
 *
 * Nato da bits/chapter-accordion e bits/timeline-accordion, due componenti
 * quasi identici (stessa meccanica, layout diverso solo nel corpo) —
 * accorpati qui; le due firme originali restano invariate, ora sono
 * adattatori sottili su questo.
 */
$items = is_array($items) ? $items : iterator_to_array($items);

if (count($items) === 0) return;

$headerVariant  ??= 'large';
$imageSizesAttr ??= '(min-width: 768px) 35vw, 90vw';
$background     ??= 'default';
?>
<div class="accordion accordion--<?= html($headerVariant) ?><?= $background !== 'default' ? ' accordion--' . html($background) : '' ?>" data-accordion>
  <?php foreach ($items as $item): ?>
    <?php
    $images     = array_values($item['images'] ?? []);
    $panelId    = html($panelPrefix . $item['id']);
    $isCarousel = count($images) > 1;
    ?>
    <div class="accordion__item" data-accordion-item>
      <h3 class="accordion__heading">
        <button
          type="button"
          class="accordion__header"
          aria-expanded="false"
          aria-controls="<?= $panelId ?>"
          data-accordion-toggle
        >
          <?= $heading($item) ?>
        </button>
      </h3>
      <div class="accordion__panel" id="<?= $panelId ?>" data-accordion-panel>
        <div class="accordion__panel-inner">
          <div class="accordion__content">
            <?php if (count($images) > 0): ?>
              <div class="accordion__media">
                <?php if ($isCarousel): ?>
                  <div class="accordion__media-controls">
                    <button type="button" class="accordion__media-button accordion__media-button--prev" aria-label="Foto precedente">←</button>
                    <button type="button" class="accordion__media-button accordion__media-button--next" aria-label="Foto successiva">→</button>
                  </div>
                <?php endif ?>
                <ul class="accordion__media-track<?= $isCarousel ? ' accordion__media-track--carousel' : '' ?>">
                  <?php foreach ($images as $image): ?>
                    <li class="accordion__media-item">
                      <?php snippet('atoms/picture', [
                        'image'     => $image,
                        'alt'       => '',
                        'sizes'     => [400, 800, 1024],
                        'sizesAttr' => $imageSizesAttr,
                        'class'     => 'accordion__img',
                      ]) ?>
                    </li>
                  <?php endforeach ?>
                </ul>
              </div>
            <?php endif ?>
            <div class="accordion__body">
              <div class="accordion__body-inner">
                <?= $body($item) ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  <?php endforeach ?>
</div>
<?= js('assets/js/accordion.js', ['defer' => true]) ?>
<?= js('assets/js/accordion-media-carousel.js', ['defer' => true]) ?>
