<div class="item-product">
  <a href="<?= htmlspecialchars($v["slug$lang"]) ?>">
    <div class="images">
      <?= $fn->getImageCustom(['file'  => $v['file'], 'class' => 'w-100', 'alt'   => htmlspecialchars($v["name$lang"]), 'title' => htmlspecialchars($v["name$lang"]), 'width' => $config['product']['san-pham']['width'], 'height' => $config['product']['san-pham']['height'], 'zc' => substr($config['product']['san-pham']['thumb'], -1), 'lazy'  => true, 'watermark' => true]) ?>
    </div>
    <div class="content">
      <div class="title">
        <h3><?= htmlspecialchars($v["name$lang"]) ?></h3>
        <p class="price-product">
          <?php if ($v['sale_price'] && $v['regular_price']): ?>
            <span class="price-new"><?= $v['sale_price'] ?>₫</span>
            <span class="price-old"><?= $v['regular_price'] ?>₫</span>
          <?php elseif ($v['regular_price']): ?>
            <span class="price-new"><?= $v['regular_price'] ?>₫</span>
          <?php else: ?>
            <span class="price-new"><?= lienhe ?></span>
          <?php endif; ?>
        </p>
        <div class="info-product">
          <p><i class="fa-solid fa-eye"></i> <?= $v['views'] ?? 0 ?> <?= luotxem ?></p>
          <p><span>Chi tiết</span></p>
        </div>
      </div>
    </div>
  </a>
</div>
