<?php use Kirby\Toolkit\Obj; ?>

<?php
// L'icona (nome file in assets/icons) la sceglie l'editor dal Panel, campo
// "Icona" della struttura Social in site.yml — vuota, il link resta testo.
$socialItems = $site->social()->toStructure()->map(fn($s) => new Obj([
  'title' => $s->platform()->value(),
  'url'   => $s->url()->value(),
  'icon'  => $s->icon()->value() ?: null,
]))->values();

// "Carbon Footprint" punta al report pubblico di Website Carbon Calculator,
// non più a una pagina del sito (vedi bits/list per il supporto a ->target()).
$secondaryLinks   = $site->find('chi-siamo/persone', 'chi-siamo/associazioni', 'statuto')->values();
$secondaryLinks[] = new Obj([
  'title'  => 'Carbon Footprint',
  'url'    => 'https://www.websitecarbon.com/website/consultafemminile-org/',
  'target' => '_blank',
]);
?>

<footer class="footer">
  <?php snippet('atoms/logo', ['type' => 'tipografia']) ?>
  <a href="mailto:consfim@gmail.com" class="button">
    <?php snippet('atoms/icon', ['name' => 'mailbox']) ?>
    consfim@gmail.com
  </a>
  <?php snippet('bits/list', ['items' => $site->children()->listed()->not('home')]) ?>
  <?php snippet('bits/list', ['items' => $secondaryLinks]) ?>
  <?php snippet('bits/list', ['items' => $socialItems]) ?>
  <p>
    &copy;<?= date('Y') ?>. Consulta Femminile Interassociativa di Milano. <br/>
    Website designed by <a href="https://federicodenni.com" target="_blank" rel="noopener noreferrer">Federico Denni</a>.
  </p>
</footer>
</body>
</html>