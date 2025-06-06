/*
$.fn.lightGalleryNews = function() {
    $(this).each(function(n) {
        var i = $(this),
            r = i.attr("title"),
            t = i.attr("src");
        t != "upload/hinhanh/logo-final-2337.png" && t != "/assets/img/embed.png" && t != "/content/images/video.png" && t != "/images/gl.png" && t != "/images/fb.png" && (i.replaceWith("<a data-src='" + t + "' title = '' class='imageGallery' id='imageGallery-" + n + "'>" + this.outerHTML + "<\/a>"))
    });
	
    $(".content-main").lightGallery({
        desc: !0,
        loop: !0,
        hash: 0,
        download: 0,
        escKey: !0,
        lang: {
            allPhotos: "Tất cả hình ảnh"
        },
        selector: ".imageGallery"
    })
}
$(document).ready(function() {
	$('.content-main img').lightGalleryNews();
});
*/
function isExist(ele) {
	return ele.length;
}

function isNumeric(value) {
	return /^\d+$/.test(value);
}

function getLen(str) {
	return /^\s*$/.test(str) ? 0 : str.length;
}

function showNotify(text = 'Notify text', title = LANG['thongbao'], status = 'success') {
	new Notify({
		status: status, // success, warning, error
		title: title,
		text: text,
		effect: 'fade',
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
		position: 'right top'
	});
}

function notifyDialog(content = '', title = LANG['thongbao'], icon = 'fas fa-exclamation-triangle', type = 'blue') {
	$.alert({
		title: title,
		icon: icon, // font awesome
		type: type, // red, green, orange, blue, purple, dark
		content: content, // html, text
		backgroundDismiss: true,
		animationSpeed: 600,
		animation: 'zoom',
		closeAnimation: 'scale',
		typeAnimated: true,
		animateFromElement: false,
		autoClose: 'accept|3000',
		escapeKey: 'accept',
		buttons: {
			accept: {
				text: LANG['dongy'],
				btnClass: 'btn-sm btn-primary'
			}
		}
	});
}

function confirmDialog(action, text, value, title = LANG['thongbao'], icon = 'fas fa-exclamation-triangle', type = 'blue') {
	$.confirm({
		title: title,
		icon: icon, // font awesome
		type: type, // red, green, orange, blue, purple, dark
		content: text, // html, text
		backgroundDismiss: true,
		animationSpeed: 600,
		animation: 'zoom',
		closeAnimation: 'scale',
		typeAnimated: true,
		animateFromElement: false,
		autoClose: 'cancel|3000',
		escapeKey: 'cancel',
		buttons: {
			success: {
				text: LANG['dongy'],
				btnClass: 'btn-sm btn-primary',
				action: function () {
					if (action == 'delete-procart') deleteCart(value);
				}
			},
			cancel: {
				text: LANG['huy'],
				btnClass: 'btn-sm btn-danger'
			}
		}
	});
}

function validateForm(ele = '') {
	if (ele) {
		$('.' + ele)
			.find('input[type=submit]')
			.removeAttr('disabled');
		var forms = document.getElementsByClassName(ele);
		var validation = Array.prototype.filter.call(forms, function (form) {
			form.addEventListener(
				'submit',
				function (event) {
					if (form.checkValidity() === false) {
						event.preventDefault();
						event.stopPropagation();
					}
					form.classList.add('was-validated');
				},
				false
			);
		});
	}
}

function readImage(inputFile, elementPhoto) {
	if (inputFile[0].files[0]) {
		if (inputFile[0].files[0].name.match(/.(jpg|jpeg|png|gif)$/i)) {
			var size = parseInt(inputFile[0].files[0].size) / 1024;

			if (size <= 4096) {
				var reader = new FileReader();
				reader.onload = function (e) {
					$(elementPhoto).attr('src', e.target.result);
				};
				reader.readAsDataURL(inputFile[0].files[0]);
			} else {
				notifyDialog(LANG['dungluonghinhanhlon']);
				return false;
			}
		} else {
			$(elementPhoto).attr('src', '');
			notifyDialog(LANG['dinhdanghinhanhkhonghople']);
			return false;
		}
	} else {
		$(elementPhoto).attr('src', '');
		return false;
	}
}

