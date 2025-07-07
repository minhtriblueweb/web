<?php
// Lấy sản phẩm
$sp_all = $fn->show_data([
  'table'  => 'tbl_product',
  'status' => 'hienthi',
  'select' => "id, file, slug{$lang}, name{$lang}, sale_price, regular_price, views, id_list, id_cat"
]);

// Gom sản phẩm theo id_list và id_cat
$sp_group = [];
foreach ($sp_all ?? [] as $row) {
  $id_list = $row['id_list'];
  $id_cat = $row['id_cat'];
  $sp_group[$id_list]['all'][] = $row;
  if ($id_cat) {
    $sp_group[$id_list]['cat'][$id_cat][] = $row;
  }
}
?>
<div class="wrap-content">
  <?php foreach ($dm_c1_rows as $lv1): ?>
    <?php
    $id_list = $lv1['id'];
    $sp_all = $sp_group[$id_list]['all'] ?? [];
    $cat_list = $lv1['sub'] ?? [];
    ?>
    <?php if (!empty($sp_all)): ?>
      <div class="box-list" data-aos="fade-up" data-aos-duration="500">
        <div class="title-list">
          <h2><span class="text-split"><?= $lv1['name' . $lang] ?></span></h2>
          <div class="box-tab-cat">
            <ul class="tab-cat" data-aos="fade-left" data-aos-duration="500">
              <li><a href="#" class="tab-cat-link active" data-tab="tab-all-<?= $id_list ?>">Tất cả</a></li>
              <?php foreach ($cat_list as $dm_c2): ?>
                <li><a href="#" class="tab-cat-link text-capitalize" data-tab="tab-<?= $dm_c2['id'] ?>"><?= $dm_c2['name' . $lang] ?></a></li>
              <?php endforeach; ?>
            </ul>
            <a class="viewlist" href="<?= $lv1['slug' . $lang] ?>">Xem tất cả</a>
          </div>
        </div>

        <!-- Tab Tất cả -->
        <div class="paging-product-list tabcontent show-fade" id="tab-all-<?= $id_list ?>" style="display: block;">
          <div class="grid-product">
            <?php foreach ($sp_all as $sp): ?>
              <?php
              $name = htmlspecialchars($sp['name' . $lang]);
              ?>
              <div class="item-product">
                <a href="<?= $sp['slug' . $lang] ?>">
                  <div class="images">
                    <?= $fn->getImage([
                      'file' => $sp['file'],
                      'class' => 'w-100',
                      'alt' => $name,
                      'title' => $name
                    ]) ?>
                  </div>
                  <div class="content">
                    <div class="title">
                      <h3><?= $name ?></h3>
                      <p class="price-product">
                        <?php if ($sp['sale_price'] && $sp['regular_price']): ?>
                          <span class="price-new"><?= $sp['sale_price'] ?>₫</span>
                          <span class="price-old"><?= $sp['regular_price'] ?>₫</span>
                        <?php elseif ($sp['regular_price']): ?>
                          <span class="price-new"><?= $sp['regular_price'] ?>₫</span>
                        <?php else: ?>
                          <span class="price-new">Liên hệ</span>
                        <?php endif; ?>
                      </p>
                      <div class="info-product">
                        <p><i class="fa-solid fa-eye"></i> <?= $sp['views'] ?? 0 ?> lượt xem</p>
                        <p><span>Chi tiết</span></p>
                      </div>
                    </div>
                  </div>
                </a>
              </div>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- Tab theo danh mục cấp 2 -->
        <?php foreach ($cat_list as $dm_c2): ?>
          <?php
          $id_cat = $dm_c2['id'];
          $sp_cat = $sp_group[$id_list]['cat'][$id_cat] ?? [];
          ?>
          <div class="paging-product-list tabcontent" id="tab-<?= $id_cat ?>" style="display: none;">
            <div class="grid-product">
              <?php if (!empty($sp_cat)): ?>
                <?php foreach ($sp_cat as $sp): ?>
                  <?php $name = htmlspecialchars($sp['name' . $lang]); ?>
                  <div class="item-product">
                    <a href="<?= $sp['slug' . $lang] ?>">
                      <div class="images">
                        <?= $fn->getImage([
                          'file' => $sp['file'],
                          'class' => 'w-100',
                          'alt' => $name,
                          'title' => $name
                        ]) ?>
                      </div>
                      <div class="content">
                        <div class="title">
                          <h3><?= $name ?></h3>
                          <p class="price-product">
                            <?php if ($sp['sale_price'] && $sp['regular_price']): ?>
                              <span class="price-new"><?= $sp['sale_price'] ?>₫</span>
                              <span class="price-old"><?= $sp['regular_price'] ?>₫</span>
                            <?php elseif ($sp['regular_price']): ?>
                              <span class="price-new"><?= $sp['regular_price'] ?>₫</span>
                            <?php else: ?>
                              <span class="price-new">Liên hệ</span>
                            <?php endif; ?>
                          </p>
                          <div class="info-product">
                            <p><i class="fa-solid fa-eye"></i> <?= $sp['views'] ?? 0 ?> lượt xem</p>
                            <p><span>Chi tiết</span></p>
                          </div>
                        </div>
                      </div>
                    </a>
                  </div>
                <?php endforeach; ?>
              <?php else: ?>
                <p class="alert alert-warning">Không có sản phẩm nào</p>
              <?php endif; ?>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  <?php endforeach; ?>
</div>
