<?php include TEMPLATE . LAYOUT . 'loader.php'; ?>
<section class="content mb-3">
  <div class="container-fluid">
    <h5 class="pt-3 pb-2">Bảng điều khiển</h5>
    <div class="row mb-2 text-sm">
      <div class="col-12 col-sm-6 col-md-3">
        <a class="my-info-box info-box" href="index.php?com=setting&act=update" title="Cấu hình website">
          <span class="my-info-box-icon info-box-icon bg-primary"><i class="fas fa-cogs"></i></span>
          <div class="info-box-content text-dark">
            <span class="info-box-text text-capitalize">Cấu hình website</span>
            <span class="info-box-number">Xem thêm</span>
          </div>
        </a>
      </div>
      <div class="col-12 col-sm-6 col-md-3">
        <a class="my-info-box info-box" href="index.php?com=user&act=info_admin" title="Tài khoản">
          <span class="my-info-box-icon info-box-icon bg-danger"><i class="fas fa-user-cog"></i></span>
          <div class="info-box-content text-dark">
            <span class="info-box-text text-capitalize">Tài khoản</span>
            <span class="info-box-number">Xem thêm</span>
          </div>
        </a>
      </div>
      <div class="clearfix hidden-md-up"></div>
      <div class="col-12 col-sm-6 col-md-3">
        <a class="my-info-box info-box" href="index.php?com=user&act=info_admin&changepass=1" title="Đổi mật khẩu">
          <span class="my-info-box-icon info-box-icon bg-success"><i class="fas fa-key"></i></span>
          <div class="info-box-content text-dark">
            <span class="info-box-text text-capitalize">Đổi mật khẩu</span>
            <span class="info-box-number">Xem thêm</span>
          </div>
        </a>
      </div>
      <div class="col-12 col-sm-6 col-md-3">
        <a class="my-info-box info-box" href="index.php?com=contact&act=man" title="Thư liên hệ">
          <span class="my-info-box-icon info-box-icon bg-info"><i class="fas fa-address-book"></i></span>
          <div class="info-box-content text-dark">
            <span class="info-box-text text-capitalize">Thư liên hệ</span>
            <span class="info-box-number">Xem thêm</span>
          </div>
        </a>
      </div>
    </div>
  </div>
</section>
<section class="content pb-4">
  <div class="container-fluid">
    <div class="card">
      <div class="card-header">
        <h5 class="mb-0">Thống kê truy cập tháng 09/2024</h5>
      </div>
      <div class="card-body">
        <form class="form-filter-charts row align-items-center mb-1" action="index.php" method="get" name="form-thongke"
          accept-charset="utf-8">
          <div class="col-md-4">
            <div class="form-group">
              <select class="form-control select2" name="month" id="month">
                <option value="">Chọn tháng</option>
                <option value="1">Tháng 1</option>
                <option value="2">Tháng 2</option>
                <option value="3">Tháng 3</option>
                <option value="4">Tháng 4</option>
                <option value="5">Tháng 5</option>
                <option value="6">Tháng 6</option>
                <option value="7">Tháng 7</option>
                <option value="8">Tháng 8</option>
                <option value="9" selected>Tháng 9</option>
                <option value="10">Tháng 10</option>
                <option value="11">Tháng 11</option>
                <option value="12">Tháng 12</option>
              </select>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <select class="form-control select2" name="year" id="year">
                <option value="">Chọn năm</option>
                <option value="2000">Năm 2000</option>
                <option value="2001">Năm 2001</option>
                <option value="2002">Năm 2002</option>
                <option value="2003">Năm 2003</option>
                <option value="2004">Năm 2004</option>
                <option value="2005">Năm 2005</option>
                <option value="2006">Năm 2006</option>
                <option value="2007">Năm 2007</option>
                <option value="2008">Năm 2008</option>
                <option value="2009">Năm 2009</option>
                <option value="2010">Năm 2010</option>
                <option value="2011">Năm 2011</option>
                <option value="2012">Năm 2012</option>
                <option value="2013">Năm 2013</option>
                <option value="2014">Năm 2014</option>
                <option value="2015">Năm 2015</option>
                <option value="2016">Năm 2016</option>
                <option value="2017">Năm 2017</option>
                <option value="2018">Năm 2018</option>
                <option value="2019">Năm 2019</option>
                <option value="2020">Năm 2020</option>
                <option value="2021">Năm 2021</option>
                <option value="2022">Năm 2022</option>
                <option value="2023">Năm 2023</option>
                <option value="2024" selected>Năm 2024</option>
                <option value="2025">Năm 2025</option>
                <option value="2026">Năm 2026</option>
                <option value="2027">Năm 2027</option>
                <option value="2028">Năm 2028</option>
                <option value="2029">Năm 2029</option>
                <option value="2030">Năm 2030</option>
                <option value="2031">Năm 2031</option>
                <option value="2032">Năm 2032</option>
                <option value="2033">Năm 2033</option>
                <option value="2034">Năm 2034</option>
                <option value="2035">Năm 2035</option>
                <option value="2036">Năm 2036</option>
                <option value="2037">Năm 2037</option>
                <option value="2038">Năm 2038</option>
                <option value="2039">Năm 2039</option>
                <option value="2040">Năm 2040</option>
                <option value="2041">Năm 2041</option>
                <option value="2042">Năm 2042</option>
                <option value="2043">Năm 2043</option>
                <option value="2044">Năm 2044</option>
              </select>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <button type="submit" class="btn btn-success">
                Thống Kê
              </button>
            </div>
          </div>
        </form>
        <div id="apexMixedChart"></div>
      </div>
    </div>
  </div>
</section>
