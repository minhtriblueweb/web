<?php
$row_gt = $db->rawQueryOne("SELECT * FROM tbl_static WHERE type = ?", ['gioithieu']);
$data_seo = $seo->get_seopage(
  $db->rawQueryOne("SELECT * FROM tbl_seopage WHERE type = ?", ['gioithieu']) ?: [],
  $lang
); // breadcrumbs
$breadcrumbs->set('gioi-thieu', 'Giới thiệu');
$breadcrumbs = $breadcrumbs->get();
