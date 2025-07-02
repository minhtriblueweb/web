<!DOCTYPE html>
<html lang="<?= $config['website']['lang-doc'] ?>">

<head>
  <?php include TEMPLATE . LAYOUT . "head.php"; ?>
  <?php include TEMPLATE . LAYOUT . "css.php"; ?>
</head>

<body>
  <div class="containers">
    <?php
    include TEMPLATE . LAYOUT . "seo.php";
    include TEMPLATE . LAYOUT . "header.php";
    if ($source == 'index') include TEMPLATE . LAYOUT . "slide-owl.php";
    else include TEMPLATE . LAYOUT . "breadcrumb.php"; ?>
    <?php include TEMPLATE . $template . "_tpl.php"; ?>
    <?php
    include TEMPLATE . LAYOUT . "footer.php";
    include TEMPLATE . LAYOUT . "js.php";
    ?>
  </div>
</body>

</html>
