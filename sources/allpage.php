<?php
$hotline         = $optsetting_json['hotline'];
$introduction    = $optsetting["slogan{$lang}"];
$worktime        = $optsetting_json['opendoor'];
$descvi          = $optsetting_json["desc'{$lang}"] ?? '';
$web_name        = $optsetting_json["name'{$lang}"] ?? '';
$address         = $optsetting_json['address'];
$coords_iframe   = $optsetting_json['coords_iframe'];
$copyright       = $optsetting_json['copyright'];
$bodyjs          = $optsetting['bodyjs'];
$headjs          = $optsetting['headjs'];
$analytics       = $optsetting['analytics'];
$color           = $optsetting_json['color'];

$show_social = $fn->show_data([
  'table' => 'tbl_photo',
  'status' => 'hienthi',
  'type'   => 'social',
  'select' => "file, link, name{$lang}, desc{$lang}"
]);

$tieuchi = $fn->show_data([
  'table' => 'tbl_news',
  'status' => 'hienthi',
  'type'   => 'tieu-chi',
  'select' => "file, name{$lang}, desc{$lang}"
]);
