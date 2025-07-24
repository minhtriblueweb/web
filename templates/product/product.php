<div class="wrap-main wrap-home w-clear">
  <!-- DANH MỤC -->
  <?php
  $list = [];
  $activeSlug = '';
  if (empty($id)) {
    if (empty($idl) && empty($idc) && !empty($productList)) {
      $list = $productList;
    } elseif (!empty($idl) && empty($idc) && !empty($productCat)) {
      $list = $productCat;
    } elseif (!empty($idc) && !empty($productCat_All)) {
      $list = $productCat_All;
      $activeSlug = $productCat["slug$lang"] ?? '';
    }
  }
  if (!empty($list)): ?>
    <div class="wrap-product-list">
      <div class="wrap-content">
        <div class="grid-list-no-index">
          <?php foreach ($list as $k => $v): ?>
            <div class="item-list-noindex <?= ($v["slug$lang"] === $activeSlug ? 'active' : '') ?>">
              <a title="<?= $v["name$lang"] ?>" href="<?= $v["slug$lang"] ?>">
                <h3 class="m-0"><?= htmlspecialchars($v["name$lang"]) ?></h3>
              </a>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  <?php endif; ?>



  <!-- TITLE -->
  <div class="title-list-hot text-center mt-3">
    <h2><?= $productCat["name{$lang}"] ?? $productList["name{$lang}"] ?? sanpham ?></h2>
    (<?= $total ?> <?= sanpham ?>)
  </div>


  <!-- DANH SÁCH SẢN PHẨM -->
  <div class="wrap-main wrap-template w-clear" style="margin: 0 auto !important;">
    <div class="content-main">
      <?php if (!empty($product)): ?>
        <div class="grid-product">
          <?php foreach ($product as $sp): ?>
            <?php
            $slug = $sp["slug$lang"];
            $name = htmlspecialchars($sp["name$lang"]);
            $sale = $sp['sale_price'];
            $regular = $sp['regular_price'];
            $views = $sp['views'];
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
                        <span class="price-new"><?= lienhe ?></span>
                      <?php endif; ?>
                    </p>
                    <div class="info-product">
                      <p><i class="fa-solid fa-eye"></i> <?= $views ?> <?= luotxem ?></p>
                      <p><span>chi tiết</span></p>
                    </div>
                  </div>
                </div>
              </a>
            </div>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <div class="alert alert-warning w-100" role="alert">
          <p class="m-0"><strong><?= noidungdangcapnhat ?></strong></p>
        </div>
      <?php endif; ?>

      <!-- PHÂN TRANG -->
      <?php if ($paging): ?><div class="mt-3 mb-3"><?= $paging ?></div><?php endif; ?>
    </div>
  </div>
</div>
