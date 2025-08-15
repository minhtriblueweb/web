<div class="wrap-main wrap-home w-clear">
  <div class="wrap-product-list">
    <div class="title-list-hot mt-4">
      <h2><?= $titleMain ?></h2>
      <div class="animate-border bg-animate-border mt-1"></div>
    </div>
    <div class="wrap-content" style="background: unset;">
      <div class="content-main">
        <div class="contact-article row">
          <div class="contact-text col-lg-6 mb-3" data-aos="fade-right" data-aos-duration="500">
            <?= $static["content"] ?></div>
          <form data-aos="fade-left" data-aos-duration="500" class="contact-form validation-contact col-lg-6 mb-3"
            novalidate="" method="post" action="" enctype="multipart/form-data">
            <div class="social">
              <p class="text-uppercase"><?= dangkynhantin ?></p>
            </div>
            <div class="row-20 row">
              <div class="contact-input col-sm-6 col-20">
                <div class="form-floating form-floating-cus">
                  <input type="text" name="dataContact[fullname]" class="form-control text-sm" id="fullname-contact" placeholder="<?= hoten ?>" value="" required="" fdprocessedid="f1s2yo">
                  <label for="fullname-contact"><?= hoten ?></label>
                </div>
                <div class="invalid-feedback"><?= vuilongnhaphoten ?></div>
              </div>
              <div class="contact-input col-sm-6 col-20">
                <div class="form-floating form-floating-cus">
                  <input type="number" name="dataContact[phone]" class="form-control text-sm" id="phone-contact" placeholder="<?= dienthoai ?>" value="" required="" fdprocessedid="82xoo">
                  <label for="phone-contact"><?= dienthoai ?></label>
                </div>
                <div class="invalid-feedback"><?= vuilongnhapsodienthoai ?></div>
              </div>
              <div class="contact-input col-sm-6 col-20">
                <div class="form-floating form-floating-cus">
                  <input type="text" class="form-control text-sm" id="address-contact" name="dataContact[address]" placeholder="<?= diachi ?>" value="" required="" data-gtm-form-interact-field-id="0" fdprocessedid="660gi">
                  <label for="address-contact"><?= diachi ?></label>
                </div>
                <div class="invalid-feedback"><?= vuilongnhapdiachi ?></div>
              </div>
              <div class="contact-input col-sm-6 col-20">
                <div class="form-floating form-floating-cus">
                  <input type="email" class="form-control text-sm" id="email-contact" name="dataContact[email]" placeholder="Email" value="" required="" fdprocessedid="dwej1">
                  <label for="email-contact">Email</label>
                </div>
                <div class="invalid-feedback"><?= vuilongnhapdiachiemail ?></div>
              </div>
            </div>

            <div class="contact-input">
              <div class="form-floating form-floating-cus">
                <input type="text" class="form-control text-sm" id="subject-contact" name="dataContact[subject]" placeholder="<?= chude ?>" value="" required="" fdprocessedid="v2fyar">
                <label for="subject-contact"><?= chude ?></label>
              </div>
              <div class="invalid-feedback"><?= vuilongnhapchude ?></div>
            </div>
            <div class="contact-input">
              <div class="form-floating form-floating-cus">
                <textarea class="form-control text-sm" id="content-contact" name="dataContact[content]" placeholder="<?= noidung ?>" required=""></textarea>
                <label for="content-contact"><?= noidung ?></label>
              </div>
              <div class="invalid-feedback"><?= vuilongnhapnoidung ?></div>
            </div>
            <input type="submit" class="btn btn-primary mr-2" name="submit-contact" value="<?= gui ?>">
            <input type="reset" class="btn btn-secondary" value="<?= nhaplai ?>">
          </form>
        </div>
        <div class="contact-map"><?= $coords_iframe ?></div>
      </div>
    </div>
  </div>
</div>
