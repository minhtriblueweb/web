<?php
if ($show_danhmuc = $fn->show_data([
  'table' => 'tbl_danhmuc_c2',
  'status' => 'hienthi,noibat'
])):
?>
  <?php if ($show_danhmuc->num_rows > 0): ?>
    <div class="wrap-product-list">
      <div class="wrap-content">
        <div class="title-list-hot">
          <h2>DANH MỤC BẠN QUAN TÂM</h2>
        </div>
        <div class="slick_product_list">
          <?php while ($dm = $show_danhmuc->fetch_assoc()): ?>
            <?php
            $slug = $dm['slugvi'];
            $name = $dm['namevi'];
            $imgSrc = !empty($dm['file']) ? BASE_ADMIN . UPLOADS . $dm['file'] : NO_IMG;
            ?>
            <a href="<?= $slug ?>" title="<?= $name ?>">
              <div class="item-list">
                <div class="item-list-img">
                  <img src="<?= $imgSrc ?>" alt="<?= $name ?>" />
                </div>
                <div class="item-list-name">
                  <h3 class="m-0"><?= $name ?></h3>
                </div>
              </div>
            </a>
          <?php endwhile; ?>
        </div>
        <div class="slick-banner slick-d-none"></div>
      </div>
    </div>
  <?php endif; ?>
<?php endif; ?>
