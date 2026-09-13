<?php
/**
 * @var string $name Nome del file SVG in assets/icons (senza estensione),
 *   es. 'mailbox', 'facebook-logo-fill', 'linkedin-logo-fill'
 */
?>
<span class="icon" aria-hidden="true">
  <?= svg('assets/icons/' . $name . '.svg') ?>
</span>
