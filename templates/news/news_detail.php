<div class="wrap-main wrap-home w-clear">
  <div class="wrap-content mt-3 p-2">
    <div class="row">
      <div class="<?= !empty($relatedNews) ? 'col-lg-9 mb-3' : 'col-12' ?>">
        <div class="title-list-hot p-2">
          <h2 class="text-start"><?= $rowDetail["name$lang"] ?></h2>
        </div>
        <div class="wrap-main wrap-template w-clear" style="margin: 0 auto !important;">
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
          <div class="share othernews mb-3">
            <b>Tin liên quan:</b>
            <div class="fix__row__news">
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
