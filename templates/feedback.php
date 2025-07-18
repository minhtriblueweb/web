<?php if (!empty($feedback)): ?>
  <div class="wrap-feedback" data-aos="fade-up" data-aos-duration="500">
    <div class="wrap-content">
      <div class="title-main">
        <h2>ĐÁNH GIÁ KHÁCH HÀNG</h2>
      </div>
      <div class="slick-feedback slick-d-none">
        <?php foreach ($feedback as $row): ?>
          <?php
          $desc    = $row['desc' . $lang];
          $name    = $row['name' . $lang];
          $address = $row['content' . $lang];
          ?>
          <div class="item-feedback">
            <p class="text-split"><?= $desc ?></p>
            <div class="content">
              <a class="scale-img hover-glass text-decoration-none"
                title="<?= $name ?>" style="width: 100px; height: 100px;">
                <?= $fn->getImageCustom([
                  'file' =>  $row['file'],
                  'width' => 100,
                  'height' => 100,
                  'zc' => 1,
                  'alt'   => $name,
                  'title' => $name,
                  'lazy' => true
                ]) ?>
              </a>
              <div class="title">
                <h3 class="text-split"><?= $name ?></h3>
                <span class="text-split"><?= $address ?></span>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
<?php endif; ?>
