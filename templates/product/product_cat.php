<div class="wrap-main wrap-home w-clear">
  <div class="wrap-product-list">
    <div class="wrap-content" style="background: unset;">
      <?php if (!empty($productCat_All)): ?>
        <div class="grid-list-no-index">
          <?php foreach ($productCat_All as $c2): ?>
            <div class="item-list-noindex <?= $c2["slug$lang"] ===  $productCat["slug$lang"] ? 'active' : '' ?>">
              <a href="<?= BASE . $c2["slug$lang"] ?>">
                <h3 class="m-0 text-capitalize"><?= $c2["name$lang"] ?></h3>
              </a>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- Tiêu đề danh mục -->
  <div class="wrap-product-list">
    <div class="wrap-content" style="background: unset;">
      <div class="title-list-hot text-center text-capitalize">
        <h2><?= $productCat["name$lang"] ?></h2>
        <p>(<?= $total ?> sản phẩm)</p>
      </div>
    </div>
  </div>

  <!-- Danh sách sản phẩm -->
  <div class="wrap-main wrap-template w-clear" style="margin: 0 auto !important;">
    <div class="content-main">
      <?php if (!empty($product)): ?>
        <div class="grid-product" data-aos="fade-up" data-aos-duration="500">
          <?php foreach ($product as $sp): ?>
            <?php
            $slug = $sp["slug$lang"];
            $name = htmlspecialchars($sp["name$lang"]);
            $sale = $sp['sale_price'] ?? '';
            $regular = $sp['regular_price'] ?? '';
            $views = $sp['views'] ?? 0;
            ?>
            <div class="item-product">
              <a href="<?= BASE . $slug ?>">
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
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <div class="alert alert-warning w-100" role="alert">
          <strong>Không tìm thấy kết quả</strong>
        </div>
      <?php endif; ?>

      <!-- Phân trang -->
      <?php if ($paging): ?><div class="mt-3 mb-3"><?= $paging ?></div><?php endif; ?>
    </div>
  </div>
</div>
