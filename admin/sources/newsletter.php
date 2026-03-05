<?php
if (!defined('SOURCES')) die("Error");
$arrCheck = array();
foreach ($config['newsletter'] as $k => $v) $arrCheck[] = $k;
if (!count($arrCheck) || !in_array($type, $arrCheck)) $func->transfer(trangkhongtontai, "index.php", false);

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
  global $func, $d, $type, $table, $keyword, $curPage, $perPage, $paging, $items;

  $keyword = trim($_GET['keyword'] ?? '');
  $curPage = max(1, (int)($_GET['p'] ?? 1));
  $perPage = 10;

  $where  = [];
  $params = [];

  $where[]  = "type = ?";
  $params[] = $type;

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
  $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
  $item = [];
  if ($id) {
    $item = $d->rawQueryOne("SELECT * FROM `$table` WHERE id = ? LIMIT 0,1", [$id]);
    if (!$item) {
      $func->transfer(dulieukhongcothuc, $linkMan, false);
    }
  }
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = $_POST['data'] ?? [];
    $data['email'] = trim($data['email'] ?? '');
    $data['phone'] = trim($data['phone'] ?? '');
    if (!$func->isEmail($data['email'])) {
      $func->Notify(emailkhonghople, $linkEdit .= "&id=" . $id, 'error');
    }
    if (!$func->isPhone($data['phone'])) {
      $func->Notify(sodienthoaikhonghople, $linkEdit .= "&id=" . $id, 'error');
    }
    $data = (!empty($_POST['data'])) ? $_POST['data'] : null;
    if ($data) {
      foreach ($data as $column => $value) {
        $data[$column] = htmlspecialchars($func->sanitize($value));
      }
    }
    if ($id) {
      $d->where('id', $id);
      if ($d->update($table, $data)) {
        if (isset($_POST['save-here'])) {
          $func->Notify(capnhatdulieuthanhcong, $linkEdit .= "&id=" . $id, 'success');
        } else {
          $func->transfer(capnhatdulieuthanhcong, $linkMan);
        }
      } else {
        $func->transfer(capnhatdulieubiloi, $linkEdit .= "&id=" . $id, false);
      }
    } else {
      $func->transfer(dulieurong, $linkEdit .= "&id=" . $id, false);
    }
  }
}

/* Delete newsletter */
function deleteMan()
{
  global $func, $table, $type, $linkMan;
  $options = [
    'table' => $table,
    'type' => $type,
    'redirect' => $linkMan
  ];
  if ($id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT)) {
    $func->delete_data($options + ['id' => $id]);
    return;
  }
  if ($listid = ($_GET['listid'] ?? '')) {
    $func->deleteMultiple_data($options + ['listid' => $listid]);
    return;
  }
}
