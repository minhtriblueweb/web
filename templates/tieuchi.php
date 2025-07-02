<?php
$tieuchi = $fn->show_data([
  'table' => 'tbl_tieuchi',
  'status' => 'hienthi',
  'select' => "file, name{$lang}, desc{$lang}"
]);

if (!empty($tieuchi)):
?>
  <div class="wrap-criterion">
    <div class="wrap-content">
      <div class="slick-criterion slick-d-none">
        <?php foreach ($tieuchi as $row): ?>
          <div>
            <div class="item-criterion hvr-icon-rotate">
              <div class="images">
                <a class="hvr-icon" title="<?= $row['name' . $lang] ?>">
                  <?= $fn->getImage([
                    'file' => $row['file'],
                    'width' => 40,
                    'height' => 40,
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
