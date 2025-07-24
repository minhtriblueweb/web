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
          <?php foreach ($product as $k => $v): ?>
            <?php include TEMPLATE . LAYOUT . 'item-product.php'; ?>
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
