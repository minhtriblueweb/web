<div class="card-footer text-sm sticky-top">
  <button type="submit" name="<?= !empty($id) ? "edit" : "add"; ?>"
    class="btn btn-sm bg-gradient-primary submit-check" disabled>
    <i class="far fa-save mr-2"></i>Lưu
  </button>
  <button type="reset" class="btn btn-sm bg-gradient-secondary">
    <i class="fas fa-redo mr-2"></i>Làm lại
  </button>
  <a class="btn btn-sm bg-gradient-danger" href="<?= $fn->getRedirectPath($table) ?>" title="Thoát"><i class="fas fa-sign-out-alt mr-2"></i>Thoát</a>
</div>
