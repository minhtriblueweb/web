<div class="wrap-main wrap-home w-clear" style="background:#fff">
  <div class="wrap-product-list">
    <div class="title-list-hot mt-4">
      <h2><?= $titleMain ?></h2>
      <div class="animate-border bg-animate-border mt-1"></div>
    </div>

    <div class="wrap-content" style="background: unset;">
      <div class="row">
        <?php if (!empty($show_data)): ?>
          <?php foreach ($show_data as $v): ?>
            <div class="col-12 col-sm-4" data-aos="fade-up" data-aos-duration="500">
              <div class="item-service">
                <div class="images">
                  <a class="scale-img hover-glass text-decoration-none" href="<?= $v["slug$lang"] ?>" title="<?= $v["name$lang"] ?>">
                    <?= $fn->getImageCustom(['width' => 540, 'height' => 360, 'zc' => 1, 'file' => $v['file'], 'class' => 'w-100', 'alt' => $v["name$lang"], 'title' => $v["name$lang"], 'lazy' => true]) ?>
                  </a>
                </div>
                <a href="<?= $v["slug$lang"] ?>">
                  <div class="content">
                    <h3 class="text-split"><?= $v["name$lang"] ?></h3>
                    <div class="content_desc text-split-3 mt-2">
                      <?= $v["desc$lang"] ?>
                    </div>
                    <p class="content_link mt-3"><?= xemthem ?> <i class="fa fa-arrow-right"></i></p>
                  </div>
                </a>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="alert alert-warning w-100" role="alert">
            <p class="m-0"><strong><?= noidungdangcapnhat ?></strong></p>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
