<?php
if (!defined('SOURCES')) die("Error");

/* Lấy bài viết tĩnh */
$static = $db->rawQueryOne("SELECT id, type, name$lang as name, content$lang as content, file FROM tbl_static WHERE type = ? LIMIT 1", [$type]);

//SEO
$seo_data = $db->rawQueryOne("SELECT * FROM tbl_seopage WHERE type = ?", array($type));
$seo->set('h1', $titleMain);
$seo->set('title', !empty($seo_data["title$lang"]) ? $seo_data["title$lang"] : '');
$seo->set('keywords', !empty($seo_data["keywords$lang"]) ? $seo_data["keywords$lang"] : '');
$seo->set('description', !empty($seo_data["description$lang"]) ? $seo_data["description$lang"] : '');

$imgJson = (!empty($seo_data['options'])) ? json_decode($seo_data['options'], true) : null;
if (!empty($imgJson)) {
  $seo->set('photo:width', $imgJson['width']);
  $seo->set('photo:height', $imgJson['height']);
}
if (!empty($seo_data['file'])) $seo->set('photo',  $fn->getImageCustom(['file' => $seo_data['file'], 'width' => 600, 'height' => 315, 'zc' => 2, 'src_only' => true]));

/* breadCrumbs */
if (!empty($titleMain)) $breadcr->set($slug, $titleMain);
$breadcrumbs = $breadcr->get();

/* Newsletter */
if (isset($_POST['submit-contact'])) {
  $dataContact = $_POST['dataContact'] ?? [];
  $response = ['messages' => []];

  /* ===== VALIDATE ===== */

  if (empty($dataContact['fullname'])) {
    $response['messages'][] = hotenkhongduoctrong;
  }

  if (empty($dataContact['phone'])) {
    $response['messages'][] = sodienthoaikhongduoctrong;
  } elseif (!$fn->isPhone($dataContact['phone'])) {
    $response['messages'][] = sodienthoaikhonghople;
  }

  if (empty($dataContact['address'])) {
    $response['messages'][] = diachikhongduoctrong;
  }

  if (empty($dataContact['email'])) {
    $response['messages'][] = emailkhongduoctrong;
  } elseif (!$fn->isEmail($dataContact['email'])) {
    $response['messages'][] = emailkhonghople;
  }

  if (empty($dataContact['subject'])) {
    $response['messages'][] = chudekhongduoctrong;
  }

  if (empty($dataContact['content'])) {
    $response['messages'][] = noidungkhongduoctrong;
  }

  /* ===== CÓ LỖI ===== */
  if (!empty($response['messages'])) {

    foreach ($dataContact as $k => $v) {
      if ($v !== '') {
        $flash->set($k, htmlspecialchars($v));
      }
    }
    $response['status'] = 'danger';
    $flash->set('message', base64_encode(json_encode($response)));
    $fn->redirect('lien-he');
  }

  /* ===== SANITIZE ===== */
  foreach ($dataContact as $k => $v) {
    $dataContact[$k] = htmlspecialchars($fn->sanitize($v));
  }

  /* ===== INSERT ===== */
  $data = [
    'fullname' => $dataContact['fullname'],
    'email'    => $dataContact['email'],
    'phone'    => $dataContact['phone'],
    'address'  => $dataContact['address'],
    'subject'  => $dataContact['subject'],
    'content'  => $dataContact['content'],
    'type'     => 'lien-he'
  ];
  $insert = $db->insert('tbl_newsletter', $data);

  $fn->transfer_tc(
    $insert ? guilienhethanhcong : guilienhethatbai,
    BASE,
    $insert
  );
}
