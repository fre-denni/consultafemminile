<?php
/**
 * @var string                    $label      Etichetta a sinistra del riquadro (default "Contattaci")
 * @var \Kirby\Cms\File|null      $image      Foto accanto al form (opzionale; su mobile sparisce)
 * @var string                    $credit     Credito della foto, HTML inline con eventuali link
 * @var string                    $background Sfondo della sezione: 'default' (bianco), 'tinted'
 *   (panna) o 'blue' (azzurro) — stesso vocabolario di blueprints/fields/background.yml.
 *   Come blocco (blocks/contact-form) lo sfondo lo applica già atoms/block-container.php:
 *   qui resta 'default'.
 * @var string                    $id         Ancora della sezione, univoca per pagina: è dove
 *   si torna dopo un invio senza JavaScript.
 *
 * Componente autonomo, usabile in qualunque template o come blocco. L'invio
 * (validazione, email) lo gestisce la route "invia-messaggio" in
 * site/plugins/contact-form; il contatore delle parole e l'invio senza
 * ricaricare la pagina, assets/js/contact-form.js.
 */
$label      ??= 'Contattaci';
$image      ??= null;
$credit     ??= '';
$background ??= 'default';
$id         ??= 'contatti';

// Esito di un invio fatto senza JavaScript (vedi contactFormReturnUrl()).
$statusCode    = (string) get('contatto');
$statusMessage = contactFormMessage($statusCode);
$maxWords      = CONTACT_FORM_MAX_WORDS;
?>
<section
  id="<?= html($id) ?>"
  class="contact-form-section block-full<?= $background !== 'default' ? ' contact-form-section--' . html($background) : '' ?>"
>
  <div class="contact-form-section__inner">
    <div class="contact-form-section__label">
      <?php snippet('atoms/section-label', ['text' => $label]) ?>
    </div>
    <div class="contact-form">
      <form
        class="contact-form__form"
        method="post"
        action="<?= url('invia-messaggio') ?>"
        data-contact-form
      >
        <input type="hidden" name="csrf" value="<?= csrf() ?>">
        <input type="hidden" name="ritorno" value="<?= html($page->url()) ?>">
        <input type="hidden" name="ancora" value="<?= html($id) ?>">

        <?php /* Trappola per i bot (vedi contactFormProcess()): fuori schermo, non display:none, così i bot più semplici la compilano comunque. */ ?>
        <div class="contact-form__trap" aria-hidden="true">
          <label>Non compilare questo campo <input type="text" name="sito_web" tabindex="-1" autocomplete="off"></label>
        </div>

        <div class="contact-form__fields">
          <div class="contact-form__field">
            <label class="contact-form__label" for="<?= html($id) ?>-email">Email</label>
            <input
              class="contact-form__input"
              type="email"
              id="<?= html($id) ?>-email"
              name="email"
              placeholder="Inserisci la tua mail"
              autocomplete="email"
              required
            >
          </div>
          <div class="contact-form__field">
            <label class="contact-form__label" for="<?= html($id) ?>-oggetto">Oggetto</label>
            <input
              class="contact-form__input"
              type="text"
              id="<?= html($id) ?>-oggetto"
              name="oggetto"
              maxlength="150"
              required
            >
          </div>
          <div class="contact-form__field">
            <label class="contact-form__label" for="<?= html($id) ?>-messaggio">Il tuo messaggio</label>
            <textarea
              class="contact-form__input contact-form__textarea"
              id="<?= html($id) ?>-messaggio"
              name="messaggio"
              rows="2"
              placeholder="Scrivi il tuo messaggio qui."
              required
            ></textarea>
            <p class="contact-form__counter" data-contact-counter>0/<?= $maxWords ?> parole</p>
          </div>
        </div>

        <button type="submit" class="contact-form__submit">Manda il messaggio</button>
        <p
          class="contact-form__status"
          role="status"
          aria-live="polite"
          data-contact-status
          <?= $statusMessage !== '' ? 'data-kind="' . ($statusCode === 'ok' ? 'ok' : 'error') . '"' : 'hidden' ?>
        ><?= html($statusMessage) ?></p>
      </form>

      <?php if ($image): ?>
        <figure class="contact-form__media">
          <?php snippet('atoms/picture', [
            'image'     => $image,
            'alt'       => '',
            'sizes'     => [500, 800, 1200],
            'sizesAttr' => '(min-width: 1062px) 27rem, 40vw',
            'class'     => 'contact-form__img',
          ]) ?>
          <?php if (trim($credit) !== ''): ?>
            <figcaption class="contact-form__credit"><?= $credit ?></figcaption>
          <?php endif ?>
        </figure>
      <?php endif ?>
    </div>
  </div>
</section>
<?= js('assets/js/contact-form.js', ['defer' => true]) ?>
