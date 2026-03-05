<?php
$table = 'tbl_seopage';
$linkMan = "index.php?com=seopage&act=update&type=" . $type;
if (!isset($config['seopage']) || empty($config['seopage']['page']) || !array_key_exists($type, $config['seopage']['page'])) $func->transfer(trangkhongtontai, "index.php", false);

if ($act === 'update') {
  $seo_data = $d->rawQueryOne("SELECT * FROM `$table` WHERE type = ? LIMIT 0,1", [$type]);
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload'])) {
    $func->save_data($_POST['data'], $_FILES, $seo_data['id'], [
      'table'      => $table,
      'type'       => $type
    ]);
    $func->transfer(capnhatdulieuthanhcong, $linkMan);
  }
  $template = "seopage/seopage";
} else {
  $func->transfer(trangkhongtontai, "index.php", false);
}
