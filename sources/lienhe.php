<?php
$row_lh = $db->rawQueryOne("SELECT * FROM tbl_static WHERE type = ?", ['lienhe']);
$data_seo = $seo->get_seopage(
  $db->rawQueryOne("SELECT * FROM tbl_seopage WHERE type = ?", ['lienhe']) ?: [],
  $lang
);

// breadcrumbs
$breadcrumbs->set('lien-he', 'Liên hệ');
$breadcrumbs = $breadcrumbs->get();
