<?php
/**
 * Overlay condiviso da tutta la pagina: una sola istanza, aperta da
 * qualunque link con l'attributo "data-lightbox" (vedi
 * assets/js/lightbox.js e bits/patrocinio-carousel.php per un
 * esempio d'uso). Se serve altrove basta aggiungere lo stesso
 * attributo a un nuovo link — niente da toccare qui.
 */
?>
<div class="lightbox" data-lightbox-overlay hidden>
  <button type="button" class="lightbox__close" data-lightbox-close aria-label="Chiudi">
    &times;
  </button>
  <img class="lightbox__img" data-lightbox-image alt="">
</div>
<?= js('assets/js/lightbox.js', ['defer' => true]) ?>
