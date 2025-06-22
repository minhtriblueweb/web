<div class="card card-primary card-outline text-sm">
  <div class="card-header">
    <h3 class="card-title">Nội dung SEO</h3>
    <a class="btn btn-sm bg-gradient-success d-inline-block text-white float-right create-seo" title="Tạo SEO">
      Tạo SEO</a>
  </div>
  <div class="card-body">
    <!-- SEO -->
    <div class="card-seo">
      <div class="card card-primary card-outline card-outline-tabs">
        <div class="card-header p-0 border-bottom-0">
          <ul class="nav nav-tabs" id="custom-tabs-three-tab-lang" role="tablist">
            <?php foreach ($config['website']['lang'] as $k => $v) { ?>
              <li class="nav-item">
                <a class="nav-link <?= ($k == 'vi') ? 'active' : '' ?>" id="tabs-lang-<?= $k ?>" data-toggle="pill"
                  href="#tabs-seolang-<?= $k ?>" role="tab" aria-controls="tabs-seolang-<?= $k ?>"
                  aria-selected="<?= ($k == 'vi') ? 'true' : 'false' ?>">
                  SEO (<?= $v ?>)
                </a>
              </li>
            <?php } ?>
          </ul>
        </div>
        <div class="card-body">
          <div class="tab-content" id="custom-tabs-three-tabContent-lang">
            <?php foreach ($config['website']['lang'] as $k => $v) { ?>
              <div class="tab-pane fade show <?= ($k == 'vi') ? 'active' : '' ?>" id="tabs-seolang-<?= $k ?>"
                role="tabpanel" aria-labelledby="tabs-lang-<?= $k ?>">

                <!-- SEO Title -->
                <div class="form-group">
                  <div class="label-seo">
                    <label for="title<?= $k ?>">SEO Title (<?= $k ?>):</label>
                    <strong class="count-seo"><span>0</span>/70 ký tự</strong>
                  </div>
                  <input type="text"
                    class="form-control check-seo title-seo text-sm"
                    name="title<?= $k ?>" id="title<?= $k ?>"
                    placeholder="SEO Title (<?= $k ?>)"
                    value="<?= $_POST['title' . $k] ?? ($result['title' . $k] ?? '') ?>">
                </div>

                <!-- SEO Keywords -->
                <div class="form-group">
                  <div class="label-seo">
                    <label for="keywords<?= $k ?>">SEO Keywords (<?= $k ?>):</label>
                    <strong class="count-seo"><span>0</span>/70 ký tự</strong>
                  </div>
                  <input type="text"
                    class="form-control check-seo keywords-seo text-sm"
                    name="keywords<?= $k ?>" id="keywords<?= $k ?>"
                    placeholder="SEO Keywords (<?= $k ?>)"
                    value="<?= $_POST['keywords' . $k] ?? ($result['keywords' . $k] ?? '') ?>">
                </div>

                <!-- SEO Description -->
                <div class="form-group">
                  <div class="label-seo">
                    <label for="description<?= $k ?>">SEO Description (<?= $k ?>):</label>
                    <strong class="count-seo"><span>0</span>/160 ký tự</strong>
                  </div>
                  <textarea
                    class="form-control check-seo description-seo text-sm"
                    name="description<?= $k ?>" id="description<?= $k ?>"
                    rows="5"
                    placeholder="SEO Description (<?= $k ?>)"><?= $_POST['description' . $k] ?? ($result['description' . $k] ?? '') ?></textarea>
                </div>
              </div>
            <?php } ?>
          </div>
        </div>
        <input type="hidden" id="seo-create" value="vi">
      </div>
    </div>
  </div>
</div>
