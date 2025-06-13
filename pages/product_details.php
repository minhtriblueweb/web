<?php
$slug = $_GET['slug'] ?? '';

if (empty($slug)) {
  http_response_code(404);
  include '404.php';
  exit();
}

// Lấy thông tin sản phẩm
$kg_sanpham = $sanpham->get_sanpham_by_slug($slug);
if (!$kg_sanpham) {
  http_response_code(404);
  include '404.php';
  exit();
}
$sanpham->update_views_by_slug($slug);
$dm_c1_name = $dm_c2_name = $dm_c1_slug = $dm_c2_slug = '';
$id = $kg_sanpham['id'];
$id_list = $kg_sanpham['id_list'];
$id_cat = $kg_sanpham['id_cat'];

$get_danhmuc = $sanpham->get_danhmuc_by_sanpham($id);
if ($get_danhmuc && $dm = $get_danhmuc->fetch_assoc()) {
  $dm_c1_name = $dm['dm_c1_name'] ?? '';
  $dm_c2_name = $dm['dm_c2_name'] ?? '';
  $dm_c1_slug = $dm['dm_c1_slug'] ?? '';
  $dm_c2_slug = $dm['dm_c2_slug'] ?? '';
}

// Sản phẩm liên quan
$records_per_page = 100;
$current_page = max(1, (int)($_GET['page'] ?? 1));
$total_records = $sanpham->total_pages_sanpham_lienquan($id, $id_cat, 1);
$total_pages = max(1, ceil($total_records / $records_per_page));

$sanpham_lienquan = $sanpham->sanpham_lienquan($id, $id_cat, $records_per_page, $current_page);

// Hình ảnh liên quan
$get_gallery = $sanpham->get_gallery($id);

