<!DOCTYPE html>
<html lang="<?= $config['website']['lang-doc'] ?>">

<head>
  <?php include TEMPLATE . "head.php"; ?>
  <?php include TEMPLATE . "css.php"; ?>
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
    include TEMPLATE . "seo.php";
    include TEMPLATE . "header.php";
    include TEMPLATE . "menu.php";
    include TEMPLATE . ($page == 'home.php' ? 'slideshow.php' : 'breadcrumb.php');
    echo $page_content;
    include TEMPLATE . "footer.php";
    include TEMPLATE . "js.php";
    ?>
  </div>
</body>

</html>
