<?php use Kirby\Toolkit\Obj; ?>

<?php
$socialItems = $site->social()->toStructure()->map(fn($s) => new Obj([
  'title' => $s->platform()->value(),
  'url'   => $s->url()->value(),
]))->values();
?>

<footer class="footer">
  <a href="<?= $site->url() ?>" class="logo"><?= $site->title() ?></a>
  <a href="mailto:consfim@gmail.com" class="button">consfim@gmail.com</a>
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