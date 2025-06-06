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
      $id = $kg_tintuc['id'];
      $relatedNews =  $news->relatedNews($id,'tintuc');
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
<div class="wrap-main wrap-home w-clear">
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
  <div class="wrap-content padding-top-bottom-detail">
    <div class="row">
      <div class="col-lg-9 mb-3">
        <div class="title-list-hot">
          <h2><?= $kg_tintuc['namevi'] ?></h2>
        </div>
        <div class="wrap-main wrap-template w-clear" style="margin: 0 auto !important;">
          <div class="content-main">
            <?= $kg_tintuc['contentvi'] ?>
          </div>
        </div>
      </div>
      <div class="col-lg-3">
        <div class="share othernews mb-3">
          <b>Tin liên quan:</b>
          <div class="fix__row__news">
            <div class="row">
              <?php if ($relatedNews && $relatedNews->num_rows > 0): ?>
              <?php while ($kg_relatedNews = $relatedNews->fetch_assoc()) : ?>
              <div class="col-lg-12 col-md-6 col-12">
                <a class="scale-img text-decoration-none pic-news-other" href="<?= $kg_relatedNews['slugvi'] ?>"
                  title="<?= $kg_relatedNews["titlevi"] ?>">
                  <div class="news-other d-flex flex-wrap">

                    <img class="w-100" alt="<?= $kg_relatedNews["titlevi"] ?>" title="<?= $kg_relatedNews["titlevi"] ?>"
                      src="<?= BASE_ADMIN . UPLOADS . $kg_relatedNews['file'] ?>">

                    <div class="info-news-other">
                      <?= $kg_relatedNews["titlevi"] ?>
                    </div>
                  </div>
                </a>
              </div>
              <?php endwhile; ?>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include 'inc/footer.php'; ?>