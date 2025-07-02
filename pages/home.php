<?php
$fn->get_seo($db->rawQueryOne("SELECT * FROM tbl_seopage WHERE `type` = ?", ['trangchu']), $lang);
?>
<?php include ROOT . '/templates/slideshow.php'; ?>
<div class="wrap-main wrap-home w-clear">
  <?php include ROOT . '/templates/tieuchi.php'; ?>
  <?php include ROOT . '/templates/banchay.php'; ?>
  <?php include ROOT . '/templates/danhmuc.php'; ?>
  <?php include ROOT . '/templates/sanpham.php'; ?>
  <?php include ROOT . '/templates/brand.php'; ?>
  <?php include ROOT . '/templates/feedback.php'; ?>
  <?php include ROOT . '/templates/tintuc.php'; ?>
</div>
