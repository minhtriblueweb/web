<div class="floating-support" data-aos="fade-left" data-aos-anchor="#example-anchor" data-aos-offset="500"
  data-aos-duration="500">
  <?php
  $show_social = $functions->show_data([
    'table' => 'tbl_social',
    'status' => 'hienthi'
  ]); ?>
  <?php if ($show_social): ?>
    <?php while ($row_social = $show_social->fetch_assoc()): ?>
      <a href="<?= $row_social['link'] ?>" class="floating-support__item" target="_blank">
        <div class="floating-support__item__icon">
          <img
            src="<?= empty($row_social['file']) ? BASE_ADMIN . "assets/img/noimage.png" : BASE_ADMIN . UPLOADS . $row_social['file']; ?>"
            alt="<?= $row_social['name'] ?>" class="tada">
        </div>
        <div class="floating-support__item__content">
          <p><b><?= $row_social['name'] ?></b></p>
          <span><?= $row_social['desc'] ?></span>
        </div>
      </a>
    <?php endwhile; ?>
  <?php endif; ?>
</div>
