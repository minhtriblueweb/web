<div class="wrap-main wrap-home w-clear" style="background:#fff">
  <div class="wrap-product-list">
    <div class="title-list-hot mt-4">
      <h2><?= $typeInfo['vi'] ?></h2>
      <div class="animate-border bg-animate-border mt-1"></div>
    </div>

    <div class="wrap-content" style="background: unset;">
      <div class="row">
        <?php if (!empty($news)): ?>
          <?php foreach ($news as $row): ?>
            <div class="col-12 col-sm-4" data-aos="fade-up" data-aos-duration="500">
              <div class="item-service">
                <div class="images">
                  <a class="scale-img hover-glass text-decoration-none" href="<?= $row['slug' . $lang] ?>" title="<?= $row['name' . $lang] ?>">
                    <?= $fn->getImageCustom([
                      'width' => 540,
                      'height' => 360,
                      'zc' => 1,
                      'file' => $row['file'],
                      'class' => 'w-100',
                      'alt' => $row['name' . $lang],
                      'title' => $row['name' . $lang],
                      'lazy' => true
                    ]) ?>
                  </a>
                </div>
                <a href="<?= $row['slug' . $lang] ?>">
                  <div class="content">
                    <h3 class="text-split"><?= $row['name' . $lang] ?></h3>
                    <div class="content_desc text-split-3 mt-2">
                      <?= $row['desc' . $lang] ?>
                    </div>
                    <p class="content_link mt-3">Xem thêm <i class="fa fa-arrow-right"></i></p>
                  </div>
                </a>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="alert alert-warning w-100" role="alert">
            <strong class="text-center">Nội dung đang cập nhật...</strong>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
