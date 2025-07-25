
/* Validation form */
function validateForm(ele) {
  window.addEventListener(
    "load",
    function () {
      var forms = document.getElementsByClassName(ele);
      var validation = Array.prototype.filter.call(forms, function (form) {
        form.addEventListener(
          "submit",
          function (event) {
            if (form.checkValidity() === false) {
              event.preventDefault();
              event.stopPropagation();
            }
            form.classList.add("was-validated");
          },
          false
        );
      });
      $("." + ele)
        .find("input[type=submit],button[type=submit]")
        .removeAttr("disabled");
    },
    false
  );
}
$(document).ready(function () {
  $('label.switch').each(function () {
    if ($(this).find('.switch-toggle-slider').length === 0) {
      const html = `
        <span class="switch-toggle-slider">
          <span class="switch-on"><i class="fa-solid fa-check"></i></span>
          <span class="switch-off"><i class="fa-solid fa-xmark"></i></span>
        </span>`;
      $(this).append(html);
    }
  });
});

/* Validation form chung */
validateForm("validation-form");

function isExist(ele) {
  return ele.length;
}

function getLen(str) {
  return /^\s*$/.test(str) ? 0 : str.length;
}

/* onChange Category */
function filterCategory(url) {
  if ($(".filter-category").length > 0 && url != "") {
    var id = "";
    var value = 0;
    $(".filter-category").each(function () {
      id = $(this).attr("id");
      if (id) {
        value = parseInt($("#" + id).val());
        if (value) {
          url += "&" + id + "=" + value;
        }
      }
    });
  }
  return url;
}
function onchangeCategory(obj) {
  var name = "";
  var keyword = $("#keyword").val();
  var url = LINK_FILTER;
  obj
    .parents(".form-group")
    .nextAll()
    .each(function () {
      name = $(this).find(".filter-category").attr("name");
      if ($(this) != obj) {
        $(this).find(".filter-category").val(0);
      }
    });
  url = filterCategory(url);
  if (keyword) {
    url += "&keyword=" + encodeURI(keyword);
  }
  return (window.location = url);
}

/* Search */
function doEnter(evt, obj, url) {
  if (url == "") {
    notifyDialog(LANG["duongdankhonghople"]);
    return false;
  }

  if (evt.keyCode == 13 || evt.which == 13) {
    onSearch(obj, url);
  }
}
function onSearch(obj, url) {
  if (url == "") {
    notifyDialog(LANG["duongdankhonghople"]);
    return false;
  } else {
    var keyword = $("#" + obj).val();
    url = filterCategory(url);

    if (keyword) {
      url += "&keyword=" + encodeURI(keyword);
    }

    window.location = filterCategory(url);
  }
}

/* Action order (Search - Export excel - Export word) */
function actionOrder(url) {
  var listid = "";
  var order_status = parseInt($("#order_status").val());
  var order_payment = parseInt($("#order_payment").val());
  var order_date = $("#order_date").val();
  var range_price = $("#range_price").val();
  var city = parseInt($("#id_city").val());
  var district = parseInt($("#id_district").val());
  var ward = parseInt($("#id_ward").val());
  var keyword = $("#keyword").val();

  $("input.select-checkbox").each(function () {
    if (this.checked) listid = listid + "," + this.value;
  });

  listid = listid.substr(1);
  if (listid) url += "&listid=" + listid;
  if (order_status) url += "&order_status=" + order_status;
  if (order_payment) url += "&order_payment=" + order_payment;
  if (order_date) url += "&order_date=" + order_date;
  if (range_price) url += "&range_price=" + range_price;
  if (city) url += "&id_city=" + city;
  if (district) url += "&id_district=" + district;
  if (ward) url += "&id_ward=" + ward;
  if (keyword) url += "&keyword=" + encodeURI(keyword);

  window.location = url;
}

/* Send email */
function sendEmail() {
  var listemail = "";

  $("input.select-checkbox").each(function () {
    if (this.checked) listemail = listemail + "," + this.value;
  });

  listemail = listemail.substr(1);

  if (listemail == "") {
    notifyDialog(LANG["banhaychonitnhat1mucdegui"]);
    return false;
  }

  $("#listemail").val(listemail);

  document.frmsendemail.submit();
}

/* Delete item */
function deleteItem(url) {
  holdonOpen();
  document.location = url;
}

/* Delete all */
function deleteAll(url) {
  var listid = "";

  $("input.select-checkbox").each(function () {
    if (this.checked) listid = listid + "," + this.value;
  });

  listid = listid.substr(1);

  if (listid == "") {
    notifyDialog(LANG["banhaychonitnhat1mucdexoa"]);
    return false;
  }

  holdonOpen();
  document.location = url + "&listid=" + listid;
}


/* Create sort filer */
$(document).ready(function () {
  $('#filer-gallery').on('change', function () {
    const files = this.files;
    const $preview = $('#preview-gallery');
    const colClass = $('.col-filer').val() || 'col-3';

    $preview.empty(); // Clear preview on every change

    if (!files.length) return;

    Array.from(files).forEach((file, index) => {
      const allowedExt = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
      const ext = file.name.split('.').pop().toLowerCase();

      if (!allowedExt.includes(ext)) return;

      const reader = new FileReader();
      reader.onload = function (e) {
        const imgHtml = `
          <div class="${colClass} mb-3">
            <div class="border p-1 shadow-sm rounded">
              <img src="${e.target.result}" class="img-fluid" alt="Ảnh ${index + 1}">
            </div>
          </div>
        `;
        $preview.append(imgHtml);
      };
      reader.readAsDataURL(file);
    });
  });
});

/* HoldOn */
function holdonOpen(
  theme = "sk-circle",
  text = "Loading...",
  backgroundColor = "rgba(0,0,0,0.8)",
  textColor = "white"
) {
  if (typeof HoldOn !== 'undefined') {
    HoldOn.open({
      theme,
      message: text,
      backgroundColor,
      textColor
    });
  }
}

function holdonClose() {
  if (typeof HoldOn !== 'undefined') {
    HoldOn.close();
  }
}


/* Go to element */
function goToByScroll(id, minusTop) {
  minusTop = parseInt(minusTop) ? parseInt(minusTop) : 0;
  id = id.replace("#", "");
  $("html,body").animate(
    {
      scrollTop: $("#" + id).offset().top - minusTop,
    },
    "slow"
  );
}

/* Show notify */
function showNotify(
  text = "Notify text",
  title = LANG["thongbao"],
  status = "success"
) {
  new Notify({
    status: status, // success, warning, error
    title: title,
    text: text,
    effect: "fade",
    speed: 400,
    customClass: null,
    customIcon: null,
    showIcon: true,
    showCloseButton: true,
    autoclose: true,
    autotimeout: 3000,
    gap: 10,
    distance: 10,
    type: 3,
    position: "right top",
  });
}

/* Notify */
function notifyDialog(
  content = "",
  title = LANG["thongbao"],
  icon = "fas fa-exclamation-triangle",
  type = "blue"
) {
  $.alert({
    title: title,
    icon: icon, // font awesome
    type: type, // red, green, orange, blue, purple, dark
    content: content, // html, text
    backgroundDismiss: true,
    animationSpeed: 600,
    animation: "zoom",
    closeAnimation: "scale",
    typeAnimated: true,
    animateFromElement: false,
    autoClose: "accept|3000",
    escapeKey: "accept",
    buttons: {
      accept: {
        text: '<i class="fas fa-check align-middle mr-2"></i>' + LANG["dongy"],
        btnClass: "btn-blue btn-sm bg-gradient-primary",
      },
    },
  });
}

/* Confirm */
function confirmDialog(
  action,
  text = LANG["banmuonloaibohinhanhnay"],
  value = null,
  title = LANG["thongbao"],
  icon = "fas fa-exclamation-triangle",
  type = "blue"
) {
  $.confirm({
    title: title,
    icon: icon,
    type: type,
    content: text,
    backgroundDismiss: true,
    animationSpeed: 600,
    animation: 'zoom',
    closeAnimation: 'scale',
    typeAnimated: true,
    animateFromElement: false,
    autoClose: 'cancel|3000',
    escapeKey: 'cancel',
    buttons: {
      confirm: {
        text: '<i class="fas fa-check mr-2"></i>' + LANG["dongy"],
        btnClass: "btn-blue btn-sm bg-gradient-primary",
        action: function () {
          switch (action) {
            case "create-seo": seoCreate(); break;
            case "push-onesignal": pushOneSignal(value); break;
            case "send-email": sendEmail(); break;
            case "delete-temp-filer":
              deleteTempFiler(value);
              break;
            case "delete-filer": deleteFiler(value); break;
            case "delete-all-filer": deleteAllFiler(value); break;
            case "delete-item": deleteItem(value); break;
            case "delete-all": deleteAll(value); break;
            case "delete-photo": deletePhoto(value); break;
          }
        },
      },
      cancel: {
        text: '<i class="fas fa-times mr-2"></i>' + LANG["huybo"],
        btnClass: "btn-red btn-sm bg-gradient-danger",
      },
    },
  });
}
function deleteFiler(id) {
  $('.jFiler-item').each(function () {
    const $item = $(this);
    if ($item.find('.jFiler-item-trash-action').data('id') == id && $item.attr("data-will-remove") == "1") {
      $item.remove();
    }
  });
}
function deleteTempFiler(index) {
  const $fileInput = $('#filer-gallery');
  const oldFiles = $fileInput[0].files;
  const dt = new DataTransfer();
  Array.from(oldFiles).forEach((file, i) => {
    if (i !== index) {
      dt.items.add(file);
    }
  });
  $fileInput[0].files = dt.files;
  $(".jFiler-item").eq(index).remove();
}
function deleteAllFiler() {
  let deletedAll = $(".deleted-images").val();

  $(".filer-checkbox:checked").each(function () {
    const $checkbox = $(this);
    const $item = $checkbox.closest(".jFiler-item");
    const id = $item.find('input[name="id-filer[]"]').val();

    if (!id) return;

    deletedAll += deletedAll ? "|" + id : id;
    $item.remove();
  });

  $(".deleted-images").val(deletedAll);
}

