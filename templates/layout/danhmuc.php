<?php if (!empty($productCat = $fn->show_data(['table' => 'tbl_product_cat', 'status' => 'hienthi,noibat', 'select' => "id, file,id_list, slug$lang, name$lang"]))): ?>
  <div class="wrap-product-list">
    <div class="wrap-content">
      <div class="title-list-hot">
        <h2>DANH MỤC BẠN QUAN TÂM</h2>
      </div>
      <div class="slick_product_list">
        <?php foreach ($productCat as $k => $v): ?>
          <a href="<?= $v["slug$lang"] ?>" title="<?= $v["name$lang"] ?>">
            <div class="item-list">
              <div class="item-list-img">
                <?= $fn->getImageCustom(['file'  => $v['file'], 'alt' => $v["name$lang"], 'title' => $v["name$lang"], 'width' => $config['product']['san-pham']['width_cat'], 'height' => $config['product']['san-pham']['height_cat'], 'zc' => substr($config['product']['san-pham']['thumb_cat'], -1)]) ?>
              </div>
              <div class="item-list-name">
                <h3 class="m-0"><?= $v["name$lang"] ?></h3>
              </div>
            </div>
          </a>
        <?php endforeach; ?>
      </div>
      <div class="slick-banner slick-d-none"></div>
    </div>
  </div>
<?php endif; ?>
