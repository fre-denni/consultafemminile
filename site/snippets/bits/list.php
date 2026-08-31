<!-- Aggiungi icone e items se definite -->
<nav class="list">
  <menu>
    <?php foreach($items as $item): ?>
      <li>
        <a href="<?= $item->url()?>">
          <?= $item->title() ?>
        </a>
      </li>
    <?php endforeach ?>
  </menu>
</nav>