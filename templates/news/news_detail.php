<div class="wrap-main wrap-home w-clear">
  <div class="wrap-content mt-3 p-2">
    <div class="row">
      <div class="<?= !empty($relatedNews) ? 'col-lg-9 mb-3' : 'col-12' ?>">
        <div class="title-list-hot p-2">
          <h2 class="text-start"><?= $rowDetail["name$lang"] ?></h2>
        </div>
        <div class="wrap-main wrap-template content-ck w-clear" style="margin: 0 auto !important;">
          <div class="wrap-toc">
            <div class="meta-toc2">
              <a class="mucluc-dropdown-list_button">Mục Lục</a>
              <div class="box-readmore">
                <ul class="toc-list" data-toc="article" data-toc-headings="h1, h2, h3"></ul>
              </div>
            </div>
          </div>
          <div class="content-main" id="toc-content">
            <?= $fn->decodeHtmlChars($rowDetail["content$lang"] ?? '') ?>
          </div>
          <div class="share">
            <b><?= chiase ?>:</b>
            <div class="social-plugin w-clear">
              <?php
              $params = array();
              $params['oaidzalo'] = $optsetting_json['oaidzalo'];
              $params['data-href'] = $fn->getCurrentPageURL();
              include TEMPLATE . LAYOUT . 'share.php'
              ?>
            </div>
          </div>
        </div>
      </div>
      <?php if (!empty($relatedNews)): ?>
        <div class="col-lg-3">
          <div class="othernews">
            <h2 class="titleSide"><span>Danh mục bài viết</span></h2>
            <div class="frame-left">
              <div class="box-left-menu">
                <ul class="list-btn-position show">
                  <li><a href="tin-tuc" title="Tin tức">Tin tức</a></li>
                  <li><a href="gioi-thieu" title="Giới thiệu">Về chúng tôi</a>
                  <li><a href="huong-dan-choi" title="Hướng dẫn chơi">Hướng Dẫn Chơi</a>
                  <li><a href="mua-hang" title="Mua hàng">Mua hàng</a></li>
                  <li><a href="thanh-toan" title="Phương thức thanh toán">Phương thức thanh toán</a></li>
                  <?php if ($show_chinhsach): ?>
                    <li>
                      <a title="Chính sách" data-bs-toggle="collapse" href="#show-chinh-sach" role="button" aria-expanded="false" aria-controls="show-chinh-sach">
                        Chính sách
                      </a>
                      <i class="fas fa-chevron-down" data-bs-toggle="collapse" href="#show-chinh-sach"></i>
                      <ul class="collapse" id="show-chinh-sach">
                        <?php foreach ($show_chinhsach as $cs): ?>
                          <li>
                            <a href="<?= $cs["slug$lang"] ?>" title="<?= $cs["name$lang"] ?>">
                              <?= $cs["name$lang"] ?>
                            </a>
                          </li>
                        <?php endforeach; ?>
                      </ul>
                    </li>
                  <?php endif; ?>
                </ul>
              </div>
            </div>
            <h2 class="titleSide"><span><?= tinlienquan ?>:</span></h2>
            <div class="frame-left">
              <div class="row">
                <?php foreach ($relatedNews as $v): ?>
                  <div class="col-lg-12 col-md-6">
                    <div class="news-other d-flex flex-wrap">
                      <a class="scale-img text-decoration-none pic-news-other" href="<?= $v["slug$lang"] ?>" title="<?= $v["name$lang"] ?>">
                        <?= $fn->getImageCustom([
                          'file' => $v['file'],
                          'class' => 'w-100',
                          'width' => 210,
                          'height' => 144,
                          'zc' => 1,
                          'alt' => $v["name$lang"],
                          'title' => $v["name$lang"],
                          'lazy' => true
                        ]) ?>
                      </a>
                      <div class="info-news-other">
                        <a class="name-news-other text-decoration-none" href="<?= $v["slug$lang"] ?>" title="<?= $v["name$lang"] ?>"><?= $v["name$lang"] ?></a>
                      </div>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
            </div>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>
