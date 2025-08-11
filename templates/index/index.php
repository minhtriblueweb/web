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
  <?php
  $productList = $fn->show_data([
    'table'  => 'tbl_product_list',
    'status' => 'hienthi,noibat',
    'select' => "id, slug$lang, name$lang"
  ]);

  foreach ($productList as $v_list):
    // Danh mục cấp 2 theo từng danh sách
    $productCat = $fn->show_data([
      'table'   => 'tbl_product_cat',
      'status'  => 'hienthi,noibat',
      'id_list' => $v_list['id'],
      'select'  => "id, slug$lang, name$lang"
    ]);

    // Sản phẩm "Tất cả" theo id_list
    $productsAll = $fn->show_data([
      'table'   => 'tbl_product',
      'status'  => 'hienthi',
      'id_list' => $v_list['id'],
      'limit'   => 10,
      'select'  => "id, file, slug$lang, name$lang, sale_price, regular_price, views, id_list, id_cat"
    ]);
    if (empty($productsAll)) continue;
  ?>
    <div class="box-list" data-aos="fade-up" data-aos-duration="500">
      <div class="title-list">
        <h2><span class="text-split"><?= $v_list["name$lang"] ?></span></h2>
        <div class="box-tab-cat">
          <ul class="tab-cat" data-aos="fade-left" data-aos-duration="500">
            <li><a href="#" class="tab-cat-link active" data-tab="tab-all-<?= $v_list['id'] ?>">Tất cả</a></li>
            <?php foreach ($productCat as $v_cat): ?>
              <li>
                <a href="#" class="tab-cat-link text-capitalize" data-tab="tab-<?= $v_cat['id'] ?>">
                  <?= $v_cat["name$lang"] ?>
                </a>
              </li>
            <?php endforeach; ?>
          </ul>
          <a class="viewlist" href="<?= $v_list["slug$lang"] ?>"><?= xemthem ?></a>
        </div>
      </div>

      <!-- Tab "Tất cả" -->
      <div class="paging-product-list tabcontent show-fade" id="tab-all-<?= $v_list['id'] ?>" style="display: block;">
        <div class="grid-product">
          <?php foreach ($productsAll as $v): ?>
            <?php include TEMPLATE . LAYOUT . 'item-product.php'; ?>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Tab theo danh mục cấp 2 -->
      <?php foreach ($productCat as $v_cat):
        $productsCat = $fn->show_data([
          'table'   => 'tbl_product',
          'status'  => 'hienthi',
          'id_cat'  => $v_cat['id'],
          'limit'   => 10,
          'select'  => "id, file, slug$lang, name$lang, sale_price, regular_price, views, id_list, id_cat"
        ]);
      ?>
        <div class="paging-product-list tabcontent hidden" id="tab-<?= $v_cat['id'] ?>">
          <div class="grid-product">
            <?php if (!empty($productsCat)): ?>
              <?php foreach ($productsCat as $v): ?>
                <?php include TEMPLATE . LAYOUT . 'item-product.php'; ?>
              <?php endforeach; ?>
            <?php else: ?>
              <p class="alert alert-warning"><?= noidungdangcapnhat ?></p>
            <?php endif; ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
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
                <?= $fn->getImageCustom(['file' => $v['file'], 'width' => $optsetting_json["danh-gia_man_width"], 'height' => $optsetting_json["danh-gia_man_height"], 'zc' => $optsetting_json["danh-gia_man_zc"], 'alt' => $v["name$lang"], 'title' => $v["name$lang"], 'lazy' => true]) ?>
              </a>
              <div class="title">
                <h3 class="text-split"><?= $v["name$lang"] ?></h3>
                <span class="text-split"><?= $v["content$lang"] ?></span>
              </div>
            </div>
            <svg class="quote-icon" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
              <rect width="48" height="48" fill="white" />
              <g transform="translate(0,48) scale(0.1,-0.1)" fill="var(--main-color)">
                <path d="M5 458 c-3 -13 -4 -118 -3 -235 l3 -212 52 39 51 40 184 2 183 3 3
      170 c1 94 0 180 -3 193 l-5 22 -230 0 -230 0 -5 -22z m189 -133 c-9 -14 -15
      -27 -13 -28 2 -1 13 -7 24 -13 25 -14 31 -56 11 -73 -23 -19 -65 -5 -77 26
      -14 38 -2 79 34 109 35 30 47 18 21 -21z m139 39 c3 -4 -3 -18 -13 -32 -25
      -31 -25 -39 0 -47 25 -8 37 -50 20 -70 -18 -22 -57 -18 -74 7 -25 35 -19 73
      15 113 31 35 40 40 52 29z" />
              </g>
            </svg>
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
