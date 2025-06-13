<?php
$result_muahang = $trangtinh->get_static('muahang');
if ($result_muahang) {
  $result_muahang = $result_muahang->fetch_assoc();
}
$seo = array_merge($seo, array(
  'title' => $result_muahang['titlevi'],
  'keywords' => $result_muahang['keywordsvi'],
  'description' => $result_muahang['descriptionvi'],
  'url' => BASE . 'tin-tuc/' . $result_muahang['slugvi'],
  'image' => isset($result_muahang['file_name']) ? BASE_ADMIN . UPLOADS . $kg_tintuc['file_name'] : '',
));
?>
<div class="wrap-main wrap-home w-clear" style="background:#fff">
  <div class="breadCrumbs">
    <div class="wrap-content">
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a class="text-decoration-none" href="<?= BASE ?>"><span>Trang chủ</span></a>
        </li>
        <li class="breadcrumb-item">
          <a class="text-decoration-none" href="mua-hang"><span>Hướng dẫn mua hàng</span></a>
        </li>
      </ol>
    </div>
  </div>
  <div class="wrap-product-list">
    <div class="title-list-hot mt-4">
      <h2><?= $result_muahang['namevi'] ?></h2>
      <div class="animate-border bg-danger mt-1"></div>
    </div>
    <div class="wrap-content" style="background: unset;">
      <div class="row">
        <?= $result_muahang['contentvi'] ?>
      </div>
    </div>
    <?php include ROOT . '/templates/tieuchi.php'; ?>
  </div>
</div>
