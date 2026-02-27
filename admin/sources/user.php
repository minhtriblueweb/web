<?php
if (!defined('SOURCES')) die("Error");
$adminId = Session::get('adminlogin')['id'] ?? null;
$adminUsername = Session::get('adminlogin')['name'] ?? '';
switch ($act) {

  case "login":
    if (!empty($is_logged_in)) $func->transfer(trangkhongtontai, "index.php", false);
    else $template = "user/login";
    break;

  case "logout":
    logout();
    break;

  case 'info_admin':
    infoAdmin();
    $template = "user/man_admin/info";
    break;

  default:
    $func->transfer(trangkhongtontai, "index.php", false);
    break;
}

/* Info admin */
function infoAdmin()
{
  global $d, $func, $flash, $item, $config, $loginAdmin, $adminUsername;

  /* Check change password */
  if (!empty($_GET['changepass']) && ($_GET['changepass'] == 1)) {
    $changepass = true;
  } else {
    $changepass = false;
  }

  if (!empty($_POST)) {
    /* Post dữ liệu */
    $message = '';
    $response = array();
    $id = (!empty($_POST['id'])) ? htmlspecialchars($_POST['id']) : 0;
    $data = (!empty($_POST['data'])) ? $_POST['data'] : null;
    if ($data) {
      foreach ($data as $column => $value) {
        $data[$column] = htmlspecialchars($func->sanitize($value));
      }
    }

    if (!empty($changepass)) {
      $old_pass = (!empty($_POST['old-password'])) ? $_POST['old-password'] : '';
      $new_pass = (!empty($_POST['new-password'])) ? $_POST['new-password'] : '';
      $renew_pass = (!empty($_POST['renew-password'])) ? $_POST['renew-password'] : '';

      /* Valid data */
      if (empty($old_pass)) {
        $response['messages'][] = 'Mật khẩu cũ không được trống';
      }

      if (!empty($old_pass)) {
        $row = $d->rawQueryOne("select id, password from tbl_user where username = ? limit 0,1", array($adminUsername));

        if (empty($row['id']) || (!empty($row['id']) && ($row['password'] != password_verify($old_pass, $row['password'])))) {
          $response['messages'][] = matkhaucukhongchinhxac;
        }
      }

      if (empty($new_pass)) {
        $response['messages'][] = matkhaumoikhongduoctrong;
      }

      if (!empty($new_pass) && in_array($new_pass, array('123', '123qwe', '123456', 'zxcvbnm'))) {
        $response['messages'][] = matkhaubandatquadongian;
      }

      if (!empty($new_pass) && empty($renew_pass)) {
        $response['messages'][] = xacnhanmatkhaumoikhongduoctrong;
      }

      if (!empty($new_pass) && !empty($renew_pass) && !$func->isMatch($new_pass, $renew_pass)) {
        $response['messages'][] = matkhaumoikhongtrungkhop;
      }

      if (!empty($response)) {
        $response['status'] = 'danger';
        $message = base64_encode(json_encode($response));
        $flash->set('message', $message);
        $func->redirect("index.php?com=user&act=info_admin&changepass=1");
      }

      /* Change to new password */
      $data['password'] = password_hash($new_pass, PASSWORD_DEFAULT);
      $flagchangepass = true;
    } else {
      $birthday = $data['birthday'];
      $data['birthday'] = strtotime(str_replace("/", "-", $data['birthday']));

      /* Valid data */
      if (empty($data['username'])) {
        $response['messages'][] = taikhoankhongduoctrong;
      }

      if (!empty($data['username']) && !$func->isAlphaNum($data['username'])) {
        $response['messages'][] = taikhoanchiduocnhapchuthuongvaso;
      }

      if (!empty($data['username'])) {
        if ($func->checkAccount($data['username'], 'username', 'user', $id)) {
          $response['messages'][] = taikhoandatontai;
        }
      }

      if (empty($data['fullname'])) {
        $response['messages'][] = hotenkhongthetrong;
      }

      if (empty($data['email'])) {
        $response['messages'][] = emailkhongduoctrong;
      }

      if (!empty($data['email']) && !$func->isEmail($data['email'])) {
        $response['messages'][] = emailkhonghople;
      }

      if (!empty($data['email'])) {
        if ($func->checkAccount($data['email'], 'email', 'user', $id)) {
          $response['messages'][] = emaildatontai;
        }
      }

      if (!empty($data['phone']) && !$func->isPhone($data['phone'])) {
        $response['messages'][] = sodienthoaikhonghople;
      }

      if (empty($data['gender'])) {
        $response['messages'][] = chuachongioitinh;
      }

      if (empty($birthday)) {
        $response['messages'][] = ngaysinhkhongduoctrong;
      }

      if (!empty($birthday) && !$func->isDate($birthday)) {
        $response['messages'][] = ngaysinhkhonghople;
      }

      if (empty($data['address'])) {
        $response['messages'][] = diachikhongduoctrong;
      }

      if (!empty($response)) {
        /* Flash data */
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
        $func->redirect("index.php?com=user&act=info_admin");
      }
    }

    /* Save data */
    $d->where('username', $adminUsername);
    if ($d->update('tbl_user', $data)) {
      if (isset($flagchangepass) && $flagchangepass == true) {
        // $func->transfer(capnhatdulieuthanhcong, "index.php");
        $func->transfer("Cập nhật mật khẩu thành công. Vui lòng đăng nhập lại.", "index.php?com=user&act=logout", true);
      }
      // $func->transfer(capnhatdulieuthanhcong, "index.php?com=user&act=info_admin");
      $func->Notify(capnhatdulieuthanhcong, "index.php?com=user&act=info_admin", 'success');
    } else {
      $func->transfer(capnhatdulieubiloi, "index.php?com=user&act=info_admin");
    }
  }
  $item = $d->rawQueryOne("select * from tbl_user where username = ? limit 0,1", array($adminUsername));
}
// function infoAdmin()
// {
//   global $d, $func, $adminId, $linkSave, $result;
//   $result = $d->rawQueryOne("SELECT * FROM `tbl_user` WHERE id = ? LIMIT 0,1", [$adminId]) ?? [];
//   $changepass = (!empty($_GET['changepass']) && $_GET['changepass'] == 1);
//   $response['messages'] = [];

