<?php
$show_tintuc = $fn->show_data([
  'table' => 'tbl_news',
  'status' => 'hienthi,noibat',
  'type'   => 'tintuc'
]);
if ($show_tintuc && $show_tintuc->num_rows > 0):
?>
  <div class="wrap-service" data-aos="fade-up" data-aos-duration="1000">
    <div class="wrap-content">
      <div class="title-main">
        <h2>TIN TỨC MỚI NHẤT</h2>
      </div>
      <div class="slick-service slick-d-none">
        <?php while ($row_tintuc = $show_tintuc->fetch_assoc()): ?>
          <div>
            <div class="item-service">
              <div class="images">
                <a class="scale-img hover-glass text-decoration-none" href="<?= $row_tintuc['slugvi'] ?>" title="<?= $row_tintuc['namevi'] ?>">
                  <img class="w-100"
                    src="<?= empty($row_tintuc['file']) ? NO_IMG : BASE_ADMIN . UPLOADS . $row_tintuc['file'] ?>"
                    alt="<?= $row_tintuc['namevi'] ?>" />
                </a>
              </div>
              <div class="content">
                <h3>
                  <a class="text-split" href="<?= $row_tintuc['slugvi'] ?>"><?= $row_tintuc['namevi'] ?></a>
                </h3>
              </div>
            </div>
          </div>
        <?php endwhile; ?>
      </div>
    </div>
  </div>
<?php endif; ?>
