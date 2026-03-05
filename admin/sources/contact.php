<?php
if (!defined('SOURCES')) die("Error");
$table = 'tbl_newsletter';
$type = 'lien-he';
$linkMan = "index.php?com=contact&act=man&type=" . $type;
$linkEdit = "index.php?com=contact&act=edit&type=" . $type;
switch ($act) {
  case "man":
    viewMans();
    $template = "contact/man/mans";
    break;

  case "edit":
    editMan();
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
  global $func, $d, $table, $type, $keyword, $curcom, $percom, $paging, $show_data;

  $keyword = trim($_GET['keyword'] ?? '');
  $curcom = max(1, (int)($_GET['p'] ?? 1));
  $percom = 10;

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
  $offset = ($curcom - 1) * $percom;

  // Lấy dữ liệu
  $show_data = $d->rawQueryArray("SELECT * FROM `$table` $whereSql ORDER BY numb ASC, id DESC LIMIT $percom OFFSET $offset", $params);
  $paging = $func->pagination($total, $percom, $curcom);
}

function deleteMan()
{
  global $func, $table, $type, $linkMan;
  $options = [
    'table'     => $table,
    'type'      => $type,
    'redirect'  => $linkMan
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
/* Edit contact */
function editMan()
{
  global $d, $func, $table, $linkMan, $id, $item;
  $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
  if (empty($id)) {
    $func->transfer(khongnhanduocdulieu, $linkMan, false);
  } else {
    $item = $d->rawQueryOne("select * from `$table` where id = ? limit 0,1", array($id));
    if (empty($item)) {
      $func->transfer(dulieukhongcothuc, $linkMan, false);
    }
  }
}

/* Save contact */
function saveMan()
{
  global $d, $func, $table, $linkMan, $id, $flash, $linkEdit;
  if (empty($_POST)) {
    $func->transfer(khongnhanduocdulieu, $linkMan, false);
  }

  $message = '';
  $response = array();
  $id = (!empty($_POST['id'])) ? htmlspecialchars($_POST['id']) : 0;
  $data = (!empty($_POST['data'])) ? $_POST['data'] : null;
  if ($data) {
    foreach ($data as $column => $value) {
      $data[$column] = htmlspecialchars($func->sanitize($value));
    }
    if (isset($_POST['status'])) {
      $status = '';
      foreach ($_POST['status'] as $attr_column => $attr_value) if ($attr_value != "") $status .= $attr_value . ',';
      $data['status'] = (!empty($status)) ? rtrim($status, ",") : "";
    } else {
      $data['status'] = "";
    }
  }
  /* Valid data */
  if (empty($data['fullname'])) {
    $response['messages'][] = hotenkhongthetrong;
  }

  if (empty($data['phone'])) {
    $response['messages'][] = sodienthoaikhongduoctrong;
  }

  if (!empty($data['phone']) && !$func->isPhone($data['phone'])) {
    $response['messages'][] = sodienthoaikhonghople;
  }

  if (empty($data['address'])) {
    $response['messages'][] = diachikhongduoctrong;
  }

  if (empty($data['email'])) {
    $response['messages'][] = emailkhongduoctrong;
  }

  if (!empty($data['email']) && !$func->isEmail($data['email'])) {
    $response['messages'][] = emailkhonghople;
  }

  if (empty($data['subject'])) {
    $response['messages'][] = chudekhongduoctrong;
  }

  if (empty($data['content'])) {
    $response['messages'][] = noidungkhongduoctrong;
  }

  if (!empty($response)) {
    if (!empty($data)) {
      foreach ($data as $k => $v) {
        if (!empty($v)) {
          $flash->set($k, $v);
        }
      }
    }
    /* Errors */
    $response['status'] = 'danger';
    $message = base64_encode(json_encode($response));
    $flash->set('message', $message);
    $func->redirect($linkEdit .= "&id=" . $id);
  }

  /* Save data */
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
