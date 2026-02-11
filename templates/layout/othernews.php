<div class="othernews">
  <h2 class="titleSide"><span>Danh mục bài viết</span></h2>
  <div class="sidebar-menu">
    <ul class="menu-news list-unstyled  shadow">
      <?php if ($news_list): ?>
        <li class="menu-item">
          <a class="menu-link has-child" data-bs-toggle="collapse" href="#menuBlog" role="button" aria-expanded="false">
            Blog
          </a>
          <ul class="menu-sub collapse list-unstyled" id="menuBlog">
            <?php foreach ($news_list as $nl): ?>
              <li>
                <a class="menu-link <?= ($slug == $nl["slug$lang"]) ? 'active' : '' ?>" href="<?= $nl["slug$lang"] ?>" title="<?= $nl["name$lang"] ?>">
                  <?= $nl["name$lang"] ?>
                </a>
              </li>
            <?php endforeach; ?>
          </ul>
        </li>
      <?php endif; ?>
      <li>
        <a class="menu-link <?= ($slug == 'gioi-thieu') ? 'active' : '' ?>" href="gioi-thieu">
          <?= gioithieu ?>
        </a>
      </li>

      <li>
        <a class="menu-link <?= ($slug == 'mua-hang') ? 'active' : '' ?>" href="mua-hang">
          Mua hàng
        </a>
      </li>

      <li>
        <a class="menu-link <?= ($slug == 'thanh-toan') ? 'active' : '' ?>" href="thanh-toan">
          Phương thức thanh toán
        </a>
      </li>

      <?php if ($show_chinhsach): ?>
        <li class="menu-item">
          <a class="menu-link has-child" data-bs-toggle="collapse" href="#menuPolicy" role="button" aria-expanded="false">
            Chính sách
          </a>

          <ul class="menu-sub collapse list-unstyled" id="menuPolicy">
            <?php foreach ($show_chinhsach as $cs): ?>
              <li>
                <a class="menu-link <?= ($slug == $cs["slug$lang"]) ? 'active' : '' ?>" href="<?= $cs["slug$lang"] ?>" title="<?= $cs["name$lang"] ?>">
                  <?= $cs["name$lang"] ?>
                </a>
              </li>
            <?php endforeach; ?>
          </ul>
        </li>
      <?php endif; ?>
    </ul>
  </div>
  <?php if (!empty($relatedNews)) { ?>
    <h2 class="titleSide"><span><?= baivietkhac ?></span></h2>
    <div class="row shadow">
      <?php foreach ($relatedNews as $v): ?>
        <div class="col-lg-12 col-md-6">
          <div class="news-other d-flex flex-wrap">
            <a class="scale-img text-decoration-none pic-news-other" href="<?= $v["slug$lang"] ?>" title="<?= $v["name$lang"] ?>">
              <?= $fn->getImageCustom([
                'file' => $v['file'],
                'class' => 'w-100',
                'width' => 210,
                'height' => 144,
                'zc' => 1,
                'alt' => $v["name$lang"],
                'title' => $v["name$lang"],
                'lazy' => true
              ]) ?>
            </a>
            <div class="info-news-other">
              <a class="name-news-other text-decoration-none" href="<?= $v["slug$lang"] ?>" title="<?= $v["name$lang"] ?>"><?= $v["name$lang"] ?></a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php } else { ?>
    <h2 class="titleSide"><span>Bài viết mới nhất</span></h2>
    <div class="row shadow">
      <?php foreach ($news_new as $v): ?>
        <div class="col-lg-12 col-md-6">
          <div class="news-other d-flex flex-wrap">
            <a class="scale-img text-decoration-none pic-news-other" href="<?= $v["slug$lang"] ?>" title="<?= $v["name$lang"] ?>">
              <?= $fn->getImageCustom([
                'file' => $v['file'],
                'class' => 'w-100',
                'width' => 210,
                'height' => 144,
                'zc' => 1,
                'alt' => $v["name$lang"],
                'title' => $v["name$lang"],
                'lazy' => true
              ]) ?>
            </a>
            <div class="info-news-other">
              <a class="name-news-other text-decoration-none" href="<?= $v["slug$lang"] ?>" title="<?= $v["name$lang"] ?>"><?= $v["name$lang"] ?></a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php } ?>
</div>
