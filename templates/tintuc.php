<?php if ($show_tintuc = $news->show_news_by_type('tintuc', 'hienthi', 'noibat')): ?>
  <?php if ($show_tintuc->num_rows > 0): ?>
    <div class="wrap-service" data-aos="fade-up" data-aos-duration="1000">
      <div class="wrap-content">
        <div class="title-main">
          <h2>TIN TỨC MỚI NHẤT</h2>
        </div>
        <div class="slick-service slick-d-none">
          <?php while ($resule_tintuc = $show_tintuc->fetch_assoc()): ?>
            <div>
              <div class="item-service">
                <div class="images">
                  <a class="scale-img hover-glass text-decoration-none" href="<?= $resule_tintuc['slugvi'] ?>" title="<?= $resule_tintuc['namevi'] ?>">
                    <img class="w-100"
                      src="<?= empty($resule_tintuc['file']) ? BASE_ADMIN . "assets/img/noimage.png" : BASE_ADMIN . UPLOADS . $resule_tintuc['file'] ?>"
                      alt="<?= $resule_tintuc['namevi'] ?>" />
                  </a>
                </div>
                <div class="content">
                  <h3>
                    <a class="text-split" href="<?= $resule_tintuc['slugvi'] ?>"><?= $resule_tintuc['namevi'] ?></a>
                  </h3>
                </div>
              </div>
            </div>
          <?php endwhile; ?>
        </div>
      </div>
    </div>
  <?php endif; ?>
<?php endif; ?>
