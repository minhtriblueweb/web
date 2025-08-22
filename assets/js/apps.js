/* Validation form */
validateForm("validation-newsletter");
validateForm("validation-cart");
validateForm("validation-user");
validateForm("validation-contact");
AOS.init({
  offset: window.innerWidth <= 768 ? 60 : 120,
  duration: 600,
  easing: 'ease-in-out',
  once: true,
});
NN_FRAMEWORK.Common = function () {
  $(".content-ck iframe,.content-ck embed").each(function (e, n) {
    $(this).wrap("<div class='video-container'></div>");
  });
  $(".content-ck table").each(function (e, t) {
    $(this).wrap("<div class='table-responsive'></div>");
  });
};

$(document).ready(function () {
  $('.tab-cat-link').on('click', function (e) {
    e.preventDefault();
    var $this = $(this);
    var tabId = $this.data('tab');
    var $boxList = $this.closest('.box-list');
    $boxList.find('.tab-cat-link').removeClass('active');
    $this.addClass('active');
    $boxList.find('.tabcontent').removeClass('show-fade').hide();
    var $currentTab = $boxList.find('#' + tabId);
    $currentTab.show();
    setTimeout(function () {
      $currentTab.addClass('show-fade');
    }, 20);
  });
});

/* Lazys */
NN_FRAMEWORK.Lazys = function () {
  if (isExist($(".lazy"))) {
    var lazyLoadInstance = new LazyLoad({
      elements_selector: ".lazy",
    });
  }
};

/* menu-bar */
$(function () {
  var $menu = $(".menu-bar");
  var $links = $menu.find("a.transition");
  var $activeReal = $links.filter(".active");
  $menu.on("mouseenter", "li", function () {
    var $link = $(this).children("a.transition");
    if ($link.length) {
      $links.removeClass("active");
      $link.addClass("active");
    }
  });
  $menu.on("mouseleave", function () {
    $links.removeClass("active");
    if ($activeReal.length) {
      $activeReal.addClass("active");
    }
  });
});

/* tabsProDetail */
$(function () {
  var $tabs = $("#tabsProDetail .nav-link");
  var $indicator = $("#tabsProDetail");
  function moveIndicator($el) {
    var left = $el.position().left;
    var width = $el.outerWidth();
    $indicator.css("--indicator-left", left + "px");
    $indicator.css("--indicator-width", width + "px");
  }
  $("<style>")
    .prop("type", "text/css")
    .html(`
      #tabsProDetail::after {
        left: var(--indicator-left, 0);
        width: var(--indicator-width, 0);
      }
    `)
    .appendTo("head");
  var $active = $("#tabsProDetail .nav-link.active");
  if ($active.length) moveIndicator($active);
  $tabs.on("mouseenter", function () {
    moveIndicator($(this));
  });
  $tabs.on("click", function () {
    $tabs.removeClass("active");
    $(this).addClass("active");
    moveIndicator($(this));
  });
  $("#tabsProDetail").on("mouseleave", function () {
    moveIndicator($("#tabsProDetail .nav-link.active"));
  });
});

/* Xem thêm , ẩn bớt nội dung */
$(function () {
  const collapsedHeight = 300;
  $(".content-toggle").each(function () {
    const $container = $(this);
    const $body = $container.find(".content-toggle__body");
    const $btn = $container.find(".content-toggle__button");
    const $text = $btn.find(".text");
    if (!$body.length) return;
    const fullHeight = $body[0].scrollHeight;
    if (fullHeight > collapsedHeight) {
      $body.css({
        "max-height": collapsedHeight + "px",
        "overflow": "hidden",
        "transition": "max-height 0.4s ease"
      }).append('<div class="content-toggle__fade"></div>');
      $btn.on("click", function () {
        if ($container.hasClass("content-toggle--active")) {
          $body.css("max-height", collapsedHeight + "px");
          $container.removeClass("content-toggle--active");
          $text.text("Đọc tiếp bài viết");
        } else {
          $body.css("max-height", fullHeight + "px");
          $container.addClass("content-toggle--active");
          $text.text("Rút gọn");
        }
      });
    } else {
      $btn.hide();
    }
  });
});

