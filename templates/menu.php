<div class="menu">
  <div class="wrap-content d-flex flex-wrap justify-content-between align-items-center">
    <!-- Menu bên trái: Danh mục sản phẩm -->
    <div class="menu-bar-left">
      <p class="title">DANH MỤC SẢN PHẨM</p>
      <div class="box-ul-left">
        <ul>
          <?php foreach ($menu_tree as $dm): ?>
            <?php
            $has_sub = count($dm['sub']) > 0;
            $name = $dm['name' . $lang];
            $slug = $dm['slug' . $lang];
            ?>
            <li>
              <a title="<?= $name ?>" href="<?= $slug ?>">
                <span>
                  <?= $fn->getImage(['file' => $dm['file'], 'width' => 25, 'thumb' => $dm['thumb'], 'zc' => 4, 'alt' => $name, 'title' => $name, 'lazy' => false]) ?>
                </span>
                <?= $name ?>
                <?= $has_sub ? '<i class="fa-solid fa-angle-right"></i>' : '' ?>
              </a>
              <?php if ($has_sub): ?>
                <div class="box-menu-cat-left">
                  <ul>
                    <?php foreach ($dm['sub'] as $dm2): ?>
                      <?php
                      $name2 = $dm2['name' . $lang];
                      $slug2 = $dm2['slug' . $lang];
                      ?>
                      <li>
                        <a class="transition" href="<?= $slug2 ?>" title="<?= $name2 ?>">
                          <?= $name2 ?>
                        </a>
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
      <li><a class="transition" href="./" title="Trang chủ"><span><i class="fa-solid fa-house"></i></span></a></li>
      <li><a class="transition" href="gioi-thieu" title="Giới thiệu"><span>Giới thiệu</span></a></li>
      <li><a class="transition" href="mua-hang" title="Mua hàng"><span>Mua hàng</span></a></li>
      <li><a class="transition" href="huong-dan-choi" title="Hướng dẫn chơi"><span>Hướng dẫn chơi</span></a></li>
      <li><a class="transition" href="tin-tuc" title="Tin tức"><span>Tin tức</span></a></li>
      <li><a class="transition" href="lien-he" title="Liên hệ"><span>Liên hệ</span></a></li>
    </ul>
  </div>
</div>

<div class="menu-mobile">
  <div class="wrap-content d-flex flex-wrap justify-content-between align-items-center">
    <div class="box-banner">
      <div class="logo-mobile">
        <a href="./">
          <?= $fn->getImage([
            'file' => $logo,
            'alt' => $web_name,
            'title' => $web_name,
            'lazy' => false
          ]) ?>
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
    <li><a href="./">Trang chủ</a></li>
    <li><a href="gioi-thieu">Giới thiệu</a></li>
    <li>
      <a href="san-pham">Sản phẩm</a>
      <?php if (!empty($menu_tree)): ?>
        <ul>
          <?php foreach ($menu_tree as $lv1): ?>
            <?php
            $name1 = $lv1['name' . $lang];
            $slug1 = $lv1['slug' . $lang];
            ?>
            <li>
              <a href="<?= $slug1 ?>"><?= $name1 ?></a>
              <?php if (!empty($lv1['sub'])): ?>
                <ul>
                  <?php foreach ($lv1['sub'] as $lv2): ?>
                    <?php
                    $name2 = $lv2['name' . $lang];
                    $slug2 = $lv2['slug' . $lang];
                    ?>
                    <li><a href="<?= $slug2 ?>"><?= $name2 ?></a></li>
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
