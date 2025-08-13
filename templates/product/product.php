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
  <?php if (!empty($idb)) : ?>
    <div class="wrap-product-list">
      <div class="wrap-content">
        <div class="grid-list-no-index">
          <?php foreach ($Brand as $b) : ?>
            <div class="item-list-noindex <?= ($b["slug$lang"] ===  $productBrand["slug$lang"] ? 'active' : '') ?>">
              <a class="scale-img" href="<?= $b["slug$lang"] ?>" title="<?= $b["name$lang"] ?>">
                <?= $fn->getImageCustom(['file' => $b['file'], 'width' => 100, 'height' => 100, 'zc' => 2, 'alt' => $b["name$lang"], 'title' => $b["name$lang"], 'lazy' => false]) ?>
              </a>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  <?php endif ?>

  <!-- TITLE -->
  <div class="title-list-hot text-center mt-3">
    <h2><?= $productCat["name{$lang}"] ?? $productList["name{$lang}"] ?? $productBrand["name{$lang}"] ?? sanpham ?></h2>
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

      <?php
      $content = '';
      if (!empty($productList["content{$lang}"])) {
        $content = $productList["content{$lang}"];
      } elseif (!empty($productCat["content{$lang}"])) {
        $content = $productCat["content{$lang}"];
      } elseif (!empty($productBrand["content{$lang}"])) {
        $content = $productBrand["content{$lang}"];
      }
      if (!empty($content)):
      ?>
        <div class="desc-list mt-4 mb-4">
          <div class="noidung_anhien">
            <div class="wrap-toc">
              <div class="meta-toc2">
                <a class="mucluc-dropdown-list_button">Mục Lục</a>
                <div class="box-readmore">
                  <ul class="toc-list" data-toc="article" data-toc-headings="h1, h2, h3"></ul>
                </div>
              </div>
            </div>
            <div class="content-main content-ck pro_tpl" id="toc-content">
              <?= $fn->decodeHtmlChars($content) ?>
            </div>
            <p class="anhien xemthemnd">Xem thêm nội dung</p>
            <p class="anhien anbot">Ẩn bớt nội dung</p>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>
