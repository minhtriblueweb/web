<?php if ($tieuchi = $fn->show_data([
  'table' => 'tbl_tieuchi',
  'status' => 'hienthi'
])): ?>
  <div class="wrap-criterion">
    <div class="wrap-content">
      <div class="slick-criterion slick-d-none">
        <?php while ($row = $tieuchi->fetch_assoc()): ?>
          <div>
            <div class="item-criterion hvr-icon-rotate">
              <div class="images">
                <a class="hvr-icon" title="<?= $row['namevi'] ?>">
                  <?= $fn->getImage([
                    'file' => $row['file'],
                    'width' => 40,
                    'height' => 40,
                    'lazy' => true
                  ]) ?>

                </a>
                <h3><span class="text-split"><?= $row['namevi'] ?></span></h3>
              </div>
              <p class="text-split"><?= $row['descvi'] ?></p>
            </div>
          </div>
        <?php endwhile; ?>
      </div>
    </div>
  </div>
<?php endif; ?>
