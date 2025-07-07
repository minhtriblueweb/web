<div class="wrap-main wrap-home w-clear">
  <div class="wrap-product-list">
    <div class="wrap-content" style="background: unset;">
      <div class="title-list-hot text-center">
        <h2>Kết quả tìm kiếm</h2>
        <p>(<?= $total_records ?> sản phẩm): <strong><?= htmlspecialchars($keyword) ?></strong></p>
      </div>
    </div>
  </div>

  <div class="wrap-main wrap-template w-clear" style="margin: 0 auto !important;">
    <div class="content-main">
      <?php if (!empty($show_product)): ?>
        <div class="grid-product">
          <?php foreach ($show_product as $sp): ?>
            <?php
            $slug = $sp['slug' . $lang];
            $name = htmlspecialchars($sp['name' . $lang]);
            $sale = $sp['sale_price'] ?? '';
            $regular = $sp['regular_price'] ?? '';
            $views = $sp['views'] ?? 0;
            ?>
            <div class="item-product" data-aos="fade-up" data-aos-duration="1000">
              <a href="<?= $slug ?>">
                <div class="images">
                  <?= $fn->getImage([
                    'file' => $sp['file'],
                    'class' => 'w-100',
                    'alt' => $name,
                    'title' => $name,
                    'lazy' => true
                  ]) ?>
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
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <div class="alert alert-warning w-100" role="alert">
          <strong>Không tìm thấy kết quả</strong>
        </div>
      <?php endif; ?>

      <div class="mt-3">
        <?= $fn->renderPagination_tc($current_page, $total_pages, BASE . 'san-pham/page-'); ?>
      </div>
    </div>
  </div>
</div>