$(document).on('change', 'input[type="file"][name="file"]', function () {
  const input = $(this);
  const file = this.files[0];
  const zone = input.closest(".photoUpload-zone");
  if (!file || !zone.length) return;
  const reader = new FileReader();
  reader.onload = function (e) {
    zone.find(".photoUpload-detail").remove();
    const previewHtml = `<div class="photoUpload-detail" id="photoUpload-preview"><img src="${e.target.result}" class="img-preview rounded" /><div class="delete-photo"><a href="javascript:void(0)" title="Xoá hình ảnh"><i class="far fa-trash-alt"></i></a></div></div>`;
    $(previewHtml).insertBefore(zone.find(".photoUpload-file"));
    zone.find("#photo-deleted-flag").remove();
  };
  reader.readAsDataURL(file);
});

// xoá hình ảnh đại diện
$(document).on("click", ".delete-photo a", function (e) {
  e.preventDefault();
  const _this = $(this);
});
function deletePhoto(_root) {
  const form = _root.closest("form");
  const zone = _root.closest(".photoUpload-zone");
  _root.closest(".photoUpload-detail").remove();
  zone.find('input[type="file"]').val("");
  if ($("#photo-deleted-flag").length === 0) {
    $("<input>", {
      type: "hidden",
      id: "photo-deleted-flag",
      name: "photo_deleted",
      value: "1",
    }).appendTo(form);
  }
}
/* Rounde number */
function roundNumber(rnum, rlength) {
  return Math.round(rnum * Math.pow(10, rlength)) / Math.pow(10, rlength);
}
/* Max Datetime Picker */
function maxDate(element) {
  if (MAX_DATE) {
    $(element).datetimepicker({
      timepicker: false,
      format: "d/m/Y",
      formatDate: "d/m/Y",
      // minDate: '1950/01/01',
      maxDate: MAX_DATE,
    });
  }
}

/* Min Datetime Picker */
function minDate(element) {
  if (MAX_DATE) {
    $(element).datetimepicker({
      timepicker: false,
      format: "d/m/Y",
      formatDate: "d/m/Y",
      minDate: MAX_DATE,
      // maxDate: MAX_DATE
    });
  }
}

/* Youtube preview */
function youtubePreview(url, element) {
  var regExp =
    /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#\&\?]*).*/;
  var match = url.match(regExp);
  url = match && match[7].length == 11 ? match[7] : false;

  $(element)
    .attr("src", "//www.youtube.com/embed/" + url)
    .css({ width: "250", height: "150" });
}
/* SEO */
$(document).ready(function () {
  seoChange();
});
function seoExist() {
  var inputs = $('.card-seo input.check-seo');
  var textareas = $('.card-seo textarea.check-seo');
  var flag = false;

  if (!flag) {
    inputs.each(function (index) {
      var input = $(this).attr('id');
      value = $('#' + input).val();
      if (value) {
        flag = true;
        return false;
      }
    });
  }

  if (!flag) {
    textareas.each(function (index) {
      var textarea = $(this).attr('id');
      value = $('#' + textarea).val();
      if (value) {
        flag = true;
        return false;
      }
    });
  }

  return flag;
}
function seoCreate() {
  var flag = true;
  var seolang = $('#seo-create').val();
  var seolangArray = seolang.split(',');
  var seolangCount = seolangArray.length;
  var inputArticle = $('.card-article input.for-seo');
  var textareaArticle = $('.card-article textarea.for-seo');
  var textareaArticleCount = textareaArticle.length;
  var count = 0;
  var inputSeo = $('.card-seo input.check-seo');
  var textareaSeo = $('.card-seo textarea.check-seo');

  /* SEO Create - Input */
  inputArticle.each(function (index) {
    var input = $(this).attr('id');
    var lang = input.substr(input.length - 2);
    if (seolang.indexOf(lang) >= 0) {
      name = $('#' + input).val();
      name = name.substr(0, 70);
      name = name.trim();
      $('#title' + lang + ', #keywords' + lang).val(name);
      seoCount($('#title' + lang));
      seoCount($('#keywords' + lang));
    }
  });

  /* SEO Create - Textarea */
  textareaArticle.each(function (index) {
    var textarea = $(this).attr('id');
    var lang = textarea.substr(textarea.length - 2);
    if (seolang.indexOf(lang) >= 0) {
      if (flag) {
        var content = $('#' + textarea).val();

        if (!content && CKEDITOR.instances[textarea]) {
          content = CKEDITOR.instances[textarea].getData();
        }

        if (content) {
          content = content.replace(/(<([^>]+)>)/gi, '');
          content = content.substr(0, 160);
          content = content.trim();
          content = content.replace(/[\r\n]+/gm, ' ');
          $('#description' + lang).val(content);
          seoCount($('#description' + lang));
          flag = false;
        } else {
          flag = true;
        }
      }
      count++;
      if (count == textareaArticleCount / seolangCount) {
        flag = true;
        count = 0;
      }
    }
  });

  /* SEO Preview */
  for (var i = 0; i < seolangArray.length; i++)
    if (seolangArray[i]) {
      seoPreview(seolangArray[i]);
    }
}
function seoPreview(lang) {
  var titlePreview = '#title-seo-preview' + lang;
  var descriptionPreview = '#description-seo-preview' + lang;
  var seourlPreviewText = '#seourlpreview' + lang;

  var titleInput = $('#title' + lang).val() || '';
  var nameInput = $('#name' + lang).val() || '';
  var description = $('#description' + lang).val() || '';
  var slug = $('#slug' + lang).val() || '';

  var maxTitleLength = 70;
  var maxDescriptionLength = 160;
  var maxSlugLength = 53;

  function truncate(text, maxLength) {
    return text.length > maxLength ? text.substring(0, maxLength) + '...' : text;
  }

  var finalTitle = (titleInput.trim() !== '' ? titleInput : nameInput) || '';

  $(titlePreview).html(finalTitle.trim() ? truncate(finalTitle, maxTitleLength) : 'Tiêu đề mô phỏng trang website của bạn');


  if ($(descriptionPreview).length) {
    $(descriptionPreview).html(
      description ? truncate(description, maxDescriptionLength) : 'Mô tả ngắn gọn sẽ hiển thị ở đây, giúp người dùng hiểu nội dung trang. Giữ khoảng 150-160 ký tự là đẹp.'
    );
  }

  if ($(seourlPreviewText).length) {
    $(seourlPreviewText).html(
      CONFIG_BASE_RTRIM + ' › ' + (slug ? truncate(slug, maxSlugLength) : '')
    );
  }
}

function seoCount(obj) {
  if (obj.length) {
    var countseo = parseInt(obj.val().toString().length);
    countseo = countseo ? countseo : 0;
    obj.parents('div.form-group').children('div.label-seo').find('.count-seo span').html(countseo);
  }
}
function seoChange() {
  var seolang = $('#seo-create').val() || 'vi,en';
  var seolangArray = seolang.split(',');

  seolangArray.forEach(function (lang) {
    seoPreview(lang);

    $('.card-seo .check-seo[id$="' + lang + '"]').each(function () {
      var $input = $(this);
      seoCount($input);
      $('body').on('input', '#' + $input.attr('id'), function () {
        seoPreview(lang);
        seoCount($(this));
      });
    });

    var $slugInput = $('#slug' + lang);
    if ($slugInput.length) {
      $('body').on('input', '#slug' + lang, function () {
        seoPreview(lang);
      });
    }

    var $nameInput = $('#name' + lang);
    if ($nameInput.length) {
      $('body').on('input', '#name' + lang, function () {
        seoPreview(lang);
      });
    }
  });
}

/* Slug */
var sluglang = LANGS;
let lastSlug = {};
let lastSlugStatus = {};
let slugVersion = {};
let debounceTimers = {};

