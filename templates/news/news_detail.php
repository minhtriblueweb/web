<div class="wrap-main wrap-home w-clear">
  <div class="wrap-content mt-3 p-2">
    <div class="row">
      <div class="<?= !empty($relatedNews) ? 'col-lg-9 mb-3' : 'col-12' ?>">
        <div class="title-list-hot p-2 mb-0">
          <h2><?= $rowDetail["name$lang"] ?? $titleMain ?></h2>
        </div>
        <div class="wrap-main wrap-template w-clear" style="margin: 0 auto !important;">
          <div class="pucblisher d-flex justify-content-center mt-2">
            <p class="me-3 mb-0"><i class="fa-regular fa-calendar me-2"></i><?= $fn->makeDate(strtotime($rowDetail["created_at"]), '/', $lang, true); ?></p>
            <p class="mb-0"><i class="fa-solid fa-eye me-2"></i><?= $rowDetail["views"] ?> <?= luotxem ?></p>
          </div>
          <?php if ($rowDetail["desc$lang"]): ?>
            <div class="content-main">
              <div class="alert alert-news p-3" role="alert">
                <?= $fn->decodeHtmlChars($rowDetail["desc$lang"] ?? '') ?>
              </div>
            </div>
          <?php endif ?>
          <div class="wrap-toc">
            <div class="meta-toc2">
              <div class="mucluc-dropdown-list_button">Mục Lục bài viết</div>
              <div class="box-readmore">
                <ul class="toc-list" data-toc="article" data-toc-headings="h1, h2, h3"></ul>
              </div>
            </div>
          </div>
          <div class="content-main content-ck" id="toc-content">
            <?= $fn->decodeHtmlChars($rowDetail["content$lang"] ?? '') ?>
            <?php if ($showFooterNews): ?>
              <?= $footer_news["content$lang"] ?? '' ?>
            <?php endif; ?>
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
          <?php include TEMPLATE . LAYOUT . 'othernews.php' ?>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>
