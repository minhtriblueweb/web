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
          <?php foreach ($product as $k => $v): ?>
            <?php include TEMPLATE . LAYOUT . 'item-product.php'; ?>
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