function slugPress() {
  sluglang.split(',').forEach(lang => {
    const slug = $(`#slug${lang}`).val();
    if (slug) {
      lastSlug[lang] = slug;
      slugCheck(lang);
    }
  });
}

$(document).ready(function () {
  sluglang.split(',').forEach(lang => {
    lastSlug[lang] = '';
    lastSlugStatus[lang] = false;
    slugVersion[lang] = 0;
  });
  slugPress();
  if ($("#slugchange").length) {
    $("body").on("click", "#slugchange", function () {
      slugChange($(this));
    });
  }
  sluglang.split(',').forEach(function (lang) {
    const $nameInput = $(`#name${lang}`);
    const $slugInput = $(`#slug${lang}`);
    if ($nameInput.length) {
      $nameInput.on('input', function () {
        const title = $(this).val();
        const slug = slugConvert(title);
        const oldSlug = $slugInput.val();
        if (slug !== oldSlug) {
          $slugInput.val(slug);
        }
        slugPreviewTitleSeo(title, lang);
        clearTimeout(debounceTimers[lang]);
        debounceTimers[lang] = setTimeout(() => {
          if (lastSlug[lang] !== slug) {
            lastSlug[lang] = slug;
            slugCheck(lang);
          }
        }, 400);
      });
    }
    if ($slugInput.length) {
      $slugInput.on('input', function () {
        const slug = $(this).val();
        clearTimeout(debounceTimers[lang]);
        debounceTimers[lang] = setTimeout(() => {
          if (lastSlug[lang] !== slug) {
            lastSlug[lang] = slug;
            slugCheck(lang);
          }
        }, 400);
      });
    }
  });
});

function slugConvert(slug, focus = false) {
  slug = slug.toLowerCase();
  slug = slug.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
  slug = slug.replace(/đ/g, "d");
  slug = slug.replace(/[^a-z0-9\- ]/g, "");
  slug = slug.replace(/\s+/g, "-").replace(/\-+/g, "-");
  if (!focus) {
    slug = "@" + slug + "@";
    slug = slug.replace(/\@\-|\-\@|\@/gi, "");
  }
  return slug;
}

function slugPreview(title, lang, focus = false) {
  const slug = slugConvert(title, focus);
  $(`#slug${lang}`).val(slug);
  $(`#slugurlpreview${lang} strong`).text(slug);
  $(`#seourlpreview${lang} strong`).text(slug);
}

function slugPreviewTitleSeo(title, lang) {
  const $title = $(`#title${lang}`);
  if ($title.length && !$title.val()) {
    $(`#title-seo-preview${lang}`).text(title || 'Title');
  }
}

function handleSlugStatus(status, lang, message = '') {
  const msgValid = "Đường dẫn hợp lệ.";
  const msgInvalid = message || "Đường dẫn đã tồn tại.";
  const $success = $(`#alert-slug-success${lang}`);
  const $danger = $(`#alert-slug-danger${lang}`);
  if (status === 1) {
    $danger.addClass("d-none");
    $success.removeClass("d-none").find("span").text(msgValid);
    lastSlugStatus[lang] = true;
  } else {
    $success.addClass("d-none");
    if (status === 0) {
      $danger.removeClass("d-none").find("span").text(msgInvalid);
    } else {
      $danger.addClass("d-none");
    }
    lastSlugStatus[lang] = false;
  }
  const isViValid = lastSlugStatus['vi'] === true;
  let otherValid = true;
  sluglang.split(',').forEach(l => {
    if (l === 'vi') return;
    const $slugInput = $(`#slug${l}`);
    const slugVal = $slugInput.length ? $slugInput.val().trim() : '';
    if (slugVal !== '') {
      if (lastSlugStatus[l] !== true) {
        otherValid = false;
      }
    }
  });
  const canSubmit = isViValid && otherValid;
  $(".submit-check").prop("disabled", !canSubmit);
}

function slugCheck(lang) {
  const slug = $(`#slug${lang}`).val();
  const id = $(".slug-id").val();
  const table = $(".slug-table").val();
  if (!slug) {
    handleSlugStatus(-1, lang);
    return;
  }
  slugVersion[lang]++;
  const currentVersion = slugVersion[lang];
  $.ajax({
    url: "api/slug.php",
    type: "POST",
    dataType: "json",
    data: { slug, id, table, lang },
    success: function (res) {
      if (slugVersion[lang] === currentVersion) {
        handleSlugStatus(res.status, lang, res.message);
      }
    },
    error: function () {
      if (slugVersion[lang] === currentVersion) {
        handleSlugStatus(-1, lang);
      }
    }
  });
}

/* Reader image */
function readImage(inputFile, elementPhoto) {
  if (inputFile[0].files[0]) {
    if (inputFile[0].files[0].name.match(/.(jpg|jpeg|png|gif|webp)$/i)) {
      var size = parseInt(inputFile[0].files[0].size) / 1024;

      if (size <= 4096) {
        var reader = new FileReader();
        reader.onload = function (e) {
          $(elementPhoto).attr("src", e.target.result);
        };
        reader.readAsDataURL(inputFile[0].files[0]);
      } else {
        notifyDialog(LANG["dungluonghinhanhlondungluongchopheplt4mb4096kb"]);
        return false;
      }
    } else {
      $(elementPhoto).attr("src", "");
      notifyDialog(LANG["dinhdanghinhanhkhonghople"]);
      return false;
    }
  } else {
    $(elementPhoto).attr("src", "");
    return false;
  }
}

/* Photo zone */
function photoZone(eDrag, iDrag, eLoad) {
  if ($(eDrag).length) {
    /* Drag over */
    $(eDrag).on("dragover", function () {
      $(this).addClass("drag-over");
      return false;
    });

    /* Drag leave */
    $(eDrag).on("dragleave", function () {
      $(this).removeClass("drag-over");
      return false;
    });

    /* Drop */
    $(eDrag).on("drop", function (e) {
      e.preventDefault();
      $(this).removeClass("drag-over");

      var lengthZone = e.originalEvent.dataTransfer.files.length;

      if (lengthZone == 1) {
        $(iDrag).prop("files", e.originalEvent.dataTransfer.files);
        readImage($(iDrag), eLoad);
      } else if (lengthZone > 1) {
        notifyDialog(LANG["banchiduocchon1hinhanhdeupload"]);
        return false;
      } else {
        notifyDialog(LANG["dulieukhonghople"]);
        return false;
      }
    });

    /* File zone */
    $(iDrag).change(function () {
      readImage($(this), eLoad);
    });
  }
}

/* Watermark */
// function toDataURL(url, callback) {
//   var xhr = new XMLHttpRequest();
//   xhr.onload = function () {
//     var reader = new FileReader();
//     reader.onloadend = function () {
//       callback(reader.result);
//     };
//     reader.readAsDataURL(xhr.response);
//   };
//   xhr.open("GET", url);
//   xhr.responseType = "blob";
//   xhr.send();
// }
// function previewWatermark() {
//   $o = $("#form-watermark");
//   var formData = new FormData();
//   formData.append("file", $("#file")[0].files[0]);
//   formData.append("data", $o.serialize());

//   $.ajax({
//     type: "POST",
//     url: "index.php?com=photo&act=save-watermark&type=" + TYPE,
//     data: formData,
//     cache: false,
//     contentType: false,
//     processData: false,
//     dataType: "json",
//     success: function (data) {
//       var url =
//         "index.php?com=photo&act=preview-watermark&type=" +
//         TYPE +
//         "&position=" +
//         data.position +
//         "&img=" +
//         data.image +
//         "&watermark=" +
//         data.path +
//         "&upload=" +
//         data.upload +
//         "&opacity=" +
//         data.data.opacity +
//         "&per=" +
//         data.data.per +
//         "&small_per=" +
//         data.data.small_per +
//         "&min=" +
//         data.data.min +
//         "&max=" +
//         data.data.max +
//         "&t=" +
//         data.time;
//       toDataURL(url, function (dataUrl) {
//         notifyDialog(
//           '<img src="' + dataUrl + '" alt="Preview Watermark">',
//           "Preview Watermark",
//           "fas fa-image",
//           "blue"
//         );
//       });
//     },
//     error: function (data) {
//       console.log("error");
//     },
//   });

//   return false;
// }

