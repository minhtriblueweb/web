<?php
$dm_c1_all = $fn->show_data([
  'table' => 'tbl_danhmuc_c1',
  'status' => 'hienthi,noibat'
]);

$dm_c2_all = $fn->show_data([
  'table' => 'tbl_danhmuc_c2',
  'status' => 'hienthi,noibat'
]);
$dm_c2_group = [];
if ($dm_c2_all && $dm_c2_all->num_rows > 0) {
  while ($row = $dm_c2_all->fetch_assoc()) {
    $dm_c2_group[$row['id_list']][] = $row;
  }
}
$dm_c1_rows = [];
if ($dm_c1_all && $dm_c1_all->num_rows > 0) {
  while ($lv1 = $dm_c1_all->fetch_assoc()) {
    $lv1['sub'] = $dm_c2_group[$lv1['id']] ?? [];
    $menu_tree[] = $lv1;
    $dm_c1_rows[] = $lv1;
  }
}

// Gọt sản phẩm theo nhóm
$sp_all = $fn->show_data([
  'table' => 'tbl_sanpham',
  'status' => 'hienthi'
]);
$sp_group = [];
if ($sp_all && $sp_all->num_rows > 0) {
  while ($row = $sp_all->fetch_assoc()) {
    $id_list = $row['id_list'];
    $id_cat = $row['id_cat'];
    $sp_group[$id_list]['all'][] = $row;
    if ($id_cat) {
      $sp_group[$id_list]['cat'][$id_cat][] = $row;
    }
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
          <h2><span class="text-split"><?= $lv1['namevi'] ?></span></h2>
          <div class="box-tab-cat">
            <ul class="tab-cat" data-aos="fade-left" data-aos-duration="500">
              <li><a href="#" class="tab-cat-link active" data-tab="tab-all-<?= $id_list ?>">Tất cả</a></li>
              <?php foreach ($cat_list as $dm_c2): ?>
                <li><a href="#" class="tab-cat-link text-capitalize" data-tab="tab-<?= $dm_c2['id'] ?>"><?= $dm_c2['namevi'] ?></a></li>
              <?php endforeach; ?>
            </ul>
            <a class="viewlist" href="<?= $lv1['slugvi'] ?>">Xem tất cả</a>
          </div>
        </div>

        <!-- Tab Tất cả -->
        <div class="paging-product-list tabcontent show-fade" id="tab-all-<?= $id_list ?>" style="display: block;">
          <div class="grid-product">
            <?php foreach ($sp_all as $sp): ?>
              <?php
              $name = htmlspecialchars($sp['namevi']);
              ?>
              <div class="item-product">
                <a href="<?= $sp['slugvi'] ?>">
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
                  <?php
                  $name = htmlspecialchars($sp['namevi']);
                  $img = !empty($sp['file']) ? BASE_ADMIN . UPLOADS . $sp['file'] : NO_IMG;
                  ?>
                  <div class="item-product">
                    <a href="<?= $sp['slugvi'] ?>">
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
