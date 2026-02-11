<div class="wrap-main wrap-home w-clear">
  <div class="wrap-product-list">
    <div class="title-list-hot mt-4">
      <h2><?= htmlspecialchars(!empty($titleCate) ? $titleCate : $titleMain) ?></h2>
    </div>
    <?php
    $hasIdList = false;
    if (!empty($news)) {
      foreach ($news as $item) {
        if (!empty($item['id_list'])) {
          $hasIdList = true;
          break;
        }
      }
    }
    ?>
    <div class="wrap-content">
      <div class="row">
        <div class="col-lg-9 col-12">
          <?php if ($hasIdList && !empty($news_list)): ?>
            <?php foreach ($news_list as $list): ?>
              <?php
              $list_news = $db->rawQuery("select id,id_list,file,slug{$lang},name{$lang},desc{$lang},updated_at,views from `tbl_news` where id_list = ? and type = ? and find_in_set('hienthi',status) order by numb, id desc limit 6",array($list['id'], $type));
              if (empty($list_news)) continue;
              ?>
              <div class="child__news__title">
                <span><?= $list['name' . $lang] ?></span>
                <a href="<?= $list['slug' . $lang] ?>" class="child__news__btn text-decoration-none transition">
                  <?= xemthem ?> <i class="fa-sharp fa-solid fa-caret-right"></i>
                </a>
              </div>
              <div class="row mb-3">
                <?php foreach ($list_news as $v): ?>
                  <div class="col-12 col-sm-4">
                    <?php include TEMPLATE . LAYOUT . 'item-service.php'; ?>
                  </div>
                <?php endforeach; ?>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <div class="row mb-3">
              <?php if (!empty($news)): ?>
                <?php foreach ($news as $v): ?>
                  <div class="col-12 col-sm-4">
                    <?php include TEMPLATE . LAYOUT . 'item-service.php'; ?>
                  </div>
                <?php endforeach; ?>
              <?php else: ?>
                <div class="alert alert-warning w-100">
                  <strong><?= noidungdangcapnhat ?></strong>
                </div>
              <?php endif; ?>
            </div>
          <?php endif; ?>
        </div>
        <div class="col-lg-3 col-12">
          <?php include TEMPLATE . LAYOUT . 'othernews.php' ?>
        </div>
      </div>
    </div>
  </div>
</div>