// SEO
$seo['title'] = !empty($kg_sanpham['titlevi']) ? $kg_sanpham['titlevi'] : $kg_sanpham['namevi'];
$seo['keywords'] = $kg_sanpham['keywordsvi'];
$seo['description'] = $kg_sanpham['descriptionvi'];
$seo['url'] = BASE . $kg_sanpham['slugvi'];
$seo['image'] = BASE_ADMIN . UPLOADS . $kg_sanpham['file'];
?>
<div class="wrap-main wrap-home w-clear">
  <div class="breadCrumbs">
    <div class="wrap-content">
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a class="text-decoration-none" href="./">
            <span>Trang chủ</span>
          </a>
        </li>
        <li class="breadcrumb-item">
          <a class="text-decoration-none" href="san-pham">
            <span>Sản phẩm</span>
          </a>
        </li>
        <?php if (isset($dm_c1_slug) && isset($dm_c1_name)): ?>
          <li class="breadcrumb-item">
            <a class="text-decoration-none" href="<?= BASE . $dm_c1_slug ?>">
              <span><?= $dm_c1_name ?></span>
            </a>
          </li>
        <?php endif; ?>
        <?php if (isset($dm_c2_slug) && isset($dm_c2_name)): ?>
          <li class="breadcrumb-item">
            <a class="text-decoration-none" href="<?= BASE . $dm_c2_slug ?>">
              <span><?= $dm_c2_name ?></span>
            </a>
          </li>
        <?php endif; ?>

        <li class="breadcrumb-item active">
          <a class="text-decoration-none" href="<?= BASE . 'san-pham/' . $kg_sanpham['slugvi'] ?>">
            <span><?= $kg_sanpham['namevi'] ?></span>
          </a>
        </li>
      </ol>
    </div>
  </div>
  <div class="wrap-main wrap-template w-clear">
    <div class="grid-pro-detail d-flex flex-wrap justify-content-between align-items-start">

      <!-- LEFT -->
      <div class="left-pro-detail">
        <div class="d-flex justify-content-center align-items-center" style="width:100%;height:400px;">
          <a id="Zoom-1" class="MagicZoom"
            data-options="zoomMode: magnifier; zoomPosition: inner; hint: off; rightClick: true; expandCaption: false; history: false;"
            href="<?= empty($kg_sanpham['file']) ? BASE_ADMIN . "assets/img/noimage.png" : BASE_ADMIN . UPLOADS . $kg_sanpham['file']; ?>"
            title="<?= $kg_sanpham['namevi'] ?>">
            <img
              src="<?= empty($kg_sanpham['file']) ? BASE_ADMIN . "assets/img/noimage.png" : BASE_ADMIN . UPLOADS . $kg_sanpham['file']; ?>"
              alt="<?= $kg_sanpham['namevi'] ?>" />
          </a>
        </div>

        <?php if ($get_gallery && $get_gallery->num_rows > 0): ?>
          <div class="gallery-thumb-pro">
            <div class="owl-page owl-carousel owl-theme owl-pro-detail" data-items="screen:0|items:4|margin:10"
              data-nav="1" data-navcontainer=".control-pro-detail">
              <div>
                <a class="thumb-pro-detail" data-zoom-id="Zoom-1"
                  href="<?= empty($kg_sanpham['file']) ? BASE_ADMIN . "assets/img/noimage.png" : BASE_ADMIN . UPLOADS . $kg_sanpham['file']; ?>">
                  <img class="w-100"
                    src="<?= empty($kg_sanpham['file']) ? BASE_ADMIN . "assets/img/noimage.png" : BASE_ADMIN . UPLOADS . $kg_sanpham['file']; ?>" />
                </a>
              </div>
              <?php while ($resule_gallery = $get_gallery->fetch_assoc()) : ?>
                <div>
                  <a class="thumb-pro-detail" data-zoom-id="Zoom-1"
                    href="<?= empty($resule_gallery['photo']) ? BASE_ADMIN . "assets/img/noimage.png" : BASE_ADMIN . UPLOADS . $resule_gallery['photo']; ?>">
                    <img class="w-100"
                      src="<?= empty($resule_gallery['photo']) ? BASE_ADMIN . "assets/img/noimage.png" : BASE_ADMIN . UPLOADS . $resule_gallery['photo']; ?>" />
                  </a>
                </div>
              <?php endwhile; ?>
            </div>
            <div class="control-pro-detail control-owl transition"></div>
          </div>
        <?php endif; ?>
      </div>
      <div class="right-pro-detail">
        <p class="title-pro-detail mb-3"><?= $kg_sanpham['namevi'] ?></p>
        <ul class="attr-pro-detail">
          <?php if (!empty($kg_sanpham['code'])): ?>
            <li>
              <label class="attr-label-pro-detail">Mã sản phẩm:</label>
              <div class="attr-content-pro-detail"><?= $kg_sanpham['code'] ?></div>
            </li>
          <?php endif; ?>
          <li>
            <label class="attr-label-pro-detail">Lượt xem:</label>
            <div class="attr-content-pro-detail"><?= $kg_sanpham['views'] ?></div>
          </li>
          <li>
            <label class="attr-label-pro-detail">Giá:</label>
            <div class="attr-content-pro-detail">
              <?php if (!empty($kg_sanpham['sale_price']) && !empty($kg_sanpham['regular_price'])): ?>
                <span class="price-new-pro-detail"><?= $kg_sanpham['sale_price'] ?> ₫</span>
                <span class="price-old-pro-detail"><?= $kg_sanpham['regular_price'] ?> ₫</span>
              <?php elseif (!empty($kg_sanpham['sale_price'])): ?>
                <span class="price-new-pro-detail"><?= $kg_sanpham['sale_price'] ?> ₫</span>
              <?php elseif (!empty($kg_sanpham['regular_price'])): ?>
                <span class="price-new-pro-detail"><?= $kg_sanpham['regular_price'] ?> ₫</span>
              <?php else: ?>
                <span class="price-new-pro-detail">Liên hệ</span>
              <?php endif; ?>
            </div>
          </li>
        </ul>
        <div class="desc-pro-detail content-ck"><?= $kg_sanpham['descvi'] ?></div>
        <div class="btn-pro-contact">
          <a target="_blank" href="tel:<?= $hotline ?>"><i><img src="assets/images/icon-t1.png" alt="Hotline"
                data-was-processed="true"></i><?= $hotline ?></a>
          <a target="_blank" href="https://zalo.me/<?= $hotline ?>"><i><img src="assets/images/icon-t2.png" alt="Zalo"
                data-was-processed="true"></i> Chat zalo</a>
        </div>
      </div>
      <div class="policy-detail">
        <?php $show_tieuchi = $tieuchi->show_tieuchi("hienthi");
        if ($show_tieuchi): ?>
          <?php while ($result_tieuchi = $show_tieuchi->fetch_assoc()): ?>
            <div class="list-policy">
              <div class="i-policy">
                <a class="scale-img hover-glass text-decoration-none" href="" title="<?= $result_tieuchi['name'] ?>">
                  <img
                    src="<?= empty($result_tieuchi['file']) ? BASE_ADMIN . "assets/img/noimage.png" : BASE_ADMIN . UPLOADS . $result_tieuchi['file']; ?>"
                    alt="<?= $result_tieuchi['name'] ?>" width="40" height="40">
                </a>
                <div class="content">
                  <h3 class="text-split" title="<?= $result_tieuchi['name'] ?>"><?= $result_tieuchi['name'] ?></h3>
                </div>
              </div>
            </div>
          <?php endwhile; ?>
        <?php endif; ?>
      </div>
    </div>

    <div class="tabs-pro-detail">
      <ul class="nav nav-tabs" id="tabsProDetail" role="tablist">
        <li class="nav-item">
          <a class="nav-link active" id="info-pro-detail-tab" data-bs-toggle="tab" href="#info-pro-detail"
            role="tab">Thông tin sản phẩm</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="commentfb-pro-detail-tab" data-bs-toggle="tab" href="#commentfb-pro-detail"
            role="tab">Bình luận</a>
        </li>
      </ul>
      <div class="tab-content" id="tabsProDetailContent">
        <div class="tab-pane fade show active" id="info-pro-detail" role="tabpanel">
          <div class="content-main content-ck" id="toc-content">
            <?= $kg_sanpham['contentvi'] ?>
          </div>
        </div>
        <div class="tab-pane fade" id="commentfb-pro-detail" role="tabpanel">
        </div>
      </div>
    </div>
    <?php if ($sanpham_lienquan && $sanpham_lienquan->num_rows > 0): ?>
      <div class="title-main mt-4" data-aos="fade-up" data-aos-duration="1000">
        <h2>Sản phẩm cùng loại</h2>
      </div>
      <div class="grid-product" data-aos="fade-up" data-aos-duration="1000">
        <?php while ($kg_sanpham_lienquan = $sanpham_lienquan->fetch_assoc()) : ?>
          <?php
          $slug = $kg_sanpham_lienquan['slugvi'];
          $name = $kg_sanpham_lienquan['namevi'];
          $img_src = empty($kg_sanpham_lienquan['file'])
            ? BASE_ADMIN . "assets/img/noimage.png"
            : BASE_ADMIN . UPLOADS . $kg_sanpham_lienquan['file'];
          $views = !empty($kg_sanpham_lienquan['views']) ? $kg_sanpham_lienquan['views'] : 0;
          $sale_price = $kg_sanpham_lienquan['sale_price'];
          $regular_price = $kg_sanpham_lienquan['regular_price'];
          ?>
          <div class="item-product">
            <a class="text-decoration-none" href="san-pham/<?= $slug ?>" title="<?= htmlspecialchars($name) ?>">
              <div class="images">
                <img class="w-100" src="<?= $img_src ?>" alt="<?= htmlspecialchars($name) ?>">
              </div>
              <div class="content">
                <div class="title">
                  <h3><?= htmlspecialchars($name) ?></h3>
                  <p class="price-product">
                    <?php if (!empty($sale_price) && !empty($regular_price)): ?>
                      <span class="price-new"><?= $sale_price ?> ₫</span>
                      <span class="price-old"><?= $regular_price ?> ₫</span>
                    <?php elseif (!empty($regular_price)): ?>
                      <span class="price-new"><?= $regular_price ?> ₫</span>
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

      <div class="pagination-home w-100">
        <?php // echo $pagination_html = $functions->renderPagination_index($current_page, $total_pages, $slug);
        ?>
      </div>
    <?php endif; ?>
  </div>
</div>
