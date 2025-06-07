function addClassesToUl($ul, level) {
    $ul.children('li').each(function (index) {
        var $li = $(this);
        var $childUl = $li.find('ul');
        if ($childUl.length > 0) {
            if (!$li.hasClass('menu-child-3') && !$li.hasClass('menu-child-4')) {
                $li.addClass('menu-child-' + level);
            }
            if (!$li.data('level')) {
                $li.attr('data-level', level);
            }

            var $a = $li.children('a');
            if (!$a.next().hasClass('btn-menu-next')) {
                $a.after('<span class="btn-menu-next"><i class="fas fa-chevron-right"></i></span>');
            }

            addClassesToUl($childUl, level + 1);
        }
    });
}
function menuMobile(options) {
    var search = false;
    var lang = false;
    if (typeof options === 'object') {
        if (options.hasOwnProperty('search')) {
            search = options.search;
        }
        if (options.hasOwnProperty('lang')) {
            lang = options.lang;
        }
    }
    $("#menu").prepend('<div class="menu-mobile-title"><div class="btn-menu-prev"><i class="fas fa-chevron-left"></i></div><span>Menu</span></div>');

    $("#menu >ul").addClass("scroll-menu-mobile");

    if (search == true) {
        $("#menu > ul").prepend(
            `<li>
                <div class="box-menu-mobile-search">
                    <div class="menu-mobile-search">
                        <input type="text" id="menu-mobile-keyword" placeholder="${LANG['timkiem']}" onkeypress="doEnter(event,'menu-mobile-keyword');" />
                        <strong onclick="onSearch('menu-mobile-keyword');"><i class="fas fa-search"></i></strong>
                    </div>
                </div>
            </li>`
        );
    }
    if (lang == true) {
        $("#menu > ul").append(
            `<li class="menu-mobile-lang">
                <a href="ngon-ngu/vi/"><img onerror="this.src='thumbs/35x25x1/assets/images/noimage.png';" alt="Tiếng Việt" src="assets/images/vi.jpg"></a>
                <a href="ngon-ngu/en/"><img onerror="this.src='thumbs/35x25x1/assets/images/noimage.png';" alt="Tiếng Anh" src="assets/images/en.jpg"></a>
            </li>`
        );
    }

    $(document).on("click", "#toggle_menu", function () {
        $("body").addClass('menu-mobile-opened');
        $("body").append('<div class="menu-mobile-slide-out"></div>');
        return false;
    });

    $(document).on("click", ".menu-mobile-slide-out", function () {
        $("body").removeClass('menu-mobile-opened');
        $('.menu-mobile-slide-out').remove();
        return false;
    });
    addClassesToUl($('#menu > ul'), 1);

    $(document).on("click", ".btn-menu-next", function () {
        var prev = $(this).parent().attr('class');
        var level = $(this).parent().attr('data-level');
        var title = $(this).parent().children('a').attr('title');
        if (title == undefined) {
            title = $(this).attr('title');
        }
        $("#menu").addClass('opened-parent');
        $(this).parent().addClass('opened-child');
        $('.scroll-menu-mobile').removeClass('scroll-menu-mobile');
        $(this).parent().children('ul').addClass('scroll-menu-mobile');
        if (level == 1) {
            $(".btn-menu-prev").attr({ "data-prev": prev, "data-level": level, "data-title": "Menu" });
        } else {
            var level_prev = level - 1;
            var title_prev = $('.opened-child.menu-child-' + level_prev).children('a').attr('title');
            $(".btn-menu-prev").attr({ "data-prev": prev, "data-level": level, "data-title": title_prev });
        }
        $(".menu-mobile-title span").text(title);
    });
    $(document).on("click", ".btn-menu-prev", function () {
        var prev = $(this).attr('data-prev');
        var level = $(this).attr('data-level') - 1;
        var level_next = level + 1;
        var title = $(this).attr('data-title');
        $('.scroll-menu-mobile').removeClass('scroll-menu-mobile');
        $('.menu-child-' + level_next).parent().addClass('scroll-menu-mobile');
        $(".menu-mobile-title span").text(title);
        $('.' + prev).removeClass('opened-child');
        if (level == 0) {
            $("#menu").removeClass('opened-parent');
            $(this).attr({ "data-title": "Menu" });
        } else {
            var level_prev = level - 1;
            var title_prev = $('.opened-child.menu-child-' + level_prev).children('a').attr('title');
            $(this).attr({ "data-prev": 'menu-child-' + level, "data-level": level, "data-title": title_prev });
        }
        if (level == 1) {
            $(this).attr({ "data-title": "Menu" });
        }
    });
}