function photoZone(eDrag, iDrag, eLoad) {
	if ($(eDrag).length) {
		/* Drag over */
		$(eDrag).on('dragover', function () {
			$(this).addClass('drag-over');
			return false;
		});

		/* Drag leave */
		$(eDrag).on('dragleave', function () {
			$(this).removeClass('drag-over');
			return false;
		});

		/* Drop */
		$(eDrag).on('drop', function (e) {
			e.preventDefault();
			$(this).removeClass('drag-over');

			var lengthZone = e.originalEvent.dataTransfer.files.length;

			if (lengthZone == 1) {
				$(iDrag).prop('files', e.originalEvent.dataTransfer.files);
				readImage($(iDrag), eLoad);
			} else if (lengthZone > 1) {
				notifyDialog(LANG['banchiduocchon1hinhanhdeuplen']);
				return false;
			} else {
				notifyDialog(LANG['dulieukhonghople']);
				return false;
			}
		});

		/* File zone */
		$(iDrag).change(function () {
			readImage($(this), eLoad);
		});
	}
}

function generateCaptcha(action, id) {
	if (RECAPTCHA_ACTIVE && action && id && $('#' + id).length) {
		grecaptcha.execute(RECAPTCHA_SITEKEY, { action: action }).then(function (token) {
			var recaptchaResponse = document.getElementById(id);
			recaptchaResponse.value = token;
		});
	}
}

function loadPaging(url = '', eShow = '') {
	if ($(eShow).length && url) {
		$.ajax({
			url: url,
			type: 'GET',
			data: {
				eShow: eShow
			},
			success: function (result) {
				$(eShow).html(result);
				NN_FRAMEWORK.Lazys();
			}
		});
	}
}

function doEnter(event, obj) {
	if (event.keyCode == 13 || event.which == 13) onSearch(obj);
}

function onSearch(obj) {
	var keyword = $('#' + obj).val();

	if (keyword == '') {
		notifyDialog(LANG['no_keywords']);
		return false;
	} else {
		location.href = 'tim-kiem?keyword=' + encodeURI(keyword);
	}
}

function doEnterMobile(event, obj) {
	if (event.keyCode == 13 || event.which == 13) onSearchMobile(obj);
}

function onSearchMobile(obj) {
	var keyword = $('#' + obj).val();

	if (keyword == '') {
		$('.search-mobile').toggleClass('show');
		return false;
	} else {
		location.href = 'tim-kiem?keyword=' + encodeURI(keyword);
	}
}

function goToByScroll(id, minusTop) {
	minusTop = parseInt(minusTop) ? parseInt(minusTop) : 0;
	id = id.replace('#', '');
	$('html,body').animate(
		{
			scrollTop: $('#' + id).offset().top - minusTop
		},
		'slow'
	);
}

function holdonOpen(theme = 'sk-circle',text = 'Loading...',backgroundColor = 'rgba(0,0,0,0.8)',textColor = 'white') {
	var options = {
		theme: theme,
		message: text,
		backgroundColor: backgroundColor,
		textColor: textColor
	};

	HoldOn.open(options);
}

function holdonClose() {
	HoldOn.close();
}

function updateCart(id = 0, color = 0, size = 0, code = '', quantity = 1) {
	if (id) {
		var formCart = $('.form-cart');
		var ward = formCart.find('.select-ward-cart').val();

		$.ajax({
			type: 'POST',
			url: 'api/cart.php',
			dataType: 'json',
			data: {
				cmd: 'update-cart',
				id: id,
				color: color,
                size: size,
                code: code,
				quantity: quantity,
				ward: ward
			},
			beforeSend: function () {
				holdonOpen();
			},
			success: function (result) {
				if (result) {
					formCart.find('.load-price-' + code).html(result.regularPrice);
					formCart.find('.load-price-new-' + code).html(result.salePrice);
					formCart.find('.load-price-temp').html(result.tempText);
					formCart.find('.load-price-total').html(result.totalText);
				}
				holdonClose();
			}
		});
	}
}

function deleteCart(obj) {
	var formCart = $('.form-cart');
	var code = obj.data('code');
	var ward = formCart.find('.select-ward-cart').val();

	$.ajax({
		type: 'POST',
		url: 'api/cart.php',
		dataType: 'json',
		data: {
			cmd: 'delete-cart',
			code: code,
			ward: ward
		},
		beforeSend: function () {
			holdonOpen();
		},
		success: function (result) {
			$('.count-cart').html(result.max);
			if (result.max) {
				formCart.find('.load-price-temp').html(result.tempText);
				formCart.find('.load-price-total').html(result.totalText);
				formCart.find('.procart-' + code).remove();
			} else {
				$('.wrap-cart').html(
					'<div class="empty-cart text-decoration-none"><i class="fa-duotone fa-cart-xmark"></i><p>' +
						LANG['no_products_in_cart'] +
						'</p><a href="" class="btn btn-warning">' +
						LANG['back_to_home'] +
						'</a></div>'
				);
			}
			holdonClose();
		}
	});
}

