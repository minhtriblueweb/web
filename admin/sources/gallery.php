<?php
$act       = $_GET['act'] ?? 'man';
$type      = 'product';
$id        = $_GET['id'] ?? null;
$id_child  = $_GET['id_child'] ?? null;

$pageConfig = [
  'name_page'      => 'hình ảnh sản phẩm',
  'table'          => 'tbl_gallery',
  'width'          => 500,
  'height'         => 500,
  'img_type_list'  => '.jpg|.gif|.png|.jpeg|.webp',
  'linkMan'        => "index.php?page=gallery&act=man&id=$id",
  'linkForm'       => "index.php?page=gallery&act=form&id=",
  'linkEdit'       => "index.php?page=gallery&act=form&id_child=",
  'linkDelete'     => "index.php?page=gallery&id=$id&delete=",
  'linkMulti'      => "index.php?page=gallery&id=$id&delete_multiple=1",
  'status'         => ['hienthi' => 'Hiển thị']
];
extract($pageConfig);

// Xoá ảnh đơn
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
  $fn->delete([
    'id'            => $_GET['delete'],
    'table'         => $table,
    'type'          => $type,
    'redirect_page' => $linkMan
  ]);
}

// Xoá nhiều ảnh
if (isset($_GET['delete_multiple'], $_GET['listid'])) {
  $fn->deleteMultiple([
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
      $parent = $db->rawQueryOne("SELECT id, name{$lang} FROM tbl_product WHERE id = ? LIMIT 1", [$id]);
    }

    $get_gallery = $fn->show_data([
      'table'            => $table,
      'type'             => $type,
      'id_parent'        => $id,
      'records_per_page' => $rp,
      'current_page'     => $p,
      'keyword'          => $_GET['keyword'] ?? ''
    ]);

    $breadcrumb = [['label' => $name_page]];
    include TEMPLATE . LAYOUT . "breadcrumb.php";
    include TEMPLATE . "gallery/gallery_man.php";
    break;

  case 'form':
    // Thêm mới
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add']) && $id) {
      $fn->add_gallery($_POST, $_FILES, $id, $type, $linkMan);
    }

    // Cập nhật
    if (
      $id_child &&
      ($result = $db->rawQueryOne("SELECT * FROM tbl_gallery WHERE id = ? LIMIT 1", [$id_child])) &&
      ($id_parent = $result['id_parent'] ?? null) &&
      $_SERVER['REQUEST_METHOD'] === 'POST' &&
      isset($_POST['edit'])
    ) {
      $product->upload_gallery($_POST, $_FILES, $id_child, $type, $id_parent);
    }

    $breadcrumb = [['label' => ($id_child ? 'Cập nhật ' : 'Thêm mới ') . $name_page]];
    include TEMPLATE . LAYOUT . "breadcrumb.php";
    include TEMPLATE . "gallery/gallery_form.php";
    break;

  default:
    $fn->transfer("Trang không tồn tại", "index.php", false);
    break;
}
