<?php
// Các biến toàn cục khác (đồng bộ từ $optsetting)
$hotline         = $optsetting['hotline'];
$web_name        = $optsetting['web_name'];
$introduction    = $optsetting['introduction'];
$worktime        = $optsetting['worktime'];
$descvi          = $optsetting['desc' . $lang] ?? '';
$address         = $optsetting['address'];
$coords_iframe   = $optsetting['coords_iframe'];
$copyright       = $optsetting['copyright'];
$bodyjs          = $optsetting['bodyjs'];
$headjs          = $optsetting['headjs'];
$analytics       = $optsetting['analytics'];
$color           = $optsetting['color'];

$show_social = $db->rawQuery("SELECT file, link, name{$lang}, desc{$lang} FROM tbl_photo WHERE type= ? AND FIND_IN_SET('hienthi', status)", ['social']);

$tieuchi = $fn->show_data([
  'table' => 'tbl_news',
  'status' => 'hienthi',
  'type'   => 'tieuchi',
  'select' => "file, name{$lang}, desc{$lang}"
]);

// Lấy danh mục cấp 1
$dm_c1_all = $fn->show_data([
  'table'  => 'tbl_product_list',
  'status' => 'hienthi,noibat',
  'select' => "id, file, slug{$lang}, name{$lang}"
]);

// Lấy danh mục cấp 2
$dm_c2_all = $fn->show_data([
  'table'  => 'tbl_product_cat',
  'status' => 'hienthi,noibat',
  'select' => "id, file,id_list, slug{$lang}, name{$lang}"
]);

// Gom nhóm cấp 2 theo id_list
$dm_c2_group = [];
foreach ($dm_c2_all ?? [] as $row) {
  $dm_c2_group[$row['id_list']][] = $row;
}

// Gộp vào menu cây
$dm_c1_rows = [];
$menu_tree = [];
foreach ($dm_c1_all ?? [] as $lv1) {
  $lv1['sub'] = $dm_c2_group[$lv1['id']] ?? [];
  $menu_tree[] = $lv1;
  $dm_c1_rows[] = $lv1;
}
