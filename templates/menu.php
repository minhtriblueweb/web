<div class="menu">
  <div class="wrap-content d-flex flex-wrap justify-content-between align-items-center">
    <!-- Menu bên trái: Danh mục sản phẩm -->
    <div class="menu-bar-left">
      <p class="title">DANH MỤC SẢN PHẨM</p>
      <?php
      $danhmuc_lv1 = $functions->show_data([
        'table' => 'tbl_danhmuc',
        'status' => 'hienthi'
      ]);
      ?>
      <?php if ($danhmuc_lv1 && $danhmuc_lv1->num_rows > 0): ?>
        <div class="box-ul-left">
          <ul>
            <?php while ($dm = $danhmuc_lv1->fetch_assoc()): ?>
              <?php
              $danhmuc_lv2 = $functions->show_data([
                'table' => 'tbl_danhmuc_c2',
                'status' => 'hienthi',
                'id_list' => $dm['id']
              ]);
              $has_sub = ($danhmuc_lv2 && $danhmuc_lv2->num_rows > 0);
              ?>
              <li>
                <a title="<?= $dm['name'] ?>" href="<?= $dm['slug'] ?>">
                  <span class="scale-img">
                    <img width="25"
                      src="<?= empty($dm['file']) ? NO_IMG : BASE_ADMIN . UPLOADS . $dm['file'] ?>"
                      alt="<?= $dm['name'] ?>" title="<?= $dm['name'] ?>" />
                  </span>
                  <?= $dm['name'] ?>
                  <?= $has_sub ? '<i class="fa-solid fa-angle-right"></i>' : '' ?>
                </a>
                <?php if ($has_sub): ?>
                  <div class="box-menu-cat-left">
                    <ul>
                      <?php while ($dm2 = $danhmuc_lv2->fetch_assoc()): ?>
                        <li>
                          <a class="transition"
                            title="<?= $dm2['name'] ?>"
                            href="<?= $dm2['slug'] ?>">
                            <?= $dm2['name'] ?>
                          </a>
                        </li>
                      <?php endwhile; ?>
                    </ul>
                  </div>
                <?php endif; ?>
              </li>
            <?php endwhile; ?>
          </ul>
        </div>
      <?php endif; ?>
    </div>

    <!-- Menu ngang bên phải -->
    <ul class="menu-bar">
      <li>
        <a class="transition" href="./" title="Trang chủ">
          <i class="fa-solid fa-house"></i>
        </a>
      </li>
      <li><a class="transition" href="gioi-thieu" title="Giới thiệu">Giới thiệu</a></li>
      <li><a class="transition" href="mua-hang" title="Mua hàng">Mua hàng</a></li>
      <li><a class="transition" href="huong-dan-choi" title="Hướng dẫn chơi">Hướng dẫn chơi</a></li>
      <li><a class="transition" href="tin-tuc" title="Tin tức">Tin tức</a></li>
      <li><a class="transition" href="lien-he" title="Liên hệ">Liên hệ</a></li>
    </ul>
  </div>
</div>

<div class="menu-mobile">
  <div class="wrap-content d-flex flex-wrap justify-content-between align-items-center">
    <div class="box-banner">
      <div class="logo-mobile">
        <a href="./">
          <img src="<?= $logo ?>" alt="<?= $web_name ?>" title="<?= $web_name ?>" />
        </a>
      </div>
    </div>
    <div class="d-flex flex-wrap justify-content-between align-items-center">
      <div class="search-mobile">
        <input type="text" id="keyword-res" placeholder="Nhập từ khóa tìm kiếm..."
          onkeypress="doEnterMobile(event,'keyword-res');" />
        <p onclick="onSearchMobile('keyword-res');">
          <i class="fa-solid fa-magnifying-glass"></i>
        </p>
      </div>
      <a id="toggle_menu" href="" title="Menu"><span></span></a>
    </div>
  </div>
</div>
<nav id="menu">
  <ul>
    <li>
      <a class="transition" href="./" title="Trang chủ">Trang chủ</a>
    </li>
    <li>
      <a class="transition" href="gioi-thieu" title="Giới thiệu">Giới thiệu</a>
    </li>
    <li>
      <a class="transition" href="san-pham" title="Sản phẩm">Sản phẩm</a>
      <?php
      $menu_lv1 = $functions->show_data([
        'table' => 'tbl_danhmuc',
        'status' => 'hienthi'
      ]);
      ?>
      <?php if ($menu_lv1 && $menu_lv1->num_rows > 0): ?>
        <ul>
          <?php while ($dm = $menu_lv1->fetch_assoc()): ?>
            <?php
            $menu_lv2 = $functions->show_data([
              'table' => 'tbl_danhmuc_c2',
              'status' => 'hienthi',
              'id_list' => $dm['id']
            ]);
            $has_sub = ($menu_lv2 && $menu_lv2->num_rows > 0);
            ?>
            <li>
              <a title="<?= $dm['name'] ?>" href="<?= $dm['slug'] ?>">
                <?= $dm['name'] ?>
              </a>
              <?php if ($has_sub): ?>
                <ul>
                  <?php while ($dm2 = $menu_lv2->fetch_assoc()): ?>
                    <li>
                      <a title="<?= $dm2['name'] ?>" href="<?= $dm['slug'] ?>/<?= $dm2['slug'] ?>">
                        <?= $dm2['name'] ?>
                      </a>
                    </li>
                  <?php endwhile; ?>
                </ul>
              <?php endif; ?>
            </li>
          <?php endwhile; ?>
        </ul>
      <?php endif; ?>
    </li>
    <li><a class="transition" href="huong-dan-choi" title="Hướng dẫn chơi">Hướng dẫn chơi</a></li>
    <li><a class="transition" href="tin-tuc" title="Tin tức">Tin tức</a></li>
    <li><a class="transition" href="mua-hang" title="Mua hàng">Mua hàng</a></li>
    <li><a class="transition" href="lien-he" title="Liên hệ">Liên hệ</a></li>
  </ul>
</nav>
