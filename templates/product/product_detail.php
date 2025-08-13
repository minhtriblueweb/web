<div class="wrap-main wrap-home w-clear">
  <div class="wrap-main wrap-template w-clear">
    <div class="grid-pro-detail d-flex flex-wrap justify-content-between align-items-start">
      <div class="left-pro-detail">
        <div class="d-flex justify-content-center align-items-center">
          <a id="Zoom-1" class="MagicZoom"
            data-options="zoomMode: magnifier; zoomPosition: inner; hint: off; rightClick: true; expandCaption: false; history: false;"
            href="<?= $fn->getImageCustom(['file' => $rowDetail['file'], 'width' => $optsetting_json["san-pham_man_width"], 'height' => $optsetting_json["san-pham_man_height"], 'zc' => $optsetting_json["san-pham_man_zc"], 'src_only' => true, 'watermark' => $config['product'][$type]['watermark']]) ?>" title="<?= $rowDetail["name$lang"] ?>">
            <?= $fn->getImageCustom(['file' => $rowDetail['file'], 'alt' => $rowDetail["name$lang"], 'title' => $rowDetail["name$lang"], 'width' => $optsetting_json["san-pham_man_width"], 'height' => $optsetting_json["san-pham_man_height"], 'zc' => $optsetting_json["san-pham_man_zc"], 'lazy' => true, 'watermark' => $config['product'][$type]['watermark']]) ?>
          </a>
        </div>
        <?php if (!empty($rowDetailPhoto)): ?>
          <div class="gallery-thumb-pro">
            <div class="slick-pro-detail">
              <div>
                <a class="thumb-pro-detail" data-zoom-id="Zoom-1" href="<?= $fn->getImageCustom(['file' => $rowDetail['file'], 'watermark' => $config['product'][$type]['watermark'], 'src_only' => true]) ?>">
                  <?= $fn->getImageCustom(['file' => $rowDetail['file'], 'title' => $rowDetail["name$lang"], 'alt' => $rowDetail["name$lang"], 'class' => '', 'width' => $optsetting_json["san-pham_man_width"], 'height' => $optsetting_json["san-pham_man_height"], 'zc' => $optsetting_json["san-pham_man_zc"], 'lazy' => true, 'watermark' => $config['product'][$type]['watermark']]) ?>
                </a>
              </div>
              <?php foreach ($rowDetailPhoto as $gallery): ?>
                <div>
                  <a href=" <?= $fn->getImageCustom(['file' => $gallery['file'], 'watermark' => $config['product'][$type]['watermark'], 'width' => $optsetting_json["san-pham_man_width"], 'height' => $optsetting_json["san-pham_man_height"], 'zc' => $optsetting_json["san-pham_man_zc"], 'src_only' => true]) ?>" class="thumb-pro-detail" data-zoom-id="Zoom-1">
                    <?= $fn->getImageCustom(['file' => $gallery['file'], 'title' => $gallery['name'], 'alt' => $gallery['name'], 'class' => '', 'width' => $optsetting_json["san-pham_man_width"], 'height' => $optsetting_json["san-pham_man_height"], 'zc' => $optsetting_json["san-pham_man_zc"], 'lazy' => true, 'watermark' => $config['product'][$type]['watermark']]) ?>
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
        <p class="title-pro-detail mb-3"><?= $rowDetail["name$lang"] ?></p>
        <?php
        $params = array();
        $params['oaidzalo'] = $optsetting_json['oaidzalo'];
        $params['data-href'] = $fn->getCurrentPageURL();
        include TEMPLATE . LAYOUT . 'share.php'
        ?>
        <ul class="attr-pro-detail mt-3">
          <?php if (!empty($rowDetail['code'])): ?>
            <li>
              <label class="attr-label-pro-detail"><?= masp ?>:</label>
              <div class="attr-content-pro-detail"><?= $rowDetail['code'] ?></div>
            </li>
          <?php endif; ?>
          <?php if (!empty($productBrand)): ?>
            <li>
              <label class="attr-label-pro-detail">Thương hiệu:</label>
              <div class="attr-content-pro-detail">
                <a href="<?= $productBrand["slug$lang"] ?>"> <?= $fn->getImageCustom(['file' => $productBrand['file'], 'width' => 30, 'height' => 30, 'zc' => 2, 'alt' => $productBrand["name$lang"], 'title' => $productBrand["name$lang"], 'lazy' => false]) ?></a>
              </div>
            </li>
          <?php endif; ?>
          <li>
            <label class="attr-label-pro-detail"><?= luotxem ?>:</label>
            <div class="attr-content-pro-detail"><?= $rowDetail['views'] ?></div>
          </li>
          <li>
            <label class="attr-label-pro-detail"><?= gia ?>:</label>
            <div class="attr-content-pro-detail">
              <?php if (!empty($rowDetail['sale_price']) && !empty($rowDetail['regular_price'])): ?>
                <span class="price-new-pro-detail"><?= $rowDetail['sale_price'] . ' ₫' ?></span>
                <span class="price-old-pro-detail"><?= $rowDetail['regular_price'] . ' ₫' ?></span>
              <?php else: ?>
                <span class="price-new-pro-detail">
                  <?= !empty($rowDetail['regular_price']) ? $rowDetail['regular_price'] . ' ₫' : lienhe ?>
                </span>
              <?php endif; ?>
            </div>
          </li>
          <li class="d-flex flex-wrap align-items-center mt-3 mb-3">
            <label class="attr-label-pro-detail d-block me-2 mb-0">Số lượng:</label>
            <div class="attr-content-pro-detail d-flex flex-wrap align-items-center justify-content-between">
              <div class="quantity-pro-detail">
                <span class="quantity-minus-pro-detail">-</span>
                <input type="number" class="qty-pro" min="1" value="1" readonly="" fdprocessedid="x3n3no">
                <span class="quantity-plus-pro-detail">+</span>
              </div>
            </div>
          </li>
        </ul>
        <div class="desc-pro-detail content-ck"><?= $rowDetail["desc$lang"] ?></div>
        <div class="btn-pro-contact">
          <a class="" data-id="100" data-action="addnow"><i class="bi bi-basket2"></i><span><?= themvaogiohang ?> </span></a>
          <a target="_blank" href="tel:<?= str_replace(' ', '', $hotline) ?>"><i><img src="assets/images/icon-t1.png" alt="Hotline"></i><?= $hotline ?></a>
          <a target="_blank" href="https://zalo.me/<?= str_replace(' ', '', $hotline) ?>"><i><img src="assets/images/icon-t2.png" alt="Zalo"></i> Chat zalo</a>
        </div>
      </div>

      <div class="policy-detail">
        <?php if (!empty($tieuchi)): ?>
          <?php foreach ($tieuchi as $row_tc): ?>
            <div class="list-policy">
              <div class="i-policy">
                <a class="me-2" title="<?= $row_tc["name$lang"] ?>">
                  <?= $fn->getImageCustom([
                    'file' => $row_tc['file'],
                    'class' => '',
                    'alt' => $row_tc["name$lang"],
                    'title' => $row_tc["name$lang"],
                    'width' => $optsetting_json["tieu-chi_man_width"],
                    'height' => $optsetting_json["tieu-chi_man_height"],
                    'zc' => $optsetting_json["tieu-chi_man_zc"],
                    'lazy' => true
                  ]) ?>
                </a>
                <div class="content">
                  <h3 class="text-split" title="<?= $row_tc["name$lang"] ?>"><?= $row_tc["name$lang"] ?></h3>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>

    <div class="tabs-pro-detail">
      <ul class="nav nav-tabs" id="tabsProDetail" role="tablist">
        <li class="nav-item"><a class="nav-link active" id="info-pro-detail-tab" data-bs-toggle="tab" href="#info-pro-detail" role="tab"><?= thongtinsanpham ?></a></li>
        <li class="nav-item"><a class="nav-link" id="commentfb-pro-detail-tab" data-bs-toggle="tab" href="#commentfb-pro-detail" role="tab"><?= binhluan ?></a></li>
      </ul>
      <div class="tab-content" id="tabsProDetailContent">
        <div class="tab-pane fade show active" id="info-pro-detail" role="tabpanel">
          <div class="content-main content-ck" id="toc-content">
            <?= $fn->decodeHtmlChars($rowDetail["content$lang"]) ?>
          </div>
        </div>
        <div class="tab-pane fade" id="commentfb-pro-detail" role="tabpanel"></div>
      </div>
    </div>

    <?php if (!empty($product)): ?>
      <div class="title-main mt-4" data-aos="fade-up" data-aos-duration="500">
        <h2><?= sanphamcungloai ?></h2>
      </div>
      <div class="slick-product slick-d-none" data-aos="fade-up" data-aos-duration="500">
        <?php foreach ($product as $k => $v): ?>
          <?php include TEMPLATE . LAYOUT . 'item-product.php'; ?>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
    <?php include TEMPLATE . LAYOUT . 'danhmuc.php'; ?>
  </div>
</div>
