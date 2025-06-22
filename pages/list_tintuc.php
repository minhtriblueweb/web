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
        <?php $show_tintuc = $news->show_news_by_type('tintuc', 'hienthi'); ?>
        <?php if ($show_tintuc): ?>
          <?php while ($resule_tintuc = $show_tintuc->fetch_assoc()) : ?>
            <div class="col-6 col-sm-3" data-aos="fade-up" data-aos-duration="1000">
              <div class="item-service">
                <div class="images">
                  <a class="scale-img hover-glass text-decoration-none" href="<?= $resule_tintuc['slug'] ?>"
                    title="<?= $resule_tintuc['name'] ?>">
                    <img class="w-100"
                      src="<?php echo empty($resule_tintuc['file']) ? NO_IMG : BASE_ADMIN . UPLOADS . $resule_tintuc['file']; ?>"
                      alt="<?= $resule_tintuc['name'] ?>" />
                  </a>
                </div>
                <div class="content">
                  <h3>
                    <a class="text-split" href="<?= $resule_tintuc['slug'] ?>"><?= $resule_tintuc['name'] ?></a>
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
