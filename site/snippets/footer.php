<?php use Kirby\Toolkit\Obj; ?>

<?php
// Nome file in assets/icons per ciascuna piattaforma social (chiave in minuscolo)
$socialIcons = [
  'facebook' => 'facebook-logo-fill',
  'linkedin' => 'linkedin-logo-fill',
];

$socialItems = $site->social()->toStructure()->map(fn($s) => new Obj([
  'title' => $s->platform()->value(),
  'url'   => $s->url()->value(),
  'icon'  => $socialIcons[strtolower($s->platform()->value())] ?? null,
]))->values();
?>

<footer class="footer">
  <?php snippet('atoms/logo', ['type' => 'tipografia']) ?>
  <a href="mailto:consfim@gmail.com" class="button">
    <?php snippet('atoms/icon', ['name' => 'mailbox']) ?>
    consfim@gmail.com
  </a>
  <?php snippet('bits/list', ['items' => $site->children()->listed()]) ?>
  <?php snippet('bits/list', ['items' => $site->find('chi-siamo/persone', 'chi-siamo/associazioni', 'statuto', 'terms', 'carbon')]) ?>
  <?php snippet('bits/list', ['items' => $socialItems]) ?>
  <p>
    &copy;<?= date('Y') ?>. Consulta Femminile Interassociativa di Milano. <br/>
    Website designed by <a href="https://federicodenni.com" target="_blank" rel="noopener noreferrer">Federico Denni</a>.
  </p>
</footer>
</body>
</html>