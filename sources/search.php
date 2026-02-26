<?php
if (!defined('SOURCES')) die("Error");

@$keyword = htmlspecialchars($_GET['keyword']);
$curPage =  max(1, isset($_GET['page']) ? (int)$_GET['page'] : 1);
$perPage = 20;
$options = ['table' => 'tbl_product', 'status' => 'hienthi', 'select' => "id, name{$lang}, slug{$lang}, file, regular_price, sale_price, views", 'keyword' => $keyword, 'pagination' => [$perPage, $curPage]];
$total = $func->count_data($options);
$product = $func->show_data($options);
$paging = $func->pagination_tc($total, $perPage, $curPage);
/* SEO */
$seo->set('h1', $keyword);
$seo->set('title', "Tìm kiếm : " . $keyword);

/* breadCrumbs */
$breadcr->set($slug, $titleMain);
$breadcrumbs =  $breadcr->get();
