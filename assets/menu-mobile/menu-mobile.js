function menuMobile(options = {}) {
  const search = options.search || false;
  const lang = options.lang || false;

  const $menu = $("#menu");
  const $menuUl = $("#menu > ul");

  // Chỉ thêm 1 lần duy nhất
  if (!$menu.hasClass("menu-inited")) {
    $menu.addClass("menu-inited");

    // Tiêu đề
    $menu.prepend(`<div class="menu-mobile-title"><span>Menu</span></div>`);

    // Tìm kiếm
    if (search) {
      $menuUl.prepend(`
        <li>
          <div class="box-menu-mobile-search">
            <div class="menu-mobile-search">
              <input type="text" id="menu-mobile-keyword" placeholder="${LANG['timkiem']}" onkeypress="doEnter(event,'menu-mobile-keyword');" />
              <strong onclick="onSearch('menu-mobile-keyword');"><i class="fas fa-search"></i></strong>
            </div>
          </div>
        </li>
      `);
    }

    // Ngôn ngữ
    if (lang) {
      $menuUl.append(`
        <li class="menu-mobile-lang">
          <a href="ngon-ngu/vi/"><img src="assets/images/vi.jpg" onerror="this.src='thumbs/35x25x1/assets/images/noimage.png';" alt="Tiếng Việt"></a>
          <a href="ngon-ngu/en/"><img src="assets/images/en.jpg" onerror="this.src='thumbs/35x25x1/assets/images/noimage.png';" alt="Tiếng Anh"></a>
        </li>
      `);
    }

    // Mở menu
    $(document).on("click", "#toggle_menu", function () {
      $("body").addClass("menu-mobile-opened").append('<div class="menu-mobile-slide-out"></div>');
      return false;
    });

    // Đóng menu
    $(document).on("click", ".menu-mobile-slide-out", function () {
      $("body").removeClass("menu-mobile-opened");
      $(".menu-mobile-slide-out").remove();
      return false;
    });

    // Bắt submenu toggle 1 lần duy nhất
    $(document).on("click", ".btn-toggle-submenu", function (e) {
      e.preventDefault();
      const $li = $(this).closest("li");
      const $submenu = $li.children("ul").first();

      if ($submenu.is(":visible")) {
        $submenu.slideUp(300, () => $li.removeClass("opened"));
      } else {
        $submenu.slideDown(300, () => $li.addClass("opened"));
      }
    });
  }

  // Gán toggle submenu cho tất cả cấp
  function addToggle($ul) {
    $ul.children("li").each(function () {
      const $li = $(this);
      const $childUl = $li.children("ul");

      if ($childUl.length > 0) {
        $li.addClass("has-submenu");
        $childUl.hide();

        if ($li.children(".btn-toggle-submenu").length === 0) {
          const $a = $li.children("a");
          $a.wrap('<div class="menu-item-wrapper"></div>');
          $a.after('<span class="btn-toggle-submenu"><i class="fas fa-chevron-down"></i></span>');
        }

        addToggle($childUl);
      }
    });
  }

  addToggle($menuUl);
}
