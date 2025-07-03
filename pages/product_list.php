<div class="wrap-main wrap-home w-clear">
  <!-- DANH MỤC -->
  <?php if (!empty($dm)): ?>
    <div class="wrap-product-list">
      <div class="wrap-content" style="background: unset;">
        <div class="grid-list-no-index">
          <?php foreach ($dm as $row_dm): ?>
            <div class="item-list-noindex">
              <a title="<?= $row_dm['name' . $lang] ?>" href="<?= $row_dm['slug' . $lang] ?>">
                <h3 class="m-0"><?= $row_dm['name' . $lang] ?></h3>
              </a>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  <?php endif; ?>

  <!-- TITLE -->
  <div class="wrap-product-list">
    <div class="wrap-content" style="background: unset;">
      <div class="title-list-hot text-center">
        <h2>Sản Phẩm</h2>
        (<?= $total_records ?> sản phẩm)
      </div>
    </div>
  </div>

  <!-- DANH SÁCH SẢN PHẨM -->
  <div class="wrap-main wrap-template w-clear" style="margin: 0 auto !important;">
    <div class="content-main">
      <?php if (!empty($show_sanpham)): ?>
        <div class="grid-product">
          <?php foreach ($show_sanpham as $sp): ?>
            <?php
            $slug = $sp['slug' . $lang];
            $name = htmlspecialchars($sp['name' . $lang]);
            $sale = $sp['sale_price'];
            $regular = $sp['regular_price'];
            $views = $sp['views'];
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

      <!-- PHÂN TRANG -->
      <div class="mt-3">
        <?= $fn->renderPagination_tc($current_page, $total_pages, BASE . 'san-pham/page-'); ?>
      </div>
    </div>
  </div>
</div>
