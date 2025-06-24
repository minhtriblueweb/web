<?php
$show_huongdanchoi = $fn->show_data([
  'table' => 'tbl_news',
  'status' => 'hienthi',
  'type'   => 'huongdanchoi'
]);
$seo['title'] = 'Hướng dẫn chơi';
$seo['keywords'] = '';
$seo['description'] = '';
$seo['url'] = '';
$seo['image'] = '';
?>
<div class="wrap-main wrap-home w-clear" style="background:#fff">
  <div class="breadCrumbs">
    <div class="wrap-content">
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a class="text-decoration-none" href="<?= BASE ?>"><span>Trang chủ</span></a>
        </li>
        <li class="breadcrumb-item active">
          <a class="text-decoration-none" href="huong-dan-choi"><span>Hướng dẫn chơi</span></a>
        </li>
      </ol>
    </div>
  </div>
  <div class="wrap-product-list">
    <div class="title-list-hot mt-4">
      <h2>Hướng dẫn chơi</h2>
      <div class="animate-border bg-danger mt-1"></div>
    </div>
    <div class="wrap-content" style="background: unset;">
      <div class="row">
        <?php if ($show_huongdanchoi): ?>
          <?php while ($row = $show_huongdanchoi->fetch_assoc()) : ?>
            <div class="col-6 col-sm-4" data-aos="fade-up" data-aos-duration="1000">
              <div class="item-service">
                <div class="images">
                  <a class="scale-img hover-glass text-decoration-none" href="<?= $row['slugvi'] ?>"
                    title="<?= $row['namevi'] ?>">
                    <img class="w-100"
                      src="<?php echo empty($row['file']) ? NO_IMG : BASE_ADMIN . UPLOADS . $row['file']; ?>"
                      alt="<?= $row['namevi'] ?>" />
                  </a>
                </div>
                <div class="content">
                  <h3>
                    <a class="text-split"
                      href="<?= $row['slugvi'] ?>"><?= $row['namevi'] ?></a>
                  </h3>
                </div>
              </div>
            </div>
          <?php endwhile; ?>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
