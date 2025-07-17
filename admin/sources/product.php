<?php
$act = $_GET['act'] ?? 'man';
$type = 'sanpham';
// $pageConfig = [
//   'name_page' => 'Sản phẩm',
//   'table' => "tbl_$type",
//   'width' => 500,
//   'height' => 500,
//   'img_type_list' => '.jpg|.gif|.png|.jpeg|.gif|.webp',
//   'linkMan' => "index.php?page=$type&act=man",
//   'linkForm' => "index.php?page=$type&act=form",
//   'linkEdit' => "index.php?page=$type&act=form&id=",
//   'linkDelete' => "index.php?page=$type&act=delete&id=",
//   'linkMulti' => "index.php?page=$type&act=delete_multiple",
//   'linkGalleryMan' => "index.php?page=gallery&act=man&id=",
//   'linkGalleryForm' => "index.php?page=gallery&act=form&id=",
//   'status' => ['hienthi' => 'Hiển thị', 'noibat' => 'Nổi bật', 'banchay' => 'Bán chạy']
// ];
// extract($pageConfig);
$act   = $_GET['act'] ?? '';
$type  = $_GET['type'] ?? '';
if (!isset($config['product'][$type])) $fn->transfer("Trang không tồn tại!", "index.php", false);

switch ($act) {
  case 'delete':
    if (!empty($_GET['id']) && is_numeric($_GET['id'])) {
      $fn->delete_data([
        'id' => (int)$_GET['id'],
        'table' => $table,
        'type' => $type,
        'delete_seo' => true,
        'delete_gallery' => true,
        'redirect_page' => $linkMan
      ]);
    }
    break;

  case 'delete_multiple':
    if (!empty($_GET['listid'])) {
      $fn->deleteMultiple_data([
        'listid' => $_GET['listid'],
        'table' => $table,
        'type' => $type,
        'delete_seo' => true,
        'delete_gallery' => true,
        'redirect_page' => $linkMan
      ]);
    }
    break;
  case 'man':
  case 'man_list':
  case 'man_cat':
    $level = match ($act) {
      'man_list' => 'list',
      'man_cat'  => 'cat',
      default    => 'product'
    };

    // Tên bảng
    $tableMap = [
      'list'    => 'tbl_product_list',
      'cat'     => 'tbl_product_cat',
      'product' => 'tbl_product'
    ];
    $table = $tableMap[$level];

    // Links
    $linkBase = "index.php?page=product&type=$type";
    $linkMan = "$linkBase&act=man" . ($level !== 'product' ? "_$level" : '');
    $linkForm = "$linkBase&act=form";
    $linkEdit = "$linkForm&id=";
    $linkDelete = "$linkBase&act=delete&id=";
    $linkMulti = "$linkBase&act=delete_multiple";

    // Bộ lọc và join
    $keyword = $_GET['keyword'] ?? '';
    $current_page = max(1, (int)($_GET['p'] ?? 1));
    $rp = 10;
    $where = [];
    $join = '';
    $select = '*';

    if ($level === 'product') {
      $id_list = $_GET['id_list'] ?? '';
      $id_cat = $_GET['id_cat'] ?? '';

      $join = "
      LEFT JOIN tbl_product_cat c2 ON p.id_cat = c2.id
      LEFT JOIN tbl_product_list c1 ON p.id_list = c1.id
    ";

      $where = array_filter([
        'c1.id' => $id_list,
        'c2.id' => $id_cat
      ]);

      $select = "p.*, c1.name{$lang} AS name_list, c2.name{$lang} AS name_cat";

      $total_records = $fn->count_data_join([
        'table'   => $table,
        'alias'   => 'p',
        'join'    => $join,
        'where'   => $where,
        'keyword' => $keyword
      ]);

      $show_data = $fn->show_data_join([
        'table' => $table,
        'alias' => 'p',
        'join'  => $join,
        'select' => $select,
        'where' => $where,
        'keyword' => $keyword,
        'records_per_page' => $rp,
        'current_page'     => $current_page
      ]);
    } else {
      $filter = [];
      if ($level === 'cat') {
        $filter['id_list'] = $_GET['id_list'] ?? '';
      }

      $total_records = $fn->count_data(array_merge([
        'table' => $table,
        'keyword' => $keyword
      ], $filter));

      $show_data = $fn->show_data(array_merge([
        'table' => $table,
        'keyword' => $keyword,
        'records_per_page' => $rp,
        'current_page'     => $current_page
      ], $filter));
    }

    $total_pages = ceil($total_records / $rp);
    $paging = $fn->renderPagination($current_page, $total_pages);

    // Breadcrumb
    $titleMap = [
      'list'    => $config['product'][$type]['title_main_list'],
      'cat'     => $config['product'][$type]['title_main_cat'],
      'product' => $config['product'][$type]['title_main']
    ];
    $breadcrumb = [['label' => $titleMap[$level]]];
    include TEMPLATE . LAYOUT . 'breadcrumb.php';

    // Template
    $templateMap = [
      'list'    => 'product/man_list/product_man_list.php',
      'cat'     => 'product/man_cat/product_man_cat.php',
      'product' => 'product/man/product_man.php'
    ];
    include TEMPLATE . $templateMap[$level];
    break;


  case 'form':
    $id = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : null;
    $result = $seo_data = $gallery = [];

    if ($id) {
      $result = $db->rawQueryOne("SELECT * FROM `$table` WHERE id = ?", [$id]);
      $seo_data = $result ? $seo->get_seo($id, $type) : [];
      $gallery = $db->rawQuery("SELECT * FROM tbl_gallery WHERE id_parent = ? AND type = ? ORDER BY numb ASC, id ASC", [$id, $type]);
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && (isset($_POST['add']) || isset($_POST['edit']))) {
      $fn->save_data($_POST, $_FILES, $id, [
        'table'         => $table,
        'fields_multi'  => ['slug', 'name', 'desc', 'content'],
        'fields_common' => ['id_list', 'id_cat', 'regular_price', 'sale_price', 'discount', 'code', 'numb', 'type'],
        'status_flags'  => array_keys($config['product'][$type]['check']),
        'redirect_page' => $linkMan,
        'convert_webp'  => true,
        'enable_slug'   => true,
        'enable_seo'    => true,
        'enable_gallery' => true
      ]);
    }

    $breadcrumb = [['label' => ($id ? 'Cập nhật ' : 'Thêm mới ') . $config['product'][$type]['title_main']]];
    include TEMPLATE . LAYOUT . "breadcrumb.php";
    include TEMPLATE . "product/product_form.php";
    break;

  default:
    $fn->transfer("Trang không tồn tại", "index.php", false);
    break;
}
