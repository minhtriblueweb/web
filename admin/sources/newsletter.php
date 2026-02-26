<?php
if (!defined('SOURCES')) die("Error");
if (!isset($config['newsletter'])) $func->transfer(trangkhongtontai, "index.php", false);

$table = 'tbl_newsletter';
$linkMan = "index.php?com=newsletter&act=man&type=" . $type;
$linkEdit = "index.php?com=newsletter&act=edit&type=" . $type;
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

  default:
    $func->transfer(trangkhongtontai, "index.php", false);
    break;
}

/* View newsletter */
function viewMans()
{
  global $func, $d, $table, $keyword, $curPage, $perPage, $paging, $items;

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
  $row = $d->rawQueryOne("SELECT COUNT(*) AS total FROM `$table` $whereSql", $params);

  $total = (int)($row['total'] ?? 0);

  // Phân trang
  $offset = ($curPage - 1) * $perPage;

  // Lấy dữ liệu
  $items = $d->rawQueryArray(
    "SELECT * FROM `$table` $whereSql ORDER BY numb ASC, id DESC LIMIT $perPage OFFSET $offset",
    $params
  );
  $paging = $func->pagination($total, $perPage, $curPage);
}

/* Edit newsletter */
function editMan()
{
  global $d, $func, $table, $linkMan, $id, $item, $linkEdit;
  $id = (isset($_GET['id']) && is_numeric($_GET['id'])) ? (int)$_GET['id'] : null;
  $item = [];
  if ($id) {
    $item = $d->rawQueryOne("SELECT * FROM `$table` WHERE id = ? LIMIT 1", [$id]);
    if (!$item) {
      $func->transfer(dulieukhongcothuc, $linkMan, false);
    }
  }
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = $_POST['data'] ?? [];
    $data['email'] = trim($data['email'] ?? '');
    $data['phone'] = trim($data['phone'] ?? '');
    if (!$func->isEmail($data['email'])) {
      $func->transfer(emailkhonghople, $linkEdit .= "&id=" . $id, false);
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

/* Delete newsletter */
function deleteMan()
{
  global $func, $table, $type, $linkMan;
  $id = !empty($_GET['id']) ? (int)$_GET['id'] : 0;
  if ($id) {
    $func->delete_data([
      'id' => (int)$_GET['id'],
      'table' => $table,
      'type' => $type,
      'redirect' => $linkMan
    ]);
  } elseif (!empty($_GET['listid'])) {
    $func->deleteMultiple_data([
      'listid' => $_GET['listid'],
      'table' => $table,
      'type' => $type,
      'redirect' => $linkMan
    ]);
  } else {
    $func->transfer(khongnhanduocdulieu, $linkMan, false);
  }
}
