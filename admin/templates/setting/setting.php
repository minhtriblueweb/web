<section class="content">
  <form class="validation-form" novalidate method="post" action="" enctype="multipart/form-data">
    <div class="card-footer text-sm sticky-top">
      <button name="upload" type="submit" class="btn btn-sm bg-gradient-primary .submit-check"><i class="far fa-save mr-2"></i>Lưu</button>
      <button type="reset" class="btn btn-sm bg-gradient-secondary"><i class="fas fa-redo mr-2"></i>Làm lại</button>
    </div>
    <?php
    /* <div class="card card-primary card-outline text-sm">
      <div class="card-header">
        <h3 class="card-title">Cấu hình kích thước hình ảnh (width x height x zoom crop:1,2,3)</h3>
      </div>
      <div class="card-body">
        <div class="row">
          <?php foreach ($config['size-img'] as $k => $v) { ?>
            <?php foreach ($v as $i => $val) {
              if ($val['active']) {  ?>
                <div class="form-group col-md-2 col-sm-2">
                  <label for="ip_host"><?= $val['title'] ?> :</label>
                  <div class="d-flex align-items-center">
                    <input step="1" min="1" pattern="[0-9]" type="number" class="form-control text-center text-sm p-1" name="data[options][<?= $k . '_' . $i . '_width' ?>]" id="<?= $k . '_' . $i . '_width' ?>" placeholder="W" value="<?= isset($options[$k . '_' . $i . '_width']) ? $options[$k . '_' . $i . '_width'] : $val['width'] ?>">
                    <span style="width: 30px; text-align: center;">x</span>
                    <input step="1" min="1" pattern="[0-9]" type="number" class="form-control text-center text-sm p-1" name="data[options][<?= $k . '_' . $i . '_height' ?>]" id="<?= $k . '_' . $i . '_height' ?>" placeholder="H" value="<?= isset($options[$k . '_' . $i . '_height']) ? $options[$k . '_' . $i . '_height'] : $val['height'] ?>">
                    <span style="width: 30px; text-align: center;">x</span>
                    <input step="1" min="1" max="3" type="number" class="form-control text-center text-sm p-1" name="data[options][<?= $k . '_' . $i . '_zc' ?>]" id="<?= $k . '_' . $i . '_zc' ?>" placeholder="Zc" value="<?= $options[$k . '_' . $i . '_zc'] ? $options[$k . '_' . $i . '_zc'] : 1 ?>">
                  </div>
                </div>
            <?php }
            } ?>
          <?php } ?>
        </div>
      </div>
    </div> */
    ?>
    <div class="card card-primary card-outline text-sm">
      <div class="card-header">
        <h3 class="card-title">Thông tin chung</h3>
      </div>
      <div class="card-body">
        <?php if (count($config['website']['lang']) > 1) { ?>
          <div class="form-group">
            <label>Ngôn ngữ mặc định:</label>
            <div class="form-group">
              <?php foreach ($config['website']['lang'] as $k => $v) { ?>
                <div class="custom-control custom-radio d-inline-block mr-3 text-md">
                  <input class="custom-control-input" type="radio" id="lang_default-<?= $k ?>" name="lang_default" value="<?= $k ?>"
                    <?= ($options['lang_default'] ?? $lang) == $k ? 'checked' : '' ?>>
                  <label for="lang_default-<?= $k ?>" class="custom-control-label font-weight-normal"><?= $v ?></label>
                </div>
              <?php } ?>
            </div>
          </div>
        <?php } ?>

        <div class="row">
          <?php if (!empty($config['setting']['color'])): ?>
            <div class="form-group col-md-4 col-sm-6">
              <label for="color">Màu Website:</label>
              <input type="text"
                class="form-control jscolor text-sm"
                name="color"
                id="background_color"
                maxlength="7"
                value="<?= htmlspecialchars($options['color'] ?? '') ?>"
                autocomplete="off"
                style="background-color: <?= htmlspecialchars($options['color'] ?? '') ?>; color: #fff;">
            </div>
          <?php endif; ?>
          <?php if (!empty($config['setting']['email'])): ?>
            <div class="form-group col-md-4 col-sm-6">
              <label for="email">Email:</label>
              <input type="email" class="form-control text-sm" name="email" id="email" placeholder="Email"
                value="<?= isset($options['email']) ? htmlspecialchars($options['email']) : ''; ?>">
            </div>
          <?php endif; ?>
          <?php if (!empty($config['setting']['hotline'])): ?>
            <div class="form-group col-md-4 col-sm-6">
              <label for="hotline">Hotline:</label>
              <input type="text" class="form-control text-sm" name="hotline" id="hotline" placeholder="Hotline"
                value="<?= isset($options['hotline']) ? htmlspecialchars($options['hotline']) : ''; ?>">
            </div>
          <?php endif; ?>
          <?php if (!empty($config['setting']['zalo'])): ?>
            <div class="form-group col-md-4 col-sm-6">
              <label for="zalo">Zalo:</label>
              <input type="text" class="form-control text-sm" name="zalo" id="zalo" placeholder="Zalo" value="<?= isset($options['zalo']) ? htmlspecialchars($options['zalo']) : ''; ?>">
            </div>
          <?php endif; ?>
          <?php if (!empty($config['setting']['oaidzalo'])): ?>
            <div class="form-group col-md-4 col-sm-6">
              <label for="oaidzalo">OAID Zalo :</label>
              <input type="text" class="form-control text-sm" name="oaidzalo" id="oaidzalo" placeholder="OAID Zalo" value="<?= isset($options['oaidzalo']) ? htmlspecialchars($options['oaidzalo']) : ''; ?>">
            </div>
          <?php endif; ?>
          <?php if (!empty($config['setting']['website'])): ?>
            <div class="form-group col-md-4 col-sm-6">
              <label for="website">Website:</label>
              <input type="text" class="form-control text-sm" name="website" id="website" placeholder="Website"
                value="<?= isset($options['website']) ? htmlspecialchars($options['website']) : ''; ?>">
            </div>
          <?php endif; ?>
          <?php if (!empty($config['setting']['fanpage'])): ?>
            <div class="form-group col-md-4 col-sm-6">
              <label for="fanpage">Fanpage:</label>
              <input type="text" class="form-control text-sm" name="fanpage" id="fanpage" placeholder="Fanpage"
                value="<?= isset($options['fanpage']) ? htmlspecialchars($options['fanpage']) : ''; ?>">
            </div>
          <?php endif; ?>
          <?php if (!empty($config['setting']['copyright'])): ?>
            <div class="form-group col-md-4 col-sm-6">
              <label for="copyright">Copyright:</label>
              <input type="text" class="form-control text-sm" name="copyright" id="copyright" placeholder="Copyright"
                value="<?= isset($options['copyright']) ? htmlspecialchars($options['copyright']) : ''; ?>">
            </div>
          <?php endif; ?>
          <?php if (!empty($config['setting']['coords_link'])): ?>
            <div class="form-group col-md-4 col-sm-6">
              <label for="coords_link">Link google maps:</label>
              <input type="text" class="form-control text-sm" name="coords_link" id="coords_link"
                placeholder="Link google maps"
                value="<?= isset($options['coords_link']) ? htmlspecialchars($options['coords_link']) : ''; ?>">
            </div>
          <?php endif; ?>
          <?php if (!empty($config['setting']['coords'])): ?>
            <div class="form-group col-md-4 col-sm-6">
              <label for="coords">Tọa độ google map:</label>
              <input type="text" class="form-control text-sm" name="coords" id="coords" placeholder="Tọa độ google map"
                value="<?= isset($options['coords']) ? htmlspecialchars($options['coords']) : ''; ?>">
            </div>
          <?php endif; ?>
          <?php if (!empty($config['setting']['opendoor'])): ?>
            <div class="form-group col-md-4 col-sm-6">
              <label for="opendoor">Giờ mở cửa:</label>
              <input type="text" class="form-control text-sm" name="opendoor" id="opendoor" placeholder="Giờ mở cửa"
                value="<?= isset($options['opendoor']) ? htmlspecialchars($options['opendoor']) : ''; ?>">
            </div>
          <?php endif; ?>
          <?php if (!empty($config['setting']['address'])): ?>
            <div class="form-group col-md-4 col-sm-6">
              <label for="address">Địa chỉ:</label>
              <input type="text" class="form-control text-sm" name="address" id="address" placeholder="Địa chỉ"
                value="<?= isset($options['address']) ? htmlspecialchars($options['address']) : ''; ?>">
            </div>
          <?php endif; ?>
        </div>
        <?php if (!empty($config['setting']['coords_iframe'])): ?>
          <div class="form-group">
            <label for="coords_iframe">
              <span>Tọa độ google map iframe:</span>
              <a class="text-sm font-weight-normal ml-1" href="https://www.google.com/maps" target="_blank"
                title="Lấy mã nhúng google map">(Lấy mã nhúng)</a>
            </label>
            <textarea class="form-control text-sm" name="coords_iframe" id="coords_iframe" rows="5"
              placeholder="Tọa độ google map iframe"><?= isset($options['coords_iframe']) ? htmlspecialchars($options['coords_iframe']) : ''; ?></textarea>
          </div>
        <?php endif; ?>
        <div class="form-group">
          <label for="analytics">Google analytics:</label>
          <textarea class="form-control text-sm" name="analytics" id="analytics" rows="5"
            placeholder="Google analytics"><?= isset($result['analytics']) ? htmlspecialchars($result['analytics']) : ''; ?></textarea>
        </div>
        <div class="form-group">
          <label for="headjs">Head JS:</label>
          <textarea class="form-control text-sm" name="headjs" id="headjs" rows="5"
            placeholder="Head JS"><?= isset($result['headjs']) ? htmlspecialchars($result['headjs']) : ''; ?></textarea>
        </div>
        <div class="form-group">
          <label for="bodyjs">Body JS:</label>
          <textarea class="form-control text-sm" name="bodyjs" id="bodyjs" rows="5"
            placeholder="Body JS"><?= isset($result['bodyjs']) ? htmlspecialchars($result['bodyjs']) : ''; ?></textarea>
        </div>
        <div class="card card-primary card-outline card-outline-tabs">
          <div class="card-header p-0 border-bottom-0">
            <ul class="nav nav-tabs" id="custom-tabs-three-tab-lang" role="tablist">
              <?php foreach ($config['website']['lang'] as $k => $v) { ?>
                <li class="nav-item">
                  <a class="nav-link <?= ($k == $lang) ? 'active' : '' ?>"
                    id="tabs-lang-article-<?= $k ?>"
                    data-toggle="pill"
                    href="#tabs-content-article-<?= $k ?>"
                    role="tab"
                    aria-controls="tabs-content-article-<?= $k ?>"
                    aria-selected="<?= ($k == $lang) ? 'true' : 'false' ?>">
                    <?= $v ?>
                  </a>
                </li>
              <?php } ?>
            </ul>
          </div>
          <div class="card-body card-article">
            <div class="tab-content" id="custom-tabs-three-tabContent-lang">
              <?php foreach ($config['website']['lang'] as $k => $v) { ?>
                <div class="tab-pane fade show <?= ($k == $lang) ? 'active' : '' ?>"
                  id="tabs-content-article-<?= $k ?>"
                  role="tabpanel"
                  aria-labelledby="tabs-lang-article-<?= $k ?>">

                  <div class="form-group">
                    <label for="name<?= $k ?>">Tiêu đề (<?= $k ?>):</label>
                    <input type="text"
                      class="form-control for-seo text-sm"
                      name="name<?= $k ?>" id="name<?= $k ?>"
                      placeholder="Tiêu đề (<?= $k ?>)"
                      value="<?= $_POST['name' . $k] ?? $result['name' . $k] ?? '' ?>" />
                  </div>
                  <div class="form-group">
                    <label for="copyright<?= $k ?>">Copyright (<?= $k ?>):</label>
                    <input type="text"
                      class="form-control for-seo text-sm"
                      name="copyright<?= $k ?>" id="copyright<?= $k ?>"
                      placeholder="Copyright (<?= $k ?>)"
                      value="<?= $_POST['copyright' . $k] ?? $result['copyright' . $k] ?? '' ?>" />
                  </div>
                  <div class="form-group">
                    <label for="slogan<?= $k ?>">Slogan (<?= $k ?>):</label>
                    <input type="text"
                      class="form-control for-seo text-sm"
                      name="slogan<?= $k ?>" id="slogan<?= $k ?>"
                      placeholder="Slogan (<?= $k ?>)"
                      value="<?= $_POST['slogan' . $k] ?? $result['slogan' . $k] ?? '' ?>" />
                  </div>
                </div>
              <?php } ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </form>
</section>
