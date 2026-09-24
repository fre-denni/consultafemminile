<?php
/**
 * @var \Kirby\Cms\Page $page Pagina "output" (vedi
 *   site/blueprints/pages/output.yml): un articolo.
 *
 * Intestazione dell'articolo: tipologia, titolo, introduzione,
 * autore/i, data e condividi sui social — a fianco, se presente, la
 * STESSA immagine di copertina già usata dalla card (vedi
 * bits/output-card, $page->images()->first()), non una nuova. Senza
 * immagine il testo resta centrato su una colonna sola: entrambe le
 * varianti sono nel riferimento Figma, stesso componente.
 */
$categoria = trim((string) $page->categoria());
$intro     = trim((string) $page->intro());
$data      = $page->data()->toItalianDate();
$image     = $page->images()->first();

// Membro della Consulta: link fisso alla pagina "Le Persone" (vedi
// site/blueprints/pages/persone.yml) — non è un profilo individuale,
// solo l'elenco. Esterno: link facoltativo scelto dall'editor.
$personeUrl = $site->find('chi-siamo/persone')?->url();

$autori = [];
foreach ($page->autori()->toStructure() as $autore) {
  $nome = trim((string) $autore->nome());
  if ($nome === '') {
    continue;
  }

  $isMember = $autore->membro_consulta()->toBool();
  $link     = $isMember ? $personeUrl : trim((string) $autore->link());

  $autori[] = ['nome' => $nome, 'link' => $link !== '' ? $link : null];
}

/**
 * "A e B" per due autori, altrimenti solo virgole ("A, B, C") — mai una
 * "e" prima dell'ultimo con tre o più nomi (richiesta esplicita).
 */
$renderAutore = fn (array $a) => $a['link']
  ? '<a href="' . html($a['link']) . '" class="article-header__author-link">' . html($a['nome']) . '</a>'
  : '<span class="article-header__author-link">' . html($a['nome']) . '</span>';

$autoriCount = count($autori);
if ($autoriCount === 2) {
  $autoriHtml = $renderAutore($autori[0]) . ' e ' . $renderAutore($autori[1]);
} else {
  $autoriHtml = implode(', ', array_map($renderAutore, $autori));
}

$shareUrl = $page->url();
?>
<div class="article-header block-full">
  <div class="article-header__text">
    <div class="article-header__group">
      <?php if ($categoria !== ''): ?>
        <p class="article-header__category"><?= html($categoria) ?></p>
      <?php endif ?>
      <h1 class="article-header__title"><?= html($page->title()) ?></h1>
    </div>
    <?php if ($intro !== ''): ?>
      <p class="article-header__lead"><?= html($intro) ?></p>
    <?php endif ?>
    <div class="article-header__meta">
      <?php if ($autoriCount > 0): ?>
        <p class="article-header__authors">Di <?= $autoriHtml ?></p>
      <?php endif ?>
      <?php if ($data !== ''): ?>
        <p class="article-header__date"><?= html($data) ?></p>
      <?php endif ?>
      <button
        type="button"
        class="article-header__share-button"
        data-copy-link="<?= html($shareUrl) ?>"
      >
        <span data-copy-label aria-live="polite">Condividi</span>
        <span class="article-header__share-icon article-header__share-icon--idle">
          <?php snippet('atoms/icon', ['name' => 'share-arrow']) ?>
        </span>
        <span class="article-header__share-icon article-header__share-icon--done">
          <?php snippet('atoms/icon', ['name' => 'check']) ?>
        </span>
      </button>
    </div>
  </div>
  <?php if ($image): ?>
    <div class="article-header__media">
      <?php snippet('atoms/picture', [
        'image'     => $image,
        'alt'       => '',
        'sizes'     => [500, 800, 1200, 1600],
        'sizesAttr' => '(min-width: 1024px) 45vw, 100vw',
        'class'     => 'article-header__img',
      ]) ?>
    </div>
  <?php endif ?>
</div>
<?= js('assets/js/article-share.js', ['defer' => true]) ?>
