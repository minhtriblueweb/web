<div class="item-service">
  <div class="images">
    <a class="scale-img hover-glass text-decoration-none" href="<?= $v["slug$lang"] ?>" title="<?= $v["name$lang"] ?>">
      <?= $fn->getImageCustom(['width' => $config['news'][$type]['width'], 'height' => $config['news'][$type]['height'], 'zc' => substr($config['news'][$type]['thumb'], -1), 'file' => $v['file'], 'class' => 'w-100', 'alt' => $v["name$lang"], 'title' => $v["name$lang"], 'lazy' => true]) ?>
    </a>
  </div>
  <a href="<?= $v["slug$lang"] ?>">
    <div class="content">
      <h3 class="text-split"><?= $v["name$lang"] ?></h3>
      <div class="content_desc text-split-3 mt-2"><?= $v["desc$lang"] ?></div>
      <p class="content_link mt-3">Xem thêm <i class="fa fa-arrow-right"></i></p>
    </div>
  </a>
</div>
