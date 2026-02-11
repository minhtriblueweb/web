<div class="wrap-main wrap-home w-clear">
  <div class="wrap-product-list">
    <div class="title-list-hot mt-4">
      <h2><?= htmlspecialchars(!empty($titleMain) ? $titleMain : "") ?></h2>
    </div>
    <div class="wrap-content">
      <div class="row">
        <div class="col-lg-9 col-12">
          <?php if (!empty($news_list)): ?>
            <?php foreach ($news_list as $list): ?>
              <?php
              $list_id   = $list['id'];
              $list_news = $newsByList[$list_id] ?? [];
              // ❌ KHÔNG có bài → bỏ qua toàn bộ layout
              if (empty($list_news)) continue;
              ?>
              <div class="child__news__title">
                <span><?= $list['name' . $lang] ?></span>
                <a href="<?= $list['slug' . $lang] ?>" class="child__news__btn text-decoration-none transition">
                  <?= xemthem ?> <i class="fa-sharp fa-solid fa-caret-right"></i>
                </a>
              </div>
              <div class="row mb-3">
                <?php if (!empty($list_news)): ?>
                  <?php foreach ($list_news as $v): ?>
                    <div class="col-12 col-sm-4">
                      <?php include TEMPLATE . LAYOUT . 'item-service.php'; ?>
                    </div>
                  <?php endforeach; ?>
                <?php else: ?>
                  <div class="alert alert-warning w-100" role="alert">
                    <p class="m-0"><strong><?= noidungdangcapnhat ?></strong></p>
                  </div>
                <?php endif; ?>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>

        <div class="col-lg-3 col-12">
          <?php include TEMPLATE . LAYOUT . 'othernews.php' ?>
        </div>
      </div>
    </div>
  </div>
</div>
