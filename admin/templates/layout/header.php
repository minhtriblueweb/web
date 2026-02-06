<nav class="layout-navbar main-header navbar navbar-expand navbar-white navbar-light text-sm navbar-detached">
  <!-- Left navbar links -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
    </li>
    <li class="nav-item nav-item-hello d-none d-md-inline-block">
      <a class="nav-link"><span class="text-split"><?= xinchao ?>, admin!</span></a>
    </li>
  </ul>
  <div class="navbar-nav align-items-center">
    <div class="nav-item navbar-search-wrapper mb-0">
      <a class="nav-item nav-link search-toggler d-flex align-items-center px-0" href="javascript:void(0);">
        <i class="fas fa-search"></i>
        <span class=" d-md-inline-block text-muted">Tìm kiếm</span>
      </a>
    </div>
  </div>
  <div class="navbar-search-wrapper search-input-wrapper d-none">
    <span class="twitter-typeahead" style="position: relative; display: inline-block;"><span
        class="twitter-typeahead" style="position: relative; display: inline-block;">
        <input type="text" class="form-control search-input border-0 container-fluid tt-input"
          placeholder="Nhập từ khóa tìm kiếm..." aria-label="Search..." autocomplete="off" spellcheck="false"
          dir="auto" style="position: relative; vertical-align: top;">
        <div class="tt-menu navbar-search-suggestion"
          style="position: absolute; top: 100%; left: 0px; z-index: 100; display: none;">
          <div class="tt-dataset tt-dataset-pages"></div>
        </div>
      </span>
    </span>
    <i class="fas fa-x search-toggler cursor-pointer"></i>
  </div>
  <div class="tt-menu navbar-search-suggestion ps">
    <div class="tt-dataset tt-dataset-pages"></div>
    <div class="tt-dataset tt-dataset-files"></div>
    <div class="tt-dataset tt-dataset-members"></div>
    <div class="ps__rail-x" style="left: 0px; bottom: 0px;">
      <div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div>
    </div>
    <div class="ps__rail-y" style="top: 0px; right: 0px;">
      <div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 0px;"></div>
    </div>
  </div>
  <!-- Right navbar links -->
  <ul class="navbar-nav ml-auto align-items-center">
    <!-- Notifications -->
    <li class="nav-item me-2 me-xl-0  d-none d-md-inline-block ">
      <a class="nav-link text-primary bold">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-brand-php" width="30" height="30" viewBox="0 0 24 24" stroke-width="1.5" stroke="var(--color-blue)" fill="none" stroke-linecap="round" stroke-linejoin="round">
          <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
          <path d="M12 12m-10 0a10 9 0 1 0 20 0a10 9 0 1 0 -20 0"></path>
          <path d="M5.5 15l.395 -1.974l.605 -3.026h1.32a1 1 0 0 1 .986 1.164l-.167 1a1 1 0 0 1 -.986 .836h-1.653">
          </path>
          <path d="M15.5 15l.395 -1.974l.605 -3.026h1.32a1 1 0 0 1 .986 1.164l-.167 1a1 1 0 0 1 -.986 .836h-1.653">
          </path>
          <path d="M12 7.5l-1 5.5"></path>
          <path d="M11.6 10h2.4l-.5 3"></path>
        </svg>
      </a>
    </li>
    <li class="nav-item d-sm-inline-block">
      <a href="../" target="_blank" class="nav-link"><i class="fas fa-reply"></i></a>
    </li>
    <li class="nav-item dropdown">
      <a id="dropdownSubMenu-info" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
        class="nav-link dropdown-toggle"><i class="fas fa-cogs"></i></a>
      <ul aria-labelledby="dropdownSubMenu-info" class="dropdown-menu dropdown-menu-right border-0 shadow">
        <li>
          <a href="index.php?page=user&act=info_admin" class="dropdown-item">
            <i class="fas fa-user-cog"></i>
            <span><?= thongtinadmin ?></span>
          </a>
        </li>
        <div class="dropdown-divider"></div>
        <li>
          <a href="index.php?page=user&act=info_admin&changepass=1" class="dropdown-item">
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
    <?php
    $contactCount    = $fn->count_data(['table' => 'tbl_newsletter', 'type' => 'lien-he']);
    $newsletterCount = $fn->count_data(['table' => 'tbl_newsletter', 'type' => 'dang-ky-nhan-tin']);
    $orderCount      = $fn->count_data(['table' => 'tbl_order']);

    $totalNotify = $contactCount + $newsletterCount + $orderCount;
    ?>
    <li class="nav-item dropdown">
      <a class="nav-link" data-toggle="dropdown" href="#">
        <i class="fas fa-bell"></i>
        <?php if ($totalNotify > 0): ?>
          <span class="badge badge-danger"><?= $totalNotify ?></span>
        <?php endif; ?>
      </a>
      <div class="dropdown-menu dropdown-menu-right shadow">
        <span class="dropdown-item dropdown-header p-0"><?= thongbao ?></span>
        <div class="dropdown-divider"></div>
        <a href="?page=contact&act=man&type=lien-he" class="dropdown-item"><i class="fas fa-envelope mr-2"></i><span class="badge badge-danger mr-1"><?= $contactCount ?></span><?= lienhe ?></a>
        <div class="dropdown-divider"></div>
        <a href="?page=newsletter&act=man&type=dang-ky-nhan-tin" class="dropdown-item"><i class="fas fa-mail-bulk mr-2"></i><span class="badge badge-danger mr-1"><?= $newsletterCount ?></span><?= dangkynhantin ?></a>
        <div class="dropdown-divider"></div>
        <a href="?page=order&act=man" class="dropdown-item"><i class="fas fa-shopping-bag mr-2"></i><span class="badge badge-danger mr-1"><?= $orderCount ?></span><?= donhang ?></a>
      </div>
    </li>
    <li class="nav-item d-sm-inline-block">
      <a href="?page=user&act=logout" class="nav-link"><i class="fas fa-sign-out-alt mr-1"></i><?= dangxuat ?></a>
    </li>
  </ul>
</nav>
