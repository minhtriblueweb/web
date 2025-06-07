<?php
include_once 'lib/autoload.php';
include_once 'lib/router.php';
?>
<?php
$slug = isset($_GET['slug']) ? $_GET['slug'] : '';

if ($slug) {
  $get_huongdan = $news->get_news($slug);
  if ($get_huongdan !== false) {
    $kg_huongdan = $get_huongdan->fetch_assoc();
    if ($kg_huongdan) {
      $id_list = $kg_huongdan['id'];
    } else {
      header('Location: ' . BASE . '404.php');
      exit();
    }
  } else {
    header('Location: ' . BASE . '404.php');
    exit();
  }
} else {
  header('Location: ' . BASE . '404.php');
  exit();
}

$seo = array_merge($seo, array(
  'title' => $kg_huongdan['titlevi'],
  'keywords' => $kg_huongdan['keywordsvi'],
  'description' => $kg_huongdan['descriptionvi'],
  'url' => BASE . $kg_huongdan['slugvi'],
  'image' => isset($kg_huongdan['file_name']) ? BASE_ADMIN . UPLOADS . $kg_huongdan['file_name'] : '',
));
?>
<?php
include 'inc/header.php';
include 'inc/menu.php';
?>
<div class="wrap-main wrap-home w-clear" style="background:#fff">
  <div class="breadCrumbs">
    <div class="wrap-content">
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a class="text-decoration-none" href="<?= BASE ?>"><span>Trang chủ</span></a>
        </li>
        <li class="breadcrumb-item">
          <a class="text-decoration-none" href="huong-dan"><span>Hướng dẫn chơi</span></a>
        </li>
      </ol>
    </div>
  </div>
  <div class="wrap-product-list">
    <div class="title-list-hot mt-4">
      <h2><?= $kg_huongdan['namevi'] ?></h2>
      <div class="animate-border bg-danger mt-1"></div>
    </div>
    <div class="wrap-content" style="background: unset;">
      <div class="row">
        <?= $kg_huongdan['contentvi'] ?>
      </div>
    </div><?php include 'inc/tieuchi.php'; ?>

  </div>
</div>
<?php include 'inc/footer.php'; ?>