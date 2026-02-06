<?php
if (!defined('SOURCES')) die("Error");
$table = 'tbl_newsletter';
$linkMan = "index.php?page=contact&act=man&type=lien-he";
$linkEdit = "index.php?page=contact&act=edit&type=lien-he&id=";
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
  case 'delete_multiple':
    deleteMultiple();
    break;
  default:
    $fn->transfer(trangkhongtontai, "index.php", false);
    break;
}
function viewMans()
{
  global $fn, $db, $table, $keyword, $curPage, $perPage, $paging, $show_data;

  $keyword = trim($_GET['keyword'] ?? '');
  $curPage = max(1, (int)($_GET['p'] ?? 1));
  $perPage = 10;

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
  $row = $db->rawQueryOne("SELECT COUNT(*) AS total FROM `$table` $whereSql",$params);

  $total = (int)($row['total'] ?? 0);

  // Phân trang
  $offset = ($curPage - 1) * $perPage;

  // Lấy dữ liệu
  $show_data = $db->rawQueryArray(
    "SELECT * FROM `$table` $whereSql ORDER BY numb ASC, id DESC LIMIT $perPage OFFSET $offset",
    $params
  );
  $paging = $fn->pagination($total, $perPage, $curPage);
}

function editMans()
{
  global $db, $fn, $table, $linkMan, $linkEdit, $id, $result;

  $id = (isset($_GET['id']) && is_numeric($_GET['id'])) ? (int)$_GET['id'] : null;
  $result = [];

  if ($id) {
    $result = $db->rawQueryOne(
      "SELECT * FROM `$table` WHERE id = ? LIMIT 1",
      [$id]
    );

    if (!$result) {
      $fn->transfer(dulieukhongcothuc, $linkMan, false);
    }
  }

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = $_POST['data'] ?? [];
    $data['email'] = trim($data['email'] ?? '');
    $data['phone'] = trim($data['phone'] ?? '');
    if (!$fn->isEmail($data['email'])) {
      $fn->transfer(emailkhonghople, $linkEdit.$id, false);
    }
    if (!$fn->isPhone($data['phone'])) {
      $fn->transfer(sodienthoaikhonghople, $linkEdit.$id, false);
    }
    $save = $fn->save_data($data, $_FILES, $id, [
      'table'    => $table,
      'redirect' => $linkMan
    ]);
    if ($save) {
      $fn->transfer(capnhatdulieuthanhcong, $linkMan, true);
    }
  }
}

function deleteMultiple()
{
  global $fn, $table, $type, $linkMan;
  if (!empty($_GET['listid'])) {
    $fn->deleteMultiple_data([
      'listid' => $_GET['listid'],
      'table' => $table,
      'type' => $type,
      'redirect' => $linkMan
    ]);
  } else {
    $fn->transfer(khongnhanduocdulieu, $linkMan, false);
  }
}

function deleteMan()
{
  global $fn, $table, $type, $linkMan;
  if (is_numeric($_GET['id'] ?? null)) {
    $fn->delete_data([
      'id' => (int)$_GET['id'],
      'table' => $table,
      'type' => $type,
      'redirect' => $linkMan
    ]);
  } else {
    $fn->transfer(khongnhanduocdulieu, $linkMan, false);
  }
}
