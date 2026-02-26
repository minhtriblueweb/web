<?php
include "config.php";
$result = [];
$keyword = trim($_POST['keyword'] ?? '');

if ($keyword !== '') {
  $products = $d->rawQuery("select id, id_list, id_cat, id_item, id_sub, id_brand, name$lang, type from `tbl_product` where name$lang like ? order by id desc limit 10",["%$keyword%"]) ?: [];

  foreach ($products as $row) {
    $result[] = [
      'id' => $row['id'],
      'name' => $row["name$lang"],
      'page' => 'product',
      'type' => $row['type'],
      'ids' => [
        'id_list'  => $row['id_list'],
        'id_cat'   => $row['id_cat'],
        'id_item'  => $row['id_item'],
        'id_sub'   => $row['id_sub'],
        'id_brand' => $row['id_brand']
      ]
    ];
  }

  $news = $d->rawQuery("select id, id_list, id_cat, id_item, id_sub, name$lang, type from `tbl_news` where name$lang like ? order by id desc limit 10", ["%$keyword%"]) ?: [];

  foreach ($news as $row) {
    $result[] = [
      'id' => $row['id'],
      'name' => $row["name$lang"],
      'page' => 'news',
      'type' => $row['type'],
      'ids' => [
        'id_list'  => $row['id_list'],
        'id_cat'   => $row['id_cat'],
        'id_item'  => $row['id_item'],
        'id_sub'   => $row['id_sub']
      ]
    ];
  }
}

echo json_encode(['success' => true, 'data' => $result], JSON_UNESCAPED_UNICODE);
exit;
