  <?php if (!empty($static)) { ?>
    <div class="title-list-hot mt-4">
      <h2><?= $static['name' . $lang] ?></h2>
    </div>
    <div class="wrap-content">
      <div class="row">
        <div class="col-lg-9 col-12 content-ck">
          <?= $func->decodeHtmlChars($static['content' . $lang]) ?>
        </div>
        <div class="col-lg-3 col-12">
          <?php include TEMPLATE . LAYOUT . 'othernews.php' ?>
        </div>
      </div>
      <div class="share">
        <b><?= chiase ?>:</b>
        <div class="social-plugin w-clear">
          <?php
          $params = array();
          $params['oaid'] = $optsetting['oaidzalo'];
          echo $func->markdown('social/share', $params);
          ?>
        </div>
      </div>
    </div>
    <?php include TEMPLATE . LAYOUT . 'tieuchi.php' ?>
  <?php } else { ?>
    <div class="alert alert-warning w-100" role="alert">
      <strong><?= dangcapnhatdulieu ?></strong>
    </div>
  <?php } ?>
