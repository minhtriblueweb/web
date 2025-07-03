<?php
// Các biến toàn cục khác (đồng bộ từ $setting)
$hotline         = $setting['hotline'];
$web_name        = $setting['web_name'];
$introduction    = $setting['introduction'];
$worktime        = $setting['worktime'];
$descvi          = $setting['desc' . $lang] ?? '';
$address         = $setting['address'];
$coords_iframe   = $setting['coords_iframe'];
$copyright       = $setting['copyright'];
$bodyjs          = $setting['bodyjs'];
$headjs          = $setting['headjs'];
$analytics       = $setting['analytics'];
$logo            = $setting['logo'];
$color           = $setting['color'];

$show_social = $cache->get(
  "SELECT file, link, name{$lang}, desc{$lang} FROM tbl_social WHERE FIND_IN_SET('hienthi', status)",
  [],
  'all',
  7200
);

// Lấy danh mục cấp 1
$dm_c1_all = $fn->show_data([
  'table'  => 'tbl_danhmuc_c1',
  'status' => 'hienthi,noibat',
  'select' => "id, file, slug{$lang}, name{$lang}"
]);

// Lấy danh mục cấp 2
$dm_c2_all = $fn->show_data([
  'table'  => 'tbl_danhmuc_c2',
  'status' => 'hienthi,noibat',
  'select' => "id, id_list, slug{$lang}, name{$lang}"
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
