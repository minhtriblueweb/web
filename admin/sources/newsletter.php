<?php
if (!defined('SOURCES')) die("Error");
if (!isset($config['newsletter'])) $fn->transfer(trangkhongtontai, "index.php", false);

$table = 'tbl_newsletter';
$linkMan = "index.php?page=newsletter&act=man&type=" . $type;
$linkEdit = "index.php?page=newsletter&act=edit&type=" . $type;
switch ($act) {
  case "man":
    viewMans();
    $template = "newsletter/man/mans";
    break;
  case "add":
    $template = "newsletter/man/man_add";
    break;
  case "edit":
    editMan();
    $template = "newsletter/man/man_add";
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

/* View newsletter */
function viewMans()
{
  global $fn, $db, $table, $keyword, $curPage, $perPage, $paging, $items;

  $keyword = trim($_GET['keyword'] ?? '');
  $curPage = max(1, (int)($_GET['p'] ?? 1));
  $perPage = 10;

  $where  = [];
  $params = [];

  // lọc type = dang-ky-nhan-tin
  $where[]  = "type = ?";
  $params[] = "dang-ky-nhan-tin";

  // tìm kiếm theo fullname
  if ($keyword !== '') {
    $where[]  = "fullname LIKE ?";
    $params[] = "%{$keyword}%";
  }

  $whereSql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

  // Tổng record
  $row = $db->rawQueryOne("SELECT COUNT(*) AS total FROM `$table` $whereSql", $params);

  $total = (int)($row['total'] ?? 0);

  // Phân trang
  $offset = ($curPage - 1) * $perPage;

  // Lấy dữ liệu
  $items = $db->rawQueryArray(
    "SELECT * FROM `$table` $whereSql ORDER BY numb ASC, id DESC LIMIT $perPage OFFSET $offset",
    $params
  );
  $paging = $fn->pagination($total, $perPage, $curPage);
}

/* Edit newsletter */
function editMan()
{
  global $db, $fn, $table, $linkMan, $id, $item, $linkEdit;
  $id = (isset($_GET['id']) && is_numeric($_GET['id'])) ? (int)$_GET['id'] : null;
  $item = [];
  if ($id) {
    $item = $db->rawQueryOne("SELECT * FROM `$table` WHERE id = ? LIMIT 1", [$id]);
    if (!$item) {
      $fn->transfer(dulieukhongcothuc, $linkMan, false);
    }
  }
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = $_POST['data'] ?? [];
    $data['email'] = trim($data['email'] ?? '');
    $data['phone'] = trim($data['phone'] ?? '');
    if (!$fn->isEmail($data['email'])) {
      $fn->transfer(emailkhonghople, $linkEdit .= "&id=" . $id, false);
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

/* Delete newsletter */
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
