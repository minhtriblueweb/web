<?php
if (!defined('SOURCES')) die("Error");
$act  = $_GET['act'] ?? '';
$type = $_GET['type'] ?? '';
if (!isset($config['product'][$type])) $fn->transfer("Trang không tồn tại!", "index.php", false);
// Khởi tạo thông tin theo cấp độ
$level = 'product';
$table = 'tbl_product';

// Phân loại cấp
if (str_contains($act, 'list')) {
  $level = 'list';
  $table = 'tbl_product_list';
} elseif (str_contains($act, 'cat')) {
  $level = 'cat';
  $table = 'tbl_product_cat';
}

$tableMap = [
  'product' => 'tbl_product',
  'list'    => 'tbl_product_list',
  'cat'     => 'tbl_product_cat'
];

$templateMap = [
  'man'  => [
    'template' => [
      'product' => 'product/man/product_man.php',
      'list'    => 'product/list/product_man_list.php',
      'cat'     => 'product/cat/product_man_cat.php'
    ],
    'title' => [
      'product' => $config['product'][$type]['title_main'],
      'list'    => $config['product'][$type]['title_main_list'],
      'cat'     => $config['product'][$type]['title_main_cat']
    ]
  ],
  'form' => [
    'template' => [
      'product' => 'product/man/product_form.php',
      'list'    => 'product/list/product_form_list.php',
      'cat'     => 'product/cat/product_form_cat.php'
    ]
  ]
];

$linkBase   = "index.php?page=product&type=$type";
$linkMan    = "$linkBase&act=man" . ($level != 'product' ? "_$level" : '');
$linkForm   = "$linkBase&act=form" . ($level != 'product' ? "_$level" : '');
$linkEdit   = "$linkForm&id=";
$linkDelete = "$linkBase&act=delete&id=";
$linkMulti  = "$linkBase&act=delete_multiple";

// DELETE đơn
if ($act === 'delete' && !empty($_GET['id']) && is_numeric($_GET['id'])) {
  $fn->delete_data([
    'id'             => (int)$_GET['id'],
    'table'          => $table,
    'type'           => $type,
    'delete_seo'     => true,
    'redirect_page'  => $linkMan,
    'delete_gallery' => ($level === 'product')
  ]);
}

// DELETE nhiều
if ($act === 'delete_multiple' && !empty($_GET['listid'])) {
  $fn->deleteMultiple_data([
    'listid'         => $_GET['listid'],
    'table'          => $table,
    'type'           => $type,
    'delete_seo'     => true,
    'redirect_page'  => $linkMan,
    'delete_gallery' => ($level === 'product')
  ]);
}

switch (true) {
  case str_starts_with($act, 'man'): {
      $keyword      = $_GET['keyword'] ?? '';
      $current_page = max(1, (int)($_GET['p'] ?? 1));
      $rp           = 10;
      $join         = '';
      $select       = '*';
      $where        = [];

      if ($level === 'product') {
        $id_list = $_GET['id_list'] ?? '';
        $id_cat  = $_GET['id_cat'] ?? '';
        $join = "
        LEFT JOIN tbl_product_cat c2 ON p.id_cat = c2.id
        LEFT JOIN tbl_product_list c1 ON p.id_list = c1.id
      ";
        $where = array_filter([
          'c1.id' => $id_list,
          'c2.id' => $id_cat
        ]);
        $select = "p.*, c1.name{$lang} AS name_list, c2.name{$lang} AS name_cat";
      } elseif ($level === 'cat') {
        if (!empty($_GET['id_list'])) $where['id_list'] = $_GET['id_list'];
      }

      $options = [
        'table'            => $table,
        'alias'            => 'p',
        'join'             => $join,
        'select'           => $select,
        'where'            => $where,
        'keyword'          => $keyword,
        'records_per_page' => $rp,
        'current_page'     => $current_page,
        'order'            => ($join ? 'p.numb ASC, p.id DESC' : 'numb ASC, id DESC')
      ];

      $total_records = $fn->count_data($options);
      $show_data     = $fn->show_data($options);
      $total_pages   = ceil($total_records / $rp);
      $paging        = $fn->renderPagination($current_page, $total_pages);

      $title     = $templateMap['man']['title'][$level];
      $breadcrumb = [['label' => $title]];
      include TEMPLATE . LAYOUT . 'breadcrumb.php';
      include TEMPLATE . $templateMap['man']['template'][$level];
      break;
    }
  case str_starts_with($act, 'form'): // gộp form và save
    {
      $id = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : null;
      // Xác định level
      $level = 'product';
      if (str_contains($act, 'list')) $level = 'list';
      elseif (str_contains($act, 'cat')) $level = 'cat';

      // Bản đồ liên quan
      $tableMap = [
        'product' => 'tbl_product',
        'list'    => 'tbl_product_list',
        'cat'     => 'tbl_product_cat'
      ];
      $actMap = [
        'product' => 'man',
        'list'    => 'man_list',
        'cat'     => 'man_cat'
      ];
      $linkMan = "index.php?page=product&act=" . $actMap[$level] . "&type=" . $type;
      $checkMap = [
        'product' => 'check',
        'list'    => 'check_list',
        'cat'     => 'check_cat'
      ];

      // Dữ liệu cấu hình
      $table = $tableMap[$level];
      $checkKey = $checkMap[$level];
      $status_flags = array_keys($config['product'][$type][$checkKey] ?? []);
      $convert_webp = in_array($level, ['product', 'cat']);

      // Fields
      $fields_multi = ['slug', 'name', 'desc', 'content'];
      $fields_common = ['numb', 'type'];
      if ($level == 'product') {
        $fields_common = array_merge($fields_common, ['id_list', 'id_cat', 'regular_price', 'sale_price', 'discount', 'code']);
      } elseif ($level == 'cat') {
        $fields_common[] = 'id_list';
      }

      // Nếu là POST => lưu
      if ($_SERVER['REQUEST_METHOD'] === 'POST' && (isset($_POST['add']) || isset($_POST['edit']))) {
        $fn->save_data($_POST, $_FILES, $id, [
          'table'         => $table,
          'type'          => $type,
          'act'           => $actMap[$level],
          'fields_multi'  => $fields_multi,
          'fields_common' => $fields_common,
          'status_flags'  => $status_flags,
          'redirect_page' => $linkMan,
          'convert_webp'  => $convert_webp,
          'enable_slug'   => true,
          'enable_seo'    => true
        ]);
        break;
      }

      // Ngược lại => load form
      $id = $_GET['id'] ?? 0;
      $result = [];
      if (!empty($id)) {
        $result = $db->rawQueryOne("SELECT * FROM `$table` WHERE id = ?", [$id]);
        if (!$result) $fn->transfer("Dữ liệu không tồn tại", $linkMan, false);
        $seo_data = $seo->get_seo($id, $type);
      }

      $title = ($id > 0)
        ? "Cập nhật " . ($config['product'][$type][$level === 'product' ? 'title_main' : 'title_main_' . $level] ?? 'Sản phẩm')
        : "Thêm mới " . ($config['product'][$type][$level === 'product' ? 'title_main' : 'title_main_' . $level] ?? 'Sản phẩm');

      $breadcrumb = [['label' => $title]];
      include TEMPLATE . LAYOUT . 'breadcrumb.php';
      include TEMPLATE . $templateMap['form']['template'][$level];
      break;
    }
}
