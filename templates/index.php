<!DOCTYPE html>
<html lang="<?= $config['website']['lang-doc'] ?>">

<head>
  <?php include TEMPLATE . LAYOUT . "head.php"; ?>
  <?php include TEMPLATE . LAYOUT . "css.php"; ?>
</head>
<style>
  :root {
    --main-color: #<?= $color ?>;
    --main-color-dark: #<?= $fn->darkenColor($color, 15) ?>;
  }
</style>

<body>
  <div class="wrap-container">
    <?php
    include TEMPLATE . LAYOUT . "seo.php";
    include TEMPLATE . LAYOUT . "header.php";
    include TEMPLATE . LAYOUT . "menu.php";
    include TEMPLATE . LAYOUT . ($sources == 'index.php' ? 'slideshow.php' : 'breadcrumb.php');
    echo $page_content;
    include TEMPLATE . LAYOUT . "footer.php";
    include TEMPLATE . LAYOUT . "js.php";
    ?>
  </div>
</body>

</html>
