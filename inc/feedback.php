<?php
$show_danhgia = $danhgia->show_danhgia("hienthi");
if ($show_danhgia && $show_danhgia->num_rows > 0):
?>
  <div class="wrap-feedback" data-aos="fade-up" data-aos-duration="1000">
    <div class="wrap-content">
      <div class="title-main">
        <h2>ĐÁNH GIÁ KHÁCH HÀNG</h2>
      </div>
      <div class="slick-feedback slick-d-none">
        <?php while ($result_danhgia = $show_danhgia->fetch_assoc()): ?>
          <div class="item-feedback">
            <p class="text-split">
              <?= $result_danhgia['desc'] ?>
            </p>
            <div class="content">
              <a class="scale-img hover-glass text-decoration-none" title="<?= $result_danhgia['name'] ?>"
                style="width: 100px; height: 100px;">
                <img class="circle-img" src="<?= empty($result_danhgia['file']) ? BASE_ADMIN . "assets/img/noimage.png" : BASE_ADMIN . UPLOADS . $result_danhgia['file']; ?>" />
              </a>
              <div class="title">
                <h3 class="text-split"><?= $result_danhgia['name'] ?></h3>
                <span class="text-split"><?= $result_danhgia['address'] ?></span>
              </div>
            </div>
          </div>
        <?php endwhile; ?>
      </div>
    </div>
  </div>
<?php endif; ?>
