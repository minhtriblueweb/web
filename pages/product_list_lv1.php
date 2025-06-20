<?php
$slug = $_GET['slug'] ?? '';

if (empty($slug)) {
  http_response_code(404);
  include '404.php';
  exit();
}

// Lấy thông tin danh mục cấp 1
$kg_danhmuc = $danhmuc->get_danhmuc_or_404($slug, 'tbl_danhmuc');
$id_list = $kg_danhmuc['id'];

// Lấy danh mục cấp 2
$get_danhmuc_c2 = $functions->show_data([
  'table' => 'tbl_danhmuc_c2',
  'status' => 'hienthi',
  'id_list' => $id_list
]);

// Phân trang sản phẩm
$records_per_page = 20;
$current_page = max(1, (int)($_GET['page'] ?? 1));

// Đếm tổng sản phẩm theo danh mục cấp 1
$total_records = $functions->count_data([
  'table' => 'tbl_sanpham',
  'status' => 'hienthi',
  'id_list' => $id_list
]);

$total_pages = max(1, ceil($total_records / $records_per_page));

// Lấy sản phẩm theo phân trang
$get_sp = $functions->show_data([
  'table' => 'tbl_sanpham',
  'status' => 'hienthi',
  'id_list' => $id_list,
  'records_per_page' => $records_per_page,
  'current_page' => $current_page
]);

$seo['title'] = !empty($kg_danhmuc['titlevi']) ? $kg_danhmuc['titlevi'] : $kg_danhmuc['namevi'];
$seo['keywords'] = $kg_danhmuc['keywordsvi'];
$seo['description'] = $kg_danhmuc['descriptionvi'];
$seo['url'] = BASE . $kg_danhmuc['slugvi'];
$seo['image'] = BASE_ADMIN . UPLOADS . $kg_danhmuc['file'];
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
          <a class="text-decoration-none"
            href="<?= $kg_danhmuc['slugvi'] ?>"><span><?= $kg_danhmuc['namevi'] ?></span></a>
        </li>
      </ol>
    </div>
  </div>

  <div class="wrap-product-list">
    <div class="wrap-content" style="background: unset;">
      <?php if ($get_danhmuc_c2 && $get_danhmuc_c2->num_rows > 0) : ?>
        <div class="grid-list-no-index">
          <?php while ($result_danhmuc_c2 = $get_danhmuc_c2->fetch_assoc()) : ?>
            <div class="item-list-noindex">
              <a href="<?= $result_danhmuc_c2['slugvi'] ?>">
                <h3 class="m-0"><?= $result_danhmuc_c2['namevi'] ?></h3>
              </a>
            </div>
          <?php endwhile; ?>
        </div>
      <?php endif; ?>
    </div>
  </div>
  <div class="title-list-hot text-center">
    <h2><?= $kg_danhmuc['namevi'] ?></h2>
    (<?= $total_records ?> sản phẩm)
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
            $img = !empty($sp['file'])
              ? BASE_ADMIN . UPLOADS . $sp['file']
              : NO_IMG;
            $sale = $sp['sale_price'] ?? '';
            $regular = $sp['regular_price'] ?? '';
            $views = $sp['views'] ?? 0;
            ?>
            <div class="item-product" data-aos="fade-up" data-aos-duration="1000">
              <a href="san-pham/<?= $slug ?>">
                <div class="images">
                  <img src="<?= $img ?>" alt="<?= $name ?>" title="<?= $name ?>" class="w-100" loading="lazy" />
                </div>
                <div class="content">
                  <div class="title">
                    <h3><?= $name ?></h3>
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
        <?= $pagination_html = $functions->renderPagination_tc($current_page, $total_pages, BASE . $kg_danhmuc['slugvi'] . '/page-');
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
        </div><?php endif; ?>
    </div>
  </div>
</div>
