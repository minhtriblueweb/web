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
$show_social = $fn->show_data([
  'table'  => 'tbl_social',
  'status' => 'hienthi',
  'select' => "file, link, name{$lang}"
]);