$(document).ready(function () {
  function updateWatermarkPosition(position) {
    var $watermark = $('#watermark-preview');
    $watermark.css({
      'top': '',
      'bottom': '',
      'left': '',
      'right': '',
      'transform': ''
    });

    switch (position) {
      case '1': $watermark.css({ 'top': '10px', 'left': '10px' }); break;
      case '2': $watermark.css({ 'top': '10px', 'left': '50%', 'transform': 'translateX(-50%)' }); break;
      case '3': $watermark.css({ 'top': '10px', 'right': '10px' }); break;
      case '4': $watermark.css({ 'top': '50%', 'right': '10px', 'transform': 'translateY(-50%)' }); break;
      case '5': $watermark.css({ 'bottom': '10px', 'right': '10px' }); break;
      case '6': $watermark.css({ 'bottom': '10px', 'left': '50%', 'transform': 'translateX(-50%)' }); break;
      case '7': $watermark.css({ 'bottom': '10px', 'left': '10px' }); break;
      case '8': $watermark.css({ 'top': '50%', 'left': '10px', 'transform': 'translateY(-50%)' }); break;
      case '9': $watermark.css({ 'top': '50%', 'left': '50%', 'transform': 'translate(-50%, -50%)' }); break;
    }
  }

  // Cập nhật opacity cho ảnh đang chọn
  function highlightSelectedImage() {
    $(".watermark-position label img").css("opacity", "0.6");
    $(".watermark-position input[type=radio]:checked").siblings("img").css("opacity", "1");
  }

  var initialPosition = $('input[name="position"]:checked').val();
  updateWatermarkPosition(initialPosition);
  highlightSelectedImage();

  $('input[name="position"]').on('change', function () {
    updateWatermarkPosition($(this).val());
    highlightSelectedImage();
  });
});


/* Login */

function login() {
  var username = $("#username").val();
  var password = $("#password").val();

  if (
    $(".alert-login").hasClass("alert-danger") ||
    $(".alert-login").hasClass("alert-success")
  ) {
    $(".alert-login").removeClass("alert-danger alert-success");
    $(".alert-login").addClass("d-none");
    $(".alert-login").html("");
  }

  if ($(".show-password").hasClass("active")) {
    $(".show-password").removeClass("active");
    $("#password").attr("type", "password");
    $(".show-password").find("span").toggleClass("fas fa-eye fas fa-eye-slash");
  }

  $(".show-password").addClass("disabled");
  $(".btn-login .sk-chase").removeClass("d-none");
  $(".btn-login span").addClass("d-none");
  $(".btn-login").attr("disabled", true);
  $("#username").attr("disabled", true);
  $("#password").attr("disabled", true);

  $.ajax({
    type: "POST",
    dataType: "json",
    url: "api/login.php",
    async: false,
    data: { username: username, password: password },
    success: function (result) {
      if (result.success) {
        window.location = "index.php";
      } else if (result.error) {
        $(".alert-login").removeClass("d-none");
        $(".show-password").removeClass("disabled");
        $(".btn-login .sk-chase").addClass("d-none");
        $(".btn-login span").removeClass("d-none");
        $(".btn-login").attr("disabled", false);
        $("#username").attr("disabled", false);
        $("#password").attr("disabled", false);
        $(".alert-login").removeClass("alert-success");
        $(".alert-login").addClass("alert-danger");
        $(".alert-login").html(result.error);
      }
    },
  });
}

/* Random password */
function randomPassword() {
  var str = "";

  for (i = 0; i < 9; i++) {
    str +=
      "!@#$%^&*()?abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890".charAt(
        Math.floor(Math.random() * 62)
      );
  }

  $("#new-password").val(str);
  $("#renew-password").val(str);
  $("#show-password").html(str);
}

