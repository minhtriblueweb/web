<?php
$show_tintuc = $fn->show_data([
  'table' => 'tbl_news',
  'status' => 'hienthi,noibat',
  'type'   => 'tintuc'
]);
$has_tintuc = ($show_tintuc && $show_tintuc->num_rows > 0);
?>
<?php if ($has_tintuc): ?>
  <div class="wrap-service" data-aos="fade-up" data-aos-duration="500">
    <div class="wrap-content">
      <div class="title-main">
        <h2>TIN TỨC MỚI NHẤT</h2>
      </div>
      <div class="slick-service slick-d-none">
        <?php while ($row_tintuc = $show_tintuc->fetch_assoc()): ?>
          <div class="item-service">
            <div class="images">
              <a class="scale-img hover-glass text-decoration-none" href="<?= $row_tintuc['slugvi'] ?>" title="<?= $row_tintuc['namevi'] ?>">
                <?= $fn->getImage([
                  'file' => $row_tintuc['file'],
                  'class' => 'w-100',
                  'alt' => $row_tintuc['namevi'],
                  'title' => $row_tintuc['namevi'],
                ]) ?>
              </a>
            </div>
            <a href="<?= $row_tintuc['slugvi'] ?>">
              <div class="content">
                <h3 class="text-split">
                  <?= $row_tintuc['namevi'] ?>
                </h3>
                <div class="content_desc text-split-3 mt-2">
                  <?= $row_tintuc['descvi'] ?>
                </div>
                <p class="content_link mt-3">Xem thêm <i class="fa fa-arrow-right"></i></p>
              </div>
            </a>
          </div>
        <?php endwhile; ?>
      </div>
    </div>
  </div>
<?php endif; ?>
