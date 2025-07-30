<div class="menu">
  <div class="wrap-content d-flex flex-wrap justify-content-between align-items-center">
    <!-- Menu bên trái: Danh mục sản phẩm -->
    <div class="menu-bar-left">
      <p class="title">DANH MỤC SẢN PHẨM</p>
      <div class="box-ul-left">
        <ul>
          <?php
          $menu_list = $fn->show_data([
            'table'  => 'tbl_product_list',
            'status' => 'hienthi,noibat,menu',
            'select' => "id, file, slug$lang, name$lang"
          ]);

          foreach ($menu_list as $k => $v_list):
            $menu_cat = $fn->show_data([
              'table'  => 'tbl_product_cat',
              'status' => 'hienthi,noibat',
              'select' => "id, file, id_list, slug$lang, name$lang",
              'id_list'  => $v_list['id']
            ]);
          ?>
            <li>
              <a title="<?= $v_list["name$lang"] ?>" href="<?= $v_list["slug$lang"] ?>">
                <span>
                  <?= $fn->getImageCustom(['file' => $v_list['file'], 'width' => 25, 'height' => 25, 'zc' => 1, 'alt' => $v_list["name$lang"], 'title' => $v_list["name$lang"]]) ?>
                </span>
                <?= $v_list["name$lang"] ?>
                <?= !empty($menu_cat) ? '<i class="fa-solid fa-angle-right"></i>' : '' ?>
              </a>

              <?php if (!empty($menu_cat)): ?>
                <div class="box-menu-cat-left">
                  <ul>
                    <?php foreach ($menu_cat as $k => $v_cat): ?>
                      <li>
                        <a class="transition" href="<?= $v_cat["slug$lang"] ?>" title="<?= $v_cat["name$lang"] ?>"><?= $v_cat["name$lang"] ?></a>
                      </li>
                    <?php endforeach; ?>
                  </ul>
                </div>
              <?php endif; ?>
            </li>
          <?php endforeach; ?>
        </ul>

      </div>
    </div>

    <!-- Menu ngang bên phải -->
    <ul class="menu-bar">
      <li><a class="transition <?= ($type == 'index') ? 'active' : '' ?>" href="./" title="Trang chủ"><span><i class="fa-solid fa-house"></i></span></a></li>
      <li><a class="transition <?= ($type == 'gioi-thieu') ? 'active' : '' ?>" href="gioi-thieu" title="Giới thiệu"><span>Giới thiệu</span></a></li>
      <li><a class="transition <?= ($type == 'mua-hang') ? 'active' : '' ?>" href="mua-hang" title="Mua hàng"><span>Mua hàng</span></a></li>
      <li><a class="transition <?= ($type == 'huong-dan-choi') ? 'active' : '' ?>" href="huong-dan-choi" title="Hướng dẫn chơi"><span>Hướng dẫn chơi</span></a></li>
      <li><a class="transition <?= ($type == 'tin-tuc') ? 'active' : '' ?>" href="tin-tuc" title="Tin tức"><span>Tin tức</span></a></li>
      <li><a class="transition <?= ($type == 'lien-he') ? 'active' : '' ?>" href="lien-he" title="Liên hệ"><span>Liên hệ</span></a></li>
    </ul>
  </div>
</div>

<div class="menu-mobile">
  <div class="wrap-content">
    <div class="d-flex flex-wrap justify-content-between align-items-center">
      <div class="search-mobile">
        <input type="text" id="keyword-res" placeholder="Nhập từ khóa tìm kiếm..." onkeypress="doEnterMobile(event,'keyword-res');" />
        <p onclick="onSearchMobile('keyword-res');">
          <i class="fa-solid fa-magnifying-glass"></i>
        </p>
      </div>
      <div class="logo-mobile">
        <a href="./">
          <?= $fn->getImageCustom(['file' => $logo['file'], 'alt' => $optsetting["name$lang"], 'title' => $optsetting["name$lang"], 'width'  => 120, 'height'  => 60, 'zc' => 3, 'lazy' => false]) ?>
        </a>
      </div>
      <div class="header-mobile-right">
        <a id="toggle_menu" href="javascript:void(0)" title="Menu"><span></span></a>
      </div>
    </div>
  </div>
</div>
<nav id="menu">
  <ul>
    <li><a href="./">Trang chủ</a></li>
    <li><a href="gioi-thieu">Giới thiệu</a></li>
    <li>
      <a href="san-pham">Sản phẩm</a>
      <?php if (!empty($menu_list)): ?>
        <ul>
          <?php foreach ($menu_list as $v_list):
            $menu_cat = $fn->show_data([
              'table'   => 'tbl_product_cat',
              'status'  => 'hienthi,noibat',
              'select'  => "id_list, slug$lang, name$lang",
              'id_list' => $v_list['id']
            ]);
          ?>
            <li>
              <a href="<?= $v_list["slug$lang"] ?>"><?= $v_list["name$lang"] ?></a>
              <?php if (!empty($menu_cat)): ?>
                <ul>
                  <?php foreach ($menu_cat as $v_cat): ?>
                    <li>
                      <a href="<?= $v_cat["slug$lang"] ?>"><?= $v_cat["name$lang"] ?></a>
                    </li>
                  <?php endforeach; ?>
                </ul>
              <?php endif; ?>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
    </li>

    <li><a href="huong-dan-choi">Hướng dẫn chơi</a></li>
    <li><a href="tin-tuc">Tin tức</a></li>
    <li><a href="mua-hang">Mua hàng</a></li>
    <li><a href="lien-he">Liên hệ</a></li>
  </ul>
</nav>
