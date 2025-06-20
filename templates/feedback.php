<?php
$show_danhgia = $functions->show_data([
  'table' => 'tbl_danhgia',
  'status' => 'hienthi'
]);
if ($show_danhgia && $show_danhgia->num_rows > 0):
?>
  <div class="wrap-feedback" data-aos="fade-up" data-aos-duration="1000">
    <div class="wrap-content">
      <div class="title-main">
        <h2>ĐÁNH GIÁ KHÁCH HÀNG</h2>
      </div>
      <div class="slick-feedback slick-d-none">
        <?php while ($row_dg = $show_danhgia->fetch_assoc()): ?>
          <div class="item-feedback">
            <p class="text-split">
              <?= $row_dg['desc'] ?>
            </p>
            <div class="content">
              <a class="scale-img hover-glass text-decoration-none" title="<?= $row_dg['name'] ?>"
                style="width: 100px; height: 100px;">
                <img class="circle-img" src="<?= empty($row_dg['file']) ? BASE_ADMIN . "assets/img/noimage.png" : BASE_ADMIN . UPLOADS . $row_dg['file']; ?>" />
              </a>
              <div class="title">
                <h3 class="text-split"><?= $row_dg['name'] ?></h3>
                <span class="text-split"><?= $row_dg['address'] ?></span>
              </div>
            </div>
          </div>
        <?php endwhile; ?>
      </div>
    </div>
  </div>
<?php endif; ?>
