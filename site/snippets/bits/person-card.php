<?php
/**
 * @var \Kirby\Cms\StructureObject $person Riga del campo structure
 *   "persone" (vedi site/blueprints/pages/persone.yml): ->nome(),
 *   ->cognome(), ->ruolo(), ->associazione(), ->email(),
 *   ->foto()->toFile().
 *
 * A riposo mostra solo la foto a piena card; in hover (o focus, da
 * tastiera) la foto si restringe a metà e appaiono nome/ruolo/
 * associazione/contatto nell'altra metà. A differenza di
 * bits/theme-carousel qui l'hover di una card non tocca le altre, quindi
 * basta puro CSS — niente hover-intent via JS (vedi person-card.css).
 * Su mobile i dettagli restano sempre visibili (niente hover su touch).
 */
$image = $person->foto()->toFile();
if (!$image) return;

$name = trim($person->nome() . ' ' . $person->cognome());
$role = trim((string) $person->ruolo()) ?: 'Delegata';
$email = trim((string) $person->email());

// Il dato può arrivare in qualunque case (tutto maiuscolo dall'elenco
// originale, o come lo digita chi lo inserisce da Panel): la pillola
// mostra sempre "Title Case", mai TUTTO MAIUSCOLO — vedi anche il
// commento su .person-card__badge in person-card.css sul perché non
// basta un text-transform CSS per ottenerlo.
$association = trim((string) $person->associazione());
$association = $association !== '' ? mb_convert_case($association, MB_CASE_TITLE, 'UTF-8') : '';
?>
<article class="person-card">
  <div class="person-card__media">
    <?php snippet('atoms/picture', [
      'image'     => $image,
      'sizes'     => [320, 500, 700],
      'sizesAttr' => '(min-width: 768px) 20vw, 45vw',
      'class'     => 'person-card__img',
    ]) ?>
  </div>
  <div class="person-card__content">
    <p class="person-card__name"><?= html($name) ?></p>
    <p class="person-card__role"><?= html($role) ?></p>
    <div class="person-card__meta">
      <?php if ($association !== ''): ?>
        <span class="person-card__badge"><?= html($association) ?></span>
      <?php endif ?>
      <?php if ($email !== ''): ?>
        <a href="mailto:<?= html($email) ?>" class="person-card__mail">
          <?php snippet('atoms/icon', ['name' => 'envelope']) ?>
          Mail
        </a>
      <?php endif ?>
    </div>
  </div>
</article>