/* Back to top */
NN_FRAMEWORK.GoTop = function () {
  "use strict";
  var progressPath = document.querySelector(".progress-wrap path");
  var pathLength = progressPath.getTotalLength();
  progressPath.style.transition = progressPath.style.WebkitTransition = "none";
  progressPath.style.strokeDasharray = pathLength + " " + pathLength;
  progressPath.style.strokeDashoffset = pathLength;
  progressPath.getBoundingClientRect();
  progressPath.style.transition = progressPath.style.WebkitTransition =
    "stroke-dashoffset 10ms linear";
  var updateProgress = function () {
    var scroll = $(window).scrollTop();
    var height = $(document).height() - $(window).height();
    var progress = pathLength - (scroll * pathLength) / height;
    progressPath.style.strokeDashoffset = progress;
  };
  updateProgress();
  $(window).scroll(updateProgress);
  var socialFixed = $(".social-contact");
  var offset = 100;
  var duration = 0;
  $(window).on("scroll", function () {
    if ($(this).scrollTop() > offset) {
      $(".progress-wrap").addClass("active-progress");
      socialFixed.addClass("visible");
    } else {
      $(".progress-wrap").removeClass("active-progress");
      socialFixed.removeClass("visible");
    }
  });
  $(".progress-wrap").on("click", function (event) {
    event.preventDefault();
    $("html, body").animate({ scrollTop: 0 }, duration);
    return false;
  });
};

/* Alt images */
NN_FRAMEWORK.AltImg = function () {
  $("img").each(function (index, element) {
    if (!$(this).attr("alt") || $(this).attr("alt") == "") {
      $(this).attr("alt", WEBSITE_NAME);
    }
  });
};

/* Menu */
NN_FRAMEWORK.Menu = function () {
  /* Menu remove empty ul */
  if (isExist($(".menu"))) {
    $(".menu ul li a").each(function () {
      $this = $(this);
      if (!isExist($this.next("ul").find("li"))) {
        $this.next("ul").remove();
        $this.removeClass("has-child");
      }
    });
  }
  $(".menu-bar li ul li").hover(function () {
    var vitri = $(this).position().top;
    $(this)
      .children(".sub_menu")
      .css({ top: vitri + "px" });
  });
  /* Mmenu */
  if (isExist($("nav#menu"))) {
    menuMobile({ search: false, lang: false });
  }
};


/* MenuFixed */
NN_FRAMEWORK.MenuFixed = function () {
  let isFixed = false;
  const $win = $(window);
  const $menuFixed = $(".menu-fixed, .menu-mobile-fixed");
  const $logoFixed = $(".menu-mobile-fixed .logo-mobile");
  const $logoOrigin = $(".menu-mobile .logo-mobile");
  $win.on("scroll", function () {
    const scrollTop = $win.scrollTop();
    const headerHeight = $(".header").outerHeight();
    if (scrollTop > headerHeight && !isFixed) {
      $menuFixed.addClass("animate show");
      $logoFixed.addClass("shrink");
      $logoOrigin.addClass("shrink");
      isFixed = true;
    } else if (scrollTop <= headerHeight && isFixed) {
      $menuFixed.removeClass("show");
      $logoFixed.removeClass("shrink");
      $logoOrigin.removeClass("shrink");
      setTimeout(() => {
        $menuFixed.removeClass("animate");
      }, 10);
      isFixed = false;
    }
  });
};

/* Wow */
NN_FRAMEWORK.Wows = function () {
  new WOW().init();
};

/* Photobox */
NN_FRAMEWORK.Photobox = function () {
  if (isExist($(".album-gallery"))) {
    $(".album-gallery").photobox("a", { thumbs: true, loop: false });
  }
};

