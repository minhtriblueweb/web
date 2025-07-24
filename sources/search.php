<?php
if (!defined('SOURCES')) die("Error");

@$keyword = htmlspecialchars($_GET['keyword']);
$curPage =  max(1, isset($_GET['page']) ? (int)$_GET['page'] : 1);
$perPage = 1;
$options = ['table' => 'tbl_product', 'status' => 'hienthi', 'select' => "id, name{$lang}, slug{$lang}, file, regular_price, sale_price, views", 'keyword' => $keyword, 'pagination' => [$perPage, $curPage]];
$total = $fn->count_data($options);
$product = $fn->show_data($options);
$paging = $fn->pagination_tc($total, $perPage, $curPage);
/* SEO */
$seo->set('h1', $keyword);
$seo->set('title', "Tìm kiếm : " . $keyword);

/* breadCrumbs */
$breadcr->set($slug, $titleMain);
$breadcrumbs =  $breadcr->get();
