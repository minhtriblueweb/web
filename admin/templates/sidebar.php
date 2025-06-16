<?php
$sidebarMenu = [
  [
    'title' => 'Bảng điều khiển',
    'icon' => 'fas fa-tachometer-alt',
    'link' => '?page=dashboard',
    'active' => ['dashboard'],
  ],
  [
    'title' => 'Sản phẩm',
    'icon' => 'fas fa-boxes',
    'children' => [
      [
        'title' => 'Danh mục cấp 1',
        'link' => '?page=category_lv1_list',
        'active' => ['category_lv1_list', 'category_lv1_form']
      ],
      [
        'title' => 'Danh mục cấp 2',
        'link' => '?page=category_lv2_list',
        'active' => ['category_lv2_list', 'category_lv2_form']
      ],
      [
        'title' => 'Sản phẩm',
        'link' => '?page=product_list',
        'active' => ['product_form', 'product_list', 'gallery_list', 'gallery_save']
      ],
    ]
  ],
  [
    'title' => 'Danh sách bài viết',
    'icon' => 'far fa-newspaper',
    'children' => [
      [
        'title' => 'Tin tức',
        'link' => '?page=tintuc_list',
        'active' => ['tintuc_list', 'tintuc_form']
      ],
      [
        'title' => 'Chính sách',
        'link' => '?page=chinhsach_list',
        'active' => ['chinhsach_list', 'chinhsach_form']
      ],
      [
        'title' => 'Tiêu chí',
        'link' => '?page=tieuchi_list',
        'active' => ['tieuchi_list', 'tieuchi_form']
      ],
      [
        'title' => 'Đánh giá khách hàng',
        'link' => '?page=danhgia',
        'active' => ['danhgia', 'themdanhgia', 'suadanhgia']
      ],
      [
        'title' => 'Hướng dẫn chơi',
        'link' => '?page=huongdanchoi_list',
        'active' => ['huongdanchoi_list', 'huongdanchoi_form']
      ],
    ]
  ],
  [
    'title' => 'Quản lý trang tĩnh',
    'icon' => 'fas fa-bookmark',
    'children' => [
      [
        'title' => 'Giới thiệu',
        'link' => '?page=gioithieu',
        'active' => ['gioithieu', 'themgioithieu', 'suagioithieu']
      ],
      [
        'title' => 'Liên hệ',
        'link' => '?page=lienhe',
        'active' => ['lienhe', 'themlienhe', 'sualienhe']
      ],
      [
        'title' => 'Mua hàng',
        'link' => '?page=muahang',
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
        'link' => '?page=logo',
        'active' => ['logo']
      ],
      [
        'title' => 'Watermark',
        'link' => '?page=watermark',
        'active' => ['watermark']
      ],
      [
        'title' => 'Favicon',
        'link' => '?page=favicon',
        'active' => ['favicon']
      ],
      [
        'title' => 'Slideshow',
        'link' => '?page=slideshow',
        'active' => ['slideshow', 'themslideshow', 'suaslideshow']
      ],
      [
        'title' => 'Social',
        'link' => '?page=social',
        'active' => ['social', 'themsocial', 'suasocial']
      ],
      [
        'title' => 'Phương thức thanh toán',
        'link' => '?page=phuongthuctt',
        'active' => ['phuongthuctt', 'themphuongthuctt', 'suaphuongthuctt']
      ]
    ]
  ],
  [
    'title' => 'Thiết lập thông tin',
    'icon' => 'fas fa-cogs',
    'link' => '?page=setting',
    'active' => ['setting']
  ]
];

$currentPage = $_GET['page'] ?? 'dashboard';
?>
<aside class="main-sidebar sidebar-dark-primary elevation-4 text-sm">
  <a class="brand-link" href="?page=dashboard">
    <img class="brand-image" src="./assets/img/logo-blueweb-white.png" alt="blueweb" />
  </a>
  <!-- Sidebar -->
  <div class="sidebar">
    <nav class="mt-3">
      <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent text-sm" data-widget="treeview" role="menu" data-accordion="false">
        <?php foreach ($sidebarMenu as $menu): ?>
          <?php
          $isActiveGroup = false;
          if (!empty($menu['children'])) {
            foreach ($menu['children'] as $child) {
              if (in_array($currentPage, $child['active'])) {
                $isActiveGroup = true;
                break;
              }
            }
          } else {
            $isActiveGroup = in_array($currentPage, $menu['active']);
          }
          ?>
          <li class="nav-item <?php if (!empty($menu['children'])): ?>has-treeview<?php endif; ?> <?php if ($isActiveGroup): ?>menu-open<?php endif; ?>">
            <a class="nav-link <?php if ($isActiveGroup): ?>active<?php endif; ?>" href="<?= $menu['link'] ?? '#' ?>" title="<?= $menu['title'] ?>">
              <i class="nav-icon text-sm <?= $menu['icon'] ?>"></i>
              <p>
                <?= $menu['title'] ?>
                <?php if (!empty($menu['children'])): ?><i class="right fas fa-angle-left"></i><?php endif; ?>
              </p>
            </a>
            <?php if (!empty($menu['children'])): ?>
              <ul class="nav nav-treeview">
                <?php foreach ($menu['children'] as $child): ?>
                  <li class="nav-item">
                    <a class="nav-link <?php if (in_array($currentPage, $child['active'])): ?>active<?php endif; ?>" href="<?= $child['link'] ?>" title="<?= $child['title'] ?>">
                      <i class="nav-icon text-sm far fa-caret-square-right"></i>
                      <p><?= $child['title'] ?></p>
                    </a>
                  </li>
                <?php endforeach; ?>
              </ul>
            <?php endif; ?>
          </li>
        <?php endforeach; ?>
      </ul>
    </nav>
  </div>
</aside>
<div class="content-wrapper">
