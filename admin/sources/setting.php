<?php
$linkMan = "index.php?com=setting&act=update";

if (!($row = $d->rawQueryOne("SELECT * FROM `tbl_setting` LIMIT 0,1"))) {
  $func->transfer(dulieukhongcothuc, $linkMan, false);
}
$options = json_decode($row['options'] ?? '{}', true);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $data = $_POST['data'] ?? [];
  $options = $data['options'] ?? [];
  $response = ['messages' => []];

  /* ========= VALIDATE ========= */
  if (empty($options['email'])) {
    $response['messages'][] = emailkhongduoctrong;
  } elseif (!$func->isEmail($options['email'])) {
    $response['messages'][] = emailkhonghople;
  }

  if (empty($options['hotline'])) {
    $response['messages'][] = sodienthoaikhongduoctrong;
  } elseif (!$func->isPhone($options['hotline'])) {
    $response['messages'][] = sodienthoaikhonghople;
  }

  if (!empty($response['messages'])) {
    $response['status'] = 'danger';
    foreach ($options as $k => $v) {
      $flash->set($k, $v);
    }
    $flash->set('message', base64_encode(json_encode($response, JSON_UNESCAPED_UNICODE)));
    $func->redirect($linkMan);
  }

  /* ========= SANITIZE ========= */
  foreach ($data as $column => $value) {
    if (is_array($value)) {
      $clean = [];
      foreach ($value as $k => $v) {
        if ($k === 'coords_iframe') {
          $clean[$k] = $func->sanitize($v, 'iframe');
        } else {
          $clean[$k] = $func->sanitize($v);
        }
      }
      $data[$column] = json_encode($clean, JSON_UNESCAPED_UNICODE);
    } else {
      if ($column === 'mastertool') {
        $data[$column] = $func->sanitize($value, 'meta');
      } elseif (in_array($column, ['headjs', 'bodyjs', 'analytics'])) {
        $data[$column] = $func->sanitize($value, 'script');
      } else {
        $data[$column] = $func->sanitize($value);
      }
    }
  }

  /* ========= UPDATE ========= */
  if ($data) {
    $d->where('id', 1);
    $success = $d->update('tbl_setting', $data);
  }
  $func->transfer( $success ? capnhatdulieuthanhcong : capnhatdulieubiloi, $linkMan,$success);
}
$template = "setting/setting";
