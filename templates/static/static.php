<div class="wrap-main wrap-home w-clear">
  <div class="wrap-product-list">
    <div class="title-list-hot mt-4">
      <h2><?= $static["name"] ?? '' ?></h2>
      <div class="animate-border bg-animate-border mt-1"></div>
    </div>
    <div class="wrap-content">
      <div class="row">
        <?= $static["content"] ?? '' ?>
      </div>
    </div>
    <?php include TEMPLATE . LAYOUT . 'tieuchi.php'; ?>
  </div>
</div>
