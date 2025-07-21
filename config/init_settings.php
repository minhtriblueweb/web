<?php
$optsetting = $db->rawQueryOne("SELECT * FROM tbl_setting WHERE id = ?", [1]);
$optsetting_json = json_decode($optsetting['options'] ?? '{}', true);
$lang = $optsetting_json['lang_default'] ?? array_key_first($config['website']['lang']);
$logo = $db->rawQueryOne("SELECT `file` FROM tbl_photo WHERE type = ? AND FIND_IN_SET('hienthi', status) LIMIT 1", ['logo']);
$favicon = $db->rawQueryOne("SELECT `file` FROM tbl_photo WHERE type = ? AND FIND_IN_SET('hienthi', status) LIMIT 1", ['favicon']);
$seo->set('url', $fn->getPageURL());
$seo->set('favicon', !empty($favicon['file']) ? $fn->getImageCustom(['file' => $favicon['file'], 'width' => 48, 'height' => 48, 'zc' => 1, 'src_only' => true]) : '');
