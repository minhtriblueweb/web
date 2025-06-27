<?php
$get_lienhe = $trangtinh->get_static('lienhe');
if ($get_lienhe) {
  $result_lienhe = $get_lienhe->fetch_assoc();
}
$seo['title'] = $result_lienhe['titlevi'];
$seo['keywords'] = $result_lienhe['keywordsvi'];
$seo['description'] = $result_lienhe['descriptionvi'];
$seo['url'] = $result_lienhe['slugvi'] ?? '';
$seo['image'] = isset($result_lienhe['file']) ? BASE_ADMIN . UPLOADS . $result_lienhe['file'] : '';
?>
<div class="wrap-main wrap-home w-clear" style="background:#fff">
  <div class="breadCrumbs">
    <div class="wrap-content">
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a class="text-decoration-none" href="<?= BASE ?>"><span>Trang chủ</span></a>
        </li>
        <li class="breadcrumb-item">
          <a class="text-decoration-none" href=""><span>Liên Hệ</span></a>
        </li>
      </ol>
    </div>
  </div>
  <div class="wrap-product-list">
    <div class="title-list-hot mt-4">
      <h2>Liên Hệ</h2>
      <div class="animate-border bg-danger mt-1"></div>
    </div>

    <div class="wrap-content" style="background: unset;">
      <div class="content-main">
        <div class="contact-article row">
          <div class="contact-text col-lg-6 mb-3" data-aos="fade-right" data-aos-duration="500">
            <?= $result_lienhe['contentvi'] ?></div>
          <form data-aos="fade-left" data-aos-duration="500" class="contact-form validation-contact col-lg-6 mb-3"
            novalidate="" method="post" action="" enctype="multipart/form-data">
            <div class="social">
              <p>ĐĂNG KÝ NHẬN TƯ VẤN</p>
            </div>
            <div class="form-row">
              <div class="contact-input col-sm-6">
                <input type="text" class="form-control text-sm" id="fullname-contact" name="dataContact[fullname]"
                  placeholder="Họ tên" value="" required="">
                <div class="invalid-feedback">Vui lòng nhập họ và tên</div>
              </div>
              <div class="contact-input col-sm-6">
                <input type="number" class="form-control text-sm" id="phone-contact" name="dataContact[phone]"
                  placeholder="Số điện thoại" value="" required="">
                <div class="invalid-feedback">Vui lòng nhập số điện thoại</div>
              </div>
            </div>
            <div class="form-row">
              <div class="contact-input col-sm-6">
                <input type="text" class="form-control text-sm" id="address-contact" name="dataContact[address]"
                  placeholder="Địa chỉ" value="" required="">
                <div class="invalid-feedback">Vui lòng nhập địa chỉ</div>
              </div>
              <div class="contact-input col-sm-6">
                <input type="email" class="form-control text-sm" id="email-contact" name="dataContact[email]"
                  placeholder="Email" value="" required="">
                <div class="invalid-feedback">Vui lòng nhập địa chỉ email</div>
              </div>
            </div>
            <div class="contact-input">
              <input type="text" class="form-control text-sm" id="subject-contact" name="dataContact[subject]"
                placeholder="Chủ đề" value="" required="">
              <div class="invalid-feedback">Vui lòng nhập chủ đề</div>
            </div>
            <div class="contact-input">
              <textarea class="form-control text-sm" id="content-contact" name="dataContact[content]"
                placeholder="Nội dung" required=""></textarea>
              <div class="invalid-feedback">Vui lòng nhập nội dung</div>
            </div>

            <input type="submit" class="btn btn-primary mr-2" name="submit-contact" value="Gửi">
            <input type="reset" class="btn btn-secondary" value="Nhập lại">
          </form>
        </div>
        <div class="contact-map"><?= $coords_iframe ?>
        </div>
      </div>
    </div>
  </div>
</div>
