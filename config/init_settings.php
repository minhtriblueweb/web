<?php
$lang = $_SESSION['lang'] ?? array_key_first($config['website']['lang']);
$optsetting = $db->rawQueryOne("SELECT * FROM tbl_setting WHERE id = ?", [1]);
// SEO mặc định
$default_seo = [
  'favicon'     => !empty($optsetting['favicon']) ? $fn->getImageCustom(['file' => $optsetting['favicon'], 'width' => 48, 'height' => 48, 'zc' => 1, 'src_only' => true]) : '',
  'title'       => $optsetting['web_name'],
  'keywords'    => $optsetting['web_name'],
  'description' => $optsetting['desc' . $lang] ?? '',
  'geo'         => $optsetting['coords'],
  'web_name'    => $optsetting['web_name'],
  'email'       => $optsetting['email'],
  'url'         => BASE,
  'image'       => !empty($optsetting['logo']) ? BASE_ADMIN . UPLOADS . $optsetting['logo'] : ''
];
