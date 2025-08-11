<div class="item-product">
  <a href="<?= htmlspecialchars($v["slug$lang"]) ?>">
    <div class="images">
      <?= $fn->getImageCustom(['file'  => $v['file'], 'class' => 'w-100', 'alt'   => htmlspecialchars($v["name$lang"]), 'title' => htmlspecialchars($v["name$lang"]), 'width' => $optsetting_json["san-pham_man_width"], 'height' => $optsetting_json["san-pham_man_height"], 'zc' => $optsetting_json["san-pham_man_zc"], 'lazy'  => true, 'watermark' => $config['product']['san-pham']['watermark']]) ?>
    </div>
    <div class="content">
      <div class="title">
        <h3><?= htmlspecialchars($v["name$lang"]) ?></h3>
        <p class="price-product">
          <?php if (!empty($v['sale_price']) && !empty($v['regular_price'])): ?>
            <span class="price-new"><?= $v['sale_price'] . '₫' ?></span>
            <span class="price-old"><?= $v['regular_price'] . '₫' ?></span>
          <?php else: ?>
            <span class="price-new">
              <?= !empty($v['regular_price']) ? $v['regular_price'] . '₫' : lienhe ?>
            </span>
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
