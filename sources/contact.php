<?php
if (!defined('SOURCES')) die("Error");

/* Lấy bài viết tĩnh */
$static = $d->rawQueryOne("SELECT id, type, name$lang as name, content$lang as content, file FROM `tbl_static` WHERE type = ? LIMIT 0,1", [$type]);

//SEO
$seo_data = $d->rawQueryOne("SELECT * FROM `tbl_seopage` WHERE type = ?", array($type));
$seo->set('h1', $titleMain);
$seo->set('title', !empty($seo_data["title$lang"]) ? $seo_data["title$lang"] : '');
$seo->set('keywords', !empty($seo_data["keywords$lang"]) ? $seo_data["keywords$lang"] : '');
$seo->set('description', !empty($seo_data["description$lang"]) ? $seo_data["description$lang"] : '');

$imgJson = (!empty($seo_data['options'])) ? json_decode($seo_data['options'], true) : null;
if (!empty($imgJson)) {
  $seo->set('photo:width', $imgJson['width']);
  $seo->set('photo:height', $imgJson['height']);
}
if (!empty($seo_data['file'])) $seo->set('photo',  $func->getImageCustom(['file' => $seo_data['file'], 'width' => 600, 'height' => 315, 'zc' => 2, 'src_only' => true]));

/* breadCrumbs */
if (!empty($titleMain)) $breadcr->set($slug, $titleMain);
$breadcrumbs = $breadcr->get();

/* Newsletter */
if (isset($_POST['submit-contact'])) {
  $dataContact = $_POST['dataContact'] ?? [];
  $response = ['messages' => []];
  foreach ($dataContact as $k => $v) {
    $dataContact[$k] = trim($v);
  }
  $rules = [
    'fullname' => hotenkhongduoctrong,
    'address'  => diachikhongduoctrong,
    'subject'  => chudekhongduoctrong,
    'content'  => noidungkhongduoctrong,
  ];

  foreach ($rules as $field => $message) {
    if (empty($dataContact[$field])) {
      $response['messages'][] = $message;
    }
  }

  /* Phone */
  if (empty($dataContact['phone'])) {
    $response['messages'][] = sodienthoaikhongduoctrong;
  } elseif (!$func->isPhone($dataContact['phone'])) {
    $response['messages'][] = sodienthoaikhonghople;
  }

  /* Email */
  if (empty($dataContact['email'])) {
    $response['messages'][] = emailkhongduoctrong;
  } elseif (!$func->isEmail($dataContact['email'])) {
    $response['messages'][] = emailkhonghople;
  } elseif (!empty($d->rawQueryOne("select id from tbl_newsletter where email = ? limit 1",[$dataContact['email']]))) {
    $response['messages'][] = "Email đã được đăng ký";
  }

  if (!empty($response['messages'])) {
    foreach ($dataContact as $k => $v) {
      if ($v !== '') $flash->set($k, htmlspecialchars($v));
    }
    $response['status'] = 'danger';
    $flash->set('message', base64_encode(json_encode($response)));
    $func->redirect('lien-he');
  }

  foreach ($dataContact as $k => $v) {
    $dataContact[$k] = htmlspecialchars($func->sanitize($v));
  }

  $data = [
    'fullname'     => $dataContact['fullname'],
    'email'        => $dataContact['email'],
    'phone'        => $dataContact['phone'],
    'address'      => $dataContact['address'],
    'subject'      => $dataContact['subject'],
    'content'      => $dataContact['content'],
    'date_created' => time(),
    'numb'         => 1,
    'type'         => 'lien-he'
  ];

  $insert = $d->insert('tbl_newsletter', $data);
  $func->transfer($insert ? guilienhethanhcong : guilienhethatbai, BASE, $insert);
}
