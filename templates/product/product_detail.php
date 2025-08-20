<div class="wrap-main wrap-home w-clear">
  <div class="wrap-main wrap-template w-clear">
    <div class=".grid-pro-detail .d-flex .flex-wrap .justify-content-between .align-items-start">
      <div class="row">
        <div class="col-12 col-lg-9">
          <div class="row">
            <div class="col-12 col-lg-6">
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
                <?php if ($khuyenmai): ?>
                  <div class="box-desc-pro-detail border border-danger text-start mt-3">
                    <div class="title-desc-pro-detail bg-danger">
                      <span><?= $khuyenmai["name$lang"] ?></span>
                    </div>
                    <div class="desc-pro-detail content-ck"><?= $khuyenmai["content$lang"] ?></div>
                  </div>
                <?php endif ?>
              </div>
            </div>
            <div class="col-12 col-lg-6">
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
                <?php if ($rowDetail["desc$lang"]): ?>
                  <div class="box-desc-pro-detail border-main">
                    <div class="title-desc-pro-detail bg-main">
                      <span><?= motasanpham ?></span>
                    </div>
                    <div class="desc-pro-detail content-ck"><?= $rowDetail["desc$lang"] ?></div>
                  </div>
                <?php endif ?>
                <div class="btn-pro-contact">
                  <a target="_blank" href="" class="me-3"><i><img class="filter-white" src="assets/images/shopping-cart.png"></i><?= themvaogiohang ?></a>
                  <a target="_blank" href="https://zalo.me/<?= str_replace(' ', '', $hotline) ?>"><i><img src="assets/images/icon-t2.png" alt="Zalo"></i> Chat zalo</a>
                </div>
              </div>
            </div>
          </div>
          <!-- Thông tin sản phẩm -->
          <div class="tabs-pro-detail">
            <ul class="nav nav-tabs" id="tabsProDetail" role="tablist">
              <li class="nav-item">
                <a class="nav-link active" id="info-pro-detail-tab" data-bs-toggle="tab" href="#info-pro-detail" role="tab">
                  <?= thongtinsanpham ?>
                </a>
              </li>
              <?php if ($muahang): ?>
                <li class="nav-item">
                  <a class="nav-link" id="muahang-pro-detail-tab" data-bs-toggle="tab" href="#muahang-pro-detail" role="tab">
                    <?= $muahang["name$lang"] ?>
                  </a>
                </li>
              <?php endif ?>
              <li class="nav-item">
                <a class="nav-link" id="commentfb-pro-detail-tab" data-bs-toggle="tab" href="#commentfb-pro-detail" role="tab">
                  <?= binhluan ?>
                </a>
              </li>
            </ul>

            <div class="tab-content" id="tabsProDetailContent">
              <div class="tab-pane fade show active" id="info-pro-detail" role="tabpanel">
                <div class="content-main content-ck content-text" id="toc-content">
                  <?= $fn->decodeHtmlChars($rowDetail["content$lang"]) ?>
                </div>
              </div>
              <?php if ($muahang): ?>
                <div class="tab-pane fade" id="muahang-pro-detail" role="tabpanel">
                  <div class="content-main content-ck content-text">
                    <?= $fn->decodeHtmlChars($muahang["content$lang"]) ?>
                  </div>
                </div>
              <?php endif ?>
              <div class="tab-pane fade" id="commentfb-pro-detail" role="tabpanel">
                <div class="fb-comments" data-href="<?= $fn->getCurrentPageURL() ?>" data-numposts="3" data-colorscheme="light" data-width="100%"></div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-12 col-lg-3">
          <div class="policy-detail">
            <?php if (!empty($camket)): ?>
              <div class="box-desc-pro-detail border-main">
                <div class="title-desc-pro-detail bg-main">
                  <span><?= $camket["name$lang"] ?></span>
                </div>
                <div class="table-criteria"><?= $camket["content$lang"] ?></div>
              </div>
            <?php endif ?>
            <div class="box-desc-pro-detail border-main">
              <div class="title-desc-pro-detail bg-main">
                <span>Chính sách hậu mãi</span>
              </div>
              <div class="table-criteria">
                <ul>
                  <?php foreach ($show_chinhsach as $row): ?>
                    <li>
                      <a class="transition" href="<?= $row['slug' . $lang] ?>" title="<?= $row['name' . $lang] ?>"><?= $row['name' . $lang] ?> →</a>
                    </li>
                  <?php endforeach; ?>
                </ul>
              </div>
            </div>
            <?php if (!empty($tieuchi)): ?>
              <?php foreach ($tieuchi as $row_tc): ?>
                <div class="i-policy hover-glass shadow-sm">
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
              <?php endforeach; ?>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>

    <?php if (!empty($product)): ?>
      <div class="title-main mt-4" data-aos="fade-up" data-aos-duration="500">
        <h2><?= sanphamcungloai ?></h2>
      </div>
      <div class="slick-product slick-d-none border-0" data-aos="fade-up" data-aos-duration="500">
        <?php foreach ($product as $k => $v): ?>
          <?php include TEMPLATE . LAYOUT . 'item-product.php'; ?>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
    <?php include TEMPLATE . LAYOUT . 'danhmuc.php'; ?>
  </div>
</div>
