<?php
$row_mh = $db->rawQueryOne("SELECT * FROM tbl_static WHERE type = ?", ['muahang']);
$data_seo = $seo->get_seopage(
  $db->rawQueryOne("SELECT * FROM tbl_seopage WHERE type = ?", ['muahang']) ?: [],
  $lang
);

$breadcrumbs->set('mua-hang', 'Mua hàng');
$breadcrumbs = $breadcrumbs->get();
