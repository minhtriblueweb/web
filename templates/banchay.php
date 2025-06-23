<?php
$sanpham_banchay = $fn->show_data([
  'table' => 'tbl_sanpham',
  'status' => 'hienthi,banchay'
]);
if ($sanpham_banchay && $sanpham_banchay->num_rows > 0): ?>
  <div class="wrap-product-hot">
    <div class="wrap-content" data-aos="fade-up" data-aos-duration="1000">
      <div class="title-product-hot">
        <h2>SẢN PHẨM BÁN CHẠY</h2>
      </div>
      <div class="slick-product slick-d-none">
        <?php
        while ($sp = $sanpham_banchay->fetch_assoc()):
          $slug = $sp['slugvi'];
          $name = htmlspecialchars($sp['namevi']);
          $img_url = BASE_ADMIN . UPLOADS . $sp['file'];
          $img = !empty($sp['file']) ? $img_url : NO_IMG;
          $sale = $sp['sale_price'] ?? '';
          $regular = $sp['regular_price'] ?? '';
          $views = $sp['views'] ?? 0;
        ?>
          <div>
            <div class="item-product">
              <a href="<?= $slug ?>">
                <div class="images">
                  <img class="w-100" src="<?= $img ?>" alt="<?= $name ?>" loading="lazy" />
                </div>
                <div class="content">
                  <div class="title">
                    <h3><?= $name ?></h3>
                    <p class="price-product">
                      <?php if (!empty($sale) && !empty($regular)): ?>
                        <span class="price-new"><?= $sale ?>₫</span>
                        <span class="price-old"><?= $regular ?>₫</span>
                      <?php elseif (!empty($regular)): ?>
                        <span class="price-new"><?= $regular ?>₫</span>
                      <?php else: ?>
                        <span class="price-new">Liên hệ</span>
                      <?php endif; ?>
                    </p>
                    <div class="info-product">
                      <p><i class="fa-solid fa-eye"></i> <?= $views ?> lượt xem</p>
                      <p><span>Chi tiết</span></p>
                    </div>
                  </div>
                </div>
              </a>
            </div>
          </div>
        <?php endwhile; ?>
      </div>
    </div>
  </div>
<?php endif; ?>
