<?php require_once $baseDir . '/sources/product_list_lv1.php'; ?>

<div class="wrap-main wrap-home w-clear">
  <div class="breadCrumbs">
    <div class="wrap-content">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= BASE ?>">Trang chủ</a></li>
        <li class="breadcrumb-item"><a href="san-pham">Sản phẩm</a></li>
        <li class="breadcrumb-item active">
          <a href="<?= $dm['slugvi'] ?>"><?= htmlspecialchars($dm['namevi']) ?></a>
        </li>
      </ol>
    </div>
  </div>

  <div class="wrap-product-list">
    <div class="wrap-content" style="background: unset;">
      <?php if (!empty($dm_c2_all) && $dm_c2_all->num_rows > 0): ?>
        <div class="grid-list-no-index">
          <?php while ($c2 = $dm_c2_all->fetch_assoc()): ?>
            <div class="item-list-noindex">
              <a href="<?= $c2['slugvi'] ?>">
                <h3 class="m-0 text-capitalize"><?= htmlspecialchars($c2['namevi']) ?></h3>
              </a>
            </div>
          <?php endwhile; ?>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <div class="title-list-hot text-center text-capitalize">
    <h2><?= htmlspecialchars($dm['namevi']) ?></h2>
    (<?= $total ?> sản phẩm)
  </div>

  <div class="wrap-main wrap-template w-clear" style="margin: 0 auto !important;">
    <div class="content-main">
      <?php if ($sp_all && $sp_all->num_rows > 0): ?>
        <div class="grid-product" data-perpage="<?= $per_page ?>" data-list="1" data-curpage="<?= $page ?>" data-total="<?= $total ?>">
          <?php while ($sp = $sp_all->fetch_assoc()): ?>
            <?php
            $slug = $sp['slugvi'];
            $name = htmlspecialchars($sp['namevi']);
            $sale = $sp['sale_price'] ?? '';
            $regular = $sp['regular_price'] ?? '';
            $views = $sp['views'] ?? 0;
            ?>
            <div class="item-product" data-aos="fade-up" data-aos-duration="1000">
              <a href="<?= $slug ?>">
                <div class="images">
                  <?= $fn->getImage([
                    'file' => $sp['file'],
                    'class' => 'w-100',
                    'alt' => $name,
                    'title' => $name,
                    'lazy' => true
                  ]) ?>
                </div>
                <div class="content">
                  <div class="title">
                    <h3><?= $name ?></h3>
                    <p class="price-product">
                      <?php if ($sale && $regular): ?>
                        <span class="price-new"><?= $sale ?>₫</span>
                        <span class="price-old"><?= $regular ?>₫</span>
                      <?php elseif ($regular): ?>
                        <span class="price-new"><?= $regular ?>₫</span>
                      <?php else: ?>
                        <span class="price-new">Liên hệ</span>
                      <?php endif; ?>
                    </p>
                    <div class="info-product">
                      <p><i class="fa-solid fa-eye"></i> <?= $views ?> lượt xem</p>
                      <p><span>Chi tiết</span></p>
                    </div>
                  </div>
                </div>
              </a>
            </div>
          <?php endwhile; ?>
        </div>
      <?php else: ?>
        <div class="alert alert-warning w-100" role="alert">
          <strong>Không tìm thấy kết quả</strong>
        </div>
      <?php endif; ?>

      <div class="mt-3">
        <?= $fn->renderPagination_tc($page, $total_pages, BASE . $dm['slugvi'] . '/page-'); ?>
      </div>

      <?php if (!empty($dm['contentvi'])): ?>
        <div class="desc-list mt-4">
          <div class="noidung_anhien">
            <div class="wrap-toc">
              <div class="meta-toc2">
                <a class="mucluc-dropdown-list_button">Mục Lục</a>
                <div class="box-readmore">
                  <ul class="toc-list" data-toc="article" data-toc-headings="h1,h2,h3"></ul>
                </div>
              </div>
            </div>
            <div class="content-main content-ck pro_tpl" id="toc-content">
              <?= $dm['contentvi'] ?>
            </div>
            <p class="anhien xemthemnd">Xem thêm nội dung</p>
            <p class="anhien anbot">Ẩn bớt nội dung</p>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>
