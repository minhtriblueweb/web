<div class="wrap-main wrap-home w-clear">
  <div class="wrap-product-list">
    <div class="wrap-content" style="background: unset;">
      <div class="title-list-hot text-center">
        <h2><?= ketquatimkiem ?></h2>
        <p>(<?= $total ?> sản phẩm): <strong><?= htmlspecialchars($keyword) ?></strong></p>
      </div>
    </div>
  </div>

  <div class="wrap-main wrap-template w-clear" style="margin: 0 auto !important;">
    <div class="content-main">
      <?php if (!empty($product)): ?>
        <div class="grid-product">
          <?php foreach ($product as $sp): ?>
            <?php
            $slug = $sp["slug$lang"];
            $name = htmlspecialchars($sp["name$lang"]);
            $sale = $sp['sale_price'] ?? '';
            $regular = $sp['regular_price'] ?? '';
            $views = $sp['views'] ?? 0;
            ?>
            <div class="item-product" data-aos="fade-up" data-aos-duration="1000">
              <a href="<?= $slug ?>">
                <div class="images">
                  <?= $fn->getImageCustom(['file' => $sp['file'], 'class' => 'w-100', 'alt' => $name, 'title' => $name, 'width' => 500, 'height' => 500, 'zc' => 1, 'lazy' => true, 'watermark' => true]) ?>
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
                        <span class="price-new"><? lienhe ?></span>
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
          <strong><?= khongtimthayketqua ?></strong>
        </div>
      <?php endif; ?>

      <?php if ($paging): ?><div class="mt-3 mb-3"><?= $paging ?></div><?php endif; ?>
    </div>
  </div>
</div>
