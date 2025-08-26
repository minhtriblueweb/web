<?php
include_once '../config/autoload.php';
$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
$result = [];
if ($keyword !== '') {
  $rows = $db->rawQuery("SELECT id,name$lang, slug$lang, file FROM tbl_product WHERE name$lang LIKE '%{$keyword}%' AND FIND_IN_SET('hienthi', status) ORDER BY id DESC LIMIT 10");
  foreach ($rows as $row) {
    $result[] = [
      "name" => $row["name$lang"],
      "slug" => $row["slug$lang"],
      "img"  => $fn->getImageCustom(['file'=> $row['file'],'width'=> 500,'height' => 500,'zc'=> 1,'alt'=> $row["name$lang"],'title'=> $row["name$lang"],'lazy'=> false])];
  }
}
echo json_encode($result, JSON_UNESCAPED_UNICODE);
exit;
