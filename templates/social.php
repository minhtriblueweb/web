<div class="floating-support" data-aos="fade-left" data-aos-anchor="#example-anchor" data-aos-offset="500"
  data-aos-duration="500">
  <?php
  $sameAs = [];
  $show_social = $fn->show_data([
    'table'  => 'tbl_social',
    'status' => 'hienthi',
    'select' => "file, link, name{$lang}, desc{$lang}"
  ]);
  ?>
  <?php if (!empty($show_social)): ?>
    <?php foreach ($show_social as $row_social): ?>
      <?php
      $name = $row_social['name' . $lang];
      $desc = $row_social['desc' . $lang];
      $link = $row_social['link'];
      $sameAs[] = $link;
      ?>
      <a href="<?= $link ?>" class="floating-support__item" target="_blank">
        <div class="floating-support__item__icon">
          <?= $fn->getImage([
            'file'   => $row_social['file'],
            'alt'    => $name,
            'title'  => $name,
            'class'  => 'tada'
          ]) ?>
        </div>
        <div class="floating-support__item__content">
          <p><b><?= $name ?></b></p>
          <span><?= $desc ?></span>
        </div>
      </a>
    <?php endforeach; ?>
  <?php endif; ?>
</div>
