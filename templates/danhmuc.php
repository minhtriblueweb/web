<?php
$show_danhmuc = $fn->show_data([
  'table'  => 'tbl_product_cat',
  'status' => 'hienthi,noibat',
  'select' => "file, slug{$lang}, name{$lang},thumb"
]);
?>

<?php if (!empty($show_danhmuc)): ?>
  <div class="wrap-product-list">
    <div class="wrap-content">
      <div class="title-list-hot">
        <h2>DANH MỤC BẠN QUAN TÂM</h2>
      </div>
      <div class="slick_product_list">
        <?php foreach ($show_danhmuc as $dm): ?>
          <?php
          $name = $dm['name' . $lang];
          $slug = $dm['slug' . $lang];
          ?>
          <a href="<?= $slug ?>" title="<?= $name ?>">
            <div class="item-list">
              <div class="item-list-img">
                <?= $fn->getImage(['file'  => $dm['file'], 'alt'   => $name, 'title' => $name, 'width' => 100, 'zc' => 1]) ?>
              </div>
              <div class="item-list-name">
                <h3 class="m-0"><?= $name ?></h3>
              </div>
            </div>
          </a>
        <?php endforeach; ?>
      </div>
      <div class="slick-banner slick-d-none"></div>
    </div>
  </div>
<?php endif; ?>
