<?php
include_once 'lib/autoload.php';
include_once 'lib/router.php';
?>
<?php
$slug = isset($_GET['slug']) ? $_GET['slug'] : '';

if ($slug) {
  $get_tintuc = $news->get_news($slug);
  if ($get_tintuc !== false) {
    $kg_tintuc = $get_tintuc->fetch_assoc();
    if ($kg_tintuc) {
      $id_list = $kg_tintuc['id'];
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
  'title' => $kg_tintuc['titlevi'],
  'keywords' => $kg_tintuc['keywordsvi'],
  'description' => $kg_tintuc['descriptionvi'],
  'url' => BASE . 'tin-tuc/' . $kg_tintuc['slugvi'],
  'image' => isset($kg_tintuc['file_name']) ? BASE_ADMIN . UPLOADS . $kg_tintuc['file_name'] : '',
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
          <a class="text-decoration-none" href=""><span>Tin tức</span></a>
        </li>
        <li class="breadcrumb-item active">
          <a class="text-decoration-none"
            href="tin-tuc/<?= $kg_tintuc['slugvi'] ?>"><span><?= $kg_tintuc['namevi'] ?></span></a>
        </li>
      </ol>
    </div>
  </div>

  <div class="title-list-hot mt-5">
    <h2><?= $kg_tintuc['namevi'] ?></h2>
    <div class="animate-border bg-danger mt-1"></div>
  </div>
  <div class="wrap-main wrap-template w-clear" style="margin: 0 auto !important;">
    <div class="content-main">
      <?= $kg_tintuc['contentvi'] ?>
    </div>
  </div>
</div>
<?php include 'inc/footer.php'; ?>