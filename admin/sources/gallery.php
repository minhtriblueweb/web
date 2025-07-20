<?php
$act        = $_GET['act'] ?? 'man';
$type       = 'sanpham';
$id         = $_GET['id'] ?? null;
$id_child   = $_GET['id_child'] ?? null;
$listid   = $_GET['listid'] ?? null;
$table      = "tbl_gallery";
$keyword    = $_GET['keyword'] ?? '';
$parent = [];
if ($id) {
  $parent = $db->rawQueryOne("SELECT id, name{$lang},slug{$lang} FROM tbl_product WHERE id = ? LIMIT 1", [$id]);
}
$linkMan    = "index.php?page=gallery&act=man&id=$id";
$linkForm   = "index.php?page=gallery&act=form&id=";
$linkEdit   = "index.php?page=gallery&act=form&id_child=";
$linkDelete = "index.php?page=gallery&act=delete&id=$id&id_child=";
$linkMulti  = "index.php?page=gallery&act=delete_multiple&id=$id";


switch ($act) {
  case 'man':
    $perPage = 10;
    $curPage = max(1, (int)($_GET['p'] ?? 1));
    $options = [
      'table'            => $table,
      'type'             => $type,
      'id_parent'        => $id,
      'keyword'          => $keyword,
      'pagination'       => [$perPage, $curPage]
    ];
    $total = $fn->count_data($options);
    $show_data = $fn->show_data($options);
    $paging = $fn->pagination($total, $perPage, $curPage);
    $breadcrumb = [['label' => $config['product'][$type]['gallery'][$type]['title_main_photo']]];
    include TEMPLATE . LAYOUT . "breadcrumb.php";
    include TEMPLATE . "gallery/gallery_man.php";
    break;

  case 'delete':
    $fn->delete_data([
      'id'            => $id_child,
      'table'         => $table,
      'type'          => $type,
      'redirect_page' => $linkMan
    ]);
    break;

  case 'delete_multiple':
    $fn->deleteMultiple_data([
      'listid'        => $listid,
      'table'         => $table,
      'type'          => $type,
      'redirect_page' => $linkMan
    ]);

    break;

  case 'form':
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
