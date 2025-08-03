<!DOCTYPE html>
<html lang="<?= $config['website']['lang-doc'] ?>">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="./assets/img/logo2-3962.png" rel="shortcut icon" type="image/x-icon" />
  <title>Administrator</title>

  <!-- Css all -->
  <!-- Css Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet" />
  <!-- Css Files -->
  <?php
  $cssFiles = [
    "fontawesome611/all.css",
    "confirm/confirm.css",
    "select2/select2.css",
    "sumoselect/sumoselect.css",
    "datetimepicker/jquery.datetimepicker.css",
    "daterangepicker/daterangepicker.css",
    "rangeSlider/ion.rangeSlider.css",
    "filer/jquery.filer.css",
    "filer/jquery.filer-dragdropbox-theme.css",
    "holdon/HoldOn.css",
    "holdon/HoldOn-style.css",
    "simplenotify/simple-notify.css",
    "comment/comment.css",
    "fancybox5/fancybox.css",
    "css/adminlte.css",
    "css/adminlte-style.css",
  ];

  foreach ($cssFiles as $file) {
    echo '<link href="./assets/' . $file . '?v=' . VERSION . '" rel="stylesheet" />' . PHP_EOL;
  }
  ?>
</head>

<body class="sidebar-mini hold-transition text-sm" data-bs-theme="light">
  <!-- Wrapper -->
  <div class="wrapper">
    <!-- Header -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light text-sm">
      <!-- Left navbar links -->
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item nav-item-hello d-sm-inline-block">
          <a class="nav-link"><span class="text-split"><?= xinchao ?>, admin!</span></a>
        </li>
      </ul>

      <!-- Right navbar links -->
      <ul class="navbar-nav ml-auto align-items-center">
        <!-- Notifications -->
        <li class="nav-item d-sm-inline-block">
          <a href="../" target="_blank" class="nav-link"><i class="fas fa-reply"></i></a>
        </li>
        <li class="nav-item dropdown">
          <a id="dropdownSubMenu-info" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
            class="nav-link dropdown-toggle"><i class="fas fa-cogs"></i></a>
          <ul aria-labelledby="dropdownSubMenu-info" class="dropdown-menu dropdown-menu-right border-0 shadow">
            <li>
              <a href="" class="dropdown-item">
                <i class="fas fa-user-cog"></i>
                <span><?= thongtinadmin ?></span>
              </a>
            </li>
            <div class="dropdown-divider"></div>
            <li>
              <a href="" class="dropdown-item">
                <i class="fas fa-key"></i>
                <span><?= doimatkhau ?></span>
              </a>
            </li>
            <div class="dropdown-divider"></div>
            <li>
              <a href="" class="dropdown-item">
                <i class="far fa-trash-alt"></i>
                <span><?= xoabonhocache ?></span>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link" data-toggle="dropdown" href="#">
            <i class="fas fa-bell"></i>
            <span class="badge badge-danger">0</span>
          </a>
          <div class="dropdown-menu dropdown-menu-right shadow">
            <span class="dropdown-item dropdown-header p-0"><?= thongbao ?></span>
            <div class="dropdown-divider"></div>
            <a href="" class="dropdown-item"><i class="fas fa-envelope mr-2"></i><span class="badge badge-danger mr-1">0</span><?= lienhe ?></a>
            <div class="dropdown-divider"></div>
            <a href="" class="dropdown-item"><i class="fas fa-mail-bulk mr-2"></i><span class="badge badge-danger mr-1">0</span><?= dangkynhantin ?></a>
          </div>
        </li>
        <li class="nav-item d-sm-inline-block">
          <a href="logout.php" class="nav-link"><i class="fas fa-sign-out-alt mr-1"></i><?= dangxuat ?></a>
        </li>
      </ul>
    </nav>
