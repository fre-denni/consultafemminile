<?php snippet('header', slots: true) ?>
  <?php slot('stylesheet')?>
    <?= $slots->stylesheet() ?>
  <?php endslot() ?>
  <?php slot('scripts')?>
    <?= $slots->scripts() ?>
  <?php endslot() ?>
<?php endsnippet() ?>

<?php snippet('main', slots: true) ?>
  <?php slot() ?>
    <?= $slot ?>
  <?php endslot() ?>
<?php endsnippet() ?>

<?php snippet('footer') ?>