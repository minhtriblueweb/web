<div class="floating-support" data-aos="fade-left" data-aos-anchor="#example-anchor" data-aos-offset="500"
  data-aos-duration="500">
  <?php $show_social = $social->show_social("hienthi"); ?>
  <?php if ($show_social): ?>
  <?php while ($result_social = $show_social->fetch_assoc()): ?>
  <a href="<?= $result_social['link'] ?>" class="floating-support__item" target="_blank">
    <div class="floating-support__item__icon">
      <img
        src="<?= empty($result_social['file']) ? BASE_ADMIN . "assets/img/noimage.png" : BASE_ADMIN . UPLOADS . $result_social['file']; ?>"
        alt="<?= $result_social['name'] ?>" class="tada">
    </div>
    <div class="floating-support__item__content">
      <p><b><?= $result_social['name'] ?></b></p>
      <span><?= $result_social['desc'] ?></span>
    </div>
  </a>
  <?php endwhile; ?>
  <?php endif; ?>
</div>