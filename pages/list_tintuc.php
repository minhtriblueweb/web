<div class="wrap-main wrap-home w-clear" style="background:#fff">
  <div class="breadCrumbs">
    <div class="wrap-content">
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a class="text-decoration-none" href="<?= BASE ?>"><span>Trang chủ</span></a>
        </li>
        <li class="breadcrumb-item active">
          <a class="text-decoration-none" href="tin-tuc"><span>Tin tức</span></a>
        </li>
      </ol>
    </div>
  </div>
  <div class="wrap-product-list">
    <div class="title-list-hot mt-4">
      <h2>Tin Tức</h2>
      <div class="animate-border bg-danger mt-1"></div>
    </div>
    <div class="wrap-content" style="background: unset;">
      <div class="row">
        <?php $show_tintuc = $fn->show_data([
          'table' => 'tbl_news',
          'status' => 'hienthi',
          'type'   => 'tintuc'
        ]); ?>
        <?php if ($show_tintuc): ?>
          <?php while ($row_tintuc = $show_tintuc->fetch_assoc()) : ?>
            <div class="col-12 col-sm-4" data-aos="fade-up" data-aos-duration="500">
              <div class="item-service">
                <div class="images">
                  <a class="scale-img hover-glass text-decoration-none" href="<?= $row_tintuc['slugvi'] ?>" title="<?= $row_tintuc['namevi'] ?>">
                    <?= $fn->getImage([
                      'file' => $row_tintuc['file'],
                      'class' => 'w-100',
                      'alt' => $row_tintuc['namevi'],
                      'title' => $row_tintuc['namevi'],
                    ]) ?>
                  </a>
                </div>
                <a href="<?= $row_tintuc['slugvi'] ?>">
                  <div class="content">
                    <h3 class="text-split">
                      <?= $row_tintuc['namevi'] ?>
                    </h3>
                    <div class="content_desc text-split-3 mt-2">
                      <?= $row_tintuc['descvi'] ?>
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
