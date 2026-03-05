<?php ob_start(); ?>
<div class="wrap-content w-100">
  <div class="d-flex flex-wrap justify-content-between align-items-center">
    <div class="header-mobile-right">
      <a id="toggle_menu" href="javascript:void(0)" title="Menu"><span></span></a>
    </div>
    <div class="logo-mobile">
      <a href="./">
        <?= $func->getImageCustom([
          'file'  => $logo['file'],
          'alt'   => $setting["name$lang"],
          'title' => $setting["name$lang"],
          'width' => 100,
          'height' => 100,
          'zc'    => 4,
          'lazy'  => false
        ]) ?>
      </a>
    </div>

    <div class="search-mobile">
      <i class=" fa-solid fa-magnifying-glass"></i>
    </div>
  </div>
</div>
<?php $menuMobileHtml = ob_get_clean(); ?>
<div class="menu-mobile position-relative"><?= $menuMobileHtml ?>
</div>
<div class="menu-mobile-fixed"><?= $menuMobileHtml ?></div>
<nav id="menu">
  <div class="box-menu-mobile-search">
    <div class="menu-mobile-search">
      <input
        type="text"
        class="js-search-input"
        placeholder="Nhập từ khóa tìm kiếm..." />
      <span class="js-search-btn">
        <i class="fa fa-search"></i>
      </span>
    </div>
  </div>

  <ul>
    <li class="<?= ($type == 'index') ? 'active' : '' ?>"><a href="./">Trang chủ</a></li>
    <li class="<?= ($type == 'gioi-thieu') ? 'active' : '' ?>"><a href="gioi-thieu">Giới thiệu</a></li>
    <li class="">
      <a href="san-pham">Sản phẩm</a>
      <?php
      $listsMenuM = $d->rawQuery("select id,name{$lang},slug{$lang},file from tbl_product_list where type = 'san-pham' and find_in_set('hienthi',status) order by numb,id desc");
      if (!empty($listsMenuM)): ?>
        <ul>
          <?php foreach ($listsMenuM as $v_list): ?>
            <li>
              <a href="<?= $v_list["slug$lang"] ?>"><?= $v_list["name$lang"] ?></a>
              <?php
              $cats = $d->rawQuery("select id,name{$lang},slug{$lang} from tbl_product_cat where id_list = ? and find_in_set('hienthi',status) order by numb,id desc", [$v_list['id']]);
              if (!empty($cats)): ?>
                <ul>
                  <?php foreach ($cats as $v_cat):
                    $items = $d->rawQuery("select id,name{$lang},slug{$lang} from tbl_product_item where id_cat = ? and find_in_set('hienthi',status) order by numb,id desc", [$v_cat['id']]);
                  ?>
                    <li>
                      <a href="<?= $v_cat["slug$lang"] ?>"><?= $v_cat["name$lang"] ?></a>
                      <?php if (!empty($items)): ?>
                        <ul>
                          <?php foreach ($items as $v_item): ?>
                            <li><a href="<?= $v_item["slug$lang"] ?>"><?= $v_item["name$lang"] ?></a></li>
                          <?php endforeach; ?>
                        </ul>
                      <?php endif; ?>
                    </li>
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
      <?php if (!empty($brand)): ?>
        <ul>
          <?php foreach ($brand as $b): ?>
            <li>
              <a href="<?= $b["slug$lang"] ?>"><?= $b["name$lang"] ?></a>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
    </li>
    <li class="<?= ($type == 'mua-hang') ? 'active' : '' ?>"><a href="mua-hang">Mua hàng</a></li>
    <li class="">
      <a href="">BLOG</a>
      <?php if (!empty($news_list)): ?>
        <ul>
          <?php foreach ($news_list as $n): ?>
            <li>
              <a href="<?= $n["slug$lang"] ?>"><?= $n["name$lang"] ?></a>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
    </li>
    <li class="<?= ($type == 'lien-he') ? 'active' : '' ?>"><a href="lien-he">Liên hệ</a></li>
  </ul>
</nav>
