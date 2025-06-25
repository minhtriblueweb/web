<?php
$get_setting = $setting->get_setting();
if ($get_setting) {
  $row_st = $get_setting->fetch_assoc();
}
$favicon = !empty($row_st['favicon']) ? BASE_ADMIN . UPLOADS . $row_st['favicon'] : NO_IMG;
?>
<div class="card card-primary card-outline text-sm">
  <div class="card-header">
    <h3 class="card-title">Nội dung SEO</h3>
    <a class="btn btn-sm bg-gradient-success d-inline-block text-white float-right create-seo" title="Tạo SEO">Tạo SEO</a>
  </div>
  <div class="card-body">
    <div class="card-seo">
      <div class="card card-primary card-outline card-outline-tabs">
        <div class="card-header p-0 border-bottom-0">
          <ul class="nav nav-tabs" id="custom-tabs-lang" role="tablist">
            <?php foreach ($config['website']['lang'] as $k => $v): ?>
              <li class="nav-item">
                <a class="nav-link <?= ($k == 'vi') ? 'active' : '' ?>" id="tab-<?= $k ?>" data-toggle="pill" href="#tab-content-<?= $k ?>" role="tab" aria-controls="tab-content-<?= $k ?>" aria-selected="<?= ($k == 'vi') ? 'true' : 'false' ?>">
                  SEO (<?= $v ?>)
                </a>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>

        <div class="card-body">
          <div class="tab-content" id="tab-content-lang">
            <?php foreach ($config['website']['lang'] as $k => $v): ?>
              <div class="tab-pane fade show <?= ($k == 'vi') ? 'active' : '' ?>" id="tab-content-<?= $k ?>" role="tabpanel" aria-labelledby="tab-<?= $k ?>">

                <!-- SEO Title -->
                <div class="form-group">
                  <div class="label-seo">
                    <label for="title<?= $k ?>">SEO Title (<?= $k ?>):</label>
                    <strong class="count-seo"><span>0</span>/70 ký tự</strong>
                  </div>
                  <input type="text" class="form-control check-seo title-seo text-sm" name="title<?= $k ?>" id="title<?= $k ?>" placeholder="SEO Title (<?= $k ?>)" value="<?= $_POST['title' . $k] ?? ($result['title' . $k] ?? '') ?>">
                </div>

                <!-- SEO Keywords -->
                <div class="form-group">
                  <div class="label-seo">
                    <label for="keywords<?= $k ?>">SEO Keywords (<?= $k ?>):</label>
                    <strong class="count-seo"><span>0</span>/70 ký tự</strong>
                  </div>
                  <input type="text" class="form-control check-seo keywords-seo text-sm" name="keywords<?= $k ?>" id="keywords<?= $k ?>" placeholder="SEO Keywords (<?= $k ?>)" value="<?= $_POST['keywords' . $k] ?? ($result['keywords' . $k] ?? '') ?>">
                </div>

                <!-- SEO Description -->
                <div class="form-group">
                  <div class="label-seo">
                    <label for="description<?= $k ?>">SEO Description (<?= $k ?>):</label>
                    <strong class="count-seo"><span>0</span>/160 ký tự</strong>
                  </div>
                  <textarea class="form-control check-seo description-seo text-sm" name="description<?= $k ?>" id="description<?= $k ?>" rows="5" placeholder="SEO Description (<?= $k ?>)"><?= $_POST['description' . $k] ?? ($result['description' . $k] ?? '') ?></textarea>
                </div>

                <!-- SEO Preview -->
                <label class="label-seo-preview mb-2 d-flex align-items-center">
                  <i class="fa fa-info-circle mr-2 text-primary"></i>
                  <strong>Hiển thị trên kết quả tìm kiếm:</strong>
                </label>
                <div class="seo-preview-google">
                  <div class="d-flex align-items-start mb-1">
                    <?php if (!empty($favicon)): ?>
                      <div class="favicon-wrapper mr-2">
                        <img src="<?= $favicon ?>" alt="favicon" class="favicon rounded-circle border" />
                      </div>
                    <?php endif; ?>
                    <div>
                      <div class="web-name text-dark fw-bold small"><?= $web_name ?></div>
                      <div class="seo-url text-muted small" id="seourlpreview<?= $k ?>">
                        <?= BASE ?><?= !empty($_POST['slug' . $k]) ? $_POST['slug' . $k] : ($result['slug' . $k] ?? '') ?>
                        <span class="seo-dot-menu ml-1">⋮</span>
                      </div>
                    </div>
                  </div>
                  <div class="seo-title" id="title-seo-preview<?= $k ?>">
                    <?= trim($_POST['title' . $k] ?? $result['title' . $k] ?? '') ?: 'Tiêu đề mô phỏng trang website của bạn' ?>
                  </div>
                  <div class="seo-description" id="description-seo-preview<?= $k ?>">
                    <?= trim($_POST['description' . $k] ?? $result['description' . $k] ?? '') ?: 'Mô tả ngắn gọn sẽ hiển thị ở đây, giúp người dùng hiểu nội dung trang. Giữ khoảng 150-160 ký tự là đẹp.' ?>
                  </div>
                </div>
              </div>
              <input type="hidden" id="seo-create" value="<?= implode(',', array_keys($config['website']['lang'])) ?>">
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