/* Check permissions */
function loadPermissions() {
  if (
    $(".card-permission").find("input[type=checkbox]:checked").length ==
    $(".card-permission").find("input[type=checkbox]").length
  ) {
    $("input#selectall-checkbox").prop("checked", true);
  } else {
    $("input#selectall-checkbox").prop("checked", false);
  }
}
$(document).on("change", ".file_upload_video", function (evt) {
  var $source = $("#video_here");
  $source[0].src = URL.createObjectURL(this.files[0]);
  $source.parent()[0].load();
});
$(document).ready(function () {
  Fancybox.bind("[data-fancybox]", {});

  $("body").on("click", ".delete-photo a", function (event) {
    confirmDialog("delete-photo", LANG["banmuonxoahinhanhnay"], $(this));
  });
  /* Loader */
  if ($(".loader-wrapper").length) {
    setTimeout(function () {
      $(".loader-wrapper").fadeOut("medium");
    }, 300);
  }

  /* Login */
  if (LOGIN_PAGE) {
    $("#username, #password").keypress(function (event) {
      if (event.keyCode == 13 || event.which == 13) login();
    });

    $(".btn-login").click(function () {
      login();
    });

    $(".show-password").click(function () {
      if ($("#password").val()) {
        if ($(this).hasClass("active")) {
          $(this).removeClass("active");
          $("#password").attr("type", "password");
        } else {
          $(this).addClass("active");
          $("#password").attr("type", "text");
        }
        $(this).find("span").toggleClass("fas fa-eye fas fa-eye-slash");
      }
    });
  }

  /* Permission */
  if (ADD_OR_EDIT_PERMISSIONS) {
    loadPermissions();

    $("input#selectall-checkbox").click(function () {
      $this = $(this);
      if ($this.prop("checked")) {
        $(".card-permission")
          .find("input[type=checkbox]")
          .prop("checked", true);
      } else {
        $(".card-permission")
          .find("input[type=checkbox]")
          .prop("checked", false);
      }
    });

    $(".card-permission").change(function () {
      loadPermissions();
    });
  }

  $(document).ready(function () {
    const currentQuery = window.location.search;

    $(".sidebar .nav-link[data-active]").each(function () {
      const activeList = $(this).data("active").split(",").map(s => s.trim());

      // So sánh đúng query string
      if (activeList.includes(currentQuery)) {
        $(this).addClass("active");
      }
    });

    // Mở nhóm menu nếu có menu con active
    $(".menu-group").each(function () {
      if ($(this).find(".nav-link.active").length > 0) {
        $(this).addClass("menu-open");
        $(this).find("> .nav-link").addClass("active");
      }
    });
  });


  /* Import excell */
  if (IMPORT_IMAGE_EXCELL && $(".copy-excel").length) {
    $(".copy-excel").click(function () {
      var text = $(this).data("text");
      var dummy = document.createElement("input");

      dummy.select();
      dummy.setSelectionRange(0, 99999);
      navigator.clipboard.writeText(text);

      if (!$(this).hasClass("active")) {
        $(this).addClass("active");
        $(this).html("Copied");
      }
    });
  }

  /* Order */
  if (ORDER_ADVANCED_SEARCH) {
    /* Date range picker */
    $("#order_date").daterangepicker({
      callback: this.render,
      autoUpdateInput: false,
      timePicker: false,
      timePickerIncrement: 30,
      locale: {
        format: "DD/MM/YYYY",
        // format: 'DD/MM/YYYY hh:mm A'
      },
    });

    $("#order_date").on("apply.daterangepicker", function (ev, picker) {
      $(this).val(
        picker.startDate.format("DD/MM/YYYY") +
        " - " +
        picker.endDate.format("DD/MM/YYYY")
      );
    });

    $("#order_date").on("cancel.daterangepicker", function (ev, picker) {
      $(this).val("");
    });

    /* rangeSlider */
    $("#range_price").ionRangeSlider({
      skin: "flat",
      min: ORDER_MIN_TOTAL,
      max: ORDER_MAX_TOTAL,
      from: ORDER_PRICE_FROM,
      to: ORDER_PRICE_TO,
      type: "double",
      step: 1,
      // prefix  : 'đ ',
      postfix: " đ",
      prettify: true,
      hasGrid: true,
    });
  }

  /* Product */
  if ($(".regular_price").length && $(".sale_price").length) {
    $(".regular_price, .sale_price").keyup(function () {
      var regular_price = $(".regular_price").val();
      var sale_price = $(".sale_price").length ? $(".sale_price").val() : 0;
      var discount = 0;

      if (
        regular_price == "" ||
        regular_price == "0" ||
        sale_price == "" ||
        sale_price == "0"
      ) {
        discount = 0;
      } else {
        regular_price = regular_price.replace(/,/g, "");
        sale_price = sale_price ? sale_price.replace(/,/g, "") : 0;
        regular_price = parseInt(regular_price);
        sale_price = parseInt(sale_price);

        if (sale_price < regular_price) {
          discount = 100 - (sale_price * 100) / regular_price;
          discount = roundNumber(discount, 0);
        } else {
          $(".regular_price, .sale_price").val(0);
          if ($(".discount").length) {
            discount = 0;
            $(".sale_price").val(0);
          }
        }
      }

      if ($(".discount").length) {
        $(".discount").val(discount);
      }
    });
  }

  /* Setting */
  if ($(".mailertype").length) {
    $(".mailertype").click(function () {
      var value = parseInt($(this).val());

      if (value == 1) {
        $(".host-email").removeClass("d-none");
        $(".host-email").addClass("d-block");
        $(".gmail-email").removeClass("d-block");
        $(".gmail-email").addClass("d-none");
      }
      if (value == 2) {
        $(".gmail-email").removeClass("d-none");
        $(".gmail-email").addClass("d-block");
        $(".host-email").removeClass("d-block");
        $(".host-email").addClass("d-none");
      }
    });
  }

  /* Max Datetime Picker */
  if ($(".max-date").length) {
    maxDate(".max-date");
  }

  /* Min Datetime Picker */
  if ($(".min-date").length) {
    minDate(".min-date");
  }

  /* Select 2 */
  if ($(".select2").length) {
    $(".select2").select2();
  }

  /* Format price */
  if ($(".format-price").length) {
    $(".format-price").priceFormat({
      limit: 13,
      prefix: "",
      centsLimit: 0,
    });
  }

  /* PhotoZone */
  if ($("#photo-zone").length) {
    photoZone("#photo-zone", "#file-zone", "#photoUpload-preview img");
  }
  /* PhotoZone1 */
  if ($("#photo-zone1").length) {
    photoZone("#photo-zone1", "#file-zone1", "#photoUpload-preview1 img");
  }

  // Lặp qua các phần tử có id bắt đầu bằng "photo-zone"
  $("[id^='photo-zone']").each(function (index) {
    var zoneId = "#photo-zone" + index; // ID của khu vực photo-zone
    var fileInputId = "#file-zone" + index; // ID của input file
    var previewImgId = "#photoUpload-preview" + index + " img"; // ID của ảnh xem trước

    // Gọi hàm photoZone cho mỗi vùng photo-zone
    photoZone(zoneId, fileInputId, previewImgId);
  });

  /* Sumoselect */
  if ($(".multiselect").length) {
    window.asd = $(".multiselect").SumoSelect({
      placeholder: LANG["chondanhmuc"],
      selectAll: true,
      search: true,
      searchText: LANG["timkiem"],
      locale: ["OK", "Cancel", "Select all"],
      //captionFormat: 'Đã chọn {0} mục',
      //captionFormatAllSelected: 'Đã chọn tất cả {0} mục'
    });
  }

  /* Ckeditor */
  if ($(".form-control-ckeditor").length) {
    $(".form-control-ckeditor").each(function () {
      var id = $(this).attr("id");
      CKEDITOR.replace(id);
    });
  }

  /* Comment */
  if ($(".comment-manager").length) {
    $(".comment-manager").comments({
      url: "api/comment.php",
    });
  }

  /* Ajax category */
  if ($(".select-category")) {
    $("body").on("change", ".select-category", function () {
      var id = $(this).val();
      var child = $(this).data("child");
      var level = parseInt($(this).data("level"));
      var table = $(this).data("table");
      if ($("#" + child).length) {
        $.ajax({
          url: "api/category.php",
          type: "POST",
          data: {
            level: level,
            id: id,
            table: table,
          },
          success: function (result) {
            var op = "<option value='0'>" + LANG["chondanhmuc"] + "</option>";
            if (level == 0) {
              $("#id_cat").html(op);
              $("#id_item").html(op);
              $("#id_sub").html(op);
            } else if (level == 1) {
              $("#id_item").html(op);
              $("#id_sub").html(op);
            } else if (level == 2) {
              $("#id_sub").html(op);
            }
            $("#" + child).html(result);
          },
        });
        return false;
      }
    });
  }
  /* Ajax filter-table-category */

  /* Ajax place */
  if ($(".select-place").length) {
    $("body").on("change", ".select-place", function () {
      var id = $(this).val();
      var child = $(this).data("child");
      var level = parseInt($(this).data("level"));
      var table = $(this).data("table");

      if ($("#" + child).length) {
        $.ajax({
          url: "api/place.php",
          type: "POST",
          data: {
            level: level,
            id: id,
            table: table,
          },
          success: function (result) {
            var op = "<option value='0'>" + LANG["chondanhmuc"] + "</option>";

            if (level == 0) {
              $("#id_district").html(op);
              $("#id_ward").html(op);
            } else if (level == 1) {
              $("#id_ward").html(op);
            }
            $("#" + child).html(result);
          },
        });
      }

      return false;
    });
  }

  /* Database */
  if ($(".btn-database").length) {
    $("body").on("click", ".btn-database", function () {
      var action = $(this).data("action");

      if (action) {
        holdonOpen();
        $.ajax({
          url: "api/database.php",
          type: "POST",
          dataType: "json",
          data: {
            action: action,
          },
          error: function () {
            holdonClose();
          },
          success: function (result) {
            if (result) {
              $(".result-database").html("");
              for (var i = 0; i < result.length; i++) {
                $str = "";
                $str += '<div class="col-md-4">';
                $str +=
                  '<div class="alert alert-success alert-dismissible bg-gradient-success">';
                $str += "<h6><strong>" + result[i].table + "</strong></h6>";
                $str +=
                  "<div><strong>Action:</strong> " +
                  result[i].action +
                  "</div>";
                $str +=
                  "<div><strong>Status:</strong> " + result[i].text + "</div>";
                $str += "</div>";
                $str += "</div>";
                $(".result-database").append($str);
              }
            } else {
              $(".result-database").html(
                '<div class="col-12"><span class="text-danger">' +
                LANG["xulythatbaivuilongthulaisau"] +
                "</span></div>"
              );
            }

            holdonClose();
          },
        });
      }

      return false;
    });
  }

  /* Check required form */
  if ($(".submit-check").length) {
    $(".submit-check").click(function () {
      var formCheck = $(this).parents("form.validation-form");
      holdonOpen();
      slugCheck();
      var flag = true;
      var slugs = "";
      var slugOffset = $(".card-slug");
      var slugsInValid = $(".card-slug :required:invalid");
      var slugsError = $(".card-slug .text-danger").not(".d-none");
      var cardOffset = 0;
      var elementsInValid = $(".card :required:invalid");
      if (slugsInValid.length || slugsError.length) {
        flag = false;
        slugs = slugsError.length ? slugsError : slugsInValid;
        slugs.each(function () {
          $this = $(this);
          var tabPane = $this.parents(".tab-pane");
          var tabPaneID = tabPane.attr("id");
          $('.nav-tabs a[href="#' + tabPaneID + '"]').tab("show");
          return false;
        });
        setTimeout(function () {
          $("html,body").animate(
            { scrollTop: slugOffset.offset().top - 40 },
            "medium"
          );
        }, 500);
      } else if (elementsInValid.length) {
        flag = false;

        /* Check elements empty */
        elementsInValid.each(function () {
          $this = $(this);
          cardOffset = $this.parents(".card-body");
          var cardCollapsed = $this.parents(".card.collapsed-card");

          if (cardCollapsed.length) {
            cardCollapsed.find(".card-body").show();
            cardCollapsed
              .find(".btn-tool i")
              .toggleClass("fas fa-plus fas fa-minus");
            cardCollapsed.removeClass("collapsed-card");
          }

          var tabPane = $this.parents(".tab-pane");
          var tabPaneID = tabPane.attr("id");
          $('.nav-tabs a[href="#' + tabPaneID + '"]').tab("show");

          return false;
        });

        /* Scroll to error */
        if (cardOffset) {
          setTimeout(function () {
            $("html,body").animate(
              { scrollTop: cardOffset.offset().top - 100 },
              "medium"
            );
          }, 500);
        }
      }

      /* Holdon close */
      holdonClose();

      /* Check form validated */
      if (!flag) {
        formCheck.addClass("was-validated");
      } else {
        formCheck.removeClass("was-validated");
      }

      return flag;
    });
  }

  /* Push oneSignal */
  if ($("#push-onesignal").length) {
    $("body").on("click", "#push-onesignal", function () {
      var url = $(this).data("url");
      confirmDialog("push-onesignal", LANG["banmuondaytinnay"], url);
    });
  }

  /* Send email */
  if ($("#send-email").length) {
    $("body").on("click", "#send-email", function () {
      confirmDialog(
        "send-email",
        LANG["banmuonguithongtinchocacmucdachon"],
        ""
      );
    });
  }

  /* Check item */
  if ($(".select-checkbox").length) {
    var lastChecked = null;
    var $checkboxItem = $(".select-checkbox");

    $checkboxItem.click(function (e) {
      if (!lastChecked) {
        lastChecked = this;
        return;
      }

      if (e.shiftKey) {
        var start = $checkboxItem.index(this);
        var end = $checkboxItem.index(lastChecked);
        $checkboxItem
          .slice(Math.min(start, end), Math.max(start, end) + 1)
          .prop("checked", true);
      }

      lastChecked = this;
    });
  }

  /* Check all */
  $(document).ready(function () {
    $("body").on("click", "#selectall-checkbox", function () {
      var table = $(this).closest("table");
      var checkboxes = table.find("input.select-checkbox");
      checkboxes.prop("checked", $(this).is(":checked"));
    });
  });

  /* Delete item */
  if ($("#delete-item").length) {
    $("body").on("click", "#delete-item", function () {
      var url = $(this).data("url");
      confirmDialog("delete-item", LANG["bancochacmuonxoamucnay"], url);
    });
  }

  /* Delete all */
  if ($("#delete-all").length) {
    $("body").on("click", "#delete-all", function () {
      var url = $(this).data("url");
      confirmDialog("delete-all", LANG["bancochacmuonxoanhungmucnay"], url);
    });
  }

  /* Load name input file */
  $("body").on("change", ".custom-file input[type=file]", function () {
    var fileName = $(this).val().split("\\").pop();
    $(this).siblings("label").html(fileName);
  });
  $(document).ready(function () {
    // Bắt sự kiện thay đổi file
    $("#file").on("change", function () {
      var input = this;
      if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
          // Cập nhật src của thẻ img với dữ liệu file ảnh đã chọn
          $("#preview-image").attr("src", e.target.result);
        };

        reader.readAsDataURL(input.files[0]); // Đọc file ảnh
      }
    });
  });
  $(document).ready(function () {
    // Bắt sự kiện thay đổi file cho mọi input có id bắt đầu bằng 'file'
    $("input[id^='file']").on("change", function () {
      var input = this;
      var fileName = $(this).val().split("\\").pop(); // Lấy tên file
      $(this).siblings("label").html(fileName); // Cập nhật tên file vào label

      if (input.files && input.files[0]) {
        var reader = new FileReader();
        var inputId = $(this).attr("id"); // Lấy ID của input file

        reader.onload = function (e) {
          // Tìm thẻ img tương ứng dựa trên ID của input
          var previewId = inputId.replace("file", "preview"); // Chuyển đổi id từ file0 -> preview0
          $("#" + previewId).attr("src", e.target.result); // Cập nhật src của thẻ img tương ứng
        };

        reader.readAsDataURL(input.files[0]); // Đọc file ảnh
      }
    });
  });

  /* Change status */
  $("body").on("click", ".show-checkbox", function () {
    const $checkbox = $(this);
    const id = $checkbox.data("id");
    const table = $checkbox.data("table");
    const attr = $checkbox.data("attr");
    const isChecked = $checkbox.is(":checked");

    $.ajax({
      url: "api/status.php",
      type: "POST",
      dataType: "json",
      data: {
        id: id,
        table: table,
        attr: attr,
        checked: isChecked ? 1 : 0,
      },
      success: function (res) {
        if (res.success) {
          $checkbox.prop("checked", isChecked);
        } else {
          $checkbox.prop("checked", !isChecked);
          alert(res.message || "Lỗi cập nhật trạng thái.");
        }
      },
      error: function () {
        $checkbox.prop("checked", !isChecked);
        alert("Lỗi kết nối đến máy chủ.");
      },
    });
    return false;
  });


  /* Change numb */
  if ($("input.update-numb").length) {
    $("body").on("change", "input.update-numb", function () {
      var id = $(this).attr("data-id");
      var table = $(this).attr("data-table");
      var value = $(this).val();

      $.ajax({
        url: "api/numb.php",
        type: "POST",
        dataType: "html",
        data: {
          id: id,
          table: table,
          value: value,
        },
      });

      return false;
    });
  }

  /* Watermark */
  if ($(".watermark-position label").length) {
    $(".watermark-position label").click(function () {
      if ($(".upload-file-image img").length) {
        var img = $(".upload-file-image img").attr("src");

        if (img) {
          $(".watermark-position label img").attr(
            "src",
            ASSET + "assets/images/noimage.png"
          );
          $(this).find("img").attr("src", img);
          $(this).find("img").show();
        }
      } else {
        notifyDialog(LANG["dulieuhinhanhkhonghople"]);
        return false;
      }
    });
  }

  /* SEO */
  seoChange();
  if (
    $(".title-seo").length &&
    $(".keywords-seo").length &&
    $(".description-seo").length
  ) {
    $("body").on(
      "keyup",
      ".title-seo, .keywords-seo, .description-seo",
      function () {
        seoCount($(this));
      }
    );
  }
  if ($(".create-seo").length) {
    $("body").on("click", ".create-seo", function () {
      if (seoExist())
        confirmDialog(
          "create-seo",
          LANG["noidungseodaduocthietlapbanmuontaolainoidungseo"],
          ""
        );
      else seoCreate();
    });
  }

  /* Copy */
  // if ($(".copy-now").length) {
  //   $("body").on("click", ".copy-now", function () {
  //     var id = $(this).attr("data-id");
  //     var table = $(this).attr("data-table");
  //     var copyimg = $(this).attr("data-copyimg");

  //     holdonOpen();

  //     $.ajax({
  //       url: "api/copy.php",
  //       type: "POST",
  //       dataType: "html",
  //       async: false,
  //       data: {
  //         id: id,
  //         table: table,
  //         copyimg: copyimg,
  //       },
  //       success: function () {
  //         holdonClose();
  //       },
  //     });

  //     window.location.reload(true);
  //   });
  // }

  /* Sort filer */
  if (ACTIVE_GALLERY) {
    createSortFiler();
  }

  /* Check all filer */
  $("body").on("change", ".filer-checkbox", function () {
    const label = $('label[for="' + this.id + '"]');
    if (label.length) {
      label.text(label.attr("data-label") || "Chọn");
    }
    const hasChecked = $(".filer-checkbox:checked").length > 0;
    $(".sort-filer").attr("disabled", !hasChecked);
  });
  $("body").on("click", ".check-all-filer", function () {
    const $this = $(this);
    const isActive = $this.hasClass("active");
    const filerItems = $(".my-jFiler-items .jFiler-items-list");
    const inputs = filerItems.find("input.filer-checkbox");
    const jFilerItems = $("#jFilerSortable").find(".my-jFiler-item");
    $this.find("i").toggleClass("far fa-square fas fa-check-square");
    if (isActive) {
      $this.removeClass("active");
      $(".sort-filer").removeClass("active").attr("disabled", false);
      inputs.each(function () {
        $(this).prop("checked", false);
        const label = $('label[for="' + this.id + '"]');
        if (label.length) label.text(label.attr("data-label") || "Chọn");
      });

    } else {
      $this.addClass("active");
      $(".sort-filer").attr("disabled", true);
      $(".alert-sort-filer").hide();
      $(".my-jFiler-item-trash").show();
      inputs.each(function () {
        $(this).prop("checked", true);
        const label = $('label[for="' + this.id + '"]');
        if (label.length) label.text(label.attr("data-label") || "Chọn");
      });
      jFilerItems.each(function () {
        $(this).find("input").attr("disabled", false);
        $(this).removeClass("moved");
      });

      if (typeof sortable !== "undefined") {
        sortable.option("disabled", true);
      }
    }
  });

  // $("body").on("click", ".filer-checkbox", function () {
  //   var input = $(".my-jFiler-items .jFiler-items-list").find(
  //     "input.filer-checkbox:checked"
  //   );

  //   if (input.length) $(".sort-filer").attr("disabled", true);
  //   else $(".sort-filer").attr("disabled", false);
  // });

  // $("body").on("click", ".sort-filer", function () {
  //   var jFilerItems = $("#jFilerSortable").find(".my-jFiler-item");

  //   if ($(this).hasClass("active")) {
  //     sortable.option("disabled", true);
  //     $(this).removeClass("active");
  //     $(".alert-sort-filer").hide();
  //     $(".my-jFiler-item-trash").show();
  //     jFilerItems.each(function () {
  //       $(this).find("input").attr("disabled", false);
  //       $(this).removeClass("moved");
  //     });
  //   } else {
  //     sortable.option("disabled", false);
  //     $(this).addClass("active");
  //     $(".alert-sort-filer").show();
  //     $(".my-jFiler-item-trash").hide();
  //     jFilerItems.each(function () {
  //       $(this).find("input").attr("disabled", true);
  //       $(this).addClass("moved");
  //     });
  //   }
  // });


  // $(document).on("click", ".my-jFiler-item-trash", function () {
  //   const id = $(this).data("id");
  //   const folder = $(this).data("folder");
  //   const value = id + "," + folder;
  //   let deleted = $(".deleted-images").val();
  //   deleted += deleted ? "|" + value : value;
  //   $(".deleted-images").val(deleted);
  //   confirmDialog("delete-filer", LANG["bancochacmuonxoahinhanhnay"], value);
  // });

  $(document).on("click", ".jFiler-item-trash-action", function () {
    const $btn = $(this);
    const $item = $btn.closest(".jFiler-item");
    const id = $btn.data("id");

    if (id) {
      // Xử lý ảnh đã có id
      let deleted = $(".deleted-images").val();
      deleted += deleted ? "|" + id : id;
      $(".deleted-images").val(deleted);

      $item.attr("data-will-remove", "1");
      confirmDialog("delete-filer", LANG["banmuonloaibohinhanhnay"], id);
    } else {
      // Xác nhận xoá ảnh mới
      confirmDialog("delete-temp-filer", LANG["banmuonxoaanhmoi"], $item.index());
    }
  });



  /* Delete all filer */
  $("body").on("click", ".delete-all-filer", function () {
    const folder = $(".folder-filer").val();
    confirmDialog(
      "delete-all-filer",
      LANG["bancochacmuonxoacachinhanhdachon"],
      folder
    );
  });

  /* Hash upload multi filer */
  $("form.validation-form").append(
    '<input type="hidden" name="hash" value="' + HASH + '" />'
  );
  $("#filer-gallery").attr({
    "data-params": BASE64_QUERY_STRING,
    "data-hash": HASH,
  });

  /* Change info filer */
  // $("body").on("change", ".my-jFiler-item-info", function () {
  //   var id = $(this).data("id");
  //   var info = $(this).data("info");
  //   var value = $(this).val();
  //   var id_parent = ID;
  //   var com = COM;
  //   var kind = ACT;
  //   var type = TYPE;
  //   var colfiler = $(".col-filer").val();
  //   var actfiler = $(".act-filer").val();
  //   var cmd = "info";

  //   $.ajax({
  //     type: "POST",
  //     dataType: "html",
  //     url: "api/filer.php",
  //     async: false,
  //     data: {
  //       id: id,
  //       id_parent: id_parent,
  //       info: info,
  //       value: value,
  //       com: com,
  //       kind: actfiler,
  //       type: type,
  //       colfiler: colfiler,
  //       cmd: cmd,
  //       hash: HASH,
  //     },
  //     success: function (result) {
  //       destroySortFiler();
  //       $("#jFilerSortable").html(result);
  //       createSortFiler();
  //     },
  //   });

  //   return false;
  // });
  /* Filer */
  $(".btn-submit-HoldOn").on("click", function () {
    HoldOn.open({
      theme: "sk-circle",
      message: "Đang tải lên..."
    });
  });
  if ($("#filer-gallery").length) {
    $("#filer-gallery").filer({
      limit: null,
      maxSize: null, removeConfirmation: false,
      extensions: ["jpg", "png", "jpeg", "webp"],
      changeInput:
        '<div class="jFiler-input-dragDrop">' +
        '  <div class="jFiler-input-inner">' +
        '    <div class="jFiler-input-icon"><i class="icon-jfi-cloud-up-o"></i></div>' +
        '    <div class="jFiler-input-text">' +
        '      <h3>' + LANG["keovathahinhvaoday"] + '</h3>' +
        '      <span>' + LANG["hoac"] + '</span>' +
        '    </div>' +
        '    <a class="jFiler-input-choose-btn blue">' + LANG["chonhinh"] + '</a>' +
        '  </div>' +
        '</div>',
      theme: "dragdropbox",
      showThumbs: true,
      addMore: true,
      allowDuplicates: false,
      clipBoardPaste: false,
      dragDrop: {
        dragEnter: function () { },
        dragLeave: function () { },
        drop: function () { }
      },
      captions: {
        button: LANG["themhinh"],
        feedback: LANG["vuilongchonhinhanh"],
        feedback2: LANG["nhunghinhdaduocchon"],
        drop: LANG["keohinhvaodaydeupload"],
        removeConfirmation: LANG["banmuonloaibohinhanhnay"],
        errors: {
          filesLimit: "Chỉ được upload mỗi lần {{fi-limit}} " + LANG["hinhanh"],
          filesType: LANG["chihotrotaptinlahinhanhcodinhdang"] + ": {{fi-extensions}}",
          filesSize: LANG["hinhanh"] + " {{fi-name}} " + LANG["cokichthuocqualonvuilonguploadhinhanhcokichthuoctoida"] + " {{fi-maxSize}} MB.",
          filesSizeAll: LANG["nhunghinhanhbanchoncokichthuocqualonvuilongchonnhunghinhanhcokichthuoctoida"] + " {{fi-maxSize}} MB.",
        },
      },
      afterShow: null,
      onSelect: function () {
        HoldOn.open({
          theme: "sk-circle",
          message: "Đang xử lý hình ảnh..."
        });
        setTimeout(function () {
          const colClass = $(".col-filer").val();
          let lastOrder = 0;

          $(".jFiler-items-list li.jFiler-item").each(function () {
            lastOrder++;
            $(this).find("input[name='numb-filer[]']").val(lastOrder);
            $(this).addClass(colClass);
          });
          HoldOn.close();
        }, 50);

      },
      removeConfirmation: false,
      templates: {
        box: '<ul class="jFiler-items-list jFiler-items-grid row scroll-bar"></ul>',
        item: `<li class="jFiler-item"><div class="jFiler-item-container"><div class="jFiler-item-inner"><div class="jFiler-item-thumb"><div class="jFiler-item-status"></div><div class="jFiler-item-thumb-overlay"><div class="jFiler-item-info"><div style="display:table-cell;vertical-align:middle;"><span class="jFiler-item-title"><b title="{{fi-name}}">{{fi-name}}</b></span></div></div></div>{{fi-image}}</div><div class="jFiler-item-assets jFiler-row"><ul class="list-inline pull-right d-flex align-items-center justify-content-between w-100"><li class="ml-1"><a class="icon-jfi-trash jFiler-item-trash-action my-jFiler-item-trash" data-id="{{fi-id}}" data-photo="{{fi-photo}}"></a></li><li class="mr-1"><div class="custom-control custom-checkbox d-inline-block align-middle text-md"><input type="checkbox" class="custom-control-input filer-checkbox" id="filer-checkbox-{{fi-id}}" value="{{fi-id}}"><label for="filer-checkbox-{{fi-id}}" class="custom-control-label font-weight-normal" data-label="Chọn">Chọn</label></div></li></ul></div><input type="number" class="form-control form-control-sm mb-1" name="numb-filer[]" placeholder="${LANG['sothutu']}" value=""><input type="text" class="form-control form-control-sm" name="name-filer[]" placeholder="Tiêu đề" value=""><input type="hidden" name="id-filer[]" value="{{fi-id}}"><input type="hidden" name="photo-filer[]" value="{{fi-photo}}"></div></div></li>`,
        progressBar: '<div class="bar"></div>',
        itemAppendToEnd: true,
        canvasImage: false,
        removeConfirmation: true,
        _selectors: {
          list: ".jFiler-items-list",
          item: ".jFiler-item",
          progressBar: ".bar",
          remove: ".jFiler-item-remove-disabled"
        }
      }
    });
  }
  /* Filer import */
  if ($("#filer-import").length) {
    $("#filer-import").filer({
      limit: null,
      maxSize: null,
      extensions: [
        "jpg",
        "png",
        "jpeg",
        "JPG",
        "PNG",
        "JPEG",
        "Png",
        "WEBP",
        "webp",
      ],
      changeInput:
        '<a class="jFiler-input-choose-btn border-primary btn btn-sm bg-gradient-primary text-white mb-3"><i class="fas fa-cloud-upload-alt mr-2"></i>' +
        LANG["uploadhinhanh"] +
        "</a>",
      theme: "default",
      showThumbs: true,
      addMore: true,
      allowDuplicates: false,
      clipBoardPaste: false,
      captions: {
        button: LANG["themhinh"],
        feedback: LANG["vuilongchonhinhanh"],
        feedback2: LANG["nhunghinhdaduocchon"],
        drop: LANG["keohinhvaodaydeupload"],
        removeConfirmation: LANG["banmuonloaibohinhanhnay"],
        errors: {
          filesLimit:
            LANG["chiduocuploadmoilan"] + " {{fi-limit}} " + LANG["hinhanh"],
          filesType:
            LANG["chihotrotaptinlahinhanhcodinhdang"] + ": {{fi-extensions}}",
          filesSize:
            LANG["hinhanh"] +
            " {{fi-name}} " +
            LANG["cokichthuocqualonvuilonguploadhinhanhcokichthuoctoida"] +
            " {{fi-maxSize}} MB.",
          filesSizeAll:
            LANG[
            "nhunghinhanhbanchoncokichthuocqualonvuilongchonnhunghinhanhcokichthuoctoida"
            ] + " {{fi-maxSize}} MB.",
        },
      },
      afterShow: function () {
        var jFilerItems = $(
          ".my-jFiler-items .jFiler-items-list li.jFiler-item"
        );
        var jFilerItemsLength = 0;
        var jFilerItemsLast = 0;
        if (jFilerItems.length) {
          jFilerItemsLength = jFilerItems.length;
          jFilerItemsLast = parseInt(
            jFilerItems.last().find("input[type=number]").val()
          );
        }
        $(".jFiler-items-list li.jFiler-item").each(function (index) {
          var colClass = $(".col-filer").val();
          var parent = $(this).parent();
          if (!parent.is("#jFilerSortable")) {
            jFilerItemsLast += 1;
            $(this).find("input[type=number]").val(jFilerItemsLast);
          }
          if (!$(this).hasClass(colClass))
            $("li.jFiler-item").addClass(colClass);
        });
      },
      templates: {
        box: '<ul class="jFiler-items-list jFiler-items-grid row scroll-bar"></ul>',
        item: `<li class="jFiler-item"><div class="jFiler-item-container"><div class="jFiler-item-inner"><div class="jFiler-item-thumb"><div class="jFiler-item-status"></div><div class="jFiler-item-thumb-overlay"><div class="jFiler-item-info"><div style="display:table-cell;vertical-align:middle;"><span class="jFiler-item-title"><b title="{{fi-name}}">{{fi-name}}</b></span></div></div></div>{{fi-image}}</div><div class="jFiler-item-assets jFiler-row"><ul class="list-inline pull-right d-flex align-items-center justify-content-between w-100"><li class="ml-1"><a class="icon-jfi-trash jFiler-item-trash-action my-jFiler-item-trash" data-id="{{fi-id}}" data-photo="{{fi-photo}}"></a></li><li class="mr-1"><div class="custom-control custom-checkbox d-inline-block align-middle text-md"><input type="checkbox" class="custom-control-input filer-checkbox" id="filer-checkbox-{{fi-id}}" value="{{fi-id}}"><label for="filer-checkbox-{{fi-id}}" class="custom-control-label font-weight-normal" data-label="Chọn">Chọn</label></div></li></ul></div><input type="number" class="form-control form-control-sm mb-1" name="numb-filer[]" placeholder="${LANG['sothutu']}" value=""><input type="text" class="form-control form-control-sm" name="name-filer[]" placeholder="Tiêu đề" value=""><input type="hidden" name="id-filer[]" value="{{fi-id}}"><input type="hidden" name="photo-filer[]" value="{{fi-photo}}"></div></div></li>`,
        itemAppend: null,
        progressBar: '<div class="bar"></div>',
        itemAppendToEnd: true,
        canvasImage: false,
        removeConfirmation: true,
        _selectors: {
          list: ".jFiler-items-list",
          item: ".jFiler-item",
          progressBar: ".bar",
          remove: ".jFiler-item-trash-action"
        }
      }
    });
  }

  /* Ckeditor */
  if ($(".form-control-ckeditor").length) {
    CKEDITOR.editorConfig = function (config) {
      config.language = "vi";
      config.removePlugins = 'image';
      config.skin = 'moono-lisa';
      config.width = "auto";
      config.height = 450;
      config.allowedContent = true;
      config.entities = false;
      config.entities_latin = false;
      config.entities_greek = false;
      config.basicEntities = false;
      // config.contentsCss = [CONFIG_BASE + ADMIN + "/ck/contents.css"];
      config.extraPlugins =
        "image2,codemirror,texttransform,copyformatting,html5audio,flash,youtube,wordcount,tableresize,widget,lineutils,clipboard,dialog,dialogui,widgetselection,lineheight,video,videodetector";
      config.line_height = "1;1.1;1.2;1.3;1.4;1.5;2;2.5;3;3.5;4;4.5;5";
      config.pasteFromWordRemoveFontStyles = false;
      config.pasteFromWordRemoveStyles = false;
      config.codemirror = {
        theme: "default",
        lineNumbers: true,
        lineWrapping: true,
        matchBrackets: true,
        autoCloseTags: true,
        autoCloseBrackets: true,
        enableSearchTools: true,
        enableCodeFolding: true,
        enableCodeFormatting: true,
        autoFormatOnStart: true,
        autoFormatOnModeChange: true,
        autoFormatOnUncomment: true,
        mode: "htmlmixed",
        showSearchButton: true,
        showTrailingSpace: true,
        highlightMatches: true,
        showFormatButton: true,
        showCommentButton: true,
        showUncommentButton: true,
        showAutoCompleteButton: true,
        styleActiveLine: true,
      };
      if (PHP_VERSION > 80000) {
        config.filebrowserBrowseUrl =
          "ckfinder3.6.1/ckfinder.html?token=" + TOKEN;
        config.filebrowserUploadUrl =
          "ckfinder3.6.1/core/connector/php/connector.php?command=QuickUpload&type=Files&token=" +
          TOKEN;
      }
      if (PHP_VERSION > 70000 && PHP_VERSION < 80000) {
        config.filebrowserBrowseUrl =
          "ckfinder3.5.2/ckfinder.html?token=" + TOKEN;
        config.filebrowserUploadUrl =
          "ckfinder3.5.2/core/connector/php/connector.php?command=QuickUpload&type=Files&token=" +
          TOKEN;
      }
      if (PHP_VERSION > 50000 && PHP_VERSION < 70000) {
        config.filebrowserBrowseUrl =
          "ckfinder3.4.1/ckfinder.html?token=" + TOKEN;
        config.filebrowserUploadUrl =
          "ckfinder3.4.1/core/connector/php/connector.php?command=QuickUpload&type=Files&token=" +
          TOKEN;
      }

      config.toolbar = [
        {
          name: "document",
          items: [
            "Source",
            "-",
            "NewPage",
            "Preview",
            "Print",
            "-",
            "Templates",
          ],
        },
        {
          name: "clipboard",
          items: [
            "Cut",
            "Copy",
            "Paste",
            "PasteText",
            "PasteFromWord",
            "PasteFromExcel",
            "-",
            "Undo",
            "Redo",
          ],
        },
        {
          name: "editing",
          items: ["Find", "Replace", "-", "SelectAll", "-", "Scayt"],
        },
        {
          name: "forms",
          items: [
            "Form",
            "Checkbox",
            "Radio",
            "TextField",
            "Textarea",
            "Select",
            "Button",
            "ImageButton",
            "HiddenField",
          ],
        },
        "/",
        {
          name: "basicstyles",
          items: [
            "Bold",
            "Italic",
            "Underline",
            "Strike",
            "Subscript",
            "Superscript",
            "-",
            "CopyFormatting",
            "RemoveFormat",
          ],
        },
        {
          name: "texttransform",
          items: [
            "TransformTextToUppercase",
            "TransformTextToLowercase",
            "TransformTextCapitalize",
            "TransformTextSwitcher",
          ],
        },
        {
          name: "paragraph",
          items: [
            "NumberedList",
            "BulletedList",
            "-",
            "Outdent",
            "Indent",
            "-",
            "Blockquote",
            "CreateDiv",
            "-",
            "JustifyLeft",
            "JustifyCenter",
            "JustifyRight",
            "JustifyBlock",
            "-",
            "BidiLtr",
            "BidiRtl",
            "Language",
          ],
        },
        { name: "links", items: ["Link", "Unlink", "Anchor"] },
        {
          name: "insert",
          items: [
            "Image",
            "Flash",
            "Youtube",
            "VideoDetector",
            "Video",
            "Html5audio",
            "Iframe",
            "Table",
            "HorizontalRule",
            "Smiley",
            "SpecialChar",
            "PageBreak",
          ],
        },
        "/",
        {
          name: "styles",
          items: ["Styles", "Format", "Font", "FontSize", "lineheight"],
        },
        { name: "colors", items: ["TextColor", "BGColor"] },
        { name: "tools", items: ["Maximize", "ShowBlocks"] },
        { name: "about", items: ["About"] },
      ];

      config.stylesSet = [
        {
          name: "Font Seguoe Regular",
          element: "span",
          attributes: { class: "segui" },
        },
        {
          name: "Font Seguoe Semibold",
          element: "span",
          attributes: { class: "seguisb" },
        },
        {
          name: "Italic title",
          element: "span",
          styles: { "font-style": "italic" },
        },
        {
          name: "Special Container",
          element: "div",
          styles: {
            background: "#eee",
            border: "1px solid #ccc",
            padding: "5px 10px",
          },
        },
        { name: "Big", element: "big" },
        { name: "Small", element: "small" },
        { name: "Inline ", element: "q" },
        { name: "marker", element: "span", attributes: { class: "marker" } },
      ];

      /* Config Wordcount */
      config.wordcount = {
        showParagraphs: true,
        showWordCount: true,
        showCharCount: true,
        countSpacesAsChars: false,
        countHTML: false,
        filter: new CKEDITOR.htmlParser.filter({
          elements: {
            div: function (element) {
              if (element.attributes.class == "mediaembed") {
                return false;
              }
            },
          },
        }),
      };
    };
  }

  /* apexMixedChart */
  if ($("#apexMixedChart").length) {
    var apexMixedChart;
    var options = {
      colors: ["#3c8dbc"],
      chart: {
        id: "apexMixedChart",
        height: 450,
        type: "line",
        dropShadow: {
          enabled: true,
          color: "#000",
          top: 18,
          left: 7,
          blur: 20,
          opacity: 0.2,
        },
      },
      series: [
        {
          name: "Thống kê truy cập tháng " + CHARTS["month"],
          type: "line",
          data: CHARTS["series"],
        },
      ],
      stroke: {
        curve: "smooth",
      },
      grid: {
        borderColor: "#e7e7e7",
        row: {
          colors: ["#f3f3f3", "transparent"],
          opacity: 0.5,
        },
      },
      markers: {
        size: 1,
      },
      dataLabels: {
        enabled: false,
      },
      labels: CHARTS["labels"],
      legend: {
        position: "top",
        horizontalAlign: "right",
        floating: true,
        offsetY: -25,
        offsetX: -5,
      },
    };

    apexMixedChart = new ApexCharts(
      document.querySelector("#apexMixedChart"),
      options
    );
    apexMixedChart.render();
  }
});
