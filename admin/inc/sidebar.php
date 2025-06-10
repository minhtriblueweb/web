<aside class="main-sidebar sidebar-dark-primary elevation-4 text-sm">
  <a class="brand-link" href="index.php">
    <img class="brand-image" src="./assets/img/logo-blueweb-white.png" alt="blueweb" />
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <nav class="mt-3">
      <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent text-sm" data-widget="treeview" role="menu"
        data-accordion="false">
        <!-- dashboard -->
        <li class="nav-item">
          <a class="nav-link <?= ActiveClass('index') || ActiveClass('') ?>" href="index.php" title="Bảng điều khiển">
            <i class="nav-icon text-sm fas fa-tachometer-alt"></i>
            <p>Bảng điều khiển</p>
          </a>
        </li>

        <!-- Group -->
        <!-- Sản phẩm -->
        <li class="nav-item has-treeview menu-group">
          <a class="nav-link" href="#" title="Sản phẩm">
            <i class="nav-icon text-sm fas fa-boxes"></i>
            <p>Sản phẩm <i class="right fas fa-angle-left"></i></p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item .menu-open">
              <a class="nav-link <?= ActiveClass("danhmuc") || ActiveClass("themdanhmuc") || ActiveClass("suadanhmuc") ?>"
                href="danhmuc.php" title="Danh mục cấp 1"><i class="nav-icon text-sm far fa-caret-square-right"></i>
                <p>Danh mục cấp 1</p>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?= ActiveClass("danhmuccap2") || ActiveClass("themdanhmuccap2") || ActiveClass("suadanhmuccap2") ?>"
                href="danhmuccap2.php" title="Danh mục cấp 2"><i class="nav-icon text-sm far fa-caret-square-right"></i>
                <p>Danh mục cấp 2</p>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?= ActiveClass("sanpham") || ActiveClass("themsanpham") || ActiveClass("suasanpham") || ActiveClass("gallery") || ActiveClass("them_gallery") ?>"
                href="sanpham.php" title="Sản phẩm"><i class="nav-icon text-sm far fa-caret-square-right"></i>
                <p>Sản phẩm</p>
              </a>
            </li>
          </ul>
        </li>

        <!-- Bài viết (Có cấp) -->
        <li class="nav-item has-treeview menu-group">
          <a class="nav-link " href="#" title="Danh sách bài viết">
            <i class="nav-icon text-sm far fa-newspaper"></i>
            <p>
              Danh sách bài viết <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item ">
              <a class="nav-link <?= ActiveClass("tintuc") || ActiveClass("themtintuc") || ActiveClass("suatintuc") ?>"
                href="tintuc.php" title="Tin tức"><i class="nav-icon text-sm far fa-caret-square-right"></i>
                <p>Tin tức</p>
              </a>
            </li>
          </ul>
          <ul class="nav nav-treeview">
            <li class="nav-item ">
              <a class="nav-link <?= ActiveClass("chinhsach") || ActiveClass("themchinhsach") || ActiveClass("suachinhsach") ?>"
                href="chinhsach.php" title="Chính sách"><i class="nav-icon text-sm far fa-caret-square-right"></i>
                <p>Chính sách</p>
              </a>
            </li>
          </ul>
          <ul class="nav nav-treeview">
            <li class="nav-item ">
              <a class="nav-link <?= ActiveClass("tieuchi") || ActiveClass("themtieuchi") || ActiveClass("suatieuchi") ?>"
                href="tieuchi.php" title="Tiêu chí"><i class="nav-icon text-sm far fa-caret-square-right"></i>
                <p>Tiêu chí</p>
              </a>
            </li>
          </ul>
          <ul class="nav nav-treeview">
            <li class="nav-item ">
              <a class="nav-link <?= ActiveClass(requestUri: "danhgia") || ActiveClass("themdanhgia") || ActiveClass("suadanhgia") ?>"
                href="danhgia.php" title="Đánh giá khách hàng"><i
                  class="nav-icon text-sm far fa-caret-square-right"></i>
                <p>Đánh giá khách hàng</p>
              </a>
            </li>
          </ul>
          <ul class="nav nav-treeview">
            <li class="nav-item ">
              <a class="nav-link <?= ActiveClass("huongdanchoi") || ActiveClass("themhuongdanchoi") || ActiveClass("suahuongdanchoi") ?>"
                href="huongdanchoi.php" title="Hướng dẫn chơi"><i
                  class="nav-icon text-sm far fa-caret-square-right"></i>
                <p>Hướng dẫn chơi</p>
              </a>
            </li>
          </ul>
        </li>

        <!-- Trang tĩnh -->
        <li class="nav-item has-treeview menu-group">
          <a class="nav-link " href="#" title="Quản lý trang tĩnh">
            <i class="nav-icon text-sm fas fa-bookmark"></i>
            <p>
              Quản lý trang tĩnh <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item ">
              <a class="nav-link <?= ActiveClass(requestUri: "gioithieu") || ActiveClass("themgioithieu") || ActiveClass("suagioithieu") ?>"
                href="gioithieu.php" title="Giới thiệu"><i class="nav-icon text-sm far fa-caret-square-right"></i>
                <p>Giới thiệu</p>
              </a>
            </li>
          </ul>
          <ul class="nav nav-treeview">
            <li class="nav-item ">
              <a class="nav-link <?= ActiveClass(requestUri: "lienhe") || ActiveClass(requestUri: "themlienhe") || ActiveClass(requestUri: "sualienhe") ?>"
                href="lienhe.php" title="Liên hệ"><i class="nav-icon text-sm far fa-caret-square-right"></i>
                <p>Liên hệ</p>
              </a>
            </li>
          </ul>
          <ul class="nav nav-treeview">
            <li class="nav-item ">
              <a class="nav-link <?= ActiveClass("muahang") || ActiveClass("themmuahang") || ActiveClass("suamuahang") ?>"
                href="muahang.php" title="Mua hàng"><i class="nav-icon text-sm far fa-caret-square-right"></i>
                <p>Mua hàng</p>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-item has-treeview menu-group">
          <a class="nav-link" href="" title="Quản lý hình ảnh - video">
            <i class="nav-icon text-sm fas fa-photo-video"></i>
            <p>
              Quản lý hình ảnh - video <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item ">
              <a class="nav-link <?= ActiveClass("logo") ?>" href="logo.php" title="Logo"><i
                  class="nav-icon text-sm far fa-caret-square-right"></i>
                <p>Logo</p>
              </a>
            </li>
            <li class="nav-item ">
              <a class="nav-link <?= ActiveClass("watermark") ?>" href="watermark.php" title="watermark"><i
                  class="nav-icon text-sm far fa-caret-square-right"></i>
                <p>watermark</p>
              </a>
            </li>
            <li class="nav-item ">
              <a class="nav-link <?= ActiveClass("favicon") ?>" href="favicon.php" title="Favicon"><i
                  class="nav-icon text-sm far fa-caret-square-right"></i>
                <p>Favicon</p>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?= ActiveClass("slideshow") || ActiveClass("themslideshow") || ActiveClass("suaslideshow") ?>"
                href="slideshow.php" title="Slideshow"><i class="nav-icon text-sm far fa-caret-square-right"></i>
                <p>Slideshow</p>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?= ActiveClass("social") || ActiveClass("themsocial") || ActiveClass("suasocial") ?>"
                href="social.php" title="Social"><i class="nav-icon text-sm far fa-caret-square-right"></i>
                <p>Social</p>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?= ActiveClass("phuongthuctt") || ActiveClass("themphuongthuctt") || ActiveClass("suaphuongthuctt") ?>"
                href="phuongthuctt.php" title="phuongthuctt"><i class="nav-icon text-sm far fa-caret-square-right"></i>
                <p>Phương thức thanh toán</p>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= ActiveClass("setting") ?>" href="setting.php" title="Thiết lập thông tin">
            <i class="nav-icon text-sm fas fa-cogs"></i>
            <p>Thiết lập thông tin</p>
          </a>
        </li>
      </ul>
    </nav>
  </div>
</aside>
<div class="content-wrapper">