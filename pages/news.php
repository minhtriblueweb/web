<?php
$slug = $_GET['slug'] ?? '';
$type = $_GET['type'] ?? '';
$typeInfo = $fn->convert_type($type);
if (empty($slug) || empty($type)) {
  http_response_code(404);
  include '404.php';
  exit();
}

$baiviet = $news->get_baiviet_by_slug_and_type($slug, $type);
if (!$baiviet) {
  http_response_code(404);
  include '404.php';
  exit();
}
$relatedNews = $news->relatedNews($baiviet['id'], $type);


$seo['title'] = !empty($baiviet['title']) ? $baiviet['title'] : $baiviet['name'];
$seo['keywords'] = $baiviet['keywords'];
$seo['description'] = $baiviet['description'];
$seo['url'] = BASE . $baiviet['slugvi'];
$seo['image'] = BASE_ADMIN . UPLOADS . $baiviet['file'];
?>
<div class="wrap-main wrap-home w-clear">
  <div class="breadCrumbs">
    <div class="wrap-content">
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a class="text-decoration-none" href="<?= BASE ?>"><span>Trang chủ</span></a>
        </li>
        <li class="breadcrumb-item">
          <a class="text-decoration-none" href="<?= $typeInfo['slug'] ?>"><span><?= $typeInfo['vi'] ?></span></a>
        </li>
        <li class="breadcrumb-item active">
          <a class="text-decoration-none"
            href="<?= $baiviet['slugvi'] ?>"><span><?= $baiviet['name'] ?></span></a>
        </li>
      </ol>
    </div>
  </div>
  <div class="wrap-content mt-3 p-2">
    <div class="row">
      <div class="col-lg-9 mb-3">
        <div class="title-list-hot p-2">
          <h2 class=" text-start"><?= $baiviet['name'] ?></h2>
        </div>
        <div class="wrap-main wrap-template w-clear" style="margin: 0 auto !important;">
          <div class="wrap-toc">
            <div class="meta-toc2">
              <a class="mucluc-dropdown-list_button">Mục Lục</a>
              <div class="box-readmore">
                <ul class="toc-list" data-toc="article" data-toc-headings="h1, h2, h3"></ul>
              </div>
            </div>
          </div>
          <div class="content-main" id="toc-content">
            <?= $baiviet['contentvi'] ?>
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
                    <div class="news-other d-flex flex-wrap">
                      <a class="scale-img text-decoration-none pic-news-other" href="<?= $kg_relatedNews['slugvi'] ?>"
                        title="<?= $kg_relatedNews["name"] ?>">
                        <img class="w-100" src="<?= BASE_ADMIN . UPLOADS . $kg_relatedNews['file'] ?>"
                          alt="<?= $kg_relatedNews["name"] ?>" title="<?= $kg_relatedNews["name"] ?>">
                      </a>
                      <div class="info-news-other">
                        <a class="name-news-other text-decoration-none" href="<?= $kg_relatedNews['slugvi'] ?>"
                          title="<?= $kg_relatedNews["name"] ?>"><?= $kg_relatedNews["name"] ?></a>
                      </div>
                    </div>
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