//   if (!$adminId) {
//     $func->Notify(banchuacotaikhoan, $linkSave, 'error');
//     exit;
//   }

//   // Xử lý đổi mật khẩu
//   if ($changepass) {
//     $linkSave .= "&changepass=1";
//     $old_pass   = $_POST['old-password'] ?? '';
//     $new_pass   = $_POST['new-password'] ?? '';
//     $renew_pass = $_POST['renew-password'] ?? '';
//     if (empty($old_pass)) $response['messages'][] = matkhaukhongduoctrong;
//     if (empty($new_pass)) {
//       $response['messages'][] = matkhaumoikhongduoctrong;
//     } else {
//       $weak_passwords = ['123', '123456', '12345678', '123qwe', '111111', 'password', 'abc123'];
//       if (strlen($new_pass) < 6 || in_array(strtolower($new_pass), $weak_passwords)) {
//         $response['messages'][] = matkhaubandatquadongian;
//       }
//       if ($new_pass !== $renew_pass) {
//         $response['messages'][] = matkhaumoikhongtrungkhop;
//       }
//     }
//     if (!empty($response['messages'])) {
//       $func->Notify($response['messages'], $linkSave, 'error');
//       exit;
//     }
//     $user = $d->rawQueryOne("SELECT id, password FROM tbl_user WHERE id = ? LIMIT 1", [$adminId]);
//     if (!$user) {
//       $func->Notify(taikhoandatontai, $linkSave, 'error');
//       exit;
//     }
//     if (!password_verify($old_pass, $user['password'])) {
//       $func->Notify(matkhaucukhongchinhxac, $linkSave, 'error');
//       exit;
//     }
//     $hashedPassword = password_hash($new_pass, PASSWORD_DEFAULT);
//     $success = $d->execute("UPDATE tbl_user SET password = ? WHERE id = ?", [$hashedPassword, $adminId]);
//     if ($success) {
//       $func->transfer("Cập nhật mật khẩu thành công. Vui lòng đăng nhập lại.", "index.php?com=user&act=logout", true);
//       exit;
//     } else {
//       $func->Notify(capnhatdulieubiloi, $linkSave, 'error');
//       exit;
//     }
//   }

//   // Xử lý cập nhật thông tin cá nhân
//   if (!empty($_POST['data'])) {
//     $data = $_POST['data'] ?? [];
//     $fieldTypes = [
//       'username' => 'string',
//       'fullname' => 'string',
//       'email'    => 'string',
//       'phone'    => 'string',
//       'address'  => 'string',
//       'gender'   => 'int',
//       'birthday' => 'date'
//     ];

//     $data_sql = [];
//     $raw_birthday = trim($data['birthday'] ?? '');
//     if (empty($raw_birthday)) {
//       $response['messages'][] = ngaysinhkhongduoctrong;
//     } elseif (!$func->isDate($raw_birthday)) {
//       $response['messages'][] = ngaysinhkhonghople;
//     }

//     foreach ($fieldTypes as $key => $type) {
//       $value = $data[$key] ?? '';
//       switch ($type) {
//         case 'string':
//           $value = trim($value);
//           break;
//         case 'int':
//           $value = (int)$value;
//           break;
//         case 'date':
//           $value = strtotime(str_replace('/', '-', $value)) ?: 0;
//           break;
//       }
//       $data_sql[$key] = $value;
//     }

//     if (!$data_sql['username']) {
//       $response['messages'][] = taikhoankhongduoctrong;
//     } elseif (!$func->isAlphaNum($data_sql['username'])) {
//       $response['messages'][] = 'Tài khoản chỉ được nhập chữ thường và số (không dấu, không khoảng trắng)';
//     }

//     if (!$data_sql['fullname']) $response['messages'][] = vuilongnhaphoten;
//     if (!$data_sql['address'])  $response['messages'][] = vuilongnhapdiachi;

//     if (!$data_sql['email']) {
//       $response['messages'][] = emailkhongduoctrong;
//     } elseif (!$func->isEmail($data_sql['email'])) {
//       $response['messages'][] = emailkhonghople;
//     }

//     if (!$data_sql['phone']) {
//       $response['messages'][] = sodienthoaikhongduoctrong;
//     } elseif (!$func->isPhone($data_sql['phone'])) {
//       $response['messages'][] = sodienthoaikhonghople;
//     }

//     if (!empty($response['messages'])) {
//       $func->Notify($response['messages'], $linkSave, 'error');
//       exit;
//     }

//     $fields = $params = [];
//     foreach ($data_sql as $key => $val) {
//       $fields[] = "`$key` = ?";
//       $params[] = $val;
//     }
//     $params[] = $adminId;
//     $success = $d->execute("UPDATE `tbl_user` SET " . implode(', ', $fields) . " WHERE id = ?", $params);
//     if ($success) {
//       $func->Notify(capnhatdulieuthanhcong, $linkSave, 'success');
//     } else {
//       $func->Notify(capnhatdulieubiloi, $linkSave, 'error');
//     }
//     return $response['messages'];
//   }
// }
function logout()
{
  session_start();
  session_unset();
  session_destroy();
  header("Location: index.php?com=user&act=login");
  exit();
}
