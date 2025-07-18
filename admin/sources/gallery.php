<?php
$act       = $_GET['act'] ?? 'man';
$type      = 'sanpham';
$id        = $_GET['id'] ?? null;
$id_child  = $_GET['id_child'] ?? null;
$table = "tbl_gallery";
$pageConfig = [
  'linkMan'        => "index.php?page=gallery&act=man&id=$id",
  'linkForm'       => "index.php?page=gallery&act=form&id=",
  'linkEdit'       => "index.php?page=gallery&act=form&id_child=",
  'linkDelete'     => "index.php?page=gallery&id=$id&delete=",
  'linkMulti'      => "index.php?page=gallery&id=$id&delete_multiple=1",
];
extract($pageConfig);

// Xoá ảnh đơn
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
  $fn->delete_data([
    'id'            => $_GET['delete'],
    'table'         => $table,
    'type'          => $type,
    'redirect_page' => $linkMan
  ]);
}

// Xoá nhiều ảnh
if (isset($_GET['delete_multiple'], $_GET['listid'])) {
  $fn->deleteMultiple_data([
    'listid'        => $_GET['listid'],
    'table'         => $table,
    'type'          => $type,
    'redirect_page' => $linkMan
  ]);
}

switch ($act) {
  case 'man':
    $rp     = 10;
    $p      = max(1, (int)($_GET['p'] ?? 1));
    $total  = $fn->count_data(['table' => $table]);
    $tp     = ceil($total / $rp);
    $paging = $fn->renderPagination($p, $tp);

    $parent = [];
    if ($id) {
      $parent = $db->rawQueryOne("SELECT id, name{$lang},slug{$lang} FROM tbl_product WHERE id = ? LIMIT 1", [$id]);
    }

    $get_gallery = $fn->show_data([
      'table'            => $table,
      'type'             => $type,
      'id_parent'        => $id,
      'records_per_page' => $rp,
      'current_page'     => $p,
      'keyword'          => $_GET['keyword'] ?? ''
    ]);

    $breadcrumb = [['label' => $config['product'][$type]['gallery'][$type]['title_main_photo']]];
    include TEMPLATE . LAYOUT . "breadcrumb.php";
    include TEMPLATE . "gallery/gallery_man.php";
    break;

  case 'form':
    // Thêm mới
    $isPost = ($_SERVER['REQUEST_METHOD'] === 'POST');
    $isAdd  = ($isPost && isset($_POST['add']) && $id);
    $isEdit = ($isPost && isset($_POST['edit']) && $id_child > 0);
    if ($isAdd) {
      $fn->save_gallery($_POST, $_FILES, $id, $type, $linkMan);
    }
    $result = $db->rawQueryOne("SELECT * FROM tbl_gallery WHERE id = ? LIMIT 1", [$id_child]);
    if ($isEdit && $result && isset($result['id_parent'])) {
      $product->upload_gallery($_POST, $_FILES, $id_child, $type, $result['id_parent']);
    }

    $breadcrumb = [['label' => ($id_child ? 'Cập nhật ' : 'Thêm mới ') . $config['product'][$type]['gallery'][$type]['title_main_photo']]];
    include TEMPLATE . LAYOUT . "breadcrumb.php";
    include TEMPLATE . "gallery/gallery_form.php";
    break;

  default:
    $fn->transfer("Trang không tồn tại", "index.php", false);
    break;
}
