<div class="item-service">
  <div class="images">
    <a class="scale-img hover-glass text-decoration-none" href="<?= $v["slug$lang"] ?>" title="<?= $v["name$lang"] ?>">
      <?= $fn->getImageCustom(['width' => $optsetting_json["{$type}_man_width"], 'height' => $optsetting_json["{$type}_man_height"], 'zc' => $optsetting_json["{$type}_man_zc"], 'file' => $v['file'], 'class' => 'w-100', 'alt' => $v["name$lang"], 'title' => $v["name$lang"], 'lazy' => true]) ?>
    </a>
  </div>
  <div class="time my-2"><i class="fa-regular fa-calendar me-2"></i><?= $fn->makeDate(strtotime($v["updated_at"]), '/', $lang, false); ?> - ADMIN</div>
  <a href="<?= $v["slug$lang"] ?>">
    <div class="content">
      <h3 class="text-split"><?= $v["name$lang"] ?></h3>
      <div class="content_desc text-split-3 mt-2"><?= $v["desc$lang"] ?></div>
      <div class="content_link mt-2 p-2 transition">Xem thêm <i class="fa fa-arrow-right mx-2"></i></div>
    </div>
  </a>
</div>
