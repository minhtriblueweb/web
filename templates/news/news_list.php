<div class="wrap-main wrap-home w-clear">
  <div class="wrap-product-list">
    <div class="title-list-hot mt-4">
      <h2><?= htmlspecialchars(!empty($titleCate) ? $titleCate : "BLOG") ?></h2>
    </div>
    <div class="wrap-content">
      <div class="row">
        <div class="col-lg-9 col-12">
          <div class="row mb-3">
            <?php if (!empty($news)): ?>
              <?php foreach ($news as $v): ?>
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
          <?php if ($paging): ?>
            <div class="mt-3 mb-3 pagination-home w-100"><?= $paging ?></div>
          <?php endif; ?>
        </div>
        <div class="col-lg-3 col-12">
          <?php include TEMPLATE . LAYOUT . 'othernews.php' ?>
        </div>
      </div>
    </div>
  </div>
</div>
