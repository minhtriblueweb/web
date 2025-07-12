<?php
$lang = $_SESSION['lang'] ?? array_key_first($config['website']['lang']);
$setting = $db->rawQueryOne("SELECT * FROM tbl_setting WHERE id = ?", [1]);
// SEO mặc định
$default_seo = [
  'favicon'     => !empty($setting['favicon']) ? $fn->getImageCustom(['file' => $setting['favicon'], 'width' => 48, 'height' => 48, 'zc' => 1, 'src_only' => true]) : '',
  'title'       => $setting['web_name'],
  'keywords'    => $setting['web_name'],
  'description' => $setting['desc' . $lang] ?? '',
  'geo'         => $setting['coords'],
  'web_name'    => $setting['web_name'],
  'email'       => $setting['email'],
  'url'         => BASE,
  'image'       => !empty($setting['logo']) ? BASE_ADMIN . UPLOADS . $setting['logo'] : ''
];
