<?php
$linkMan = "index.php?page=setting&act=update";

if (!($row = $db->rawQueryOne("SELECT * FROM tbl_setting LIMIT 1"))) {
  $fn->transfer(dulieukhongcothuc, $linkMan, false);
}
$options = json_decode($row['options'] ?? '{}', true);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $data = $_POST['data'] ?? [];
  $options = $data['options'] ?? [];
  $response = ['messages' => []];

  /* ========= VALIDATE ========= */
  if (empty($options['email'])) {
    $response['messages'][] = emailkhongduoctrong;
  } elseif (!$fn->isEmail($options['email'])) {
    $response['messages'][] = emailkhonghople;
  }

  if (empty($options['hotline'])) {
    $response['messages'][] = sodienthoaikhongduoctrong;
  } elseif (!$fn->isPhone($options['hotline'])) {
    $response['messages'][] = sodienthoaikhonghople;
  }

  if (!empty($response['messages'])) {
    $response['status'] = 'danger';
    foreach ($options as $k => $v) {
      $flash->set($k, $v);
    }
    $flash->set('message', base64_encode(json_encode($response, JSON_UNESCAPED_UNICODE)));
    $fn->redirect($linkMan);
  }

  /* ========= SANITIZE ========= */
  foreach ($data as $column => $value) {
    if (is_array($value)) {
      $clean = [];
      foreach ($value as $k => $v) {
        if ($k === 'coords_iframe') {
          $clean[$k] = $fn->sanitize($v, 'iframe');
        } else {
          $clean[$k] = $fn->sanitize($v);
        }
      }
      $data[$column] = json_encode($clean, JSON_UNESCAPED_UNICODE);
    } else {
      if ($column === 'mastertool') {
        $data[$column] = $fn->sanitize($value, 'meta');
      } elseif (in_array($column, ['headjs', 'bodyjs', 'analytics'])) {
        $data[$column] = $fn->sanitize($value, 'script');
      } else {
        $data[$column] = $fn->sanitize($value);
      }
    }
  }

  /* ========= UPDATE ========= */
  if ($data) {
    $db->where('id', 1);
    $success = $db->update('tbl_setting', $data);
  }
  $fn->transfer( $success ? capnhatdulieuthanhcong : capnhatdulieubiloi, $linkMan,$success);
}
$template = "setting/setting";