/* Videos */
NN_FRAMEWORK.Videos = function () {
  Fancybox.bind("[data-fancybox]", {});
};

/* Owl Data */
NN_FRAMEWORK.OwlData = function (obj) {
  if (!isExist(obj)) return false;
  var items = obj.attr("data-items");
  var rewind = Number(obj.attr("data-rewind")) ? true : false;
  var autoplay = Number(obj.attr("data-autoplay")) ? true : false;
  var loop = Number(obj.attr("data-loop")) ? true : false;
  var lazyLoad = Number(obj.attr("data-lazyload")) ? true : false;
  var mouseDrag = Number(obj.attr("data-mousedrag")) ? true : false;
  var touchDrag = Number(obj.attr("data-touchdrag")) ? true : false;
  var animations = obj.attr("data-animations") || false;
  var smartSpeed = Number(obj.attr("data-smartspeed")) || 800;
  var autoplaySpeed = Number(obj.attr("data-autoplayspeed")) || 800;
  var autoplayTimeout = Number(obj.attr("data-autoplaytimeout")) || 5000;
  var dots = Number(obj.attr("data-dots")) ? true : false;
  var responsive = {};
  var responsiveClass = true;
  var responsiveRefreshRate = 200;
  var nav = Number(obj.attr("data-nav")) ? true : false;
  var navContainer = obj.attr("data-navcontainer") || false;
  var navTextTemp =
    "<svg xmlns='http://www.w3.org/2000/svg' class='icon icon-tabler icon-tabler-chevron-left' width='44' height='45' viewBox='0 0 24 24' stroke-width='1.5' stroke='#2c3e50' fill='none' stroke-linecap='round' stroke-linejoin='round'><path stroke='none' d='M0 0h24v24H0z' fill='none'/><polyline points='15 6 9 12 15 18' /></svg>|<svg xmlns='http://www.w3.org/2000/svg' class='icon icon-tabler icon-tabler-chevron-right' width='44' height='45' viewBox='0 0 24 24' stroke-width='1.5' stroke='#2c3e50' fill='none' stroke-linecap='round' stroke-linejoin='round'><path stroke='none' d='M0 0h24v24H0z' fill='none'/><polyline points='9 6 15 12 9 18' /></svg>";
  var navText = obj.attr("data-navtext");
  navText =
    nav &&
    navContainer &&
    (((navText === undefined || Number(navText)) && navTextTemp) ||
      (isNaN(Number(navText)) && navText) ||
      (Number(navText) === 0 && false));

  if (items) {
    items = items.split(",");

    if (items.length) {
      var itemsCount = items.length;

      for (var i = 0; i < itemsCount; i++) {
        var options = items[i].split("|"),
          optionsCount = options.length,
          responsiveKey;

        for (var j = 0; j < optionsCount; j++) {
          const attr = options[j].indexOf(":")
            ? options[j].split(":")
            : options[j];

          if (attr[0] === "screen") {
            responsiveKey = Number(attr[1]);
          } else if (Number(responsiveKey) >= 0) {
            responsive[responsiveKey] = {
              ...responsive[responsiveKey],
              [attr[0]]: (isNumeric(attr[1]) && Number(attr[1])) ?? attr[1],
            };
          }
        }
      }
    }
  }

  if (nav && navText) {
    navText =
      navText.indexOf("|") > 0 ? navText.split("|") : navText.split(":");
    navText = [navText[0], navText[1]];
  }

  obj.owlCarousel({
    rewind,
    autoplay,
    loop,
    lazyLoad,
    mouseDrag,
    touchDrag,
    smartSpeed,
    autoplaySpeed,
    autoplayTimeout,
    dots,
    nav,
    navText,
    navContainer: nav && navText && navContainer,
    responsiveClass,
    responsiveRefreshRate,
    responsive,
  });

  if (autoplay) {
    obj.on("translate.owl.carousel", function (event) {
      obj.trigger("stop.owl.autoplay");
    });

    obj.on("translated.owl.carousel", function (event) {
      obj.trigger("play.owl.autoplay", [autoplayTimeout]);
    });
  }

  if (animations && isExist(obj.find("[owl-item-animation]"))) {
    var animation_now = "";
    var animation_count = 0;
    var animations_excuted = [];
    var animations_list = animations.indexOf(",")
      ? animations.split(",")
      : animations;

    obj.on("changed.owl.carousel", function (event) {
      $(this)
        .find(".owl-item.active")
        .find("[owl-item-animation]")
        .removeClass(animation_now);
    });

    obj.on("translate.owl.carousel", function (event) {
      var item = event.item.index;

      if (Array.isArray(animations_list)) {
        var animation_trim = animations_list[animation_count].trim();

        if (!animations_excuted.includes(animation_trim)) {
          animation_now = "animate__animated " + animation_trim;
          animations_excuted.push(animation_trim);
          animation_count++;
        }

        if (animations_excuted.length == animations_list.length) {
          animation_count = 0;
          animations_excuted = [];
        }
      } else {
        animation_now = "animate__animated " + animations_list.trim();
      }
      $(this)
        .find(".owl-item")
        .eq(item)
        .find("[owl-item-animation]")
        .addClass(animation_now);
    });
  }
};

