<?php
$menuData = [];

$menu_list = $fn->show_data([
  'table'  => 'tbl_product_list',
  'status' => 'hienthi,noibat,menu',
  'select' => "id, file, slug$lang, name$lang"
]);

$brand_menu = $fn->show_data([
  'table'  => 'tbl_product_brand',
  'status' => 'hienthi',
  'select' => "id,slug$lang, name$lang"
]);

foreach ($menu_list as $v_list) {
  $menu_cat = $fn->show_data([
    'table'   => 'tbl_product_cat',
    'status'  => 'hienthi,noibat',
    'select'  => "id, file, id_list, slug$lang, name$lang",
    'id_list' => $v_list['id']
  ]);
  foreach ($menu_cat as &$v_cat) {
    $menu_item = $fn->show_data([
      'table'   => 'tbl_product_item',
      'status'  => 'hienthi',
      'select'  => "id, file, id_cat, slug$lang, name$lang",
      'id_cat'  => $v_cat['id']
    ]);
    $v_cat['item'] = $menu_item;
  }
  $v_list['cat'] = $menu_cat;
  $menuData[] = $v_list;
}
?>
<?php ob_start(); ?>
<div class="wrap-content d-flex flex-wrap justify-content-between align-items-center">

  <!-- Menu trái -->
  <div class="menu-bar-left <?= ($type == 'index') ? 'index' : '' ?>">
    <p>DANH MỤC SẢN PHẨM</p>

    <!-- Menu cấp 1 -->
    <div class="menu-bar-left-list w-100">
      <ul>
        <?php foreach ($menuData as $v_list): ?>
          <li>
            <a href=" <?= $v_list["slug$lang"] ?>" title="<?= $v_list["name$lang"] ?>">
              <span>
                <?= $fn->getImageCustom([
                  'file' => $v_list['file'],
                  'width' => $optsetting_json["san-pham_list_width"],
                  'height' => $optsetting_json["san-pham_list_height"],
                  'zc' => $optsetting_json["san-pham_list_zc"],
                  'alt' => $v_list["name$lang"],
                  'title' => $v_list["name$lang"],
                  'class' => 'me-3',
                  'lazy' => false
                ]) ?>
              </span>
              <?= $v_list["name$lang"] ?>
              <?= !empty($v_list['cat']) ? '<i class="fa-solid fa-angle-right"></i>' : '' ?>
            </a>
            <!-- Menu cấp 2 -->
            <?php if (!empty($v_list['cat'])): ?>
              <div class="menu-bar-left-cat">
                <?php foreach ($v_list['cat'] as $v_cat): ?>
                  <div class="col">
                    <!-- Cấp 2 -->
                    <h3 class="m-0">
                      <a href="<?= $v_cat["slug$lang"] ?>" title="<?= $v_cat["name$lang"] ?>">
                        <?= $v_cat["name$lang"] ?>
                      </a>
                    </h3>

                    <!-- Cấp 3 -->
                    <?php if (!empty($v_cat['item'])): ?>
                      <ul>
                        <?php foreach ($v_cat['item'] as $v_item): ?>
                          <li>
                            <a href="<?= $v_item["slug$lang"] ?>" title="<?= $v_item["name$lang"] ?>">
                              <?= $v_item["name$lang"] ?>
                            </a>
                          </li>
                        <?php endforeach; ?>
                      </ul>
                    <?php endif; ?>
                  </div>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>

  <!-- Menu ngang -->
  <ul class="menu-bar">
    <li><a class="transition <?= ($type == 'index') ? 'active' : '' ?>" href="./"><span><i class="fa-solid fa-house"></i></span></a></li>
    <li><a class="transition <?= ($type == 'gioi-thieu') ? 'active' : '' ?>" href="gioi-thieu"><span>Giới thiệu</span></a></li>
    <li><a class="transition <?= ($type == 'mua-hang') ? 'active' : '' ?>" href="mua-hang"><span>Mua hàng</span></a></li>
    <li>
      <a class="transition <?= ($idb) ? 'active' : '' ?>"><span>Thương hiệu</span></a>
      <?php if (!empty($brand_menu)) : ?>
        <ul>
          <?php foreach ($brand_menu as $b) : ?>
            <li><a href="<?= $b["slug$lang"] ?>"><?= htmlspecialchars($b["name$lang"]) ?></a></li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
    </li>
    <li>
      <a class="transition <?= in_array($type, ['tin-tuc', 'huong-dan-choi']) ? 'active' : '' ?>"><span>Blog</span></a>
      <ul>
        <li><a href="tin-tuc">Tin tức</a></li>
        <li><a href="huong-dan-choi">Hướng dẫn chơi</a></li>
      </ul>
    </li>
    <li><a class="transition <?= ($type == 'lien-he') ? 'active' : '' ?>" href="lien-he"><span>Liên hệ</span></a></li>
  </ul>
</div>

<?php $menuHtml = ob_get_clean(); ?>
<div class="menu bg-main"><?= $menuHtml ?></div>
<div class="menu-fixed bg-main"><?= $menuHtml ?></div>
