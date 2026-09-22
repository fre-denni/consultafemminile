<?php
/**
 * @var iterable<array{
 *   id: string,
 *   anno: string,
 *   etichetta?: string,
 *   description?: string,
 *   images?: \Kirby\Cms\File[],
 * }> $items Capitoli della timeline (vedi site/templates/timeline.php).
 *
 * Stessa meccanica di apertura/chiusura di bits/chapter-accordion — riusa
 * lo stesso script (assets/js/chapter-accordion.js opera solo sugli
 * attributi data-accordion*, è già agnostico al contenuto) — ma un
 * layout diverso, non condiviso con quel componente: il numero di
 * immagini in $item['images'] decide da sé cosa mostrare, non un campo
 * "tipo" separato da tenere sincronizzato a mano — vedi
 * site/blueprints/pages/timeline.yml (max 7). Zero immagini: niente
 * media. Una sola: una foto singola (una presidenza). Da due in su: un
 * piccolo carosello con le frecce sempre visibili (vedi
 * bits/patrocinio-carousel, stesso trattamento — non solo su mobile
 * come bits/theme-carousel), gestito da assets/js/timeline-media-carousel.js.
 */
$items = is_array($items) ? $items : iterator_to_array($items);

if (count($items) === 0) return;
?>
<div class="timeline-accordion" data-accordion>
  <?php foreach ($items as $item): ?>
    <?php
    $etichetta   = trim((string) ($item['etichetta'] ?? ''));
    $description = trim((string) ($item['description'] ?? ''));
    $images      = array_values($item['images'] ?? []);
    $panelId     = 'timeline-panel-' . $item['id'];
    $isCarousel  = count($images) > 1;
    ?>
    <div class="timeline-accordion__item" data-accordion-item>
      <h3 class="timeline-accordion__heading">
        <button
          type="button"
          class="timeline-accordion__header"
          aria-expanded="false"
          aria-controls="<?= html($panelId) ?>"
          data-accordion-toggle
        >
          <?= html($item['anno']) ?>
        </button>
      </h3>
      <div class="timeline-accordion__panel" id="<?= html($panelId) ?>" data-accordion-panel>
        <div class="timeline-accordion__panel-inner">
          <div class="timeline-accordion__content">
            <?php if (count($images) > 0): ?>
              <div class="timeline-accordion__media">
                <?php if ($isCarousel): ?>
                  <div class="timeline-accordion__media-controls">
                    <button type="button" class="timeline-accordion__media-button timeline-accordion__media-button--prev" aria-label="Foto precedente">←</button>
                    <button type="button" class="timeline-accordion__media-button timeline-accordion__media-button--next" aria-label="Foto successiva">→</button>
                  </div>
                <?php endif ?>
                <ul class="timeline-accordion__media-track<?= $isCarousel ? ' timeline-accordion__media-track--carousel' : '' ?>">
                  <?php foreach ($images as $image): ?>
                    <li class="timeline-accordion__media-item">
                      <?php snippet('atoms/picture', [
                        'image'     => $image,
                        'alt'       => '',
                        'sizes'     => [400, 700, 1000],
                        'sizesAttr' => '(min-width: 768px) 30vw, 90vw',
                        'class'     => 'timeline-accordion__img',
                      ]) ?>
                    </li>
                  <?php endforeach ?>
                </ul>
              </div>
            <?php endif ?>
            <div class="timeline-accordion__body">
              <div class="timeline-accordion__body-inner">
                <?php if ($etichetta !== ''): ?>
                  <p class="timeline-accordion__label"><?= html($etichetta) ?></p>
                <?php endif ?>
                <?php if ($description !== ''): ?>
                  <p class="timeline-accordion__description"><?= html($description) ?></p>
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
<?= js('assets/js/timeline-media-carousel.js', ['defer' => true]) ?>
