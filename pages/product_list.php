<?php
$records_per_page = 20;
$current_page = max(1, isset($_GET['page']) ? (int)$_GET['page'] : 1);
$total_records = $fn->count_data([
  'table' => 'tbl_sanpham',
  'status' => 'hienthi',
]);
$total_pages = ceil($total_records / $records_per_page);
$show_sanpham = $fn->show_data([
  'table' => 'tbl_sanpham',
  'status' => 'hienthi',
  'records_per_page' => $records_per_page,
  'current_page' => $current_page,
]);
?>
<div class="wrap-main wrap-home w-clear">
  <div class="breadCrumbs">
    <div class="wrap-content">
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a class="text-decoration-none" href="<?= BASE ?>"><span>Trang chủ</span></a>
        </li>
        <li class="breadcrumb-item active">
          <a class="text-decoration-none" href="san-pham"><span>Sản phẩm</span></a>
        </li>
      </ol>
    </div>
  </div>

  <div class="wrap-product-list">
    <div class="wrap-content" style="background: unset;">

      <div class="grid-list-no-index">
        <?php
        $dm = $fn->show_data([
          'table' => 'tbl_danhmuc_c1',
          'status' => 'hienthi,noibat'
        ]);
        if ($dm) {
          while ($row_dm = $dm->fetch_assoc()) {
        ?>
            <div class="item-list-noindex">
              <a title="<?= $row_dm['namevi'] ?>" class="" href="<?= $row_dm['slugvi'] ?>">
                <h3 class="m-0">
                  <?= $row_dm['namevi'] ?>
                </h3>
              </a>
            </div>
        <?php }
        } ?>
      </div>
    </div>
  </div>

  <div class="wrap-product-list">
    <div class="wrap-content" style="background: unset;">
      <div class="title-list-hot text-center">
        <h2>Sản Phẩm</h2>
        (<?= $total_records ?> sản phẩm)
      </div>
    </div>
  </div>

  <div class="wrap-main wrap-template w-clear" style="margin: 0 auto !important;">
    <div class="content-main">
      <?php if ($show_sanpham && $show_sanpham->num_rows > 0) : ?>
        <div class="grid-product .paging-product-loadmore .paging-product-loadmore-1" data-perpage="25" data-list="1"
          data-cat="" data-item="" data-brand="" data-curpage="2" data-total="124">
          <?php while ($sp = $show_sanpham->fetch_assoc()) : ?>
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
                    <h3><?= $namevi ?></h3>
                    <p class="price-product">
                      <?php if (!empty($sale) && !empty($regular)): ?>
                        <span class="price-new"><?= $sale ?>₫</span>
                        <span class="price-old"><?= $regular ?>₫</span>
                      <?php elseif (!empty($regular)): ?>
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
      <?php else : ?>
        <div class="alert alert-warning w-100" role="alert">
          <strong>Không tìm thấy kết quả</strong>
        </div>
      <?php endif ?>
      <div class="mt-3">
        <?= $fn->renderPagination_tc($current_page, $total_pages, BASE . 'san-pham/page-'); ?>
      </div>
    </div>
  </div>
</div>
