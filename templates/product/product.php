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
  if ((!empty($list) && empty($idi)) || !empty($idb)) : ?>
    <div class="wrap-product-list">
      <div class="wrap-content">
        <div class="grid-list-no-index">
          <?php if (!empty($list) && empty($idi)) : ?>
            <?php foreach ($list as $v): ?>
              <div class="item-list-noindex <?= ($v["slug$lang"] === $activeSlug ? 'active' : '') ?>">
                <a title="<?= $v["name$lang"] ?>" href="<?= $v["slug$lang"] ?>">
                  <h3 class="m-0 text-capitalize"><?= htmlspecialchars($v["name$lang"]) ?></h3>
                </a>
              </div>
            <?php endforeach; ?>
          <?php elseif (!empty($idb) && !empty($brandAll)) : ?>
            <?php if ($productBrand['icon']) : ?>
              <div class="m-auto mt-3" style="width: 1000px">
                <?= $fn->getImage(['file' => $productBrand['icon'], 'class' => 'w-100', 'alt' => $productBrand["name$lang"], 'title' => $productBrand["name$lang"], 'lazy' => false]) ?>
              </div>
            <?php endif; ?>
          <?php endif; ?>
        </div>
      </div>
    </div>
  <?php endif; ?>


  <!-- TITLE -->
  <div class="title-list-hot text-center mt-3">
    <h2><?= htmlspecialchars(!empty($titleCate) ? $titleCate : sanpham) ?></h2>
    (<?= $total ?> <?= sanpham ?>)
  </div>

  <!-- DANH SÁCH SẢN PHẨM -->
  <div class="wrap-main wrap-template w-clear" style="margin: 0 auto !important;">
    <div class="content-main">
      <?php if (!empty($product)): ?>
        <div class="grid-product">
          <?php foreach ($product as $k => $v): ?>
            <div class="col-12" data-aos="fade-up" data-aos-duration="500">
              <?php include TEMPLATE . LAYOUT . 'item-product.php'; ?>
            </div>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <div class="alert alert-warning w-100" role="alert">
          <p class="m-0"><strong><?= noidungdangcapnhat ?></strong></p>
        </div>
      <?php endif; ?>

      <!-- PHÂN TRANG -->
      <?php if ($paging): ?><div class="mt-3 mb-3 pagination-home w-100"><?= $paging ?></div><?php endif; ?>

      <!-- BÀI VIẾT -->
      <?php if ($contentCate): ?>
        <div class="content-toggle mt-3 mb-3">
          <div class="content-toggle__body-wrapper">
            <div class="content-toggle__body content-main content-ck pro_tpl" id="toc-content">
              <?= $fn->decodeHtmlChars($contentCate) ?>
            </div>
          </div>
          <p class="content-toggle__button">
            <span class="text">Đọc tiếp bài viết</span>
            <i class="fas fa-chevron-down"></i>
          </p>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>
