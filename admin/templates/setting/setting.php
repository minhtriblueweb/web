<section class="content-header text-sm">
  <div class="container-fluid">
    <div class="row">
      <ol class="breadcrumb float-sm-left">
        <li class="breadcrumb-item"><a href="index.php" title="<?= dashboard ?>"><?= dashboard ?></a></li>
        <li class="breadcrumb-item active"><?= thongtincongty ?></li>
      </ol>
    </div>
  </div>
</section>
<section class="content">
  <form class="validation-form" novalidate method="post" action="" enctype="multipart/form-data">
    <div class="card-footer text-sm sticky-top">
      <button name="upload" type="submit" class="btn btn-sm bg-gradient-primary .submit-check"><i class="far fa-save mr-2"></i><?= luu ?></button>
      <button type="reset" class="btn btn-sm bg-gradient-secondary"><i class="fas fa-redo mr-2"></i><?= lamlai ?></button>
    </div>
    <div class="card card-primary card-outline text-sm">
      <div class="card-header">
        <h3 class="card-title">Database</h3>
      </div>
      <div class="card-body">
        <div class="form-group">
          <!-- <button type="button" class="btn btn-database bg-gradient-warning" data-action="ANALYZE"><i class="fas fa-database mr-2"></i>Analyze</button>
          <button type="button" class="btn btn-database bg-gradient-success" data-action="OPTIMIZE"><i class="fas fa-magic mr-2"></i>Optimize</button>
          <button type="button" class="btn btn-database bg-gradient-info" data-action="CHECK"><i class="fas fa-tasks mr-2"></i>Check</button>
          <button type="button" class="btn btn-database bg-gradient-primary" data-action="REPAIR"><i class="fas fa-tools mr-2"></i>Repair</button> -->
          <button type="button" class="btn btn-database bg-gradient-dark"
            onclick="window.location.href='download_db.php'">
            <i class="fas fa-download mr-2"></i>DOWNLOAD
          </button>
        </div>
      </div>
    </div>
    <div class="card card-primary card-outline text-sm">
      <div class="card-header">
        <h3 class="card-title">Cấu hình kích thước hình ảnh (width x height x zoom crop:1,2,3)</h3>
      </div>
      <div class="card-body">
        <div class="row">
          <?php foreach ($config['size-img'] as $k => $v): ?>
            <?php foreach ($v as $i => $val): ?>
              <?php if (!empty($val['active'])): ?>
                <div class="form-group col-md-2 col-sm-2">
                  <label><?= $val['title'] ?> :</label>
                  <div class="d-flex align-items-center">
                    <input step="1" min="1" pattern="[0-9]+" type="number" class="form-control text-center text-sm p-1"
                      name="data[options][<?= "{$k}_{$i}_width" ?>]"
                      value="<?= $options["{$k}_{$i}_width"] ?? $val['width'] ?>" placeholder="W">
                    <span style="width: 30px; text-align: center;">x</span>
                    <input step="1" min="1" pattern="[0-9]+" type="number" class="form-control text-center text-sm p-1"
                      name="data[options][<?= "{$k}_{$i}_height" ?>]"
                      value="<?= $options["{$k}_{$i}_height"] ?? $val['height'] ?>" placeholder="H">
                    <span style="width: 30px; text-align: center;">x</span>
                    <input step="1" min="1" max="3" type="number" class="form-control text-center text-sm p-1"
                      name="data[options][<?= "{$k}_{$i}_zc" ?>]"
                      value="<?= $options["{$k}_{$i}_zc"] ?? 1 ?>" placeholder="Zc">
                  </div>
                </div>
              <?php endif; ?>
            <?php endforeach; ?>
          <?php endforeach; ?>
        </div>

      </div>
    </div>

    <div class="card card-primary card-outline text-sm">
      <div class="card-header">
        <h3 class="card-title"><?= thongtinchung ?></h3>
      </div>
      <div class="card-body">
        <?php if (count($config['website']['lang']) > 1) { ?>
          <div class="form-group">
            <label><?= ngonngumacdinh ?>:</label>
            <div class="form-group">
              <?php foreach ($config['website']['lang'] as $k => $v) { ?>
                <div class="custom-control custom-radio d-inline-block mr-3 text-md">
                  <input class="custom-control-input" type="radio" id="lang_default-<?= $k ?>" name="data[options][lang_default]" value="<?= $k ?>"
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
              <label class="text-capitalize" for="color"><?= mausac ?>:</label>
              <input type="text"
                class="form-control jscolor text-sm"
                name="data[options][color]"
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
              <input type="email" class="form-control text-sm" name="data[options][email]" id="email" placeholder="Email"
                value="<?= isset($options['email']) ? htmlspecialchars($options['email']) : ''; ?>">
            </div>
          <?php endif; ?>
          <?php if (!empty($config['setting']['hotline'])): ?>
            <div class="form-group col-md-4 col-sm-6">
              <label for="hotline">Hotline:</label>
              <input type="text" class="form-control text-sm" name="data[options][hotline]" id="hotline" placeholder="Hotline"
                value="<?= isset($options['hotline']) ? htmlspecialchars($options['hotline']) : ''; ?>">
            </div>
          <?php endif; ?>
          <?php if (!empty($config['setting']['zalo'])): ?>
            <div class="form-group col-md-4 col-sm-6">
              <label for="zalo">Zalo:</label>
              <input type="text" class="form-control text-sm" name="data[options][zalo]" id="zalo" placeholder="Zalo" value="<?= isset($options['zalo']) ? htmlspecialchars($options['zalo']) : ''; ?>">
            </div>
          <?php endif; ?>
          <?php if (!empty($config['setting']['oaidzalo'])): ?>
            <div class="form-group col-md-4 col-sm-6">
              <label for="oaidzalo">OAID Zalo :</label>
              <input type="text" class="form-control text-sm" name="data[options][oaidzalo]" id="oaidzalo" placeholder="OAID Zalo" value="<?= isset($options['oaidzalo']) ? htmlspecialchars($options['oaidzalo']) : ''; ?>">
            </div>
          <?php endif; ?>
          <?php if (!empty($config['setting']['website'])): ?>
            <div class="form-group col-md-4 col-sm-6">
              <label for="website">Website:</label>
              <input type="text" class="form-control text-sm" name="data[options][website]" id="website" placeholder="Website"
                value="<?= isset($options['website']) ? htmlspecialchars($options['website']) : ''; ?>">
            </div>
          <?php endif; ?>
          <?php if (!empty($config['setting']['fanpage'])): ?>
            <div class="form-group col-md-4 col-sm-6">
              <label for="fanpage">Fanpage:</label>
              <input type="text" class="form-control text-sm" name="data[options][fanpage]" id="fanpage" placeholder="Fanpage"
                value="<?= isset($options['fanpage']) ? htmlspecialchars($options['fanpage']) : ''; ?>">
            </div>
          <?php endif; ?>
          <?php if (!empty($config['setting']['copyright'])): ?>
            <div class="form-group col-md-4 col-sm-6">
              <label for="copyright">Copyright:</label>
              <input type="text" class="form-control text-sm" name="data[options][copyright]" id="copyright" placeholder="Copyright"
                value="<?= isset($options['copyright']) ? htmlspecialchars($options['copyright']) : ''; ?>">
            </div>
          <?php endif; ?>
          <?php if (!empty($config['setting']['coords_link'])): ?>
            <div class="form-group col-md-4 col-sm-6">
              <label for="coords_link">Link google maps:</label>
              <input type="text" class="form-control text-sm" name="data[options][coords_link]" id="coords_link"
                placeholder="Link google maps"
                value="<?= isset($options['coords_link']) ? htmlspecialchars($options['coords_link']) : ''; ?>">
            </div>
          <?php endif; ?>
          <?php if (!empty($config['setting']['coords'])): ?>
            <div class="form-group col-md-4 col-sm-6">
              <label for="coords"><?= toadogooglemap ?>:</label>
              <input type="text" class="form-control text-sm" name="data[options][coords]" id="coords" placeholder="<?= toadogooglemap ?>"
                value="<?= isset($options['coords']) ? htmlspecialchars($options['coords']) : ''; ?>">
            </div>
          <?php endif; ?>
          <?php if (!empty($config['setting']['opendoor'])): ?>
            <div class="form-group col-md-4 col-sm-6">
              <label for="opendoor">Giờ mở cửa:</label>
              <input type="text" class="form-control text-sm" name="data[options][opendoor]" id="opendoor" placeholder="Giờ mở cửa"
                value="<?= isset($options['opendoor']) ? htmlspecialchars($options['opendoor']) : ''; ?>">
            </div>
          <?php endif; ?>
          <?php if (!empty($config['setting']['address'])): ?>
            <div class="form-group col-md-4 col-sm-6">
              <label for="address"><?= diachi ?>:</label>
              <input type="text" class="form-control text-sm" name="data[options][address]" id="address" placeholder="<?= diachi ?>"
                value="<?= isset($options['address']) ? htmlspecialchars($options['address']) : ''; ?>">
            </div>
          <?php endif; ?>
        </div>
        <?php if (!empty($config['setting']['coords_iframe'])): ?>
          <div class="form-group">
            <label for="coords_iframe">
              <span><?= toadogooglemapiframe ?>:</span>
              <a class="text-sm font-weight-normal ml-1" href="https://www.google.com/maps" target="_blank"
                title="<?= laymanhung ?> google map">(<?= laymanhung ?>)</a>
            </label>
            <textarea class="form-control text-sm" name="data[options][coords_iframe]" id="coords_iframe" rows="5"
              placeholder="<?= toadogooglemapiframe ?>"><?= isset($options['coords_iframe']) ? htmlspecialchars($options['coords_iframe']) : ''; ?></textarea>
          </div>
        <?php endif; ?>
        <div class="form-group">
          <label for="analytics">Google analytics:</label>
          <textarea class="form-control text-sm" name="data[analytics]" id="analytics" rows="5"
            placeholder="Google analytics"><?= isset($row['analytics']) ? htmlspecialchars($row['analytics']) : ''; ?></textarea>
        </div>
        <div class="form-group">
          <label for="mastertool">Google Webmaster Tool:</label>
          <textarea class="form-control text-sm" name="data[mastertool]" id="mastertool" rows="5" placeholder="Google Webmaster Tool"><?= isset($row['mastertool']) ? htmlspecialchars($row['mastertool']) : ''; ?></textarea>
        </div>
        <div class="form-group">
          <label for="headjs">Head JS:</label>
          <textarea class="form-control text-sm" name="data[headjs]" id="headjs" rows="6"
            placeholder="Head JS"><?= isset($row['headjs']) ? htmlspecialchars($row['headjs']) : ''; ?></textarea>
        </div>
        <div class="form-group">
          <label for="bodyjs">Body JS:</label>
          <textarea class="form-control text-sm" name="data[bodyjs]" id="bodyjs" rows="6"
            placeholder="Body JS"><?= isset($row['bodyjs']) ? htmlspecialchars($row['bodyjs']) : ''; ?></textarea>
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
                    <label for="name<?= $k ?>"><?= tieude ?> (<?= $k ?>):</label>
                    <input type="text"
                      class="form-control for-seo text-sm"
                      name="data[name<?= $k ?>]" id="name<?= $k ?>"
                      placeholder="<?= tieude ?> (<?= $k ?>)"
                      value="<?= $_POST['name' . $k] ?? $row['name' . $k] ?? '' ?>" />
                  </div>
                  <div class="form-group">
                    <label for="copyright<?= $k ?>">Copyright (<?= $k ?>):</label>
                    <input type="text"
                      class="form-control for-seo text-sm"
                      name="data[copyright<?= $k ?>]" id="copyright<?= $k ?>"
                      placeholder="Copyright (<?= $k ?>)"
                      value="<?= $_POST['copyright' . $k] ?? $row['copyright' . $k] ?? '' ?>" />
                  </div>
                  <div class="form-group">
                    <label for="slogan<?= $k ?>">Slogan (<?= $k ?>):</label>
                    <input type="text"
                      class="form-control for-seo text-sm"
                      name="data[slogan<?= $k ?>]" id="slogan<?= $k ?>"
                      placeholder="Slogan (<?= $k ?>)"
                      value="<?= $_POST['slogan' . $k] ?? $row['slogan' . $k] ?? '' ?>" />
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
