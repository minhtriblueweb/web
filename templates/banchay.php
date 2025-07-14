<?php if (!empty($sanpham_banchay)): ?>
  <div class="wrap-product-hot">
    <div class="wrap-content" data-aos="fade-up" data-aos-duration="500">
      <div class="title-product-hot">
        <h2>SẢN PHẨM BÁN CHẠY</h2>
      </div>
      <div class="slick-product slick-d-none">
        <?php foreach ($sanpham_banchay as $sp): ?>
          <?php
          $slug    = $sp['slug' . $lang];
          $name    = htmlspecialchars($sp['name' . $lang]);
          $sale    = $sp['sale_price'];
          $regular = $sp['regular_price'];
          $views   = $sp['views'] ?? 0;
          ?>
          <div>
            <div class="item-product">
              <a href="<?= $slug ?>">
                <div class="images">
                  <?= $fn->getImageCustom(['file'  => $sp['file'], 'class' => 'w-100', 'alt'   => $name, 'title' => $name, 'width' => 500, 'height' => 500, 'zc' => 1, 'lazy'  => true, 'watermark' => true]) ?>
                </div>
                <div class="content">
                  <div class="title">
                    <h3><?= $name ?></h3>
                    <p class="price-product">
                      <?php if ($sale && $regular): ?>
                        <span class="price-new"><?= $sale ?>₫</span>
                        <span class="price-old"><?= $regular ?>₫</span>
                      <?php elseif ($regular): ?>
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
        <?php endforeach; ?>
      </div>
    </div>
  </div>
<?php endif; ?>
