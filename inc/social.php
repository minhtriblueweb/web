<?php
/*
<div class="social-contact toolbar-custom">
  <?php $show_social = $social->show_social("hienthi"); ?>
  <?php if ($show_social): ?>
  <?php while ($result_social = $show_social->fetch_assoc()): ?>
  <a href="<?= $result_social['link'] ?>" target="_blank" class="d-block hvr-icon-rotate"><img
      class="transition hvr-icon" src="<?= BASE_ADMIN . UPLOADS . $result_social['file'] ?>"
      alt="<?= $result_social['name'] ?>">
  </a>
  <?php endwhile; ?>
  <?php endif; ?>
</div>
*/
?>
<div class="floating-support">
  <?php $show_social = $social->show_social("hienthi"); ?>
  <?php if ($show_social): ?>
  <?php while ($result_social = $show_social->fetch_assoc()): ?>
  <a href="<?= $result_social['link'] ?>" class="floating-support__item" target="_blank">
    <div class="floating-support__item__icon">
      <img src="<?= BASE_ADMIN . UPLOADS . $result_social['file'] ?>" alt="<?= $result_social['name'] ?>">
    </div>
    <div class="floating-support__item__content">
      <p><b><?= $result_social['name'] ?></b></p>
      <span><?= $result_social['desc'] ?></span>
    </div>
  </a> <?php endwhile; ?>
  <?php endif; ?>
</div>