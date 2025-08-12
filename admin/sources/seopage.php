<?php
$table = 'tbl_seopage';
$linkMan = "index.php?page=seopage&act=update&type=$type";
if (!isset($config['seopage']) || empty($config['seopage']['page']) || !array_key_exists($type, $config['seopage']['page'])) $fn->transfer(trangkhongtontai, "index.php", false);

if ($act === 'update') {
  $seo_data = $db->rawQueryOne("SELECT * FROM $table WHERE type = ? LIMIT 1", [$type]);
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload'])) {
    $fn->save_data($_POST['data'], $_FILES, $seo_data['id'], [
      'table'      => $table,
      'type'       => $type,
      'redirect'   => $linkMan
    ]);
  }
  $template = "seopage/seopage";
} else {
  $fn->transfer(trangkhongtontai, "index.php", false);
}
