<!-- SEO -->
<?php
$favicon = $d->rawQueryOne("select photo from #_photo where type = ? and act = ? and find_in_set('hienthi',status) limit 0,1", array('favicon', 'photo_static'));
$slugurlArray = '';
$seo_create = '';
if (($com == "static" || $com == "seopage") && isset($config['website']['comlang'])) {
  foreach ($config['website']['comlang'] as $k => $v) {
    if ($type == $k) {
      $slugurlArray = $v;
      break;
    }
  }
}
?>
<div class="card-seo">
  <div class="card card-primary card-outline card-outline-tabs">
    <div class="card-header p-0 border-bottom-0">
      <ul class="nav nav-tabs" id="custom-tabs-three-tab-lang" role="tablist">
        <?php foreach ($config['website']['seo'] as $k => $v) {
          $seo_create .= $k . ","; ?>
          <li class="nav-item">
            <a class="nav-link <?= ($k == 'vi') ? 'active' : '' ?>" id="tabs-lang" data-toggle="pill" href="#tabs-seolang-<?= $k ?>" role="tab" aria-controls="tabs-seolang-<?= $k ?>" aria-selected="true">SEO (<?= $v ?>)</a>
          </li>
        <?php } ?>
      </ul>
    </div>
    <div class="card-body">
      <div class="tab-content" id="custom-tabs-three-tabContent-lang">
        <?php foreach ($config['website']['seo'] as $k => $v) { ?>
          <div class="tab-pane fade show <?= ($k == 'vi') ? 'active' : '' ?>" id="tabs-seolang-<?= $k ?>" role="tabpanel" aria-labelledby="tabs-lang">
            <div class="form-group">
              <div class="label-seo">
                <label for="title<?= $k ?>">SEO Title (<?= $k ?>):</label>
                <strong class="count-seo"><span><?= strlen(htmlspecialchars(@$seoDB['title' . $k] ?: '')) ?></span>/70 <?= kytu ?></strong>
              </div>
              <input type="text" class="form-control check-seo title-seo text-sm" name="dataSeo[title<?= $k ?>]" id="title<?= $k ?>" placeholder="SEO Title (<?= $k ?>)" value="<?= (!empty($flash->has('title' . $k))) ? $flash->get('title' . $k) : @$seoDB['title' . $k] ?>">
            </div>
            <div class="form-group">
              <div class="label-seo">
                <label for="keywords<?= $k ?>">SEO Keywords (<?= $k ?>):</label>
                <strong class="count-seo"><span><?= strlen(htmlspecialchars(@$seoDB['keywords' . $k] ?: '')) ?></span>/70 <?= kytu ?></strong>
              </div>
              <input type="text" class="form-control check-seo keywords-seo text-sm" name="dataSeo[keywords<?= $k ?>]" id="keywords<?= $k ?>" placeholder="SEO Keywords (<?= $k ?>)" value="<?= (!empty($flash->has('keywords' . $k))) ? $flash->get('keywords' . $k) : @$seoDB['keywords' . $k] ?>">
            </div>
            <div class="form-group">
              <div class="label-seo">
                <label for="description<?= $k ?>">SEO Description (<?= $k ?>):</label>
                <strong class="count-seo"><span><?= strlen(htmlspecialchars(@$seoDB['description' . $k] ?: '')) ?></span>/160 <?= kytu ?></strong>
              </div>
              <textarea class="form-control check-seo description-seo text-sm" name="dataSeo[description<?= $k ?>]" id="description<?= $k ?>" rows="5" placeholder="SEO Description (<?= $k ?>)"><?= $func->decodeHtmlChars($flash->get('description' . $k)) ?: $func->decodeHtmlChars(@$seoDB['description' . $k]) ?></textarea>
            </div>

            <label class="label-seo-preview mb-2 d-flex align-items-center"><i class="fa fa-info-circle mr-2 text-primary"></i><strong><?= hienthitrenketquatimkiem ?>:</strong></label>
            <div class="seo-preview-google">
              <div class="d-flex align-items-start mb-1"><?php if (!empty($favicon['file'])): ?><div class="favicon-wrapper mr-2 rounded-circle">
                    <?= $func->getImage(['class' => 'favicon rounded-circle border', 'sizes' => '', 'upload' => UPLOAD, 'image' => $favicon['photo'], 'alt' => 'favicon']) ?>
                  </div><?php else: ?><div class="favicon-wrapper mr-2 rounded-circle" style="background-color:#e5edff;"><svg style="width:20px;height:20px;" focusable="false" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                      <path fill="#4285F4" d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z" />
                    </svg></div><?php endif; ?><div>
                  <div class="web-name text-dark fw-bold small"><?= $setting["name{$lang}"] ?></div>
                  <div class="seo-url text-muted small" id="seourlpreview<?= $k ?>"><?= rtrim($configBase, '/') ?> › <span class="seo-dot-menu ml-1">⋮</span></div>
                </div>
              </div>
              <div class="seo-title" id="title-seo-preview<?= $k ?>"><?= strlen(htmlspecialchars(@$seoDB['title' . $k] ?: 'Tiêu đề mô phỏng trang website của bạn')) ?></div>
              <div class="seo-description" id="description-seo-preview<?= $k ?>"><?= strlen(htmlspecialchars(@$seoDB['description' . $k] ?: 'Mô tả ngắn gọn sẽ hiển thị ở đây, giúp người dùng hiểu nội dung trang. Giữ khoảng 150-160 ký tự là đẹp.')) ?></div>
            </div>
          </div>
        <?php } ?>
      </div>
    </div>
    <input type="hidden" id="seo-create" value="<?= (isset($seo_create)) ? rtrim($seo_create, ",") : '' ?>">
  </div>
</div>
