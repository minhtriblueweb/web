<?php
$lang = $_SESSION['lang'] ?? array_key_first($config['website']['lang']);
$setting = $db->rawQueryOne("SELECT * FROM tbl_setting WHERE id = ?", [1]);
// SEO mặc định
$default_seo = [
  'favicon'     => !empty($setting['favicon']) ? BASE_ADMIN . UPLOADS . $setting['favicon'] : '',
  'title'       => $setting['web_name'],
  'keywords'    => '',
  'description' => $setting['desc' . $lang] ?? '',
  'geo'         => $setting['coords'],
  'web_name'    => $setting['web_name'],
  'email'       => $setting['email'],
  'url'         => BASE,
  'image'       => !empty($setting['logo']) ? BASE_ADMIN . UPLOADS . $setting['logo'] : ''
];
