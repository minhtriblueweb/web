<?php
include_once 'lib/autoload.php';
include_once 'lib/router.php';
?>
<?php
$slug = isset($_GET['slug']) ? $_GET['slug'] : '';
if ($slug) {
  $get_sanpham = $sanpham->get_sanpham($slug);
  if ($get_sanpham !== false) {
    $update_views_by_slug = $sanpham->update_views_by_slug($slug);
    $kg_sanpham = $get_sanpham->fetch_assoc();
    if ($kg_sanpham) {
      if (!empty($kg_sanpham['id'])) {
        $id = $kg_sanpham['id'];
        $id_cat = $kg_sanpham['id_cat'];
        $get_danhmuc_by_sanpham =  $sanpham->get_danhmuc_by_sanpham($id);
        if ($get_danhmuc_by_sanpham) {
          $result_danhmuc = $get_danhmuc_by_sanpham->fetch_assoc();
          $danhmuc_name = $result_danhmuc['danhmuc'];
          $danhmuc_c2_name = $result_danhmuc['danhmuc_c2'];
          $danhmuc_slugvi = $result_danhmuc['danhmuc_slugvi'];
          $danhmuc_c2_slugvi = $result_danhmuc['danhmuc_c2_slugvi'];
        }
        $records_per_page = 100; // Số bản ghi trên mỗi trang
        $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Trang hiện tại
        $total_records = $sanpham->total_pages_sanpham_lienquan($id, $id_cat, $limit = 1);
        $total_pages = ceil($total_records / $records_per_page); // Tính số trang
        $sanpham_lienquan =  $sanpham->sanpham_lienquan($id, $id_cat, $records_per_page, $current_page);
      } else {
        header('Location: ' . BASE . '404.php');
        exit();
      }
    } else {
      header('Location: ' . BASE . '404.php');
      exit();
    }
  } else {
    header('Location: ' . BASE . '404.php');
    exit();
  }

  $get_gallery = $sanpham->get_gallery($kg_sanpham['id']);
}

