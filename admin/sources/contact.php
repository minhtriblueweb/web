<?php
if (!defined('SOURCES')) die("Error");
$table = 'tbl_newsletter';
$linkMan = "index.php?com=contact&act=man&type=lien-he";
$linkEdit = "index.php?com=contact&act=edit&type=lien-he&id=";
switch ($act) {
  case "man":
    viewMans();
    $template = "contact/man/mans";
    break;

  case "edit":
    editMans();
    $template = "contact/man/man_add";
    break;

  case "save":
    saveMan();
    break;

  case "delete":
    deleteMan();
    break;

  default:
    $func->transfer(trangkhongtontai, "index.php", false);
    break;
}
function viewMans()
{
  global $func, $d, $table, $keyword, $curcom, $percom, $paging, $show_data;

  $keyword = trim($_GET['keyword'] ?? '');
  $curcom = max(1, (int)($_GET['p'] ?? 1));
  $percom = 10;

  $where  = [];
  $params = [];

  // lọc type = lien-he
  $where[]  = "type = ?";
  $params[] = "lien-he";

  // tìm kiếm theo fullname
  if ($keyword !== '') {
    $where[]  = "fullname LIKE ?";
    $params[] = "%{$keyword}%";
  }

  $whereSql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

  // Tổng record
  $row = $d->rawQueryOne("SELECT COUNT(*) AS total FROM `$table` $whereSql",$params);

  $total = (int)($row['total'] ?? 0);

  // Phân trang
  $offset = ($curcom - 1) * $percom;

  // Lấy dữ liệu
  $show_data = $d->rawQueryArray(
    "SELECT * FROM `$table` $whereSql ORDER BY numb ASC, id DESC LIMIT $percom OFFSET $offset",
    $params
  );
  $paging = $func->pagination($total, $percom, $curcom);
}

function editMans()
{
  global $d, $func, $table, $linkMan, $linkEdit, $id, $result;

  $id = (isset($_GET['id']) && is_numeric($_GET['id'])) ? (int)$_GET['id'] : null;
  $result = [];

  if ($id) {
    $result = $d->rawQueryOne("SELECT * FROM `$table` WHERE id = ? LIMIT 1",[$id]);

    if (!$result) {
      $func->transfer(dulieukhongcothuc, $linkMan, false);
    }
  }

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = $_POST['data'] ?? [];
    $data['email'] = trim($data['email'] ?? '');
    $data['phone'] = trim($data['phone'] ?? '');
    if (!$func->isEmail($data['email'])) {
      $func->transfer(emailkhonghople, $linkEdit.$id, false);
    }
    if (!$func->isPhone($data['phone'])) {
      $func->transfer(sodienthoaikhonghople, $linkEdit.$id, false);
    }
    $save = $func->save_data($data, $_FILES, $id, [
      'table'    => $table,
      'redirect' => $linkMan
    ]);
    if ($save) {
      $func->transfer(capnhatdulieuthanhcong, $linkMan, true);
    }
  }
}

function deleteMan()
{
  global $func, $table, $type, $linkMan;
  $id = !empty($_GET['id']) ? (int)$_GET['id'] : 0;
  if ($id) {
    $func->delete_data([
      'id'        => $id,
      'table'     => $table,
      'type'      => $type,
      'redirect'  => $linkMan
    ]);
  } elseif (!empty($_GET['listid'])) {
    $func->deleteMultiple_data([
      'listid'    => $_GET['listid'],
      'table'     => $table,
      'type'      => $type,
      'redirect'  => $linkMan
    ]);
  } else {
    $func->transfer(khongnhanduocdulieu, $linkMan, false);
  }
}
