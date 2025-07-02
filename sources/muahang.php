<?php
$row_mh = $trangtinh->get_static('muahang');
$data_seo = $seo->get_seopage(
  $db->rawQueryOne("SELECT * FROM tbl_seopage WHERE type = ?", ['muahang']) ?: [],
  $lang
); // breadcrumbs
$breadcrumbs->set('mua-hang', 'Mua hàng');
$breadcrumbs = $breadcrumbs->get();
