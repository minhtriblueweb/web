<div class="item-product position-relative">
  <?php if (!empty($v['discount']) && $v['discount'] > 0): ?>
    <span class="discount-badge ribbon">-<?= (int)$v['discount'] ?>%</span>
  <?php endif; ?>
  <a href="<?= htmlspecialchars($v["slug$lang"]) ?>">
    <div class="images">
      <?= $fn->getImageCustom(['file' => $v['file'], 'class' => 'w-100', 'alt'   => htmlspecialchars($v["name$lang"]), 'title' => htmlspecialchars($v["name$lang"]), 'width' => $optsetting_json["san-pham_man_width"], 'height' => $optsetting_json["san-pham_man_height"], 'zc' => $optsetting_json["san-pham_man_zc"], 'lazy'  => true, 'watermark' => $config['product']['san-pham']['watermark']]) ?>
    </div>
    <div class="content">
      <div class="title">
        <h3><?= htmlspecialchars($v["name$lang"]) ?></h3>
        <?php if ($v['sale_price']) { ?>
          <div class="price-product">
            <span class="price-new"><?= $fn->formatMoney($v['sale_price']) ?></span>
            <span class="price-old"><?= ($v['regular_price']) ? $fn->formatMoney($v['regular_price']) : lienhe ?></span>
          </div>
        <?php } else { ?>
          <div class="price-product">
            <span class="price-new"><?= ($v['regular_price']) ? $fn->formatMoney($v['regular_price']) : lienhe ?></span>
          </div>
        <?php } ?>
        <div class="info-product">
          <p><i class="fa-solid fa-eye"></i> <?= $v['views'] ?? 0 ?> <?= luotxem ?></p>
          <p><span>Chi tiết</span></p>
        </div>
      </div>
    </div>
  </a>
</div>
