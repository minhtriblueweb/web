<div class="wrap-main wrap-home w-clear">
  <!-- DANH MỤC -->
  <?php
  $list = [];
  $activeSlug = '';
  if (empty($id)) {
    if (empty($idl) && empty($idc) && !empty($productList)) {
      $list = $productList;
    } elseif (!empty($idl) && empty($idc) && !empty($productCat)) {
      $list = $productCat;
    } elseif (!empty($idc) && !empty($productCat_All)) {
      $list = $productCat_All;
      $activeSlug = $productCat["slug$lang"] ?? '';
    }
  }
  if (empty($ids) && ((!empty($list) && empty($idi)) || !empty($idb))) : ?>
    <div class="wrap-product-list">
      <div class="wrap-content">
        <div class="grid-list-no-index">
          <?php if (!empty($list) && empty($idi)) : ?>
            <?php foreach ($list as $v): ?>
              <div class="item-list-noindex <?= ($v["slug$lang"] === $activeSlug ? 'active' : '') ?>">
                <a title="<?= $v["name$lang"] ?>" href="<?= $v["slug$lang"] ?>">
                  <h3 class="m-0 text-capitalize"><?= htmlspecialchars($v["name$lang"]) ?></h3>
                </a>
              </div>
            <?php endforeach; ?>
          <?php elseif (!empty($idb) && !empty($brandAll)) : ?>
            <?php if ($productBrand['icon']) : ?>
              <div class="m-auto mt-3" style="width: 1000px">
                <?= $fn->getImage(['file' => $productBrand['icon'], 'class' => 'w-100', 'alt' => $productBrand["name$lang"], 'title' => $productBrand["name$lang"], 'lazy' => false]) ?>
              </div>
            <?php endif; ?>
          <?php endif; ?>
        </div>
      </div>
    </div>
  <?php endif; ?>

  <!-- TITLE -->
  <div class="title-list-hot text-center mt-3">
    <h2><?= htmlspecialchars(!empty($titleCate) ? $titleCate : sanpham) ?></h2>
  </div>
  <p class="text-center">(<?= $total ?> <?= sanpham ?>)</p>
  <!-- DANH SÁCH SẢN PHẨM -->
  <div class="wrap-main wrap-template w-clear">
    <div class="content-main">
      <div class="row">
        <div class="col-lg-3">
          <div class="othernews">
            <h2 class="titleSide"><span><?= sanpham ?></span></h2>
            <div class="sidebar-menu shadow">
              <ul class="menu-level level-1">
                <?php
                $lists = $db->rawQuery("select id,name{$lang},slug{$lang},file from tbl_product_list where type = 'san-pham' and find_in_set('hienthi',status) order by numb,id desc");
                foreach ($lists as $list): ?>
                  <li>
                    <a class="has-child <?= ($slug == $list["slug$lang"]) ? 'active' : '' ?>" href="<?= $list["slug$lang"] ?>"><?= $list["name$lang"] ?></a>
                    <?php
                    $cats = $db->rawQuery("select id,name{$lang},slug{$lang} from tbl_product_cat where id_list = ? and find_in_set('hienthi',status) order by numb,id desc", [$list['id']]);
                    if (!empty($cats)): ?>
                      <ul class="menu-level level-2">
                        <?php foreach ($cats as $cat): ?>
                          <?php
                          $items = $db->rawQuery("select id,name{$lang},slug{$lang} from tbl_product_item where id_cat = ? and find_in_set('hienthi',status) order by numb,id desc",[$cat['id']]);
                          ?>
                          <li>
                            <a class="<?= !empty($items) ? 'has-child ' : '' ?><?= ($slug == $cat["slug$lang"]) ? 'active' : '' ?>" href="<?= $cat["slug$lang"] ?>">
                              <?= $cat["name$lang"] ?>
                            </a>
                            <?php if (!empty($items)): ?>
                              <ul class="menu-level level-3">
                                <?php foreach ($items as $item): ?>
                                  <?php
                                  $subs = $db->rawQuery("select id,name{$lang},slug{$lang} from tbl_product_sub where id_item = ? and find_in_set('hienthi',status) order by numb,id desc",[$item['id']]);
                                  ?>
                                  <li>
                                    <a class="<?= !empty($subs) ? 'has-child ' : '' ?><?= ($slug == $item["slug$lang"]) ? 'active' : '' ?>" href="<?= $item["slug$lang"] ?>">
                                      <?= $item["name$lang"] ?>
                                    </a>
                                    <?php if (!empty($subs)): ?>
                                      <ul class="menu-level level-4">
                                        <?php foreach ($subs as $sub): ?>
                                          <li>
                                            <a href="<?= $sub["slug$lang"] ?>">
                                              <?= $sub["name$lang"] ?>
                                            </a>
                                          </li>
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
                <?php endforeach; ?>
              </ul>
            </div>
          </div>
        </div>
        <div class="col-lg-9">
          <?php if (!empty($product)): ?>
            <div class="grid-product no-index">
              <?php foreach ($product as $k => $v): ?>
                <div class="col-12" data-aos="fade-up" data-aos-duration="500">
                  <?php include TEMPLATE . LAYOUT . 'item-product.php'; ?>
                </div>
              <?php endforeach; ?>
            </div>
          <?php else: ?>
            <div class="alert alert-warning w-100" role="alert">
              <p class="m-0"><strong><?= noidungdangcapnhat ?></strong></p>
            </div>
          <?php endif; ?>
        </div>
      </div>


      <!-- PHÂN TRANG -->
      <?php if ($paging): ?><div class="mt-3 mb-3 pagination-home w-100"><?= $paging ?></div><?php endif; ?>

      <!-- BÀI VIẾT -->
      <?php if (!empty($contentCate)): ?>
        <div class="content-toggle mt-3 mb-3">
          <div class="content-toggle__body-wrapper">
            <div class="content-toggle__body content-main content-ck pro_tpl" id="toc-content">
              <?= $fn->decodeHtmlChars($contentCate) ?>
            </div>
          </div>
          <p class="content-toggle__button">
            <span class="text">Đọc tiếp bài viết</span>
            <i class="fas fa-chevron-down"></i>
          </p>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>
