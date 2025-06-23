<div class="wrap-content">
  <?php $show_danhmuc = $fn->show_data([
    'table' => 'tbl_danhmuc_c1',
    'status' => 'hienthi,noibat'
  ]); ?>

  <?php if ($show_danhmuc): ?>
    <?php while ($danhmuc_lv1 = $show_danhmuc->fetch_assoc()): ?>
      <?php
      $id_list = $danhmuc_lv1['id'];
      $sp_all = $fn->show_data([
        'table' => 'tbl_sanpham',
        'status' => 'hienthi',
        'id_list' => $id_list
      ]);
      $danhmuc_c2 = $fn->show_data([
        'table' => 'tbl_danhmuc_c2',
        'status' => 'hienthi',
        'id_list' => $id_list
      ]);
      ?>

      <?php if ($sp_all && $sp_all->num_rows > 0): ?>
        <div class="box-list" data-aos="fade-up" data-aos-duration="1000">
          <div class="title-list">
            <h2><span class="text-split"><?= $danhmuc_lv1['namevi'] ?></span></h2>
            <div class="box-tab-cat">
              <ul class="tab-cat" data-aos="fade-left" data-aos-duration="500">
                <li><a href="#" class="tab-cat-link active" data-tab="tab-all-<?= $id_list ?>">Tất cả</a></li>
                <?php if ($danhmuc_c2): ?>
                  <?php while ($dm_c2 = $danhmuc_c2->fetch_assoc()): ?>
                    <li>
                      <a href="#" class="tab-cat-link" data-tab="tab-<?= $dm_c2['id'] ?>"><?= $dm_c2['namevi'] ?></a>
                    </li>
                  <?php endwhile; ?>
                <?php endif; ?>
              </ul>
              <a class="viewlist" href="<?= $danhmuc_lv1['slugvi'] ?>">Xem tất cả</a>
            </div>
          </div>

          <!-- Tab Tất cả -->
          <div class="paging-product-list tabcontent show-fade" id="tab-all-<?= $id_list ?>" style="display: block;">
            <div class="grid-product">
              <?php while ($sp = $sp_all->fetch_assoc()): ?>
                <?php
                $slugvi = $sp['slugvi'];
                $namevi = htmlspecialchars($sp['namevi']);
                $img = !empty($sp['file']) ? BASE_ADMIN . UPLOADS . $sp['file'] : NO_IMG;
                $sale = $sp['sale_price'] ?? '';
                $regular = $sp['regular_price'] ?? '';
                $views = $sp['views'] ?? 0;
                ?>
                <div class="item-product">
                  <a href="<?= $slugvi ?>">
                    <div class="images"><img src="<?= $img ?>" alt="<?= $namevi ?>" title="<?= $namevi ?>" class="w-100" /></div>
                    <div class="content">
                      <div class="title">
                        <h3><?= $namevi ?></h3>
                        <p class="price-product">
                          <?php if ($sale && $regular): ?>
                            <span class="price-new"><?= $sale ?>₫</span>
                            <span class="price-old"><?= $regular ?>₫</span>
                          <?php elseif ($regular): ?>
                            <span class="price-new"><?= $regular ?>₫</span>
                          <?php else: ?>
                            <span class="price-new">Liên hệ</span>
                          <?php endif; ?>
                        </p>
                        <div class="info-product">
                          <p><i class="fa-solid fa-eye"></i> <?= $views ?> lượt xem</p>
                          <p><span>Chi tiết</span></p>
                        </div>
                      </div>
                    </div>
                  </a>
                </div>
              <?php endwhile; ?>
            </div>
          </div>

          <!-- Tab theo từng danh mục cấp 2 -->
          <?php
          $danhmuc_c2 = $fn->show_data([
            'table' => 'tbl_danhmuc_c2',
            'status' => 'hienthi',
            'id_list' => $id_list
          ]);
          if ($danhmuc_c2):
            while ($dm_c2 = $danhmuc_c2->fetch_assoc()):
              $id_cat = $dm_c2['id'];
              $sp_cat = $fn->show_data([
                'table' => 'tbl_sanpham',
                'status' => 'hienthi',
                'id_list' => $id_list,
                'id_cat' => $id_cat
              ]);
          ?>
              <div class="paging-product-list tabcontent" id="tab-<?= $id_cat ?>" style="display: none;">
                <div class="grid-product">
                  <?php if ($sp_cat && $sp_cat->num_rows > 0): ?>
                    <?php while ($sp = $sp_cat->fetch_assoc()): ?>
                      <?php
                      $slugvi = $sp['slugvi'];
                      $namevi = htmlspecialchars($sp['namevi']);
                      $img = !empty($sp['file']) ? BASE_ADMIN . UPLOADS . $sp['file'] : NO_IMG;
                      $sale = $sp['sale_price'] ?? '';
                      $regular = $sp['regular_price'] ?? '';
                      $views = $sp['views'] ?? 0;
                      ?>
                      <div class="item-product">
                        <a href="<?= $slugvi ?>">
                          <div class="images"><img src="<?= $img ?>" alt="<?= $namevi ?>" title="<?= $namevi ?>" class="w-100" /></div>
                          <div class="content">
                            <div class="title">
                              <h3><?= $namevi ?></h3>
                              <p class="price-product">
                                <?php if ($sale && $regular): ?>
                                  <span class="price-new"><?= $sale ?>₫</span>
                                  <span class="price-old"><?= $regular ?>₫</span>
                                <?php elseif ($regular): ?>
                                  <span class="price-new"><?= $regular ?>₫</span>
                                <?php else: ?>
                                  <span class="price-new">Liên hệ</span>
                                <?php endif; ?>
                              </p>
                              <div class="info-product">
                                <p><i class="fa-solid fa-eye"></i> <?= $views ?> lượt xem</p>
                                <p><span>Chi tiết</span></p>
                              </div>
                            </div>
                          </div>
                        </a>
                      </div>
                    <?php endwhile; ?>
                  <?php else: ?>
                    <p class="alert alert-warning">Không có sản phẩm nào</p>
                  <?php endif; ?>
                </div>
              </div>
            <?php endwhile; ?>
          <?php endif; ?>
        </div>
      <?php endif; ?>
    <?php endwhile; ?>
  <?php endif; ?>
</div>
