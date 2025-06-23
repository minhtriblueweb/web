<?php
$slug = $_GET['slug'] ?? '';

if (empty($slug)) $fn->abort_404();

// Lấy thông tin danh mục cấp 2 và cấp 1 (JOIN)
$dm_c2 = $danhmuc->get_danhmuc_c2_with_parent_or_404($slug);
// Cấp 1 (cha)
$dm_c1 = [
  'namevi' => $dm_c2['name_lv1'] ?? '',
  'slugvi' => $dm_c2['slug_lv1'] ?? '',
  'titlevi' => $dm_c2['title_lv1'] ?? '',
  'keywordsvi' => $dm_c2['keywords_lv1'] ?? '',
  'descriptionvi' => $dm_c2['description_lv1'] ?? ''
];
// Sidebar: danh sách danh mục cấp 2 thuộc cùng cấp 1
$id_list = (int)$dm_c2['id_list'];
$list_danhmuc_c2 = $fn->show_data([
  'table' => 'tbl_danhmuc_c2',
  'status' => 'hienthi',
  'id_list' => $id_list
]);

// Phân trang sản phẩm
$records_per_page = 20;
$current_page = max(1, (int)($_GET['page'] ?? 1));

$total_records = $fn->count_data([
  'table' => 'tbl_sanpham',
  'status' => 'hienthi',
  'id_cat' => $dm_c2['id']
]);

$total_pages = max(1, ceil($total_records / $records_per_page));

$get_sp = $fn->show_data([
  'table' => 'tbl_sanpham',
  'status' => 'hienthi',
  'id_cat' => $dm_c2['id'],
  'records_per_page' => $records_per_page,
  'current_page' => $current_page
]);
// SEO
$seo['title'] = !empty($dm_c2['titlevi']) ? $dm_c2['titlevi'] : $dm_c2['namevi'];
$seo['keywords'] = $dm_c2['keywordsvi'];
$seo['description'] = $dm_c2['descriptionvi'];
$seo['url'] = BASE . $dm_c2['slugvi'];
$seo['image'] = BASE_ADMIN . UPLOADS . $dm_c2['file'];
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
        <li class="breadcrumb-item">
          <a class="text-decoration-none"
            href="<?= $dm_c1['slugvi'] ?>"><span><?= $dm_c1['namevi'] ?></span></a>
        </li>
        <li class="breadcrumb-item active">
          <a class="text-decoration-none"
            href="<?= $dm_c2['slugvi'] ?>"><span><?= $dm_c2['namevi'] ?></span></a>
        </li>
      </ol>
    </div>
  </div>

  <div class="wrap-product-list">
    <div class="wrap-content" style="background: unset;">
      <?php if ($list_danhmuc_c2 && $list_danhmuc_c2->num_rows > 0): ?>
        <div class="grid-list-no-index">
          <?php while ($dm_c2_item = $list_danhmuc_c2->fetch_assoc()): ?>
            <div class="item-list-noindex">
              <a href="<?= $dm_c2_item['slugvi'] ?>">
                <h3 class="m-0"><?= $dm_c2_item['namevi'] ?></h3>
              </a>
            </div>
          <?php endwhile; ?>
        </div>
      <?php endif; ?>

    </div>
  </div>

  <div class="wrap-product-list">
    <div class="wrap-content" style="background: unset;">
      <div class="title-list-hot text-center">
        <h2><?= $dm_c2['namevi'] ?></h2>
        (<?= $total_records ?> sản phẩm)
      </div>
    </div>
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
              <a href="<?= $slug ?>">
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
      <?php else : ?>
        <div class="alert alert-warning w-100" role="alert">
          <strong>Không tìm thấy kết quả</strong>
        </div>
      <?php endif ?>
      <div class="mt-3">
        <?= $pagination_html = $fn->renderPagination_tc($current_page, $total_pages, BASE . $dm_c2['slugvi'] . '/page-');
        ?>
      </div>
    </div>
  </div>
</div>
