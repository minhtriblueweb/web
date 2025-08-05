<?php
if (!defined('SOURCES')) die("Error");

$table = 'tbl_user';
$linkSave = "index.php?page=user&act=info_admin&changepass=1";

switch ($act) {
  case 'info_admin':
    $message = '';
    $messageType = ''; // 'success' hoặc 'danger'

    if (!empty($_POST)) {
      $oldPassword = $_POST['old-password'] ?? '';
      $newPassword = $_POST['new-password'] ?? '';
      $renewPassword = $_POST['renew-password'] ?? '';

      $result = changePassword($oldPassword, $newPassword, $renewPassword);

      if ($result == 'success') {
        $fn->transfer(capnhatdulieuthanhcong, $linkSave, true);
      } else {
        $message = $result;
      }
    }

    $template = "user/man_admin/info";
    break;

  default:
    $fn->transfer(trangkhongtontai, "index.php", false);
    break;
}

function changePassword($oldPassword, $newPassword, $renewPassword)
{
  global $db;
  $adminId = Session::get('adminId');

  if (!$adminId) return banchuacotaikhoan;

  if (empty($oldPassword) || empty($newPassword) || empty($renewPassword)) {
    return "Vui lòng điền đầy đủ thông tin!";
  }

  if ($newPassword !== $renewPassword) {
    return "Mật khẩu mới không khớp!";
  }

  $user = $db->rawQueryOne("SELECT * FROM `tbl_user` WHERE id = ? LIMIT 1", [$adminId]);

  if (!$user) return "Tài khoản không tồn tại!";
  if (!password_verify($oldPassword, $user['password'])) return "Mật khẩu cũ không đúng!";

  $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);
  $db->rawQuery("UPDATE `tbl_user` SET password = ? WHERE id = ?", [$hashedNewPassword, $adminId]);

  return "success";
}