/* Owl Page */
NN_FRAMEWORK.OwlPage = function () {
  if (isExist($(".owl-page"))) {
    $(".owl-page").each(function () {
      NN_FRAMEWORK.OwlData($(this));
    });
  }
};

/* Dom Change */
NN_FRAMEWORK.DomChange = function () {
  /* Video Fotorama */
  $("#video-fotorama").one("DOMSubtreeModified", function () {
    $("#fotorama-videos").fotorama();
  });

  /* Video Select */
  $("#video-select").one("DOMSubtreeModified", function () {
    $(".listvideos").change(function () {
      var id = $(this).val();
      $.ajax({
        url: "api/video.php",
        type: "POST",
        dataType: "html",
        data: {
          id: id,
        },
        beforeSend: function () {
          holdonOpen();
        },
        success: function (result) {
          $(".video-main").html(result);
          holdonClose();
        },
      });
    });
  });

  /* Chat Facebook */
  $("#messages-facebook").one("DOMSubtreeModified", function () {
    $(".js-facebook-messenger-box").on("click", function () {
      $(
        ".js-facebook-messenger-box, .js-facebook-messenger-container"
      ).toggleClass("open"),
        $(".js-facebook-messenger-tooltip").length &&
        $(".js-facebook-messenger-tooltip").toggle();
    }),
      $(".js-facebook-messenger-box").hasClass("cfm") &&
      setTimeout(function () {
        $(".js-facebook-messenger-box").addClass("rubberBand animated");
      }, 3500),
      $(".js-facebook-messenger-tooltip").length &&
      ($(".js-facebook-messenger-tooltip").hasClass("fixed")
        ? $(".js-facebook-messenger-tooltip").show()
        : $(".js-facebook-messenger-box").on("hover", function () {
          $(".js-facebook-messenger-tooltip").show();
        }),
        $(".js-facebook-messenger-close-tooltip").on("click", function () {
          $(".js-facebook-messenger-tooltip").addClass("closed");
        }));
    $(".search_open").click(function () {
      $(".search_box_hide").toggleClass("opening");
    });
  });
};

