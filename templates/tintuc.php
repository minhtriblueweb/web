<?php
$show_tintuc = $fn->show_data([
  'table' => 'tbl_news',
  'status' => 'hienthi,noibat',
  'type'   => 'tintuc',
  'select' => "file, name{$lang}, desc{$lang}, slug{$lang}",
  'limit'  => 8
]);
?>

<?php if (!empty($show_tintuc)): ?>
  <div class="wrap-service" data-aos="fade-up" data-aos-duration="500">
    <div class="wrap-content">
      <div class="title-main">
        <h2>TIN TỨC MỚI NHẤT</h2>
      </div>
      <div class="slick-service slick-d-none">
        <?php foreach ($show_tintuc as $row_tintuc): ?>
          <?php
          $name = $row_tintuc['name' . $lang];
          $desc = $row_tintuc['desc' . $lang];
          $slug = $row_tintuc['slug' . $lang];
          ?>
          <div class="item-service">
            <div class="images">
              <a class="scale-img hover-glass text-decoration-none" href="<?= $slug ?>" title="<?= $name ?>">
                <?= $fn->getImage([
                  'file' => $row_tintuc['file'],
                  'class' => 'w-100',
                  'alt' => $name,
                  'title' => $name
                ]) ?>
              </a>
            </div>
            <a href="<?= $slug ?>">
              <div class="content">
                <h3 class="text-split"><?= $name ?></h3>
                <div class="content_desc text-split-3 mt-2"><?= $desc ?></div>
                <p class="content_link mt-3">Xem thêm <i class="fa fa-arrow-right"></i></p>
              </div>
            </a>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
<?php endif; ?>
