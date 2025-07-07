<div class="wrap-main wrap-home w-clear">
  <div class="wrap-product-list">
    <div class="wrap-content" style="background: unset;">
      <?php if (!empty($dm_c2_all)): ?>
        <div class="grid-list-no-index">
          <?php foreach ($dm_c2_all as $c2): ?>
            <div class="item-list-noindex">
              <a href="<?= $c2['slugvi'] ?>">
                <h3 class="m-0 text-capitalize"><?= htmlspecialchars($c2['namevi']) ?></h3>
              </a>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <div class="title-list-hot text-center text-capitalize">
    <h2><?= htmlspecialchars($dm['name' . $lang]) ?></h2>
    (<?= $total ?> sản phẩm)
  </div>

  <div class="wrap-main wrap-template w-clear" style="margin: 0 auto !important;">
    <div class="content-main">
      <?php if (!empty($sp_all)): ?>
        <div class="grid-product" data-perpage="<?= $per_page ?>" data-list="1" data-curpage="<?= $page ?>" data-total="<?= $total ?>">
          <?php foreach ($sp_all as $sp): ?>
            <?php
            $slug = $sp['slug' . $lang];
            $name = htmlspecialchars($sp['name' . $lang]);
            $sale = $sp['sale_price'] ?? '';
            $regular = $sp['regular_price'] ?? '';
            $views = $sp['views'] ?? 0;
            ?>
            <div class="item-product" data-aos="fade-up" data-aos-duration="500">
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
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <div class="alert alert-warning w-100" role="alert">
          <strong>Không tìm thấy kết quả</strong>
        </div>
      <?php endif; ?>
      <div class="mt-3">
        <?= $fn->renderPagination_tc($page, $total_pages, BASE . $dm['slug' . $lang] . '/page-'); ?>
      </div>

      <?php if (!empty($dm['content' . $lang])): ?>
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
              <?= $dm['content' . $lang] ?>
            </div>
            <p class="anhien xemthemnd">Xem thêm nội dung</p>
            <p class="anhien anbot">Ẩn bớt nội dung</p>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>
