<div class="wrap-main wrap-home w-clear">
  <div class="wrap-product-list">
    <div class="title-list-hot mt-4">
      <h2>Liên Hệ</h2>
      <div class="animate-border bg-animate-border mt-1"></div>
    </div>
    <div class="wrap-content" style="background: unset;">
      <div class="content-main">
        <div class="contact-article row">
          <div class="contact-text col-lg-6 mb-3" data-aos="fade-right" data-aos-duration="500">
            <?= $row_lh['content' . $lang] ?></div>
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
        <div class="contact-map"><?= $coords_iframe ?></div>
      </div>
    </div>
  </div>
</div>
