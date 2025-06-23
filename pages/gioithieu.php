<?php
$get_gioithieu = $trangtinh->get_static('gioithieu');
if ($get_gioithieu) {
  $row_gt = $get_gioithieu->fetch_assoc();
}
$seo['title'] = $row_gt['titlevi'];
$seo['keywords'] = $row_gt['keywordsvi'];
$seo['description'] = $row_gt['descriptionvi'];
$seo['url'] = $row_gt['slugvi'] ?? '';
$seo['image'] = isset($row_gt['file']) ? BASE_ADMIN . UPLOADS . $row_gt['file'] : '';
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
      <h2><?= $row_gt['namevi'] ?></h2>
      <div class="animate-border bg-danger mt-1"></div>
    </div>
    <div class="wrap-content" style="background: unset;">
      <div class="row">
        <?= $row_gt['contentvi'] ?>
      </div>
    </div>
    <?php include ROOT . '/templates/tieuchi.php'; ?>
  </div>
</div>
