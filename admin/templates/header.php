<!DOCTYPE html>
<html lang="vi">

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
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet" />
  <link href="./assets/css/animate.min.css" rel="stylesheet" />
  <link href="./assets/confirm/confirm.css" rel="stylesheet" />
  <link href="./assets/select2/select2.css" rel="stylesheet" />
  <link href="./assets/sumoselect/sumoselect.css" rel="stylesheet" />
  <link href="./assets/datetimepicker/jquery.datetimepicker.css" rel="stylesheet" />
  <link href="./assets/daterangepicker/daterangepicker.css" rel="stylesheet" />
  <link href="./assets/rangeSlider/ion.rangeSlider.css" rel="stylesheet" />
  <link href="./assets/filer/jquery.filer.css" rel="stylesheet" />
  <link href="./assets/filer/jquery.filer-dragdropbox-theme.css" rel="stylesheet" />
  <link href="./assets/holdon/HoldOn.css" rel="stylesheet" />
  <link href="./assets/holdon/HoldOn-style.css" rel="stylesheet" />
  <link href="./assets/simplenotify/simple-notify.css" rel="stylesheet" />
  <link href="./assets/comment/comment.css" rel="stylesheet" />
  <link href="./assets/fancybox5/fancybox.css" rel="stylesheet" />
  <link href="./assets/css/adminlte.css" rel="stylesheet" />
  <link href="./assets/css/adminlte-style.css" rel="stylesheet" />
</head>

<body class="sidebar-mini hold-transition text-sm">
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
          <a class="nav-link"><span class="text-split">Xin chào, admin!</span></a>
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
                <span>Thông tin admin</span>
              </a>
            </li>
            <div class="dropdown-divider"></div>
            <li>
              <a href="" class="dropdown-item">
                <i class="fas fa-key"></i>
                <span>Đổi mật khẩu</span>
              </a>
            </li>
            <div class="dropdown-divider"></div>
            <li>
              <a href="" class="dropdown-item">
                <i class="far fa-trash-alt"></i>
                <span>Xóa bộ nhớ cache</span>
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
            <span class="dropdown-item dropdown-header p-0">Thông báo</span>
            <div class="dropdown-divider"></div>
            <a href="" class="dropdown-item"><i class="fas fa-envelope mr-2"></i><span
                class="badge badge-danger mr-1">0</span> Liên hệ</a>
            <div class="dropdown-divider"></div>
            <a href="" class="dropdown-item"><i class="fas fa-mail-bulk mr-2"></i><span
                class="badge badge-danger mr-1">0</span>
              Đăng ký nhận tin
            </a>
          </div>
        </li>
        <li class="nav-item d-sm-inline-block">
          <a href="logout.php" class="nav-link"><i class="fas fa-sign-out-alt mr-1"></i>Đăng xuất</a>
        </li>
      </ul>
    </nav>