/* Slick */
NN_FRAMEWORK.SlickPage = function () {
  if (isExist($(".slick-product"))) {
    $(".slick-product").slick({
      dots: false,
      arrows: false,
      autoplay: true,
      infinite: true,
      verticalSwiping: false,
      slidesToShow: 5,
      slidesToScroll: 1,
      centerMode: false,
      vertical: false,
      swipeToSlide: true,
      responsive: [
        {
          breakpoint: 770,
          settings: {
            arrows: false,
            slidesToShow: 2,
          },
        },
        {
          breakpoint: 655,
          settings: {
            arrows: false,
            slidesToShow: 2,
          },
        },
        {
          breakpoint: 325,
          settings: {
            arrows: false,
            slidesToShow: 2,
          },
        },
      ],
    });
  }
  if (isExist($(".slick-slideshow"))) {
    $('.slick-slideshow').slick({
      slidesToShow: 1,
      slidesToScroll: 1,
      dots: false,
      arrows: false,
      autoplay: true,
      autoplaySpeed: 5000,
      speed: 800,
      infinite: false,
      pauseOnHover: true,
      adaptiveHeight: true,
      fade: true
    });
  }
  if (isExist($(".slick-service"))) {
    $(".slick-service").slick({
      dots: false,
      arrows: false,
      autoplay: true,
      infinite: true,
      verticalSwiping: false,
      slidesToShow: 3,
      slidesToScroll: 1,
      centerMode: false,
      vertical: false,
      swipeToSlide: true,
      responsive: [
        {
          breakpoint: 770,
          settings: {
            arrows: false,
            slidesToShow: 3,
          },
        },
        {
          breakpoint: 695,
          settings: {
            arrows: false,
            slidesToShow: 2,
          },
        },
        {
          breakpoint: 375,
          settings: {
            arrows: false,
            slidesToShow: 1,
          },
        },
      ],
    });
  }
  if (isExist($(".slick-feedback"))) {
    $(".slick-feedback").slick({
      dots: false,
      arrows: true,
      prevArrow:
        "<span class='slick-arr'><i class='fa-solid fa-arrow-left'></i></span>",
      nextArrow:
        "<span class='slick-arr arr-next'><i class='fa-solid fa-arrow-right'></i></span>",
      autoplay: true,
      infinite: true,
      verticalSwiping: false,
      slidesToShow: 2,
      slidesToScroll: 1,
      centerMode: false,
      vertical: false,
      swipeToSlide: true,
      responsive: [
        {
          breakpoint: 770,
          settings: {
            arrows: false,
          },
        },
        {
          breakpoint: 655,
          settings: {
            arrows: false,
            slidesToShow: 1, fade: true,
          },
        },
      ],
    });
  }

  $(".slick-tab-cat").slick({
    slidesToShow: 4,
    slidesToScroll: 4,
    infinite: false,
    autoplay: false,
    speed: 1000,
    arrows: true,
    prevArrow: '<button type="button" class="slick-tab-cat-prev"><i class="fa fa-angle-left"></i></button>',
    nextArrow: '<button type="button" class="slick-tab-cat-next"><i class="fa fa-angle-right"></i></button>',
    dots: false,
    variableWidth: false,
    responsive: [
      {
        breakpoint: 768,
        settings: {
          slidesToShow: 3,
          slidesToScroll: 3
        }
      },
      {
        breakpoint: 480,
        settings: {
          slidesToShow: 2,
          slidesToScroll: 2
        }
      }
    ]
  });


  if (isExist($(".slick-col-right"))) {
    $(".slick-col-right").slick({
      dots: false,
      arrows: false,
      autoplay: true,
      infinite: true,
      verticalSwiping: true,
      slidesToShow: 3,
      slidesToScroll: 1,
      centerMode: false,
      vertical: true,
      swipeToSlide: true,
      responsive: [
        {
          breakpoint: 770,
          settings: {
            arrows: false,
            slidesToShow: 3,
          },
        },
        {
          breakpoint: 475,
          settings: {
            arrows: false,
            slidesToShow: 2,
          },
        },
        {
          breakpoint: 375,
          settings: {
            arrows: false,
            slidesToShow: 1,
          },
        },
      ],
    });
  }
  if (isExist($(".slick-brand"))) {
    $(".slick-brand").slick({
      slidesToShow: 6,
      slidesToScroll: 4,
      arrows: false,
      dots: true,
      autoplay: true,
      infinite: true,
      speed: 500,
      responsive: [
        {
          breakpoint: 992,
          settings: {
            slidesToShow: 6
          }
        },
        {
          breakpoint: 768,
          settings: {
            slidesToShow: 4
          }
        },
        {
          breakpoint: 480,
          settings: {
            slidesToShow: 2
          }
        }
      ]
    });
  }

  if (isExist($(".slick-pro-detail"))) {
    $(".slick-pro-detail").slick({
      slidesToShow: 4,
      slidesToScroll: 1,
      arrows: true,
      prevArrow: $(".slick-prev-btn"),
      nextArrow: $(".slick-next-btn"),
      dots: false,
      infinite: true,
      speed: 500,
      responsive: [
        {
          breakpoint: 992,
          settings: {
            slidesToShow: 3
          }
        },
        {
          breakpoint: 768,
          settings: {
            slidesToShow: 4
          }
        },
        {
          breakpoint: 480,
          settings: {
            slidesToShow: 4
          }
        }
      ]
    });
  }

  if (isExist($(".slick_product_list"))) {
    var $slider = $('.slick_product_list');
    $slider.slick({
      dots: false,
      arrows: true,
      prevArrow:
        "<span class='slick-arr-product_list slick-product-prev'><i class='fa-solid fa-chevron-left'></i></span>",
      nextArrow:
        "<span class='slick-arr-product_list slick-product-next arr-next'><i class='fa-solid fa-chevron-right'></i></span>",
      autoplay: false,
      infinite: false,
      slidesToShow: 7,
      slidesToScroll: 2,
      rows: 2,
      responsive: [
        {
          breakpoint: 1024,
          settings: {
            slidesToShow: 7,
            slidesToScroll: 3,
            rows: 2
          }
        },
        {
          breakpoint: 800,
          settings: {
            slidesToShow: 3,
            slidesToScroll: 2,
            rows: 2
          }
        },
        {
          breakpoint: 480,
          settings: {
            slidesToShow: 3,
            slidesToScroll: 1,
            rows: 2
          }
        }
      ]
    });
    function updateArrows(currentSlide) {
      var slickObj = $slider.slick("getSlick");
      var totalSlides = slickObj.slideCount;
      var slidesToShow = slickObj.options.slidesToShow;
      if (currentSlide === 0) {
        $('.slick-product-prev').addClass('disabled');
      } else {
        $('.slick-product-prev').removeClass('disabled');
      }
      if (currentSlide >= totalSlides - slidesToShow) {
        $('.slick-product-next').addClass('disabled');
      } else {
        $('.slick-product-next').removeClass('disabled');
      }
    }
    updateArrows(0);
    $slider.on('afterChange', function (event, slick, currentSlide) {
      updateArrows(currentSlide);
    });
  }
};
/* TOC */
NN_FRAMEWORK.Toc = function () {
  if (isExist($(".toc-list, .toc-list-2"))) {
    $(".toc-list").toc({
      content: "div#toc-content",
      headings: "h2,h3,h4",
    });
    $(".toc-list-2").toc({
      content: "div#toc-content2",
      headings: "h2,h3,h4",
    });
    if (!$(".toc-list li, .toc-list-2 li").length) $(".meta-toc").hide();
    if (!$(".toc-list li, .toc-list-2 li").length)
      $(".meta-toc .mucluc-dropdown-list_button").hide();
    $(".toc-list, .toc-list-2")
      .find("a")
      .click(function () {
        var x = $(this).attr("data-rel");
        goToByScroll(x);
      });
    $("body").on("click", ".mucluc-dropdown-list_button", function () {
      $(".box-readmore").slideToggle(200);
    });
    $(document).scroll(function () {
      var y = $(this).scrollTop();
      if (y > 300) {
        $(".meta-toc").fadeIn();
      } else {
        $(".meta-toc").fadeOut();
      }
    });
  }
};
function goToByScroll(id) {
  var $el = $(id);
  if ($el.length) {
    $("html, body").animate(
      {
        scrollTop: $el.offset().top - 90,
      },
      500
    );
  }
}

