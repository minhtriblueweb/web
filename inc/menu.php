<div class="menu">
  <div class="wrap-content d-flex flex-wrap justify-content-between align-items-center">
    <div class="menu-bar-left">
      <p class="title">DANH MỤC SẢN PHẨM</p>
      <?php if ($show = $danhmuc->show_danhmuc_index('hienthi')): ?>
        <?php if ($show->num_rows > 0): ?>
          <div class="box-ul-left">
            <ul>
              <?php while ($dm = $show->fetch_assoc()): ?>
                <?php
                $c2 = $danhmuc->show_danhmuc_c2_index($dm['id']);
                $has_sub = ($c2 && $c2->num_rows > 0);
                ?>
                <li>
                  <a title="<?= $dm['namevi'] ?>" href="danh-muc/<?= $dm['slugvi'] ?>">
                    <span class="scale-img">
                      <img width="25"
                        src="<?= empty($dm['file']) ? BASE_ADMIN . "assets/img/noimage.png" : BASE_ADMIN . UPLOADS . $dm['file']; ?>"
                        alt="<?= $dm['namevi'] ?>" title="<?= $dm['namevi'] ?>" />
                    </span>
                    <?= $dm['namevi'] ?>
                    <?= $has_sub ? '<i class="fa-solid fa-angle-right"></i>' : '' ?>
                  </a>
                  <?php if ($has_sub): ?>
                    <div class="box-menu-cat-left">
                      <ul>
                        <?php while ($c2row = $c2->fetch_assoc()): ?>
                          <li><a class="transition" title="<?= $c2row['namevi'] ?>" href="cate/<?= $c2row['slugvi'] ?>"><?= $c2row['namevi'] ?></a></li>
                        <?php endwhile; ?>
                      </ul>
                    </div>
                  <?php endif; ?>
                </li>
              <?php endwhile; ?>
            </ul>
          </div>

        <?php endif; ?>
      <?php endif; ?>
    </div>
    <ul class="menu-bar">
      <li>
        <a class="transition" href="./" title="Trang chủ">
          <i class="fa-solid fa-house"></i>
        </a>
      </li>
      <li>
        <a class="transition" href="gioi-thieu" title="Giới thiệu">Giới thiệu</a>
      </li>
      <li>
        <a class="transition" href="mua-hang" title="Mua hàng">Mua hàng</a>
      </li>
      <li><a class="transition" href="huong-dan-choi" title="Hướng dẫn chơi">Hướng dẫn chơi</a></li>
      <li>
        <a class="transition" href="tin-tuc" title="Tin tức">Tin tức</a>
      </li>
      <li>
        <a class="transition" href="lien-he" title="Liên hệ">Liên hệ</a>
      </li>
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
      <?php if ($show = $danhmuc->show_danhmuc_index('hienthi')): ?>
        <ul>
          <?php while ($dm = $show->fetch_assoc()): ?>
            <?php
            $list_id = $dm['id'];
            $sub = $danhmuc->show_danhmuc_c2_index($list_id);
            $has_sub = ($sub && $sub->num_rows > 0);
            ?>
            <li>
              <a title="<?= $dm['namevi'] ?>" href="danh-muc/<?= $dm['slugvi'] ?>">
                <?= $dm['namevi'] ?>
              </a>
              <?php if ($has_sub): ?>
                <ul>
                  <?php while ($row = $sub->fetch_assoc()): ?>
                    <li>
                      <a title="<?= $row['namevi'] ?>" href="cate/<?= $row['slugvi'] ?>">
                        <?= $row['namevi'] ?>
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
