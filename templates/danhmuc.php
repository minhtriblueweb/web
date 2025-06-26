<?php
$show_danhmuc = $fn->show_data([
  'table' => 'tbl_danhmuc_c2',
  'status' => 'hienthi,noibat'
]);

if ($show_danhmuc && $show_danhmuc->num_rows > 0):
?>
  <div class="wrap-product-list">
    <div class="wrap-content">
      <div class="title-list-hot">
        <h2>DANH MỤC BẠN QUAN TÂM</h2>
      </div>
      <div class="slick_product_list">
        <?php while ($dm = $show_danhmuc->fetch_assoc()): ?>
          <a href="<?= $dm['slugvi'] ?>" title="<?= $dm['namevi'] ?>">
            <div class="item-list">
              <div class="item-list-img">
                <?= $fn->getImage([
                  'file' => $dm['file'],
                  'alt' => $dm['namevi'],
                  'width' => 100,
                  'title' => $dm['namevi']
                ]) ?>
              </div>
              <div class="item-list-name">
                <h3 class="m-0"><?= $dm['namevi'] ?></h3>
              </div>
            </div>
          </a>
        <?php endwhile; ?>
      </div>
      <div class="slick-banner slick-d-none"></div>
    </div>
  </div>
<?php endif; ?>
