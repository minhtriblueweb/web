<?php
include_once 'lib/autoload.php';
include_once 'lib/router.php';
?>
<?php
$get_gioithieu = $trangtinh->get_static('gioithieu');
if ($get_gioithieu) {
  $result_gioithieu = $get_gioithieu->fetch_assoc();
}
$seo = array_merge($seo, array(
  'title' => $result_gioithieu['titlevi'],
  'keywords' => $result_gioithieu['keywordsvi'],
  'description' => $result_gioithieu['descriptionvi'],
  'url' => BASE . 'tin-tuc/' . $result_gioithieu['slugvi'],
  'image' => isset($result_gioithieu['file_name']) ? BASE_ADMIN . UPLOADS . $kg_tintuc['file_name'] : '',
));
?>
<?php
include 'inc/header.php';
include 'inc/menu.php';
?>
<div class="wrap-main wrap-home w-clear" style="background:#fff">
  <div class="breadCrumbs">
    <div class="wrap-content">
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a class="text-decoration-none" href="<?= BASE ?>"><span>Trang chủ</span></a>
        </li>
        <li class="breadcrumb-item">
          <a class="text-decoration-none" href="gioi-thieu"><span>Giới thiệu</span></a>
        </li>
      </ol>
    </div>
  </div>
  <div class="wrap-product-list">
    <div class="title-list-hot mt-4">
      <h2><?= $result_gioithieu['namevi'] ?></h2>
      <div class="animate-border bg-danger mt-1"></div>
    </div>
    <div class="wrap-content" style="background: unset;">
      <div class="row">
        <?= $result_gioithieu['contentvi'] ?>
      </div>
    </div><?php include 'inc/tieuchi.php'; ?>

  </div>
</div>
<?php include 'inc/footer.php'; ?>