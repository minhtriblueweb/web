<?php
include_once 'lib/autoload.php';
include_once 'lib/router.php';
?>
<?php
$slug = $_GET['slug'] ?? '';
if (empty($slug)) {
  http_response_code(404);
  include '404.php';
  exit();
}

$get_danhmuc_c2 = $danhmuc->get_danhmuc_c2($slug);
if (!$get_danhmuc_c2 || !($kg_danhmuc_c2 = $get_danhmuc_c2->fetch_assoc())) {
  http_response_code(404);
  include '404.php';
  exit();
}

$id_list = $kg_danhmuc_c2['id_list'];
$get_name_danhmuc_c1 = $sanpham->get_name_danhmuc($id_list, 'tbl_danhmuc');
$get_danhmuc_c2 = $danhmuc->show_danhmuc_c2_index($id_list);

if (!$get_name_danhmuc_c1 || !($kg_danhmuc = $get_danhmuc_c2->fetch_assoc())) {
  http_response_code(404);
  include '404.php';
  exit();
}

$id_cat = $kg_danhmuc_c2['id'];
$records_per_page = 20;
$current_page = max(1, (int)($_GET['page'] ?? 1));
$total_records = $sanpham->count_sanpham('', $id_cat);
$total_pages = max(1, ceil($total_records / $records_per_page));

$get_sp = $sanpham->show_sanpham_pagination(
  $records_per_page,
  $current_page,
  'hienthi',
  '',
  $id_cat,
  ''
);


$seo = array_merge($seo, array(
  'title' => $kg_danhmuc_c2['titlevi'],
  'keywords' => $kg_danhmuc_c2['keywordsvi'],
  'description' => $kg_danhmuc_c2['descriptionvi'],
  'url' => BASE . 'danh-muc/' . $kg_danhmuc_c2['slugvi'],
  'image' => isset($kg_danhmuc_c2['file']) ? BASE_ADMIN . UPLOADS . $kg_danhmuc_c2['file'] : '',
));
?>
<?php
include 'inc/header.php';
include 'inc/menu.php';
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
            href="danh-muc/<?= $kg_danhmuc['slugvi'] ?>"><span><?= $kg_danhmuc['namevi'] ?></span></a>
        </li>
        <li class="breadcrumb-item active">
          <a class="text-decoration-none"
            href="danh-muc/<?= $kg_danhmuc_c2['slugvi'] ?>"><span><?= $kg_danhmuc_c2['namevi'] ?></span></a>
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
              <a class="" href="danh-muc/<?= $result_danhmuc_c2['slugvi'] ?>">
                <h3 class="m-0">
                  <?= $result_danhmuc_c2['namevi'] ?>
                </h3>
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
        <h2><?= $kg_danhmuc_c2['namevi'] ?></h2>
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
      <?php else : ?>
        <div class="alert alert-warning w-100" role="alert">
          <strong>Không tìm thấy kết quả</strong>
        </div>
      <?php endif ?>
      <div class="mt-3">
        <?= $pagination_html = $functions->renderPagination_tc($current_page, $total_pages, BASE . 'danh-muc/' . $kg_danhmuc_c2['slugvi'] . '/page-');
        ?>
      </div>
    </div>
  </div>
</div>
<?php include 'inc/footer.php'; ?>
