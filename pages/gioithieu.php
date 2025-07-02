<div class="wrap-main wrap-home w-clear" style="background:#fff">
  <div class="wrap-product-list">
    <div class="title-list-hot mt-4">
      <h2><?= $row_gt['name' . $lang] ?></h2>
      <div class="animate-border bg-animate-border mt-1"></div>
    </div>
    <div class="wrap-content" style="background: unset;">
      <div class="row">
        <?= $row_gt['content' . $lang] ?>
      </div>
    </div>
    <?php include TEMPLATE . 'tieuchi.php'; ?>
  </div>
</div>
