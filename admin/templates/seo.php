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
            <li class="nav-item">
              <a class="nav-link active" id="tabs-lang" data-toggle="pill" href="#tabs-seolang-vi" role="tab"
                aria-controls="tabs-seolang-vi" aria-selected="true">SEO (Tiếng Việt)</a>
            </li>
          </ul>
        </div>
        <div class="card-body">
          <div class="tab-content" id="custom-tabs-three-tabContent-lang">
            <div class="tab-pane fade show active" id="tabs-seolang-vi" role="tabpanel" aria-labelledby="tabs-lang">
              <div class="form-group">
                <div class="label-seo">
                  <label for="title">SEO Title (vi):</label>
                  <strong class="count-seo"><span>0</span>/70 ký tự</strong>
                </div>
                <input type="text" class="form-control check-seo title-seo text-sm" name="title" id="title"
                  placeholder="SEO Title (vi)" value="<?= $_POST['title'] ?? ($result['title'] ?? "") ?>">
              </div>
              <div class="form-group">
                <div class="label-seo">
                  <label for="titlekeywords">SEO Keywords (vi):</label>
                  <strong class="count-seo"><span>0</span>/70 ký tự</strong>
                </div>
                <input type="text" class="form-control check-seo keywords-seo text-sm" name="titlekeywords"
                  id="titlekeywords" placeholder="SEO Keywords (vi)" value="<?= $_POST['titlekeywords'] ?? ($result['titlekeywords'] ?? "") ?>">
              </div>
              <div class="form-group">
                <div class="label-seo">
                  <label for="description">SEO Description (vi):</label>
                  <strong class="count-seo"><span>0</span>/160 ký tự</strong>
                </div>
                <textarea class="form-control check-seo description-seo text-sm" name="description"
                  id="description" rows="5" placeholder="SEO Description (vi)"><?= $_POST['description'] ?? ($result['description'] ?? "") ?></textarea>
              </div>
            </div>
          </div>
        </div>
        <input type="hidden" id="seo-create" value="vi">
      </div>
    </div>
  </div>
</div>
