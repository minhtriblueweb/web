<?php if (!empty($tieuchi)): ?>
  <div class="wrap-criterion">
    <div class="wrap-content">
      <div class="slick-criterion slick-d-none">
        <?php foreach ($tieuchi as $row): ?>
          <div>
            <div class="item-criterion hvr-icon-rotate">
              <div class="images">
                <a class="hvr-icon me-2" title="<?= $row['name' . $lang] ?>">
                  <?= $fn->getImageCustom([
                    'file' =>  $row['file'],
                    'width' => $config['news']['tieu-chi']['width'],
                    'height' => $config['news']['tieu-chi']['height'],
                    'zc' => substr($config['news']['tieu-chi']['thumb'], -1),
                    'alt' => $row['name' . $lang],
                    'title' => $row['name' . $lang],
                    'lazy' => true
                  ]) ?>
                </a>
                <h3><span class="text-split"><?= $row['name' . $lang] ?></span></h3>
              </div>
              <p class="text-split"><?= $row['desc' . $lang] ?></p>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
<?php endif; ?>
