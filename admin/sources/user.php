<?php
if (!defined('SOURCES')) die("Error");

$table = 'tbl_user';
switch ($act) {
  case 'info_admin':
    $adminId = Session::get('adminId');
    $result = [];
    $result = $db->rawQueryOne("SELECT * FROM tbl_user WHERE id = ? LIMIT 1", [$adminId]);
    if (!empty($_POST)) {
      $messages = infoAdmin();

      if (!empty($messages)) {
        $message = implode('<br>', $messages);
      }
    }

    $template = "user/man_admin/info";
    break;

  default:
    $fn->transfer(trangkhongtontai, "index.php", false);
    break;
}
function infoAdmin()
{
  global $db, $fn;
  $linkSave = "index.php?page=user&act=info_admin";
  $changepass = (!empty($_GET['changepass']) && $_GET['changepass'] == 1);
  $messages = [];

  $adminId = Session::get('adminId');
  if (!$adminId) {
    $messages[] = "Bạn chưa đăng nhập";
    return $messages;
  }

  if ($changepass) {
    $linkSave .= "&changepass=1";

    $old_pass   = $_POST['old-password'] ?? '';
    $new_pass   = $_POST['new-password'] ?? '';
    $renew_pass = $_POST['renew-password'] ?? '';
    if (empty($old_pass)) {
      $messages[] = "Mật khẩu cũ không được để trống";
    }
    if (empty($new_pass)) {
      $messages[] = "Mật khẩu mới không được để trống";
    } else {
      $weak_passwords = ['123', '123456', '12345678', '123qwe', '111111', 'password', 'abc123'];
      if (strlen($new_pass) < 6 || in_array(strtolower($new_pass), $weak_passwords)) {
        $messages[] = "Mật khẩu mới quá đơn giản. Vui lòng đặt mật khẩu phức tạp hơn.";
      }
      if ($new_pass !== $renew_pass) {
        $messages[] = "Nhập lại mật khẩu không trùng khớp";
      }
    }
    if (!empty($messages)) return $messages;
    $user = $db->rawQueryOne("SELECT id, password FROM tbl_user WHERE id = ? LIMIT 1", [$adminId]);
    if (!$user) {
      $messages[] = "Tài khoản không tồn tại";
      return $messages;
    }
    if (!password_verify($old_pass, $user['password'])) {
      $messages[] = "Mật khẩu cũ không đúng";
      return $messages;
    }
    $hashedPassword = password_hash($new_pass, PASSWORD_DEFAULT);
    $success = $db->execute("UPDATE tbl_user SET password = ? WHERE id = ?", [$hashedPassword, $adminId]);
    if ($success) {
      $fn->transfer("Cập nhật mật khẩu thành công. Vui lòng đăng nhập lại.", "logout.php?change_success=1", true);
      exit;
    } else {
      $messages[] = "Có lỗi xảy ra khi cập nhật mật khẩu";
    }
  } else {
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
    $data_sql = $messages = [];
    $raw_birthday = trim($data['birthday'] ?? '');
    if (empty($raw_birthday)) {
      $messages[] = 'Ngày sinh không được trống';
    }
    if (!empty($raw_birthday) && !$fn->isDate($raw_birthday)) {
      $messages[] = 'Ngày sinh không hợp lệ';
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
    if (!$data_sql['username']) $messages[] = 'Tài khoản không được để trống';
    if (!$data_sql['fullname']) $messages[] = 'Họ tên không được để trống';
    if (!$data_sql['address'])  $messages[] = 'Địa chỉ không được để trống';
    if (!$data_sql['gender'])   $messages[] = 'Vui lòng chọn giới tính';
    if (empty($data_sql['phone'])) {
      $messages[] = 'Số điện thoại không được để trống';
    }
    if (!empty($data_sql['phone']) && !$fn->isPhone($data_sql['phone'])) {
      $messages[] = 'Số điện thoại không hợp lệ';
    }
    if (!empty($messages)) return $messages;
    $fields = $params = [];
    foreach ($data_sql as $key => $val) {
      $fields[] = "`$key` = ?";
      $params[] = $val;
    }
    $params[] = $adminId;
    $sql = "UPDATE `tbl_user` SET " . implode(', ', $fields) . " WHERE id = ?";
    $success = $db->execute($sql, $params);
    if ($success) {
      // $fn->transfer(capnhatdulieuthanhcong, $linkSave, true);
      // exit;
      $_SESSION['toast'] = [
        'title' => 'Thành công',
        'message' => 'Cập nhật dữ liệu thành công!',
        'type' => 'success' // Có thể là: success | error | warning | info
      ];
      header("Location: " . $_SERVER['REQUEST_URI']);
      exit;
    } else {
      $messages[] = capnhatdulieubiloi;
      exit;
    }
  }

  return $messages;
}
