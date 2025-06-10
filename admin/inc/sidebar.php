  <?php
  $sidebarMenu = [
    [
      'title' => 'Bảng điều khiển',
      'icon' => 'fas fa-tachometer-alt',
      'link' => 'index.php',
      'active' => ['index'],
    ],
    [
      'title' => 'Sản phẩm',
      'icon' => 'fas fa-boxes',
      'children' => [
        [
          'title' => 'Danh mục cấp 1',
          'link' => 'category_lv1_list.php',
          'active' => ['category_lv1_list', 'category_lv1_form']
        ],
        [
          'title' => 'Danh mục cấp 2',
          'link' => 'category_lv2_list.php',
          'active' => ['category_lv2_list', 'category_lv2_form']
        ],
        [
          'title' => 'Sản phẩm',
          'link' => 'product_list.php',
          'active' => ['product_form', 'product_list', 'gallery', 'them_gallery']
        ],
      ]
    ],
    [
      'title' => 'Danh sách bài viết',
      'icon' => 'far fa-newspaper',
      'children' => [
        [
          'title' => 'Tin tức',
          'link' => 'tintuc.php',
          'active' => ['tintuc', 'themtintuc', 'suatintuc']
        ],
        [
          'title' => 'Chính sách',
          'link' => 'chinhsach.php',
          'active' => ['chinhsach', 'themchinhsach', 'suachinhsach']
        ],
        [
          'title' => 'Tiêu chí',
          'link' => 'tieuchi.php',
          'active' => ['tieuchi', 'themtieuchi', 'suatieuchi']
        ],
        [
          'title' => 'Đánh giá khách hàng',
          'link' => 'danhgia.php',
          'active' => ['danhgia', 'themdanhgia', 'suadanhgia']
        ],
        [
          'title' => 'Hướng dẫn chơi',
          'link' => 'huongdanchoi.php',
          'active' => ['huongdanchoi', 'themhuongdanchoi', 'suahuongdanchoi']
        ],
      ]
    ],
    [
      'title' => 'Quản lý trang tĩnh',
      'icon' => 'fas fa-bookmark',
      'children' => [
        [
          'title' => 'Giới thiệu',
          'link' => 'gioithieu.php',
          'active' => ['gioithieu', 'themgioithieu', 'suagioithieu']
        ],
        [
          'title' => 'Liên hệ',
          'link' => 'lienhe.php',
          'active' => ['lienhe', 'themlienhe', 'sualienhe']
        ],
        [
          'title' => 'Mua hàng',
          'link' => 'muahang.php',
          'active' => ['muahang', 'themmuahang', 'suamuahang']
        ]
      ]
    ],
    [
      'title' => 'Quản lý hình ảnh - video',
      'icon' => 'fas fa-photo-video',
      'children' => [
        [
          'title' => 'Logo',
          'link' => 'logo.php',
          'active' => ['logo']
        ],
        [
          'title' => 'Watermark',
          'link' => 'watermark.php',
          'active' => ['watermark']
        ],
        [
          'title' => 'Favicon',
          'link' => 'favicon.php',
          'active' => ['favicon']
        ],
        [
          'title' => 'Slideshow',
          'link' => 'slideshow.php',
          'active' => ['slideshow', 'themslideshow', 'suaslideshow']
        ],
        [
          'title' => 'Social',
          'link' => 'social.php',
          'active' => ['social', 'themsocial', 'suasocial']
        ],
        [
          'title' => 'Phương thức thanh toán',
          'link' => 'phuongthuctt.php',
          'active' => ['phuongthuctt', 'themphuongthuctt', 'suaphuongthuctt']
        ]
      ]
    ],
    [
      'title' => 'Thiết lập thông tin',
      'icon' => 'fas fa-cogs',
      'link' => 'setting.php',
      'active' => ['setting']
    ]
  ];
  ?>
  <aside class="main-sidebar sidebar-dark-primary elevation-4 text-sm">
    <a class="brand-link" href="index.php">
      <img class="brand-image" src="./assets/img/logo-blueweb-white.png" alt="blueweb" />
    </a>
    <!-- Sidebar -->
    <div class="sidebar">
      <nav class="mt-3">
        <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent text-sm" data-widget="treeview" role="menu"
          data-accordion="false">
          <?php
          $currentPage = basename($_SERVER['PHP_SELF'], '.php');
          ?>
          <?php foreach ($sidebarMenu as $menu): ?>
            <?php if (!empty($menu['children'])): ?>
              <li class="nav-item has-treeview menu-group">
                <a class="nav-link" href="#" title="<?= $menu['title'] ?>">
                  <i class="nav-icon text-sm <?= $menu['icon'] ?>"></i>
                  <p><?= $menu['title'] ?> <i class="right fas fa-angle-left"></i></p>
                </a>
                <ul class="nav nav-treeview">
                  <?php foreach ($menu['children'] as $child): ?>
                    <li class="nav-item">
                      <a class="nav-link" href="<?= $child['link'] ?>" title="<?= $child['title'] ?>"
                        data-active="<?= implode(',', $child['active']) ?>">
                        <i class="nav-icon text-sm far fa-caret-square-right"></i>
                        <p><?= $child['title'] ?></p>
                      </a>
                    </li>
                  <?php endforeach; ?>
                </ul>
              </li>
            <?php else: ?>
              <li class="nav-item">
                <a class="nav-link" href="<?= $menu['link'] ?>" title="<?= $menu['title'] ?>"
                  data-active="<?= implode(',', (array)$menu['active']) ?>">
                  <i class="nav-icon text-sm <?= $menu['icon'] ?>"></i>
                  <p><?= $menu['title'] ?></p>
                </a>
              </li>
            <?php endif; ?>
          <?php endforeach; ?>

        </ul>
      </nav>
    </div>
  </aside>
  <div class="content-wrapper">