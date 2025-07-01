<?php
$show_huongdanchoi = $fn->show_data([
  'table' => 'tbl_news',
  'status' => 'hienthi',
  'type'   => 'huongdanchoi'
]);
$seo_data = $fn->get_seo($db->rawQueryOne("SELECT * FROM tbl_seopage WHERE `type` = ?", ['huongdanchoi']) ?? [], $lang);
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
      <div class="animate-border bg-animate-border mt-1"></div>
    </div>
    <div class="wrap-content" style="background: unset;">
      <div class="row">
        <?php if ($show_huongdanchoi): ?>
          <?php while ($row = $show_huongdanchoi->fetch_assoc()) : ?>
            <div class="col-12 col-sm-4" data-aos="fade-up" data-aos-duration="500">
              <div class="item-service">
                <div class="images">
                  <a class="scale-img hover-glass text-decoration-none" href="<?= $row['slugvi'] ?>" title="<?= $row['namevi'] ?>">
                    <?= $fn->getImage([
                      'file' => $row['file'],
                      'class' => 'w-100',
                      'alt' => $row['namevi'],
                      'title' => $row['namevi'],
                    ]) ?>
                  </a>
                </div>
                <a href="<?= $row['slugvi'] ?>">
                  <div class="content">
                    <h3 class="text-split">
                      <?= $row['namevi'] ?>
                    </h3>
                    <div class="content_desc text-split-3 mt-2">
                      <?= $row['descvi'] ?>
                    </div>
                    <p class="content_link mt-3">Xem thêm <i class="fa fa-arrow-right"></i></p>
                  </div>
                </a>
              </div>
            </div>
          <?php endwhile; ?>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