// NN_FRAMEWORK.aweOwlPage = function () {
//   var owl = $(".owl-carousel.in-page");
//   owl.each(function () {
//     var xs_item = $(this).attr("data-xs-items");
//     var md_item = $(this).attr("data-md-items");
//     var lg_item = $(this).attr("data-lg-items");
//     var sm_item = $(this).attr("data-sm-items");
//     var margin = $(this).attr("data-margin");
//     var dot = $(this).attr("data-dot");
//     var nav = $(this).attr("data-nav");
//     var height = $(this).attr("data-height");
//     var play = $(this).attr("data-play");
//     var loop = $(this).attr("data-loop");

//     if (typeof margin !== typeof undefined && margin !== false) {
//     } else {
//       margin = 30;
//     }
//     if (typeof xs_item !== typeof undefined && xs_item !== false) {
//     } else {
//       xs_item = 1;
//     }
//     if (typeof sm_item !== typeof undefined && sm_item !== false) {
//     } else {
//       sm_item = 3;
//     }
//     if (typeof md_item !== typeof undefined && md_item !== false) {
//     } else {
//       md_item = 3;
//     }
//     if (typeof lg_item !== typeof undefined && lg_item !== false) {
//     } else {
//       lg_item = 3;
//     }

//     if (loop == 1) {
//       loop = true;
//     } else {
//       loop = false;
//     }
//     if (dot == 1) {
//       dot = true;
//     } else {
//       dot = false;
//     }
//     if (nav == 1) {
//       nav = true;
//     } else {
//       nav = false;
//     }
//     if (play == 1) {
//       play = true;
//     } else {
//       play = false;
//     }

