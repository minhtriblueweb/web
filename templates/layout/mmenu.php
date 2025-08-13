<?php ob_start(); ?>
<div class="wrap-content">
  <div class="d-flex flex-wrap justify-content-between align-items-center">
    <!-- <div class="search-mobile">
      <input type="text" id="keyword-res" placeholder="Nhập từ khóa tìm kiếm..." onkeypress="doEnterMobile(event,'keyword-res');" />
      <p onclick="onSearchMobile('keyword-res');">
        <i class="fa-solid fa-magnifying-glass"></i>
      </p>
    </div> -->
    <div class="logo-mobile">
      <a href="./">
        <?= $fn->getImageCustom([
          'file'  => $logo['file'],
          'alt'   => $optsetting["name$lang"],
          'title' => $optsetting["name$lang"],
          'width' => 120,
          'height' => 60,
          'zc'    => 3,
          'lazy'  => false
        ]) ?>
      </a>
    </div>
    <div class="header-mobile-right">
      <a id="toggle_menu" href="javascript:void(0)" title="Menu"><span></span></a>
    </div>
  </div>
</div>
<?php $menuMobileHtml = ob_get_clean(); ?>
<div class="menu-mobile"><?= $menuMobileHtml ?></div>
<div class="menu-mobile-fixed"><?= $menuMobileHtml ?></div>
<nav id="menu">
  <ul>
    <li class="<?= ($type == 'index') ? 'active' : '' ?>"><a href="./">Trang chủ</a></li>
    <li class="<?= ($type == 'gioi-thieu') ? 'active' : '' ?>"><a href="gioi-thieu">Giới thiệu</a></li>
    <li class="">
      <a href="san-pham">Sản phẩm</a>
      <?php if (!empty($menuData)): ?>
        <ul>
          <?php foreach ($menuData as $v_list): ?>
            <li>
              <a href="<?= $v_list["slug$lang"] ?>"><?= $v_list["name$lang"] ?></a>
              <?php if (!empty($v_list['cats'])): ?>
                <ul>
                  <?php foreach ($v_list['cats'] as $v_cat): ?>
                    <li><a href="<?= $v_cat["slug$lang"] ?>"><?= $v_cat["name$lang"] ?></a></li>
                  <?php endforeach; ?>
                </ul>
              <?php endif; ?>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
    </li>
    <li class="">
      <a>Thương hiệu</a>
      <?php if (!empty($brand_menu)): ?>
        <ul>
          <?php foreach ($brand_menu as $b): ?>
            <li>
              <a href="<?= $b["slug$lang"] ?>"><?= $b["name$lang"] ?></a>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
    </li>
    <li class="<?= ($type == 'huong-dan-choi') ? 'active' : '' ?>"><a href="huong-dan-choi">Hướng dẫn chơi</a></li>
    <li class="<?= ($type == 'tin-tuc') ? 'active' : '' ?>"><a href="tin-tuc">Tin tức</a></li>
    <li class="<?= ($type == 'mua-hang') ? 'active' : '' ?>"><a href="mua-hang">Mua hàng</a></li>
    <li class="<?= ($type == 'lien-he') ? 'active' : '' ?>"><a href="lien-he">Liên hệ</a></li>
  </ul>
</nav>
