<?php include TEMPLATE . LAYOUT . 'tieuchi.php'; ?>
<?php if (!empty($banchay)): ?>
  <div class="wrap-product-hot">
    <div class="wrap-content" data-aos="fade-up" data-aos-duration="500">
      <div class="title-product-hot">
        <h2>SẢN PHẨM BÁN CHẠY</h2>
      </div>
      <div class="slick-product slick-d-none">
        <?php foreach ($banchay as $k => $v): ?>
          <div>
            <?php include TEMPLATE . LAYOUT . 'item-product.php'; ?>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
<?php endif; ?>
<?php include TEMPLATE . LAYOUT . 'danhmuc.php'; ?>
<div class="wrap-content">
  <?php foreach ($dm_c1_rows as $lv1): ?>
    <?php
    $id_list = $lv1['id'];
    // $sp_all = $sp_group[$id_list]['all'] ?? [];
    $sp_all = array_slice($sp_group[$id_list]['all'] ?? [], 0, 10);
    $cat_list = $lv1['sub'] ?? [];
    ?>
    <?php if (!empty($sp_all)): ?>
      <div class="box-list" data-aos="fade-up" data-aos-duration="500">
        <div class="title-list">
          <h2><span class="text-split"><?= $lv1["name$lang"] ?></span></h2>
          <div class="box-tab-cat">
            <ul class="tab-cat" data-aos="fade-left" data-aos-duration="500">
              <li><a href="#" class="tab-cat-link active" data-tab="tab-all-<?= $id_list ?>">Tất cả</a></li>
              <?php foreach ($cat_list as $dm_c2): ?>
                <li><a href="#" class="tab-cat-link text-capitalize" data-tab="tab-<?= $dm_c2['id'] ?>"><?= $dm_c2["name$lang"] ?></a></li>
              <?php endforeach; ?>
            </ul>
            <a class="viewlist" href="<?= $lv1["slug$lang"] ?>">Xem tất cả</a>
          </div>
        </div>

        <!-- Tab Tất cả -->
        <div class="paging-product-list tabcontent show-fade" id="tab-all-<?= $id_list ?>" style="display: block;">
          <div class="grid-product">
            <?php foreach ($sp_all as $k => $v): ?>
              <?php include TEMPLATE . LAYOUT . 'item-product.php'; ?>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- Tab theo danh mục cấp 2 -->
        <?php foreach ($cat_list as $dm_c2): ?>
          <?php
          $id_cat = $dm_c2['id'];
          // $sp_cat = $sp_group[$id_list]['cat'][$id_cat] ?? [];
          $sp_cat = array_slice($sp_group[$id_list]['cat'][$id_cat] ?? [], 0, 10);
          ?>
          <div class="paging-product-list tabcontent" id="tab-<?= $id_cat ?>" style="display: none;">
            <div class="grid-product">
              <?php if (!empty($sp_cat)): ?>
                <?php foreach ($sp_cat as $k => $v): ?>
                  <?php include TEMPLATE . LAYOUT . 'item-product.php'; ?>
                <?php endforeach; ?>
              <?php else: ?>
                <p class="alert alert-warning">Không có sản phẩm nào</p>
              <?php endif; ?>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  <?php endforeach; ?>
</div>

<?php if (!empty($feedback)): ?>
  <div class="wrap-feedback" data-aos="fade-up" data-aos-duration="500">
    <div class="wrap-content">
      <div class="title-main">
        <h2>ĐÁNH GIÁ KHÁCH HÀNG</h2>
      </div>
      <div class="slick-feedback slick-d-none">
        <?php
        $type = "danh-gia";
        foreach ($feedback as $k => $v): ?>
          <div class="item-feedback">
            <p class="text-split"><?= $v["desc$lang"] ?></p>
            <div class="content">
              <a class="scale-img hover-glass text-decoration-none"
                title="<?= $v["name$lang"] ?>" style="width: 100px; height: 100px;">
                <?= $fn->getImageCustom(['file' => $v['file'], 'width' => $config['news'][$type]['width'], 'height' => $config['news'][$type]['height'], 'zc' => substr($config['news'][$type]['thumb'], -1), 'alt' => $v["name$lang"], 'title' => $v["name$lang"], 'lazy' => true]) ?>
              </a>
              <div class="title">
                <h3 class="text-split"><?= $v["name$lang"] ?></h3>
                <span class="text-split"><?= $v["content$lang"] ?></span>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
<?php endif; ?>

<?php if (!empty($tintuc)): ?>
  <div class="wrap-service" data-aos="fade-up" data-aos-duration="500">
    <div class="wrap-content">
      <div class="title-main">
        <h2>TIN TỨC MỚI NHẤT</h2>
      </div>
      <div class="slick-service slick-d-none">
        <?php
        $type = 'tin-tuc';
        foreach ($tintuc as $k => $v): ?>
          <?php include TEMPLATE . LAYOUT . 'item-service.php'; ?>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
<?php endif; ?>
