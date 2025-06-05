<div class="menu">
  <div class="wrap-content d-flex flex-wrap justify-content-between align-items-center">
    <div class="menu-bar-left">
      <p class="title">DANH MỤC SẢN PHẨM</p>
      <div class="box-ul-left hide-menu">
        <ul>
          <?php
          if ($show_danhmuc) {
            while ($result_danhmuc = $show_danhmuc->fetch_assoc()) {
              $list_id = $result_danhmuc['id'];
              $show_danhmuc_c2 = $danhmuc->show_danhmuc_c2_index($list_id);
              $has_submenu = ($show_danhmuc_c2 && $show_danhmuc_c2->num_rows > 0);
          ?>
          <li>
            <a class="transition position-relative" title="<?= $result_danhmuc['namevi'] ?>"
              href="danh-muc/<?= $result_danhmuc['slugvi'] ?>">
              <span class="scale-img">
                <img width="25" src="<?= BASE_ADMIN . UPLOADS . $result_danhmuc['file_name'] ?>"
                  alt="<?= $result_danhmuc['namevi'] ?>" title="<?= $result_danhmuc['namevi'] ?>" />
              </span>
              <?= $result_danhmuc['namevi'] ?>
              <?php if ($has_submenu): ?><i class="fa-solid fa-angle-right"></i><?php endif; ?>
            </a>

            <?php if ($has_submenu): ?>
            <div class="box-menu-cat-left">
              <ul>
                <?php while ($result_danhmuc_c2 = $show_danhmuc_c2->fetch_assoc()) : ?>
                <li>
                  <a class="transition" title="<?= $result_danhmuc_c2['namevi'] ?>"
                    href="danh-muc/<?= $result_danhmuc_c2['slugvi'] ?>"><?= $result_danhmuc_c2['namevi'] ?></a>
                </li>
                <?php endwhile; ?>
              </ul>
              <div class="images-list">
                <a class="scale-img" href="danh-muc/<?= $result_danhmuc['slugvi'] ?>">
                  <img width="250" src="<?= BASE_ADMIN . UPLOADS . $result_danhmuc['file_name'] ?>"
                    alt="<?= $result_danhmuc['namevi'] ?>" title="<?= $result_danhmuc['namevi'] ?>" />
                </a>
              </div>
            </div>
            <?php endif; ?>
          </li>
          <?php
            }
          }
          ?>
        </ul>
      </div>


    </div>
    <ul class="menu-bar">
      <li>
        <a class="transition <?= ActiveClass('index') ?>" href="index.php" title="Trang chủ">
          <i class="fa-solid fa-house"></i>

        </a>
      </li>
      <li>
        <a class="transition" href="gioi-thieu" title="Giới thiệu">Giới thiệu</a>
      </li>
      <li>
        <a class="transition" href="tin-tuc" title="Tin tức">Tin tức</a>
      </li>
      <li><a class="transition" href="" title="Hướng dẫn chơi">Hướng dẫn chơi</a></li>
      <li>
        <a class="transition" href="" title="Mua hàng">Mua hàng</a>
      </li>
      <li>
        <a class="transition" href="" title="Liên hệ">Liên hệ</a>
      </li>
    </ul>
  </div>
</div>
<div class="menu-mobile">
  <div class="wrap-content d-flex flex-wrap justify-content-between align-items-center">
    <div class="box-banner">
      <div class="logo-mobile">
        <a href="./">
          <img
            src="<?= isset($result_setting['file']) ? $config['baseAdmin'] . 'uploads/' . $result_setting['file'] : ''; ?>"
            alt="<?= isset($result_setting['web_name']) ? htmlspecialchars($result_setting['web_name']) : ''; ?>"
            title="<?= isset($result_setting['web_name']) ? htmlspecialchars($result_setting['web_name']) : ''; ?>" />
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
      <a id="hamburger" href="#menu" title="Menu"><span></span></a>
    </div>
  </div>
</div>
<nav id="menu">
  <ul>
    <li>
      <a class="transition <?php ActiveClass('index') || ActiveClass(''); ?>" href="./" title="Trang chủ">Trang chủ</a>
    </li>
    <li>
      <a class="transition" href="san-pham" title="Sản phẩm">Sản phẩm</a>
      <ul>
        <?php
        $show_danhmuc = $danhmuc->show_danhmuc_index('hienthi');
        while ($result_danhmuc_mobile = $show_danhmuc->fetch_assoc()) :
          $list_id = $result_danhmuc_mobile['id'];
          $show_danhmuc_c2 = $danhmuc->show_danhmuc_c2_index($list_id);
          $has_submenu = ($show_danhmuc_c2 && $show_danhmuc_c2->num_rows > 0);
        ?>
        <li>
          <a title="<?= $result_danhmuc_mobile['namevi'] ?>" href=""><?= $result_danhmuc_mobile['namevi'] ?></a>
          <ul>
            <?php if ($has_submenu): ?>
            <?php while ($result_danhmuc_c2 = $show_danhmuc_c2->fetch_assoc()) : ?>
            <li>
              <a title="<?= $result_danhmuc_c2['namevi'] ?>" href="danh-muc/<?= $result_danhmuc_c2['slugvi'] ?>">
                <?= $result_danhmuc_c2['namevi'] ?>
              </a>
            </li>
            <?php endwhile; ?>
            <?php endif; ?>
          </ul>
        </li>
        <?php endwhile; ?>
      </ul>
    </li>
    <li>
      <a class="transition" href="gioi-thieu" title="Giới thiệu">Giới thiệu</a>
    </li>
    <li><a class="transition" href="huong-dan-choi" title="Hướng dẫn chơi">Hướng dẫn chơi</a></li>
    <li>
      <a class="transition" href="tin-tuc" title="Tin tức">Tin tức</a>
    </li>
    <li>
      <a class="transition" href="mua-hang" title="Mua hàng">Mua hàng</a>
    </li>
    <li>
      <a class="transition" href="lien-he" title="Liên hệ">Liên hệ</a>
    </li>
  </ul>
</nav>