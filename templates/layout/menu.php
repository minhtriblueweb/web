<?php
$menuData = [];
$menu_list = $fn->show_data([
  'table'  => 'tbl_product_list',
  'status' => 'hienthi,noibat,menu',
  'select' => "id, file, slug$lang, name$lang"
]);

foreach ($menu_list as $v_list) {
  $menu_cat = $fn->show_data([
    'table'   => 'tbl_product_cat',
    'status'  => 'hienthi,noibat',
    'select'  => "id, file, id_list, slug$lang, name$lang",
    'id_list' => $v_list['id']
  ]);

  $v_list['cats'] = $menu_cat;
  $menuData[] = $v_list;
}
?>

<?php
ob_start();
?>
<div class="wrap-content d-flex flex-wrap justify-content-between align-items-center">
  <div class="menu-bar-left">
    <p class="title">DANH MỤC SẢN PHẨM</p>
    <div class="box-ul-left">
      <ul>
        <?php foreach ($menuData as $v_list): ?>
          <li>
            <a href="<?= $v_list["slug$lang"] ?>" title="<?= $v_list["name$lang"] ?>">
              <span>
                <?= $fn->getImageCustom(['file' => $v_list['file'], 'width' => 25, 'height' => 25, 'zc' => 1, 'alt' => $v_list["name$lang"], 'title' => $v_list["name$lang"]]) ?>
              </span>
              <?= $v_list["name$lang"] ?>
              <?= !empty($v_list['cats']) ? '<i class="fa-solid fa-angle-right"></i>' : '' ?>
            </a>
            <?php if (!empty($v_list['cats'])): ?>
              <div class="box-menu-cat-left">
                <ul>
                  <?php foreach ($v_list['cats'] as $v_cat): ?>
                    <li>
                      <a class="transition" href="<?= $v_cat["slug$lang"] ?>" title="<?= $v_cat["name$lang"] ?>">
                        <?= $v_cat["name$lang"] ?>
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
    <li><a class="transition <?= ($type == 'index') ? 'active' : '' ?>" href="./"><span><i class="fa-solid fa-house"></i></span></a></li>
    <li><a class="transition <?= ($type == 'gioi-thieu') ? 'active' : '' ?>" href="gioi-thieu"><span>Giới thiệu</span></a></li>
    <li><a class="transition <?= ($type == 'mua-hang') ? 'active' : '' ?>" href="mua-hang"><span>Mua hàng</span></a></li>
    <li><a class="transition <?= ($type == 'huong-dan-choi') ? 'active' : '' ?>" href="huong-dan-choi"><span>Hướng dẫn chơi</span></a></li>
    <li><a class="transition <?= ($type == 'tin-tuc') ? 'active' : '' ?>" href="tin-tuc"><span>Tin tức</span></a></li>
    <li><a class="transition <?= ($type == 'lien-he') ? 'active' : '' ?>" href="lien-he"><span>Liên hệ</span></a></li>
  </ul>
</div>
<?php $menuHtml = ob_get_clean(); ?>
<div class="menu"><?= $menuHtml ?></div>
<div class="menu-fixed"><?= $menuHtml ?></div>
