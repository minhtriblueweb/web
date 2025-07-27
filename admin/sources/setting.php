<?php
$linkMan = "index.php?page=setting&act=update";

if (!($row = $db->rawQueryOne("SELECT * FROM tbl_setting LIMIT 1"))) {
  $fn->transfer(dulieukhongcothuc, $linkMan, false);
}

$options = json_decode($row['options'] ?? '{}', true);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload'])) {
  $fn->save_data($_POST, null, 1, [
    'table'           => "tbl_setting",
    'fields_multi'    => ['name', 'slogan', 'copyright'],
    'fields_common'   => ['video', 'mastertool', 'analytics', 'headjs', 'bodyjs'],
    'fields_options'  => [
      'address',
      'opendoor',
      'phone',
      'hotline',
      'zalo',
      'oaidzalo',
      'email',
      'website',
      'fanpage',
      'coords',
      'coords_iframe',
      'coords_link',
      'slogan',
      'color',
      'copyright',
      'lang_default'
    ],
    'redirect_page'   => $linkMan
  ]);
}

$template = "setting/setting";
