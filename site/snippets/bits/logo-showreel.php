<?php
/**
 * @var iterable $items   Associazioni (righe structure, vedi
 *   site/collections/associazioni.php) — qui si usa solo il logo, gli
 *   altri campi (foto, descrizione, delegate...) sono per quando
 *   costruiremo la pagina "Le Associazioni".
 * @var string   $heading    Titolo centrato sopra lo showreel (opzionale).
 * @var string   $background Sfondo della sezione: 'default' (bianco), 'tinted' (panna) o
 *   'blue' (azzurro) — stesso vocabolario del campo condiviso blueprints/fields/background.yml.
 *   Facoltativo, di default 'default'.
 *
 * Striscia di loghi che scorre da destra verso sinistra in loop
 * infinito: la lista è duplicata due volte (vedi
 * .logo-showreel__group più sotto), e assets/js/logo-showreel.js
 * trasla il track di preciso della larghezza di un gruppo prima di
 * "riavvolgere" l'offset — quando questo succede la seconda copia è
 * già esattamente dove la prima ha iniziato, quindi il loop è
 * invisibile. Il JS (non una @keyframes CSS) serve perché la
 * larghezza del gruppo cambia via via che i loghi finiscono di
 * caricare, e viene ricalcolata a ogni load — vedi il file per i
 * dettagli.
 */
$items = $items->filter(fn ($assoc) => $assoc->logo()->toFile() !== null);

if ($items->count() === 0) return;

$heading    ??= '';
$background ??= 'default';
?>
<section class="logo-showreel-section block-full<?= $background !== 'default' ? ' logo-showreel-section--' . html($background) : '' ?>">
  <?php if ($heading !== ''): ?>
    <h2 class="logo-showreel-section__heading"><?= html($heading) ?></h2>
  <?php endif ?>
  <div class="logo-showreel">
    <div class="logo-showreel__track">
      <?php for ($i = 0; $i < 2; $i++): ?>
        <ul class="logo-showreel__group"<?= $i === 1 ? ' aria-hidden="true"' : '' ?>>
          <?php foreach ($items as $assoc): ?>
            <?php
            $logo   = $assoc->logo()->toFile();
            $alt    = trim((string) $assoc->nome_completo()) ?: trim((string) $assoc->nome_breve());
            $website = trim((string) $assoc->sito_web());
            // Derivati ridimensionati, non l'originale (fino a 4 MB per logo):
            // 240/320px coprono i 165px di larghezza massima sullo schermo
            // (90px su mobile), anche a 2x. Non lazy di proposito: la striscia scorre via
            // JS (transform), non con lo scroll, quindi il lazy-load nativo
            // caricherebbe i loghi fuori dal riquadro solo quando ci
            // entrano — con lo spazio vuoto in mezzo; i derivati sono
            // comunque pochi KB l'uno.
            $img    = snippet('atoms/picture', [
              'image'     => $logo,
              'alt'       => $i === 0 ? $alt : '',
              'sizes'     => [240, 320],
              'sizesAttr' => '(max-width: 768px) 90px, 160px',
              'class'     => 'logo-showreel__img',
              'lazy'      => false,
            ], return: true);
            ?>
            <li class="logo-showreel__item">
              <?php if ($website !== ''): ?>
                <a
                  href="<?= html($website) ?>"
                  target="_blank"
                  rel="noopener noreferrer"
                  class="logo-showreel__link"
                  <?php if ($i === 1): ?>tabindex="-1"<?php endif ?>
                >
                  <?= $img ?>
                </a>
              <?php else: ?>
                <span class="logo-showreel__link"><?= $img ?></span>
              <?php endif ?>
            </li>
          <?php endforeach ?>
        </ul>
      <?php endfor ?>
    </div>
  </div>
</section>
<?= js('assets/js/logo-showreel.js', ['defer' => true]) ?>
