<?php
/**
 * @var \Kirby\Cms\StructureObject $association Riga del campo structure
 *   "associazioni" (vedi site/blueprints/pages/associazioni.yml): ->logo()->toFile(),
 *   ->foto()->toFile(), ->nome_breve(), ->nome_completo(), ->descrizione(),
 *   ->sito_web(), ->delegate() (elenco nomi separati da virgola).
 *
 * Modale coi dettagli dell'associazione, aperta dal bottone di
 * bits/association-card (stesso id, costruito da $association->id()).
 * Un elemento <dialog> nativo: si apre/chiude via showModal()/close()
 * in assets/js/association-modal.js, che gestisce anche il click sullo
 * sfondo — l'Escape e il focus-trap li dà già il browser gratis, niente
 * da reimplementare (a differenza di atoms/lightbox, scritto prima che
 * questo pattern fosse usato altrove nel sito).
 *
 * Le delegate sono salvate come semplice elenco di nomi (vedi il campo
 * "delegate" in associazioni.yml, un multiselect che pesca da
 * persone.yml ma non conserva un riferimento — solo il testo "Nome
 * Cognome"): per il link "mailto" qui sotto le confrontiamo per nome
 * con collection('persone'), l'unico posto dove l'email esiste
 * davvero. Se una non viene trovata (nome cambiato, persona rimossa)
 * il nome resta comunque visibile, solo senza link.
 */
$logo   = $association->logo()->toFile();
$foto   = $association->foto()->toFile();
$sito   = trim((string) $association->sito_web());
$nomeBreve    = trim((string) $association->nome_breve());
$nomeCompleto = trim((string) $association->nome_completo());

$delegateNomi = array_filter(array_map('trim', $association->delegate()->split(',')));
$persone      = collection('persone');
$delegate     = array_map(function ($nome) use ($persone) {
  $match = $persone->filter(fn ($person) => trim($person->nome() . ' ' . $person->cognome()) === $nome)->first();
  return ['nome' => $nome, 'email' => $match ? trim((string) $match->email()) : ''];
}, $delegateNomi);

$modalId = 'association-' . $association->id();
?>
<dialog id="<?= $modalId ?>" class="association-modal" aria-labelledby="<?= $modalId ?>-heading">
  <button type="button" class="association-modal__close" data-modal-close aria-label="Chiudi">
    <?php snippet('atoms/icon', ['name' => 'arrow-left']) ?>
  </button>

  <?php if ($foto): ?>
    <div class="association-modal__hero">
      <?php snippet('atoms/picture', [
        'image'     => $foto,
        'alt'       => '',
        'sizes'     => [500, 900, 1300],
        'sizesAttr' => '(min-width: 768px) 60vw, 100vw',
        'class'     => 'association-modal__hero-img',
        'lazy'      => false,
      ]) ?>
      <?php if ($logo): ?>
        <div class="association-modal__logo">
          <?php snippet('atoms/picture', [
            'image'     => $logo,
            'alt'       => '',
            // Stesse dimensioni di bits/association-card (non 80/120,
            // più adatte alla resa qui): riusa gli stessi derivati già
            // generati per la card, invece di farne generare di nuovi
            // — il server ha un memory_limit basso e generare thumb da
            // un logo sorgente pesante può esaurirlo (vedi PHP Fatal
            // error osservato in test: "Allowed memory size of
            // 134217728 bytes exhausted" dentro SimpleImage).
            'sizes'     => [160, 240],
            'sizesAttr' => '56px',
            'class'     => 'association-modal__logo-img',
            'lazy'      => false,
          ]) ?>
        </div>
      <?php endif ?>
      <p class="association-modal__heading" id="<?= $modalId ?>-heading">
        <strong><?= html($nomeBreve) ?>.</strong> <?= html($nomeCompleto) ?>
      </p>
    </div>
  <?php else: ?>
    <div class="association-modal__header">
      <?php if ($logo): ?>
        <div class="association-modal__logo">
          <?php snippet('atoms/picture', [
            'image'     => $logo,
            'alt'       => '',
            'sizes'     => [160, 240],
            'sizesAttr' => '56px',
            'class'     => 'association-modal__logo-img',
            'lazy'      => false,
          ]) ?>
        </div>
      <?php endif ?>
      <p class="association-modal__heading association-modal__heading--dark" id="<?= $modalId ?>-heading">
        <strong><?= html($nomeBreve) ?>.</strong> <?= html($nomeCompleto) ?>
      </p>
    </div>
  <?php endif ?>

  <div class="association-modal__body">
    <div class="association-modal__description">
      <?= $association->descrizione()->kirbytext() ?>
    </div>
    <div class="association-modal__aside">
      <?php if (count($delegate) > 0): ?>
        <p class="association-modal__aside-label">Delegate</p>
        <ul class="association-modal__contacts">
          <?php foreach ($delegate as $d): ?>
            <li>
              <?php if ($d['email'] !== ''): ?>
                <a href="mailto:<?= html($d['email']) ?>" class="association-modal__contact">
                  <?php snippet('atoms/icon', ['name' => 'envelope']) ?>
                  <?= html($d['nome']) ?>
                </a>
              <?php else: ?>
                <span class="association-modal__contact">
                  <?php snippet('atoms/icon', ['name' => 'envelope']) ?>
                  <?= html($d['nome']) ?>
                </span>
              <?php endif ?>
            </li>
          <?php endforeach ?>
        </ul>
      <?php endif ?>
      <?php if ($sito !== ''): ?>
        <a href="<?= html($sito) ?>" target="_blank" rel="noopener noreferrer" class="association-modal__contact">
          <?php snippet('atoms/icon', ['name' => 'globe']) ?>
          Sito Web
        </a>
      <?php endif ?>
    </div>
  </div>
</dialog>