function loadDistrict(id = 0) {
	holdonOpen();
	fetch(CONFIG_BASE+`assets/jsons/district-`+id+`.json`,{headers: {"Content-Type": "application/json"}}).then(response => {
		return response.json();
	}).then(function(data) {
		$('.select-district').html(`<option value="">Quận/huyện</option>`);
		$('.select-ward').html('<option value="">' + LANG['ward'] + '</option>');
		$.each(data, function(index, val) {
			$('.select-district').append(`<option value="`+val.id+`">`+val.name+`</option>`);
		});
		holdonClose()
	});
}

function loadWard(city = 0,id = 0) {
	holdonOpen();
	fetch(CONFIG_BASE+`assets/jsons/wards-`+city+`-`+id+`.json`,{headers: {"Content-Type": "application/json"}}).then(response => {
		return response.json();
	}).then(function(data) {
		$('.select-ward').html(`<option value="">`+LANG['ward']+`</option>`);
		$.each(data, function(index, val) {
			$('.select-ward').append(`<option value="`+val.id+`">`+val.name+`</option>`);
		});
		holdonClose()
	});
}

function loadShip(id = 0) {
	if (SHIP_CART) {
		var formCart = $('.form-cart');

		$.ajax({
			type: 'POST',
			url: 'api/cart.php',
			dataType: 'json',
			data: {
				cmd: 'ship-cart',
				id: id
			},
			beforeSend: function () {
				holdonOpen();
			},
			success: function (result) {
				if (result) {
					formCart.find('.load-price-ship').html(result.shipText);
					formCart.find('.load-price-total').html(result.totalText);
				}
				holdonClose();
			}
		});
	}
}

function FirstLoadAPI(div, url, name_api) {
    $(div).addClass('active');
    var id = $(div).data('id');
    var tenkhongdau = $(div).data('tenkhongdau');
    FrameAjax(url, "POST", {
        id: id,
        tenkhongdau: tenkhongdau
    }, name_api);
}

function LoadAPI(div, url, name_api) {
    $(div).click(function(event) {
        $(div).removeClass('active');
        $(this).addClass('active');
        var id = $(this).data('id');
        var tenkhongdau = $(this).data('tenkhongdau');
        FrameAjax(url, "POST", {
            id: id,
            tenkhongdau: tenkhongdau
        }, name_api);
    });
}

function FrameAjax(url, type, data, name) {
    $.ajax({
        url: url,
        type: type,
        data: data,
        success: function(data) {
            $(name).html(data);
            NN_FRAMEWORK.Lazys();
        }
    });
}
function getPriceVariation(parents, id_pro, id_color, id_size){
    $.getJSON(PATH_JSON + 'variation-'+id_pro+'.json?v=' + stringRandom(5), function(data) {
        parents.find('.size-pro-detail').removeClass('disable');
        parents.find('.color-pro-detail').removeClass('disable');
        $.each(data, function(index, val) {
            if(val['id_color'] == id_color && val['id_size'] == id_size){
                if(val['sale_price'] > 0){
                    parents.find('.price-new-pro-detail').html(formatPrice(val['sale_price']));
                    parents.find('.price-old-pro-detail').html(formatPrice(val['regular_price']));
                }else{
                    parents.find('.price-new-pro-detail').html(formatPrice(val['regular_price']));
                    parents.find('.price-old-pro-detail').html('');
                }
            }
            if(val['id_color'] == id_color && val['status'] == 0){
				console.log('xx2');
                parents.find('.size-pro-detail-'+val['id_size']).addClass('disable');
            }
            if(val['id_size'] == id_size && val['status'] == 0){
				console.log('xx3');
                parents.find('.color-pro-detail-'+val['id_color']).addClass('disable');
            }
        });
    });
    return false;
}

function formatPrice(price) {
    const amount = Math.abs(Number(price)) || 0;
    const parts = amount.toFixed(0).split('.');
    const formattedAmount = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    const formattedPrice = formattedAmount + ' đ';

    return formattedPrice;
}

/* Radndom */
function stringRandom(length) {
    var result = '';
    var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    var charactersLength = characters.length;

    for (var i = 0; i < length; i++) {
        result += characters.charAt(Math.floor(Math.random() * charactersLength));
    }

    return result;
}