//     $(this).owlCarousel({
//       loop: loop,
//       margin: Number(margin),
//       responsiveClass: true,
//       dots: dot,
//       nav: nav,
//       navText: [
//         '<div class="owlleft"><svg viewBox="0 0 16000 16000" style="position:absolute;top:0;left:0;width:100%;height:100%;"><polyline class="a" points="11040,1920 4960,8000 11040,14080 "></polyline></svg></div>',
//         '<div class="owlright"><svg viewBox="0 0 16000 16000" style="position:absolute;top:0;left:0;width:100%;height:100%;"><polyline class="a" points="4960,1920 11040,8000 4960,14080 "></polyline></svg></div>',
//       ],
//       autoplay: play,
//       autoplayTimeout: 4000,
//       smartSpeed: 3000,
//       autoplayHoverPause: true,
//       autoHeight: false,
//       responsive: {
//         0: {
//           items: Number(xs_item),
//         },
//         600: {
//           items: Number(sm_item),
//         },
//         1000: {
//           items: Number(md_item),
//         },
//         1200: {
//           items: Number(lg_item),
//         },
//       },
//     });
//   });
// };

/* Tools */
NN_FRAMEWORK.Toolbar = function () {
  /* Toolbar */
  $(".toolbar .phone").click(function (e) {
    e.stopPropagation();
    $(".toolbar").toggleClass("is-active");
  });
  $(document).click(function () {
    $(".toolbar").removeClass("is-active");
  });
  $(window).scroll(function () {
    var ex6Exists = $(".ex6").length > 0;
    if ($(this).scrollTop() > 100) {
      if (!ex6Exists) {
        $(".toolbar .scrollToTopMobile").addClass("ex6");
      }
    } else {
      $(".toolbar .scrollToTopMobile").removeClass("ex6");
    }
  });
};

