<?php
require_once __DIR__ . '/../init.php';
$db = new Database();

$id_list = (int)($_POST['id_list'] ?? 0);
if ($id_list <= 0) {
  echo '<tr><td colspan="100" class="text-center">Vui lòng chọn danh mục</td></tr>';
  exit;
}

$table = "tbl_danhmuc_c2";
$linkDelete = "index.php?page=delete&table=$table&id=";
$linkEdit = "index.php?page=danhmuc_c2_form&id=";

$query = "SELECT * FROM $table WHERE id_list = $id_list ORDER BY numb, id DESC";
$result = $db->select($query);
$html = '';

if ($result && $result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $id = $row['id'];
    $name = htmlspecialchars($row['namevi']);
    $file = empty($row['file']) ? NO_IMG : BASE_ADMIN . UPLOADS . $row['file'];

    $html .= '<tr>';

    // Checkbox
    $html .= '
    <td class="align-middle">
      <div class="custom-control custom-checkbox my-checkbox">
        <input type="checkbox" class="custom-control-input select-checkbox" id="select-checkbox-' . $id . '" value="' . $id . '" name="checkbox_id' . $id . '" />
        <label for="select-checkbox-' . $id . '" class="custom-control-label"></label>
      </div>
    </td>';

    // STT
    $html .= '
    <td class="align-middle">
      <input type="number" class="form-control form-control-mini m-auto update-numb" min="0" value="' . $row['numb'] . '" data-id="' . $id . '" data-table="' . $table . '" />
    </td>';

    // Hình ảnh
    $html .= '
    <td class="align-middle">
      <a href="' . $linkEdit . $id . '" title="' . $name . '">
        <img src="' . $file . '" class="rounded img-preview" alt="' . $name . '" />
      </a>
    </td>';

    // Tên
    $html .= '
    <td class="align-middle">
      <a class="text-dark text-break" href="' . $linkEdit . $id . '" title="' . $name . '">' . $name . '</a>
    </td>';

    // Hiển thị / Nổi bật
    foreach (['hienthi', 'noibat'] as $attr) {
      $checked = (strpos($row['status'], $attr) !== false) ? 'checked' : '';
      $html .= '
    <td class="align-middle text-center">
      <label class="switch switch-success">
        <input type="checkbox" class="switch-input custom-control-input show-checkbox" id="show-checkbox-' . $attr . '-' . $id . '" data-table="' . $table . '" data-id="' . $id . '" data-attr="' . $attr . '" ' . $checked . '>
        <span class="switch-toggle-slider">
          <span class="switch-on"><i class="fa-solid fa-check"></i></span>
          <span class="switch-off"><i class="fa-solid fa-xmark"></i></span>
        </span>
      </label>
    </td>';
    }

    // Thao tác
    $html .= '
    <td class="align-middle text-center text-md text-nowrap">
      <a class="text-primary mr-2" href="' . $linkEdit . $id . '" title="Chỉnh sửa">
        <i class="fas fa-edit"></i>
      </a>
      <a class="text-danger" id="delete-item" data-url="' . $linkDelete . $id . '" title="Xóa">
        <i class="fas fa-trash-alt"></i>
      </a>
    </td>';

    $html .= '
  </tr>';
  }
} else {
  $html .= '<tr>
    <td colspan="100" class="text-center">Không có dữ liệu</td>
  </tr>';
}

echo $html;
exit;
