<div class="floating-support" data-aos="fade-left" data-aos-anchor="#example-anchor" data-aos-offset="500"
  data-aos-duration="500">
  <?php
  $sameAs = [];
  $show_social = $fn->show_data([
    'table' => 'tbl_social',
    'status' => 'hienthi'
  ]); ?>
  <?php if ($show_social): ?>
    <?php while ($row_social = $show_social->fetch_assoc()): ?>
      <?php $sameAs[] = $row_social['link']; ?>
      <a href="<?= $row_social['link'] ?>" class="floating-support__item" target="_blank">
        <div class="floating-support__item__icon">
          <?= $fn->getImage([
            'file'   => $row_social['file'],
            'alt'    => $row_social['namevi'],
            'title'  => $row_social['namevi'],
            'class'  => 'tada'
          ]) ?>
        </div>
        <div class="floating-support__item__content">
          <p><b><?= $row_social['namevi'] ?></b></p>
          <span><?= $row_social['descvi'] ?></span>
        </div>
      </a>
    <?php endwhile; ?>
  <?php endif; ?>
</div>
