<div class="wrap-main wrap-home w-clear">
  <?= $fn->dump($seo_data); ?>
  <div class="wrap-main wrap-template w-clear">
    <div class="grid-pro-detail d-flex flex-wrap justify-content-between align-items-start">
      <div class="left-pro-detail">
        <div class="d-flex justify-content-center align-items-center">
          <a id="Zoom-1" class="MagicZoom"
            data-options="zoomMode: magnifier; zoomPosition: inner; hint: off; rightClick: true; expandCaption: false; history: false;"
            href="<?= $fn->getImageCustom(['file' => $row_sp['file'], 'width' => 500, 'height' => 500, 'zc' => 1, 'src_only' => true, 'watermark' => true]) ?>" title="<?= $row_sp['name' . $lang] ?>">
            <?= $fn->getImageCustom(['file' => $row_sp['file'], 'alt' => $row_sp['name' . $lang], 'title' => $row_sp['name' . $lang], 'width' => 500, 'height' => 500, 'zc' => 1, 'lazy' => true, 'watermark' => true]) ?>
          </a>
        </div>
        <?php if (!empty($get_gallery)): ?>
          <div class="gallery-thumb-pro">
            <div class="slick-pro-detail">
              <div>
                <a class="thumb-pro-detail" data-zoom-id="Zoom-1" href="<?= $fn->getImageCustom(['file' => $row_sp['file'], 'watermark' => true, 'src_only' => true]) ?>">
                  <?= $fn->getImageCustom(['file' => $row_sp['file'], 'class' => '', 'width' => 500, 'height' => 500, 'zc' => 1, 'lazy' => true, 'watermark' => true]) ?>
                </a>
              </div>
              <?php foreach ($get_gallery as $gallery): ?>
                <div>
                  <a href=" <?= $fn->getImageCustom(['file' => $gallery['file'], 'watermark' => true, 'width' => 500, 'height' => 500, 'zc' => 1, 'src_only' => true]) ?>" class="thumb-pro-detail" data-zoom-id="Zoom-1">
                    <?= $fn->getImageCustom(['file' => $gallery['file'], 'class' => '', 'width' => 500, 'height' => 500, 'zc' => 1, 'lazy' => true, 'watermark' => true]) ?>
                  </a>
                </div>
              <?php endforeach; ?>
            </div>
            <div class="slick-arrow-control">
              <button class="slick-prev-btn slick-arrow-custom" type="button"><i class="fa fa-chevron-left"></i></button>
              <button class="slick-next-btn slick-arrow-custom" type="button"><i class="fa fa-chevron-right"></i></button>
            </div>
          </div>
        <?php endif; ?>
      </div>

      <div class="right-pro-detail">
        <p class="title-pro-detail mb-3"><?= $row_sp['name' . $lang] ?></p>
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
          <a target="_blank" href="tel:<?= str_replace(' ', '', $hotline) ?>"><i><img src="assets/images/icon-t1.png" alt="Hotline"></i><?= $hotline ?></a>
          <a target="_blank" href="https://zalo.me/<?= str_replace(' ', '', $hotline) ?>"><i><img src="assets/images/icon-t2.png" alt="Zalo"></i> Chat zalo</a>
        </div>
      </div>

      <div class="policy-detail">
        <?php if (!empty($tieuchi)): ?>
          <?php foreach ($tieuchi as $row_tc): ?>
            <div class="list-policy">
              <div class="i-policy">
                <?= $fn->getImageCustom(['file' => $row_tc['file'], 'class' => 'me-3', 'alt' => $row_tc['name' . $lang], 'title' => $row_tc['name' . $lang], 'width' => 40, 'height' => 40, 'zc' => 1, 'lazy' => true]) ?>
                <div class="content">
                  <h3 class="text-split" title="<?= $row_tc['name' . $lang] ?>"><?= $row_tc['name' . $lang] ?></h3>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>

    <div class="tabs-pro-detail">
      <ul class="nav nav-tabs" id="tabsProDetail" role="tablist">
        <li class="nav-item"><a class="nav-link active" id="info-pro-detail-tab" data-bs-toggle="tab" href="#info-pro-detail" role="tab">Thông tin sản phẩm</a></li>
        <li class="nav-item"><a class="nav-link" id="commentfb-pro-detail-tab" data-bs-toggle="tab" href="#commentfb-pro-detail" role="tab">Bình luận</a></li>
      </ul>
      <div class="tab-content" id="tabsProDetailContent">
        <div class="tab-pane fade show active" id="info-pro-detail" role="tabpanel">
          <div class="content-main content-ck" id="toc-content"><?= $row_sp['content' . $lang] ?></div>
        </div>
        <div class="tab-pane fade" id="commentfb-pro-detail" role="tabpanel"></div>
      </div>
    </div>

    <?php if (!empty($sanpham_lienquan)): ?>
      <div class="title-main mt-4" data-aos="fade-up" data-aos-duration="1000">
        <h2>Sản phẩm cùng loại</h2>
      </div>
      <div class="grid-product" data-aos="fade-up" data-aos-duration="1000">
        <?php foreach ($sanpham_lienquan as $row_lq): ?>
          <?php
          $slug = $row_lq['slug' . $lang];
          $name = $row_lq['name' . $lang];
          $views = $row_lq['views'] ?? 0;
          $sale_price = $row_lq['sale_price'];
          $regular_price = $row_lq['regular_price'];
          ?>
          <div class="item-product">
            <a class="text-decoration-none" href="<?= $slug ?>" title="<?= htmlspecialchars($name) ?>">
              <div class="images">
                <?= $fn->getImageCustom(['file' => $row_lq['file'], 'class' => 'w-100', 'alt' => $name, 'title' => $name, 'width' => 500, 'height' => 500, 'zc' => 1, 'lazy' => true, 'watermark' => true]) ?>
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
        <?php endforeach; ?>
      </div>

      <div class="pagination-home w-100">
        <?php /* $fn->renderPagination_index($current_page, $total_pages, $slug); */ ?>
      </div>
    <?php endif; ?>
  </div>
</div>
