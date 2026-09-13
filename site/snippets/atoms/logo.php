<?php
/**
 * @var string $type Variante iniziale del logo:
 *   - 'esteso'     → icona + testo (stato di default nell'header)
 *   - 'logo'       → solo icona
 *   - 'tipografia' → solo testo (footer)
 *
 * Su desktop/mobile, per l'uso nell'header, il passaggio tra "esteso" e
 * "solo icona" (scroll / apertura menu) è gestito dinamicamente via CSS
 * scope su .header — vedi header.css — non da questo parametro, che
 * imposta solo lo stato con cui il componente viene renderizzato la
 * prima volta (server-side).
 */
$type ??= 'esteso';
?>
<a
  href="<?= $site->url() ?>"
  class="logo logo--<?= $type ?>"
  aria-label="<?= html($site->title()) ?>"
>
  <span class="logo__icon" aria-hidden="true">
    <?= svg('assets/logo.svg') ?>
  </span>
  <span class="logo__text">
    <span class="logo__text-primary">Consulta Femminile</span> <br/> <span class="logo__text-secondary">di Milano</span>
  </span>
</a>