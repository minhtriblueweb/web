<?php if (!empty($dm_c2_all)): ?>
  <div class="wrap-product-list">
    <div class="wrap-content">
      <div class="title-list-hot">
        <h2>DANH MỤC BẠN QUAN TÂM</h2>
      </div>
      <div class="slick_product_list">
        <?php foreach ($dm_c2_all as $dm): ?>
          <a href="<?= $dm['slug' . $lang] ?>" title="<?= $dm['name' . $lang] ?>">
            <div class="item-list">
              <div class="item-list-img">
                <?= $fn->getImageCustom([
                  'file'  => $dm['file'],
                  'alt'   => $dm['name' . $lang],
                  'title' => $dm['name' . $lang],
                  'width' => 100,
                  'height' => 100,
                  'zc' => 1
                ]) ?>
              </div>
              <div class="item-list-name">
                <h3 class="m-0"><?= $dm['name' . $lang] ?></h3>
              </div>
            </div>
          </a>
        <?php endforeach; ?>
      </div>
      <div class="slick-banner slick-d-none"></div>
    </div>
  </div>
<?php endif; ?>
