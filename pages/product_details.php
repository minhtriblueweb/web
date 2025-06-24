<?php
require_once $baseDir . '/sources/product_details.php';
?>
<div class="wrap-main wrap-home w-clear">
  <div class="breadCrumbs">
    <div class="wrap-content">
      <ol class="breadcrumb">
        <?php foreach ($breadcrumbs as $item): ?>
          <li class="breadcrumb-item<?= !empty($item['active']) ? ' active' : '' ?>">
            <a class="text-decoration-none" href="<?= $item['url'] ?>">
              <span><?= $item['label'] ?></span>
            </a>
          </li>
        <?php endforeach; ?>
      </ol>
    </div>
  </div>
  <div class="wrap-main wrap-template w-clear">
    <div class="grid-pro-detail d-flex flex-wrap justify-content-between align-items-start">
      <div class="left-pro-detail">
        <div class="d-flex justify-content-center align-items-center">
          <a id="Zoom-1" class="MagicZoom"
            data-options="zoomMode: magnifier; zoomPosition: inner; hint: off; rightClick: true; expandCaption: false; history: false;"
            href="<?= $img_main ?>" title="<?= $img_alt ?>">
            <img src="<?= $img_main ?>" alt="<?= $img_alt ?>" />
          </a>
        </div>
        <?php if ($get_gallery && $get_gallery->num_rows > 0): ?>
          <div class="gallery-thumb-pro">
            <div class="owl-page owl-carousel owl-theme owl-pro-detail" data-items="screen:0|items:4|margin:10"
              data-nav="1" data-navcontainer=".control-pro-detail">
              <?php
              // Ảnh đại diện sản phẩm
              echo '<div>
                <a class="thumb-pro-detail" data-zoom-id="Zoom-1" href="' . $img_main . '">
                  <img class="w-100" src="' . $img_main . '" />
                </a>
              </div>';

              // Các ảnh gallery
              while ($gallery = $get_gallery->fetch_assoc()):
                $img_src = !empty($gallery['file']) ? BASE_ADMIN . UPLOADS . $gallery['file'] : NO_IMG;
              ?>
                <div>
                  <a href="<?= $img_src ?>" class="thumb-pro-detail" data-zoom-id="Zoom-1">
                    <img class="w-100" src="<?= $img_src ?>" />
                  </a>
                </div>
              <?php endwhile; ?>
            </div>
            <div class="control-pro-detail control-owl transition"></div>
          </div>
        <?php endif; ?>
      </div>

      <div class="right-pro-detail">
        <p class="title-pro-detail mb-3"><?= $row_sp['namevi'] ?></p>
        <ul class="attr-pro-detail">
          <?php if (!empty($row_sp['code'])): ?>
            <li>
              <label class="attr-label-pro-detail">Mã sản phẩm:</label>
              <div class="attr-content-pro-detail"><?= $row_sp['code'] ?></div>
            </li>
          <?php endif; ?>
          <li>
            <label class="attr-label-pro-detail">Lượt xem:</label>
            <div class="attr-content-pro-detail"><?= $row_sp['views'] ?></div>
          </li>
          <li>
            <label class="attr-label-pro-detail">Giá:</label>
            <div class="attr-content-pro-detail">
              <?php if (!empty($row_sp['sale_price']) && !empty($row_sp['regular_price'])): ?>
                <span class="price-new-pro-detail"><?= $row_sp['sale_price'] ?> ₫</span>
                <span class="price-old-pro-detail"><?= $row_sp['regular_price'] ?> ₫</span>
              <?php elseif (!empty($row_sp['sale_price'])): ?>
                <span class="price-new-pro-detail"><?= $row_sp['sale_price'] ?> ₫</span>
              <?php elseif (!empty($row_sp['regular_price'])): ?>
                <span class="price-new-pro-detail"><?= $row_sp['regular_price'] ?> ₫</span>
              <?php else: ?>
                <span class="price-new-pro-detail">Liên hệ</span>
              <?php endif; ?>
            </div>
          </li>
        </ul>
        <div class="desc-pro-detail content-ck"><?= $row_sp['descvi'] ?></div>
        <div class="btn-pro-contact">
          <a target="_blank" href="tel:<?= str_replace(' ', '', $hotline) ?>"><i><img src="assets/images/icon-t1.png" alt="Hotline"
                data-was-processed="true"></i><?= $hotline ?></a>
          <a target="_blank" href="https://zalo.me/<?= str_replace(' ', '', $hotline) ?>"><i><img src="assets/images/icon-t2.png" alt="Zalo"
                data-was-processed="true"></i> Chat zalo</a>
        </div>
      </div>
      <div class="policy-detail">
        <?php $show_tieuchi = $fn->show_data([
          'table' => 'tbl_tieuchi',
          'status' => 'hienthi',
        ]);
        if ($show_tieuchi): ?>
          <?php while ($row_tc = $show_tieuchi->fetch_assoc()): ?>
            <div class="list-policy">
              <div class="i-policy hover-glass">
                <img class="me-3"
                  src="<?= empty($row_tc['file']) ? NO_IMG : BASE_ADMIN . UPLOADS . $row_tc['file']; ?>"
                  alt="<?= $row_tc['namevi'] ?>">
                <div class="content">
                  <h3 class="text-split" title="<?= $row_tc['namevi'] ?>"><?= $row_tc['namevi'] ?></h3>
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
            <?= $row_sp['contentvi'] ?>
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
        <?php while ($row_lq = $sanpham_lienquan->fetch_assoc()) : ?>
          <?php
          $slug = $row_lq['slugvi'];
          $name = $row_lq['namevi'];
          $img_src = empty($row_lq['file'])
            ? NO_IMG
            : BASE_ADMIN . UPLOADS . $row_lq['file'];
          $views = !empty($row_lq['views']) ? $row_lq['views'] : 0;
          $sale_price = $row_lq['sale_price'];
          $regular_price = $row_lq['regular_price'];
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
        <?php // echo $pagination_html = $fn->renderPagination_index($current_page, $total_pages, $slug);
        ?>
      </div>
    <?php endif; ?>
  </div>
</div>
