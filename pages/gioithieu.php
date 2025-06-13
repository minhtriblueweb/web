<?php
$get_gioithieu = $trangtinh->get_static('gioithieu');
if ($get_gioithieu) {
  $result_gioithieu = $get_gioithieu->fetch_assoc();
}
$seo['title'] = $result_gioithieu['titlevi'];
$seo['keywords'] = $result_gioithieu['keywordsvi'];
$seo['description'] = $result_gioithieu['descriptionvi'];
$seo['url'] = $result_gioithieu['slugvi'];
$seo['image'] = isset($result_gioithieu['file_name']) ? BASE_ADMIN . UPLOADS . $result_gioithieu['file_name'] : '';
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
    </div>
    <?php include ROOT . '/templates/tieuchi.php'; ?>
  </div>
</div>
