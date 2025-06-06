<?php
include_once 'lib/autoload.php';
include_once 'lib/router.php';
?>
<?php
$slug = isset($_GET['slug']) ? $_GET['slug'] : '';

if ($slug) {
  $get_danhmuc_c2 = $danhmuc->get_danhmuc_c2($slug);
  if ($get_danhmuc_c2 !== false) {
    $kg_danhmuc_c2 = $get_danhmuc_c2->fetch_assoc();
    if ($kg_danhmuc_c2) {
      $id_list = $kg_danhmuc_c2['id_list'];
      $get_name_danhmuc = $danhmuc->get_name_danhmuc($id_list);
      $get_danhmuc_c2 = $danhmuc->show_danhmuc_c2_index($id_list);
      if ($get_name_danhmuc !== false) {
        $kg_danhmuc = $get_name_danhmuc->fetch_assoc();
        if ($kg_danhmuc) {
          $id_cat = $kg_danhmuc_c2['id'];
          $get_sp = $sanpham->show_sanpham_c2_tc($id_cat);
          $get_count_sp = $sanpham->count_sanpham_cap_2($id_cat);
        } else {
          header('Location: ' . BASE . '404.php');
          exit();
        }
      } else {
        header('Location: ' . BASE . '404.php');
        exit();
      }
    } else {
      header('Location: ' . BASE . '404.php');
      exit();
    }
  } else {
    header('Location: ' . BASE . '404.php');
    exit();
  }
} else {
  header('Location: ' . BASE . '404.php');
  exit();
}

$seo = array_merge($seo, array(
  'title' => $kg_danhmuc_c2['titlevi'],
  'keywords' => $kg_danhmuc_c2['keywordsvi'],
  'description' => $kg_danhmuc_c2['descriptionvi'],
  'url' => BASE . 'cate/' . $kg_danhmuc_c2['slugvi'],
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
          <a class="text-decoration-none" href=""><span>Danh mục</span></a>
        </li>
        <li class="breadcrumb-item">
          <a class="text-decoration-none"
            href="danh-muc/<?= $kg_danhmuc['slugvi'] ?>"><span><?= $kg_danhmuc['namevi'] ?></span></a>
        </li>
        <li class="breadcrumb-item active">
          <a class="text-decoration-none"
            href="cate/<?= $kg_danhmuc_c2['slugvi'] ?>"><span><?= $kg_danhmuc_c2['namevi'] ?></span></a>
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
              <a class="" href="cate/<?= $result_danhmuc_c2['slugvi'] ?>">
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
        (<?= $get_count_sp ?> sản phẩm)
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
              : $config['baseAdmin'] . "assets/img/noimage.png";
            $sale = $sp['sale_price'] ?? '';
            $regular = $sp['regular_price'] ?? '';
            $views = $sp['views'] ?? 0;
            ?>
            <div class="item-product">
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
    </div>
  </div>
</div>
<?php include 'inc/footer.php'; ?>