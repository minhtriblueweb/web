<!DOCTYPE html>
<html lang="<?= $config['website']['lang-doc'] ?>">

<head>
  <?php include TEMPLATE . LAYOUT . "head.php"; ?>
  <?php include TEMPLATE . LAYOUT . "css.php"; ?>
</head>
<style>
  :root {
    --main-color: #<?= $color ?>;
    --main-color-dark: #<?= $func->darkenColor($color, 15) ?>;
  }

  <?= $func->decodeHtmlChars($setting['custom_css']) ?>
</style>

<body class="<?= ($source == 'index') ? '' : 'in-page' ?>">
  <div class="wrap-container">
    <?php
    include TEMPLATE . LAYOUT . "seo.php";
    include TEMPLATE . LAYOUT . "header.php";
    include TEMPLATE . LAYOUT . "menu.php";
    include TEMPLATE . LAYOUT . "mmenu.php";
    include TEMPLATE . LAYOUT . ($source == 'index' ? 'slideshow.php' : 'breadcrumb.php');
    ?>
    <div class="<?= ($source == 'index') ? 'wrap-home' : 'wrap-content padding-top-bottom' ?>">
      <?php include TEMPLATE . $template . ".php"; ?>
    </div>
    <?php
    include TEMPLATE . LAYOUT . "footer.php";
    include TEMPLATE . LAYOUT . "js.php";
    ?>
  </div>
</body>

</html>
