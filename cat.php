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
        <div class="grid-list">
          <?php while ($result_danhmuc_c2 = $get_danhmuc_c2->fetch_assoc()) : ?>
            <div class="item-list-noindex">
              <h3>
                <a class="text-split"
                  href="cate/<?= $result_danhmuc_c2['slugvi'] ?>"><?= $result_danhmuc_c2['namevi'] ?></a>
              </h3>
            </div>
          <?php endwhile; ?>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <div class="wrap-product-list">
    <div class="wrap-content" style="background: unset;">
      <div class="title-list-hot">
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
          <?php while ($result_sp = $get_sp->fetch_assoc()) : ?>
            <div class="item-product">
              <a href="san-pham/<?= $result_sp['slugvi'] ?>">
                <div class="images">
                  <img src="<?= BASE_ADMIN . UPLOADS . $result_sp['file'] ?>" alt="<?= $result_sp['namevi'] ?>"
                    title="<?= $result_sp['namevi'] ?>" class="w-100" />
                </div>
                <div class="content">
                  <div class="title">
                    <h3><?= $result_sp['namevi'] ?></h3>
                    <p class="price-product">
                      <?php
                      if (!empty($result_sp['sale_price']) && !empty($result_sp['regular_price'])) {
                        echo '<span class="price-new">' . $result_sp['sale_price'] . '₫</span>';
                        echo '<span class="price-old">' . $result_sp['regular_price'] . '₫</span>';
                      } elseif (!empty($result_sp['regular_price'])) {
                        echo '<span class="price-new">' . $result_sp['regular_price'] . '₫</span>';
                      } else {
                        echo '<span class="price-new">Liên hệ</span>';
                      }
                      ?>
                    </p>
                    </p>
                    <div class="info-product">
                      <p><i class="fa-solid fa-eye"></i><?= $result_sp['views'] ?> lượt xem</p>
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