<div class="card-footer text-sm sticky-top">
  <a class="btn btn-sm bg-gradient-primary text-white" href="<?= $linkAdd ?>" title="Thêm mới"><i
      class="fas fa-plus mr-2"></i>Thêm mới</a>
  <a class="btn btn-sm bg-gradient-danger text-white" id="delete-all" data-url="<?= $linkMulti ?>" title="Xóa tất cả"><i
      class="far fa-trash-alt mr-2"></i>Xóa tất cả</a>
  <div class="form-inline form-search d-inline-block align-middle ml-3">
    <div class="input-group input-group-sm">
      <input class="form-control form-control-navbar text-sm" type="search" id="keyword" placeholder="Tìm kiếm"
        aria-label="Tìm kiếm" value=""
        onkeypress="doEnter(event,'keyword','<?= basename($_SERVER['PHP_SELF']) . '?' . $_SERVER['QUERY_STRING']; ?>')" />
      <div class="input-group-append bg-primary rounded-right">
        <button class="btn btn-navbar text-white" type="button"
          onclick="onSearch('keyword','<?= basename($_SERVER['PHP_SELF']) . '?' . $_SERVER['QUERY_STRING']; ?>')">
          <i class="fas fa-search"></i>
        </button>
      </div>
    </div>
  </div>
</div>
