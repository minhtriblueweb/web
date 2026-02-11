<?php ob_start(); ?>
<div class="wrap-content d-flex flex-wrap justify-content-between align-items-center">

  <!-- Menu trái -->
  <div class="menu-bar-left position-relative <?= ($type == 'index') ? 'index' : '' ?>">
    <p><a href="san-pham">DANH MỤC SẢN PHẨM</a></p>
    <!-- Menu cấp 1 -->
    <?php if ($type != 'san-pham') { ?>
      <div class="menu-bar-left-list">
        <div class="menu-list">
          <?php
          $listsMenu = $db->rawQuery("select id,name{$lang},slug{$lang},file from tbl_product_list where type = 'san-pham' and find_in_set('hienthi',status) and find_in_set('menu',status) order by numb,id desc");
          foreach ($listsMenu as $v_list): ?>
            <div class="menu-list-item <?= !empty($cats) ? 'has-cat' : '' ?>">
              <a href="<?= $v_list["slug$lang"] ?>" title="<?= $v_list["name$lang"] ?>">
                <span class="menu-icon">
                  <?= $fn->getImageCustom(['file' => $v_list['file'], 'width' => 24, 'height' => 24, 'alt' => $v_list["name$lang"], 'title' => $v_list["name$lang"], 'lazy' => false]) ?>
                </span>
                <span class="menu-text"><?= $v_list["name$lang"] ?></span>
              </a>
              <?php
              $cats = $db->rawQuery("select id,id_list,name{$lang},slug{$lang},file from tbl_product_cat where id_list = ? and find_in_set('hienthi',status) order by numb,id desc", [$v_list['id']]);
              if (!empty($cats)): ?>
                <div class="menu-cat-box shadow">
                  <div class="menu-cat-title"><?= $v_list["name$lang"] ?></div>
                  <div class="menu-cat-grid">
                    <?php foreach ($cats as $v_cat): ?>
                      <div class="menu-cat-col">
                        <a href="<?= $v_cat["slug$lang"] ?>" title="<?= $v_cat["name$lang"] ?>">
                          <span class="menu-cat-img">
                            <?= $fn->getImageCustom(['file' => $v_cat['file'], 'width' => 64, 'height' => 64, 'alt' => $v_cat["name$lang"], 'title' => $v_cat["name$lang"], 'lazy' => true]) ?>
                          </span>
                          <span class="menu-cat-text"><?= $v_cat["name$lang"] ?></span>
                        </a>
                      </div>
                    <?php endforeach; ?>
                  </div>
                </div>
              <?php endif; ?>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    <?php } ?>
  </div>

  <!-- Menu ngang -->
  <ul class="menu-bar">
    <li><a class="transition <?= ($type == 'index') ? 'active' : '' ?>" href="./"><span><i class="fa-solid fa-house"></i></span></a></li>
    <li><a class="transition <?= ($type == 'gioi-thieu') ? 'active' : '' ?>" href="gioi-thieu"><span>Giới thiệu</span></a></li>
    <li><a class="transition <?= ($type == 'mua-hang') ? 'active' : '' ?>" href="mua-hang"><span>Mua hàng</span></a></li>
    <li>
      <a class="transition <?= (!empty($idb)) ? 'active' : '' ?>"><span>Thương hiệu</span></a>
      <?php if (!empty($brand)) : ?>
        <ul>
          <?php foreach ($brand as $b) : ?>
            <li><a href="<?= $b["slug$lang"] ?>"><?= htmlspecialchars($b["name$lang"]) ?></a></li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
    </li>
    <li>
      <a href="blog" class="transition <?= ($type == 'blog') ? 'active' : '' ?>"><span>BLOG</span></a>
      <ul>
        <?php foreach ($news_list as $n) : ?>
          <li><a href="<?= $n["slug$lang"] ?>"><?= htmlspecialchars($n["name$lang"]) ?></a></li>
        <?php endforeach; ?>
      </ul>
    </li>
    <li><a class="transition <?= ($type == 'lien-he') ? 'active' : '' ?>" href="lien-he"><span>Liên hệ</span></a></li>
  </ul>
</div>

<?php $menuHtml = ob_get_clean(); ?>
<div class="menu bg-main"><?= $menuHtml ?></div>
<div class="menu-fixed bg-main"><?= $menuHtml ?></div>
