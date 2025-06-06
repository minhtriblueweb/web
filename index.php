<?php include 'inc/header.php'; ?>
<?php include 'inc/menu.php'; ?>
<?php include 'inc/slideshow.php'; ?>
<div class="wrap-main wrap-home w-clear">
  <?php include 'inc/tieuchi.php'; ?>
  <?php
  $sanpham_banchay = $sanpham->sanpham_banchay();
  if ($sanpham_banchay && $sanpham_banchay->num_rows > 0): ?>
    <div class="wrap-product-hot">
      <div class="wrap-content">
        <div class="title-product-hot">
          <h2>SẢN PHẨM BÁN CHẠY</h2>
        </div>
        <div class="slick-product slick-d-none">
          <?php while ($resule_banchay = $sanpham_banchay->fetch_assoc()) : ?>
            <div>
              <div class="item-product">
                <a href="san-pham/<?= $resule_banchay['slugvi'] ?>">
                  <div class="images">
                    <img class="w-100" src="<?= BASE_ADMIN . UPLOADS . $resule_banchay['file'] ?>"
                      alt="<?= $resule_banchay['namevi'] ?>" />
                  </div>
                  <div class="content">
                    <div class="title">
                      <h3><?= $resule_banchay['namevi'] ?></h3>
                      <p class="price-product">
                        <?php
                        if (!empty($resule_banchay['sale_price']) && !empty($resule_banchay['regular_price'])) {
                          echo '<span class="price-new">' . $resule_banchay['sale_price'] . '₫</span>';
                          echo '<span class="price-old">' . $resule_banchay['regular_price'] . '₫</span>';
                        } elseif (!empty($resule_banchay['regular_price'])) {
                          echo '<span class="price-new">' . $resule_banchay['regular_price'] . '₫</span>';
                        } else {
                          echo '<span class="price-new">Liên hệ</span>';
                        }
                        ?>
                      </p>
                      <div class="info-product">
                        <p><i class="fa-solid fa-eye"></i> <?= $resule_banchay['views'] ?> lượt xem</p>
                        <p><span>Chi tiết</span></p>
                      </div>
                    </div>
                  </div>
                </a>
              </div>
            </div>
          <?php endwhile; ?>
        </div>
      </div>
    </div>
  <?php endif; ?>
  <div class="wrap-product-list">
    <div class="wrap-content">
      <div class="title-list-hot">
        <h2>DANH MỤC BẠN QUAN TÂM</h2>
      </div>
      <div class="grid-list">
        <?php if ($show_danhmuc = $danhmuc->show_danhmuc_c2()): ?>
          <?php while ($dm = $show_danhmuc->fetch_assoc()): ?>
            <?php
            $slug = $dm['slugvi'];
            $name = $dm['namevi'];
            $imgSrc = !empty($dm['file'])
              ? BASE_ADMIN . UPLOADS . $dm['file']
              : BASE_ADMIN . "assets/img/noimage.png";
            ?>
            <div class="item-list">
              <a class="scale-img" href="cate/<?= $slug ?>" title="<?= $name ?>">
                <img src="<?= $imgSrc ?>" alt="<?= $name ?>" />
              </a>
              <h3>
                <a class="text-split" href="cate/<?= $slug ?>"><?= $name ?></a>
              </h3>
            </div>
          <?php endwhile; ?>
        <?php endif; ?>
      </div>

      <div class="slick-banner slick-d-none"></div>
    </div>
  </div>

  <div class="wrap-content">
    <?php $show_danhmuc = $danhmuc->show_danhmuc_noibat('hienthi', 'noibat'); ?>
    <?php if ($show_danhmuc): ?>
      <?php while ($resule_danhmuc = $show_danhmuc->fetch_assoc()) : ?>
        <?php
        $id_list = $resule_danhmuc['id'];
        $show_sanpham = $sanpham->show_sanpham_tc($id_list);
        if ($show_sanpham && $show_sanpham->num_rows > 0):
        ?>
          <div class="box-list">
            <div class="title-list">
              <h2><span class="text-split"><?= $resule_danhmuc['namevi'] ?></span></h2>
              <div class="box-tab-cat">
                <ul class="tab-cat">
                  <?php $show_danhmuc_c2 = $danhmuc->show_danhmuc_c2_index($id_list); ?>
                  <?php if ($show_danhmuc_c2): ?>
                    <?php while ($resule_danhmuc_c2 = $show_danhmuc_c2->fetch_assoc()) : ?>
                      <li><a href="cate/<?= $resule_danhmuc_c2['slugvi'] ?>"><?= $resule_danhmuc_c2['namevi'] ?></a></li>
                    <?php endwhile; ?>
                  <?php endif; ?>
                </ul>
                <a class="viewlist" href="danh-muc/<?= $resule_danhmuc['slugvi'] ?>">Xem tất cả</a>
              </div>
            </div>
            <div class="paging-product-list paging-product-list-1" data-list="1">
              <div class="grid-product">
                <?php while ($sp = $show_sanpham->fetch_assoc()) : ?>
                  <?php
                  $slug = $sp['slugvi'];
                  $name = htmlspecialchars($sp['namevi']);
                  $img = !empty($sp['file'])
                    ? BASE_ADMIN . UPLOADS . $sp['file']
                    : $config['baseAdmin'] . "assets/img/noimage.png";
                  $sale = $sp['sale_price'] ?? '';
                  $regular = $sp['regular_price'] ?? '';
                  $views = $sp['views'] ?? 0;
                  ?>
                  <div class="item-product">
                    <a href="san-pham/<?= $slug ?>">
                      <div class="images">
                        <img src="<?= $img ?>" alt="<?= $name ?>" title="<?= $name ?>" class="w-100" />
                      </div>
                      <div class="content">
                        <div class="title">
                          <h3><?= $name ?></h3>
                          <p class="price-product">
                            <?php if (!empty($sale) && !empty($regular)): ?>
                              <span class="price-new"><?= $sale ?>₫</span>
                              <span class="price-old"><?= $regular ?>₫</span>
                            <?php elseif (!empty($regular)): ?>
                              <span class="price-new"><?= $regular ?>₫</span>
                            <?php else: ?>
                              <span class="price-new">Liên hệ</span>
                            <?php endif; ?>
                          </p>
                          <div class="info-product">
                            <p><i class="fa-solid fa-eye"></i> <?= $views ?> lượt xem</p>
                            <p><span>Chi tiết</span></p>
                          </div>
                        </div>
                      </div>
                    </a>
                  </div>
                <?php endwhile; ?>
              </div>
            </div>
          </div>
        <?php endif; ?>
      <?php endwhile; ?>
    <?php endif; ?>
  </div>
  <!-- <div class="wrap-brand">
    <div class="wrap-content">
      <div class="title-brand">
        <h2>THƯƠNG HIỆU NỔI BẬT</h2>
      </div>
      <div class="slick-brand slick-d-none">
        <div>
          <a class="scale-img" href="imou-logo" title="IMOU">
            <img src="https://dientuthanhduy.com/thumbs/190x120x2/upload/product/imou-2517.png" alt="IMOU" />
          </a>
        </div>
        <div>
          <a class="scale-img" href="electrolux" title="electrolux">
            <img src="https://dientuthanhduy.com/thumbs/190x120x2/upload/product/download-6762.png" alt="electrolux" />
          </a>
        </div>
        <div>
          <a class="scale-img" href="daikin" title="Daikin">
            <img src="https://dientuthanhduy.com/thumbs/190x120x2/upload/product/daikin-logo-1953-1061.png"
              alt="Daikin" />
          </a>
        </div>
        <div>
          <a class="scale-img" href="gaaboor" title="GAABOOR">
            <img src="https://dientuthanhduy.com/thumbs/190x120x2/upload/product/download-7295.png" alt="GAABOOR" />
          </a>
        </div>
        <div>
          <a class="scale-img" href="carrier" title="Carrier">
            <img src="https://dientuthanhduy.com/thumbs/190x120x2/upload/product/logocarrier-7008.png" alt="Carrier" />
          </a>
        </div>
        <div>
          <a class="scale-img" href="samsung" title="Samsung">
            <img src="https://dientuthanhduy.com/thumbs/190x120x2/upload/product/samsung-2332.png" alt="Samsung" />
          </a>
        </div>
        <div>
          <a class="scale-img" href="tosiba" title="Tosiba">
            <img src="https://dientuthanhduy.com/thumbs/190x120x2/upload/product/toshibalogo-2163.png" alt="Tosiba" />
          </a>
        </div>
        <div>
          <a class="scale-img" href="hitachi" title="Hitachi">
            <img src="https://dientuthanhduy.com/thumbs/190x120x2/upload/product/hitachi-logo-3471.png" alt="Hitachi" />
          </a>
        </div>
        <div>
          <a class="scale-img" href="sharp" title="SHARP">
            <img src="https://dientuthanhduy.com/thumbs/190x120x2/upload/product/download-6938.png" alt="SHARP" />
          </a>
        </div>
        <div>
          <a class="scale-img" href="sanyo" title="Sanyo">
            <img src="https://dientuthanhduy.com/thumbs/190x120x2/upload/product/2560px-sanyologosvg-7235.png"
              alt="Sanyo" />
          </a>
        </div>
        <div>
          <a class="scale-img" href="mitsubishi" title="Mitsubishi">
            <img src="https://dientuthanhduy.com/thumbs/190x120x2/upload/product/mitsubishi-electric-logo-7340.png"
              alt="Mitsubishi" />
          </a>
        </div>
        <div>
          <a class="scale-img" href="panasonic" title="PANASONIC">
            <img src="https://dientuthanhduy.com/thumbs/190x120x2/upload/product/download-5498.png" alt="PANASONIC" />
          </a>
        </div>
        <div>
          <a class="scale-img" href="lg" title="LG">
            <img
              src="https://dientuthanhduy.com/thumbs/190x120x2/upload/product/logo-lg-vector-inkythuatso-01-30-13-54-57-3946.jpg"
              alt="LG" />
          </a>
        </div>
        <div>
          <a class="scale-img" href="xixaomi" title="Xiaomi">
            <img src="https://dientuthanhduy.com/thumbs/190x120x2/upload/product/xiaomilogo2021-svg-8118.png"
              alt="Xiaomi" />
          </a>
        </div>
        <div>
          <a class="scale-img" href="dahua" title="DAHUA">
            <img src="https://dientuthanhduy.com/thumbs/190x120x2/upload/product/download-5529.png" alt="DAHUA" />
          </a>
        </div>
      </div>
    </div>
  </div> -->
  <?php include 'inc/feedback.php'; ?>
  <?php include 'inc/tintuc.php'; ?>
</div>
<?php include 'inc/footer.php'; ?>