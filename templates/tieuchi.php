<div class="wrap-criterion">
  <div class="wrap-content">
    <div class="slick-criterion slick-d-none">
      <?php $show_tieuchi = $tieuchi->show_tieuchi_index();
      if ($show_tieuchi): ?>
        <?php while ($result_tieuchi = $show_tieuchi->fetch_assoc()): ?>
          <div>
            <div class="item-criterion hvr-icon-rotate">
              <div class="images">
                <a class="hvr-icon" title="<?= $result_tieuchi['name'] ?>">
                  <img
                    src="<?= empty($result_tieuchi['file']) ? BASE_ADMIN . "assets/img/noimage.png" : BASE_ADMIN . UPLOADS . $result_tieuchi['file']; ?>"
                    width="40" height="40" />
                </a>
                <h3>
                  <span class="text-split"><?= $result_tieuchi['name'] ?></span>
                </h3>
              </div>
              <p class="text-split">
                <?= $result_tieuchi['desc'] ?>
              </p>
            </div>
          </div>
        <?php endwhile; ?>
      <?php endif; ?>
    </div>
  </div>
</div>
