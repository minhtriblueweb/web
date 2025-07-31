<div class="wrap-main wrap-home w-clear">
  <div class="wrap-product-list">
    <?php if (!empty($static["content"])) { ?>
      <div class="title-list-hot mt-4">
        <h2><?= !empty($static["name"]) ? $static["name"] : $titleMain ?></h2>
        <div class="animate-border bg-animate-border mt-1"></div>
      </div>
      <div class="wrap-content">
        <div class="row">
          <?= $fn->decodeHtmlChars($static["content"] ?? '') ?>
        </div>
        <div class="share">
          <b><?= chiase ?>:</b>
          <div class="social-plugin w-clear">
            <?php
            $params = array();
            $params['oaidzalo'] = $optsetting_json['oaidzalo'];
            $params['data-href'] = $fn->getCurrentPageURL();
            include TEMPLATE . LAYOUT . 'share.php'
            ?>
          </div>
        </div>
      </div>
      <?php include TEMPLATE . LAYOUT . 'tieuchi.php' ?>
    <?php } else { ?>
      <div class="alert alert-warning w-100" role="alert">
        <p class="text-center m-0 fw-bolder"><?= dangcapnhatdulieu ?></p>
      </div>
    <?php } ?>
  </div>
</div>
