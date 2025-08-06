<?php
if (!defined('SOURCES')) die("Error");

$table = 'tbl_user';
$linkSave = "index.php?page=user&act=info_admin&changepass=1";

switch ($act) {
  case 'info_admin':
    if (!empty($_POST)) {
      $result = infoAdmin();

      if (!empty($result['messages'])) {
        $message = implode('<br>', $result['messages']);
        $messageType = $result['status'] ?? 'danger';
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
  global $db, $fn, $linkSave;

  $changepass = (!empty($_GET['changepass']) && $_GET['changepass'] == 1);
  $response = ['status' => 'danger', 'messages' => []];

  if ($changepass) {
    $adminId = Session::get('adminId');

    if (!$adminId) {
      $response['messages'][] = "Bạn chưa đăng nhập";
      return $response;
    }

    // Lấy dữ liệu từ form
    $old_pass   = $_POST['old-password'] ?? '';
    $new_pass   = $_POST['new-password'] ?? '';
    $renew_pass = $_POST['renew-password'] ?? '';

    // Kiểm tra dữ liệu
    if (empty($old_pass)) {
      $response['messages'][] = "Mật khẩu cũ không được để trống";
    }

    if (empty($new_pass)) {
      $response['messages'][] = "Mật khẩu mới không được để trống";
    } elseif (in_array($new_pass, ['123', '123qwe', '123456', 'ninaco'])) {
      $response['messages'][] = "Mật khẩu mới quá đơn giản";
    }

    if (empty($renew_pass)) {
      $response['messages'][] = "Xác nhận mật khẩu mới không được để trống";
    } elseif ($new_pass !== $renew_pass) {
      $response['messages'][] = "Mật khẩu mới không trùng khớp";
    }

    // Trả lỗi nếu có
    if (!empty($response['messages'])) return $response;

    // Kiểm tra tài khoản có tồn tại không
    $user = $db->rawQueryOne("SELECT id, password FROM tbl_user WHERE id = ? LIMIT 1", [$adminId]);
    if (!$user) {
      $response['messages'][] = "Tài khoản không tồn tại";
      return $response;
    }

    // Kiểm tra mật khẩu cũ
    if (!password_verify($old_pass, $user['password'])) {
      $response['messages'][] = "Mật khẩu cũ không đúng";
      return $response;
    }

    // Cập nhật mật khẩu mới
    $hashedPassword = password_hash($new_pass, PASSWORD_DEFAULT);
    $success = $db->execute("UPDATE tbl_user SET password = ? WHERE id = ?", [$hashedPassword, $adminId]);

    if ($success) {
      $fn->transfer("Cập nhật mật khẩu thành công", $linkSave, true);
      exit;
    } else {
      $response['messages'][] = "Có lỗi xảy ra khi cập nhật mật khẩu";
      return $response;
    }
  }

  return []; // Không xử lý đổi mật khẩu
}
