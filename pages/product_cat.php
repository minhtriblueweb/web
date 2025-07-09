<div class="wrap-main wrap-home w-clear">
  <!-- Danh mục cấp 2 -->
  <?php if (!empty($list_danhmuc_c2)): ?>
    <div class="wrap-product-list">
      <div class="wrap-content" style="background: unset;">
        <div class="grid-list-no-index">
          <?php foreach ($list_danhmuc_c2 as $dm_c2_item): ?>
            <div class="item-list-noindex <?= $dm_c2_item['slug' . $lang] === $dm_c2['slug' . $lang] ? 'active' : '' ?>">
              <a href="<?= BASE . $dm_c2_item['slug' . $lang] ?>">
                <h3 class="m-0 text-capitalize"><?= $dm_c2_item['name' . $lang] ?></h3>
              </a>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  <?php endif; ?>


  <!-- Tiêu đề danh mục -->
  <div class="wrap-product-list">
    <div class="wrap-content" style="background: unset;">
      <div class="title-list-hot text-center text-capitalize">
        <h2><?= $dm_c2['name' . $lang] ?></h2>
        <p>(<?= $total ?> sản phẩm)</p>
      </div>
    </div>
  </div>

  <!-- Danh sách sản phẩm -->
  <div class="wrap-main wrap-template w-clear" style="margin: 0 auto !important;">
    <div class="content-main">
      <?php if (!empty($get_sp)): ?>
        <div class="grid-product" data-aos="fade-up" data-aos-duration="500">
          <?php foreach ($get_sp as $sp): ?>
            <?php
            $slug = $sp['slug' . $lang];
            $name = htmlspecialchars($sp['name' . $lang]);
            $sale = $sp['sale_price'] ?? '';
            $regular = $sp['regular_price'] ?? '';
            $views = $sp['views'] ?? 0;
            ?>
            <div class="item-product">
              <a href="<?= BASE . $slug ?>">
                <div class="images">
                  <?= $fn->getImageCustom(['file'  => $sp['file'], 'class' => 'w-100', 'alt'   => $name, 'title' => $name, 'width' => 500, 'height' => 500, 'zc' => 1, 'lazy'  => true]) ?>
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
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <div class="alert alert-warning w-100" role="alert">
          <strong>Không tìm thấy kết quả</strong>
        </div>
      <?php endif; ?>

      <!-- Phân trang -->
      <div class="mt-3">
        <?= $fn->renderPagination_tc($page, $total_pages, BASE . $dm_c2['slug' . $lang] . '/page-') ?>
      </div>
    </div>
  </div>
</div>