/* Pagings */
NN_FRAMEWORK.Pagings = function () {
  /* Tab cat */
  if (isExist($(".tab-cat"))) {
    $(".tab-li").click(function () {
      var idList = $(this)
        .parents(".box-list")
        .find(".paging-product-list")
        .data("list");
      var idCat = $(this).data("id");
      $(this).addClass("active").siblings().removeClass("active");
      loadPaging(
        "api/product.php?perpage=5&idList=" + idList + "&idCat=" + idCat,
        ".paging-product-list-" + idList
      );
    });
  }

  /* Categories */
  if (isExist($(".paging-product-list"))) {
    $(".paging-product-list").each(function () {
      var list = $(this).data("list");
      loadPaging(
        "api/product.php?perpage=5&idList=" + list,
        ".paging-product-list-" + list
      );
    });
  }

  if (isExist($(".paging-product-loadmore"))) {
    function loadMorePage(id_list, id_cat, id_item, id_brand) {
      $(".loadmore-product-" + id_list).addClass("loadding");
      var current = $(".paging-product-loadmore-" + id_list);
      var curpage = parseInt(current.attr("data-curpage"));
      var perpage = parseInt(current.attr("data-perpage"));
      var total = parseInt(current.attr("data-total"));
      var start_page = curpage * perpage;
      var total_page = Math.ceil(total / perpage);
      $.ajax({
        type: "POST",
        url: "api/productLoadPage.php",
        data: {
          start_page,
          perpage,
          total,
          id_list,
          id_cat,
          id_item,
          id_brand,
        },
        beforeSend: function () {
          holdonOpen();
        },
        success: function (result) {
          holdonClose();
          var page_next = curpage + 1;
          current.attr("data-curpage", page_next);
          current.append(result);
          $(".loadmore-product-" + id_list).removeClass("loadding");
          if (page_next >= total_page) {
            $(".loadmore-product-" + id_list).remove();
          }
        },
      });
    }
    $(".paging-product-loadmore").each(function () {
      var id_list = $(this).attr("data-list");
      var id_cat = $(this).attr("data-cat");
      var id_item = $(this).attr("data-item");
      var id_brand = $(this).attr("data-brand");

      loadMorePage(id_list, id_cat, id_item, id_brand);
    });
    $(".loadmore-product").click(function () {
      var id_list = $(this).attr("data-list");
      var id_cat = $(this).attr("data-cat");
      var id_item = $(this).attr("data-item");
      var id_brand = $(this).attr("data-brand");
      loadMorePage(id_list, id_cat, id_item, id_brand);
    });
  }
  $(document).ready(function () {
    var hotline = $("#hotline-data").text().trim();
    if (hotline.length === 10) {
      var formattedNumber = hotline.replace(
        /(\d{4})(\d{3})(\d{3})/,
        "$1 $2 $3"
      );
      $("#hotline-data").text(formattedNumber);
    }
  });

  $("body").on("click", ".policy-more", function () {
    var id = $(this).data("id");
    $.ajax({
      type: "POST",
      url: "api/policy.php",
      dataType: "json",
      data: { id },
      success: function (result) {
        $("#popup-policy .modal-title").html(result.title);
        $("#popup-policy .modal-body").html(result.content);
        $("#popup-policy").modal("show");
      },
    });
  });
};

$("table").addClass("table table-hover");

/* Ready */
$(document).ready(function () {
  NN_FRAMEWORK.Common();
  NN_FRAMEWORK.SlickPage();
  NN_FRAMEWORK.Lazys();
  NN_FRAMEWORK.Wows();
  NN_FRAMEWORK.AltImg();
  NN_FRAMEWORK.GoTop();
  NN_FRAMEWORK.Menu();
  NN_FRAMEWORK.MenuFixed();
  // NN_FRAMEWORK.OwlPage();
  NN_FRAMEWORK.Pagings();
  NN_FRAMEWORK.Videos();
  NN_FRAMEWORK.Photobox();
  NN_FRAMEWORK.DomChange();
  NN_FRAMEWORK.Toc();
  // NN_FRAMEWORK.Toolbar();
});
