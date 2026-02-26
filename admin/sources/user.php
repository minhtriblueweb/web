<?php
if (!defined('SOURCES')) die("Error");
$table = 'tbl_user';
$linkSave = "index.php?com=user&act=info_admin";
$adminId = Session::get('adminId');
switch ($act) {
  case "login":
    if (!empty($is_logged_in)) $func->transfer(trangkhongtontai, "index.php", false);
    else $template = "user/login";
    break;

  case "logout":
    logout();
    break;

  case 'info_admin':
    $result = $d->rawQueryOne("SELECT * FROM `$table` WHERE id = ? LIMIT 1", [$adminId]) ?? [];
    if (!empty($_POST)) infoAdmin();
    $template = "user/man_admin/info";
    break;

  default:
    $func->transfer(trangkhongtontai, "index.php", false);
    break;
}
function infoAdmin()
{
  global $d, $func, $adminId, $linkSave;
  $changepass = (!empty($_GET['changepass']) && $_GET['changepass'] == 1);
  $response['messages'] = [];

  if (!$adminId) {
    $func->Notify(banchuacotaikhoan, $linkSave, 'error');
    exit;
  }

  // Xử lý đổi mật khẩu
  if ($changepass) {
    $linkSave .= "&changepass=1";
    $old_pass   = $_POST['old-password'] ?? '';
    $new_pass   = $_POST['new-password'] ?? '';
    $renew_pass = $_POST['renew-password'] ?? '';

    if (empty($old_pass)) $response['messages'][] = matkhaukhongduoctrong;
    if (empty($new_pass)) {
      $response['messages'][] = matkhaumoikhongduoctrong;
    } else {
      $weak_passwords = ['123', '123456', '12345678', '123qwe', '111111', 'password', 'abc123'];
      if (strlen($new_pass) < 6 || in_array(strtolower($new_pass), $weak_passwords)) {
        $response['messages'][] = matkhaubandatquadongian;
      }
      if ($new_pass !== $renew_pass) {
        $response['messages'][] = matkhaumoikhongtrungkhop;
      }
    }

    if (!empty($response['messages'])) {
      $func->Notify($response['messages'], $linkSave, 'error');
      exit;
    }

    $user = $d->rawQueryOne("SELECT id, password FROM tbl_user WHERE id = ? LIMIT 1", [$adminId]);
    if (!$user) {
      $func->Notify(taikhoandatontai, $linkSave, 'error');
      exit;
    }

    if (!password_verify($old_pass, $user['password'])) {
      $func->Notify(matkhaucukhongchinhxac, $linkSave, 'error');
      exit;
    }

    $hashedPassword = password_hash($new_pass, PASSWORD_DEFAULT);
    $success = $d->execute("UPDATE tbl_user SET password = ? WHERE id = ?", [$hashedPassword, $adminId]);

    if ($success) {
      $func->transfer("Cập nhật mật khẩu thành công. Vui lòng đăng nhập lại.", "index.php?com=user&act=logout", true);
      exit;
    } else {
      $func->Notify(capnhatdulieubiloi, $linkSave, 'error');
      exit;
    }
  }

  // Xử lý cập nhật thông tin cá nhân
  $data = $_POST['data'] ?? [];
  $fieldTypes = [
    'username' => 'string',
    'fullname' => 'string',
    'email'    => 'string',
    'phone'    => 'string',
    'address'  => 'string',
    'gender'   => 'int',
    'birthday' => 'date'
  ];

  $data_sql = [];
  $raw_birthday = trim($data['birthday'] ?? '');
  if (empty($raw_birthday)) {
    $response['messages'][] = ngaysinhkhongduoctrong;
  } elseif (!$func->isDate($raw_birthday)) {
    $response['messages'][] = ngaysinhkhonghople;
  }

  foreach ($fieldTypes as $key => $type) {
    $value = $data[$key] ?? '';
    switch ($type) {
      case 'string':
        $value = trim($value);
        break;
      case 'int':
        $value = (int)$value;
        break;
      case 'date':
        $value = strtotime(str_replace('/', '-', $value)) ?: 0;
        break;
    }
    $data_sql[$key] = $value;
  }

  if (!$data_sql['username']) {
    $response['messages'][] = taikhoankhongduoctrong;
  } elseif (!$func->isAlphaNum($data_sql['username'])) {
    $response['messages'][] = 'Tài khoản chỉ được nhập chữ thường và số (không dấu, không khoảng trắng)';
  }

  if (!$data_sql['fullname']) $response['messages'][] = vuilongnhaphoten;
  if (!$data_sql['address'])  $response['messages'][] = vuilongnhapdiachi;

  if (!$data_sql['email']) {
    $response['messages'][] = emailkhongduoctrong;
  } elseif (!$func->isEmail($data_sql['email'])) {
    $response['messages'][] = emailkhonghople;
  }

  if (!$data_sql['phone']) {
    $response['messages'][] = sodienthoaikhongduoctrong;
  } elseif (!$func->isPhone($data_sql['phone'])) {
    $response['messages'][] = sodienthoaikhonghople;
  }

  if (!empty($response['messages'])) {
    $func->Notify($response['messages'], $linkSave, 'error');
    exit;
  }

  $fields = $params = [];
  foreach ($data_sql as $key => $val) {
    $fields[] = "`$key` = ?";
    $params[] = $val;
  }
  $params[] = $adminId;

  $success = $d->execute("UPDATE `tbl_user` SET " . implode(', ', $fields) . " WHERE id = ?", $params);
  if ($success) {
    $func->Notify(capnhatdulieuthanhcong, $linkSave, 'success');
  } else {
    $func->Notify(capnhatdulieubiloi, $linkSave, 'error');
  }

  return $response['messages'];
}
function logout()
{
  session_start();
  session_unset();
  session_destroy();
  header("Location: index.php?com=user&act=login");
  exit();
}
