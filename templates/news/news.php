<div class="wrap-main wrap-home w-clear" style="background:#fff">
  <div class="wrap-product-list">
    <div class="title-list-hot mt-4">
      <h2><?= $titleMain ?></h2>
      <div class="animate-border bg-animate-border mt-1"></div>
    </div>

    <div class="wrap-content" style="background: unset;">
      <div class="row">
        <?php if (!empty($show_data)): ?>
          <?php foreach ($show_data as $k => $v): ?>
            <div class="col-12 col-sm-4" data-aos="fade-up" data-aos-duration="500">
              <?php include TEMPLATE . LAYOUT . 'item-service.php'; ?>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="alert alert-warning w-100" role="alert">
            <p class="m-0"><strong><?= noidungdangcapnhat ?></strong></p>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
