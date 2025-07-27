<?php
$table = 'tbl_seopage';
$linkMan = "index.php?page=seopage&act=update&type=$type";
if (!isset($config['seopage']) || empty($config['seopage']['page']) || !array_key_exists($type, $config['seopage']['page'])) $fn->transfer(trangkhongtontai, "index.php", false);

if ($act === 'update') {
  $seo_data = $db->rawQueryOne("SELECT * FROM $table WHERE type = ? LIMIT 1", [$type]);
  if (!$seo_data) $fn->transfer(dulieukhongcothuc, $linkMan, false);
  $width  = $config['seopage']['width'];
  $height = $config['seopage']['height'];
  $zc = substr($config['seopage']['thumb'], -1) ?? 1;
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload'])) {
    $fn->save_data($_POST, $_FILES, $seo_data['id'], [
      'table'           => $table,
      'fields_multi'    => ['title', 'keywords', 'description'],
      'fields_common'   => ['type'],
      'fields_options'  => ['width', 'height', 'zc'],
      'redirect_page'   => $linkMan,
      'enable_seo'      => true,
    ]);
  }
  $template = "seopage/seopage";
} else {
  $fn->transfer(trangkhongtontai, "index.php", false);
}