$seo = array_merge($seo, array(
  'title' => $kg_sanpham['namevi'],
  'keywords' => $kg_sanpham['keywordsvi'],
  'description' => $kg_sanpham['descriptionvi'],
  'url' => BASE . 'san-pham/' . $kg_sanpham['slugvi'],
  'image' => isset($kg_sanpham['file']) ? BASE_ADMIN . UPLOADS . $kg_sanpham['file'] : '',
));
?>
<?php
include 'inc/header.php';
include 'inc/menu.php';
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
        <?php if (isset($danhmuc_slugvi) && isset($danhmuc_name)): ?>
          <li class="breadcrumb-item">
            <a class="text-decoration-none" href="<?= BASE . 'danh-muc/' . $danhmuc_slugvi ?>">
              <span><?= $danhmuc_name ?></span>
            </a>
          </li>
        <?php endif; ?>
        <?php if (isset($danhmuc_c2_slugvi) && isset($danhmuc_c2_name)): ?>
          <li class="breadcrumb-item">
            <a class="text-decoration-none" href="<?= BASE . 'cate/' . $danhmuc_c2_slugvi ?>">
              <span><?= $danhmuc_c2_name ?></span>
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
      <div class="left-pro-detail">
        <div class="d-flex justify-content-center align-items-center" style="width:100%;height:400px;">
          <a id="Zoom-1" class="MagicZoom"
            data-options="zoomMode: magnifier; zoomPosition: inner; hint: off; rightClick: true; expandCaption: false; history: false;"
            href="<?= BASE_ADMIN . UPLOADS . $kg_sanpham['file'] ?>" title="<?= $kg_sanpham['namevi'] ?>">
            <img src='<?= BASE_ADMIN . UPLOADS . $kg_sanpham['file'] ?>' alt='<?= $kg_sanpham['namevi'] ?>' /></a>
        </div>
        <?php if ($get_gallery && $get_gallery->num_rows > 0): ?>
          <div class="gallery-thumb-pro">
            <div class="owl-page owl-carousel owl-theme owl-pro-detail" data-items="screen:0|items:4|margin:10"
              data-nav="1" data-navcontainer=".control-pro-detail">
              <?php if ($get_gallery): ?>
                <div>
                  <a class="thumb-pro-detail" data-zoom-id="Zoom-1"
                    href="<?= BASE_ADMIN . UPLOADS . $kg_sanpham['file'] ?>">
                    <img class="w-100" src="<?= BASE_ADMIN . UPLOADS . $kg_sanpham['file'] ?>" />
                  </a>
                </div>
                <?php while ($resule_gallery = $get_gallery->fetch_assoc()) : ?>
                  <div>
                    <a class="thumb-pro-detail" data-zoom-id="Zoom-1"
                      href="<?= BASE_ADMIN . UPLOADS . $resule_gallery['photo'] ?>">
                      <img class="w-100" src="<?= BASE_ADMIN . UPLOADS . $resule_gallery['photo'] ?>" />
                    </a>
                  </div>
                <?php endwhile; ?>
              <?php endif ?>
            </div>
            <div class="control-pro-detail control-owl transition"></div>
          </div>
        <?php endif; ?>
      </div>

      <div class="right-pro-detail">
        <p class="title-pro-detail mb-3"><?= $kg_sanpham['namevi'] ?></p>
        <ul class="attr-pro-detail">
          <li>
            <label class="attr-label-pro-detail">Mã sản phẩm:</label>
            <div class="attr-content-pro-detail"><?= $kg_sanpham['code'] ?></div>
          </li>
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
          <a target="_blank"
            href="tel:<?= isset($result_setting['hotline']) ? htmlspecialchars($result_setting['hotline']) : ''; ?>"><i><img
                src="assets/images/icon-t1.png" alt="Hotline"
                data-was-processed="true"></i><?= isset($result_setting['hotline']) ? htmlspecialchars($result_setting['hotline']) : ''; ?></a>
          <a target="_blank"
            href="https://zalo.me/<?= isset($result_setting['hotline']) ? htmlspecialchars($result_setting['hotline']) : ''; ?>"><i><img
                src="assets/images/icon-t2.png" alt="Zalo" data-was-processed="true"></i> Chat zalo</a>
        </div>
      </div>
      <div class="policy-detail">
        <?php $show_tieuchi = $tieuchi->show_tieuchi("hienthi");
        if ($show_tieuchi): ?>
          <?php while ($result_tieuchi = $show_tieuchi->fetch_assoc()): ?>
            <div class="list-policy">
              <div class="i-policy">
                <a class="scale-img hover-glass text-decoration-none" href="" title="<?= $result_tieuchi['name'] ?>">
                  <img class=".w-100" src="<?= BASE_ADMIN . UPLOADS . $result_tieuchi['file'] ?>"
                    alt="<?= $result_tieuchi['name'] ?>" alt="<?= $result_tieuchi['name'] ?>" width="40" height="40">
                </a>
                <div class="content">
                  <h3 class="text-split" title="<?= $result_tieuchi['name'] ?>"><?= $result_tieuchi['name'] ?></h3>
                  <!-- <span class="policy-more">Xem ngay</span> -->
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
          <div class="noidung_anhien">
            <div class="content-main content-ck" id="toc-content">
              <?= $kg_sanpham['contentvi'] ?>
            </div>
            <p class="anhien xemthemnd">Xem thêm nội dung</p>
            <p class="anhien anbot">Ẩn bớt nội dung</p>
          </div>
        </div>
        <div class="tab-pane fade" id="commentfb-pro-detail" role="tabpanel">
        </div>
      </div>
    </div>
    <?php if ($sanpham_lienquan && $sanpham_lienquan->num_rows > 0): ?>
      <div class="title-main mt-4">
        <h2>Sản phẩm cùng loại</h2>
      </div>
      <div class="grid-product">
        <?php while ($kg_sanpham_lienquan = $sanpham_lienquan->fetch_assoc()) : ?>
          <div class="item-product">
            <a class="text-decoration-none" href="san-pham/<?= $kg_sanpham_lienquan['slugvi'] ?>"
              title="<?= $kg_sanpham_lienquan['namevi'] ?>">
              <div class="images">
                <img class="w-100" src="<?= BASE_ADMIN . UPLOADS . $kg_sanpham_lienquan['file'] ?>"
                  alt="<?= $kg_sanpham_lienquan['namevi'] ?>">
              </div>
              <div class="content">
                <div class="title">
                  <h3><?= $kg_sanpham_lienquan['namevi'] ?></h3>
                  <p class="price-product">
                    <?php
                    if (!empty($kg_sanpham_lienquan['sale_price']) && !empty($kg_sanpham_lienquan['regular_price'])) {
                      echo '<span class="price-new">' . $kg_sanpham_lienquan['sale_price'] . '</span>';
                      echo '<span class="price-old">' . $kg_sanpham_lienquan['regular_price'] . '</span>';
                    } elseif (!empty($kg_sanpham_lienquan['regular_price'])) {
                      echo '<span class="price-new">' . $kg_sanpham_lienquan['regular_price'] . '</span>';
                    } else {
                      echo '<span class="price-new">Liên hệ</span>';
                    }
                    ?>
                  </p>
                  <div class="info-product">
                    <p><i class="fa-solid fa-eye"></i><?= $kg_sanpham_lienquan['views'] ?> lượt xem</p>
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
<?php include 'inc/footer.php'; ?>