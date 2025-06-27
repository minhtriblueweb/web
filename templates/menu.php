<?php
// Truy vấn danh mục cấp 1
$dm_c1_all = $fn->show_data([
  'table' => 'tbl_danhmuc_c1',
  'status' => 'hienthi,noibat'
]);

// Truy vấn danh mục cấp 2
$dm_c2_all = $fn->show_data([
  'table' => 'tbl_danhmuc_c2',
  'status' => 'hienthi,noibat'
]);

// Gom nhóm cấp 2 theo id_list (id cấp 1)
$dm_c2_group = [];
if ($dm_c2_all && $dm_c2_all->num_rows > 0) {
  while ($row = $dm_c2_all->fetch_assoc()) {
    $dm_c2_group[$row['id_list']][] = $row;
  }
}

// Tạo cây menu dùng chung
$menu_tree = [];
if ($dm_c1_all && $dm_c1_all->num_rows > 0) {
  while ($lv1 = $dm_c1_all->fetch_assoc()) {
    $lv1['sub'] = $dm_c2_group[$lv1['id']] ?? [];
    $menu_tree[] = $lv1;
  }
}
?>

<div class="menu">
  <div class="wrap-content d-flex flex-wrap justify-content-between align-items-center">
    <!-- Menu bên trái: Danh mục sản phẩm -->
    <div class="menu-bar-left">
      <p class="title">DANH MỤC SẢN PHẨM</p>
      <div class="box-ul-left">
        <ul>
          <?php foreach ($menu_tree as $dm): ?>
            <?php $has_sub = count($dm['sub']) > 0; ?>
            <li>
              <a title="<?= $dm['namevi'] ?>" href="<?= $dm['slugvi'] ?>">
                <span>
                  <?= $fn->getImage([
                    'file' => $dm['file'],
                    'width' => 25,
                    'alt' => $dm['namevi'],
                    'title' => $dm['namevi'],
                    'lazy' => false
                  ]) ?>
                </span>
                <?= $dm['namevi'] ?>
                <?= $has_sub ? '<i class="fa-solid fa-angle-right"></i>' : '' ?>
              </a>
              <?php if ($has_sub): ?>
                <div class="box-menu-cat-left">
                  <ul>
                    <?php foreach ($dm['sub'] as $dm2): ?>
                      <li>
                        <a class="transition" href="<?= $dm2['slugvi'] ?>" title="<?= $dm2['namevi'] ?>">
                          <?= $dm2['namevi'] ?>
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
      <li>
        <a class="transition" href="./" title="Trang chủ">
          <i class="fa-solid fa-house"></i>
        </a>
      </li>
      <li><a class="transition" href="gioi-thieu" title="Giới thiệu"><span>Giới thiệu</span></a></li>
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
            <li>
              <a href="<?= $lv1['slugvi'] ?>"><?= $lv1['namevi'] ?></a>
              <?php if (!empty($lv1['sub'])): ?>
                <ul>
                  <?php foreach ($lv1['sub'] as $lv2): ?>
                    <li><a href="<?= $lv2['slugvi'] ?>"><?= $lv2['namevi'] ?></a></li>
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
