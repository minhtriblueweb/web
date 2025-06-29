<?php
require_once $baseDir . '/sources/product_list_lv1.php';
?>
<div class="wrap-main wrap-home w-clear">
  <div class="breadCrumbs">
    <div class="wrap-content">
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a class="text-decoration-none" href="<?= BASE ?>"><span>Trang chủ</span></a>
        </li>
        <li class="breadcrumb-item">
          <a class="text-decoration-none" href="san-pham"><span>Sản phẩm</span></a>
        </li>
        <li class="breadcrumb-item active">
          <a class="text-decoration-none" href="<?= $kg_danhmuc['slugvi'] ?>"><span>
              <?= $kg_danhmuc['namevi'] ?>
            </span></a>
        </li>
      </ol>
    </div>
  </div>

  <div class="wrap-product-list">
    <div class="wrap-content" style="background: unset;">
      <?php if ($get_danhmuc_c2 && $get_danhmuc_c2->num_rows > 0) : ?>
        <div class="grid-list-no-index">
          <?php while ($row_dm_c2 = $get_danhmuc_c2->fetch_assoc()) : ?>
            <div class="item-list-noindex">
              <a href="<?= $row_dm_c2['slugvi'] ?>">
                <h3 class="m-0 text-capitalize">
                  <?= $row_dm_c2['namevi'] ?>
                </h3>
              </a>
            </div>
          <?php endwhile; ?>
        </div>
      <?php endif; ?>
    </div>
  </div>
  <div class="title-list-hot text-center text-capitalize">
    <h2>
      <?= $kg_danhmuc['namevi'] ?>
    </h2>
    (
    <?= $total_records ?> sản phẩm)
  </div>
  <div class="wrap-main wrap-template w-clear" style="margin: 0 auto !important;">
    <div class="content-main">
      <?php if ($get_sp && $get_sp->num_rows > 0) : ?>
        <div class="grid-product .paging-product-loadmore .paging-product-loadmore-1" data-perpage="25" data-list="1"
          data-cat="" data-item="" data-brand="" data-curpage="2" data-total="124">
          <?php while ($sp = $get_sp->fetch_assoc()) : ?>
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
                    <h3>
                      <?= $name ?>
                    </h3>
                    <p class="price-product">
                      <?php if (!empty($sale) && !empty($regular)): ?>
                        <span class="price-new">
                          <?= $sale ?>₫
                        </span>
                        <span class="price-old">
                          <?= $regular ?>₫
                        </span>
                      <?php elseif (!empty($regular)): ?>
                        <span class="price-new">
                          <?= $regular ?>₫
                        </span>
                      <?php else: ?>
                        <span class="price-new">Liên hệ</span>
                      <?php endif; ?>
                    </p>
                    <div class="info-product">
                      <p><i class="fa-solid fa-eye"></i>
                        <?= $views ?> lượt xem
                      </p>
                      <p><span>Chi tiết</span></p>
                    </div>
                  </div>
                </div>
              </a>
            </div>
          <?php endwhile; ?>

        </div>
        <!-- <div class="text-center mt-4">
          <span class="loadmore-product loadmore-product-1" data-list="1">Xem thêm sản phẩm
            <i class="fa-solid fa-chevron-down"></i></span>
        </div> -->
      <?php else : ?>
        <div class="alert alert-warning w-100" role="alert">
          <strong>Không tìm thấy kết quả</strong>
        </div>
      <?php endif ?>
      <div class="mt-3">
        <?= $pagination_html = $fn->renderPagination_tc($current_page, $total_pages, BASE . $kg_danhmuc['slugvi'] . '/page-');
        ?>
      </div>
      <?php if (!empty($kg_danhmuc['contentvi'])): ?>
        <div class="desc-list mt-4">
          <div class="noidung_anhien">
            <div class="wrap-toc">
              <div class="meta-toc2">
                <a class="mucluc-dropdown-list_button">Mục Lục</a>
                <div class="box-readmore">
                  <ul class="toc-list" data-toc="article" data-toc-headings="h1, h2, h3"></ul>
                </div>
              </div>
            </div>
            <div class="content-main content-ck pro_tpl" id="toc-content">
              <?= $kg_danhmuc['contentvi'] ?>
            </div>
            <p class="anhien xemthemnd">Xem thêm nội dung</p>
            <p class="anhien anbot">Ẩn bớt nội dung</p>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>
