<?php
$data_seo = $seo->get_seopage(
  $db->rawQueryOne("SELECT * FROM tbl_seopage WHERE type = ?", ['trangchu']) ?: [],
  $lang
);
$slides = $fn->show_data([
  'table'  => 'tbl_slideshow',
  'status' => 'hienthi',
  'select' => "file, name{$lang}, link"
]);
$tintuc = $fn->show_data([
  'table' => 'tbl_news',
  'status' => 'hienthi,noibat',
  'type'   => 'tintuc',
  'select' => "file, name{$lang}, desc{$lang}, slug{$lang}",
  'limit'  => 8
]);
$tieuchi = $fn->show_data([
  'table' => 'tbl_news',
  'status' => 'hienthi',
  'type'   => 'tieuchi',
  'select' => "file, name{$lang}, desc{$lang}"
]);
$danhgia = $fn->show_data([
  'table' => 'tbl_news',
  'status' => 'hienthi',
  'type'   => 'danhgia',
  'select' => "file, name{$lang}, desc{$lang},content{$lang}"
]);
$sanpham_banchay = $fn->show_data([
  'table'  => 'tbl_product',
  'status' => 'hienthi,banchay',
  'select' => "id, file, slug{$lang}, name{$lang}, sale_price, regular_price, views"
]);
$sp_all = $fn->show_data([
  'table'  => 'tbl_product',
  'status' => 'hienthi',
  'select' => "id, file, slug{$lang}, name{$lang}, sale_price, regular_price, views, id_list, id_cat"
]);
$sp_group = [];
foreach ($sp_all ?? [] as $row) {
  $id_list = $row['id_list'];
  $id_cat = $row['id_cat'];
  $sp_group[$id_list]['all'][] = $row;
  if ($id_cat) {
    $sp_group[$id_list]['cat'][$id_cat][] = $row;
  }
}
