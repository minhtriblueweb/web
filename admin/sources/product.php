<?php
if (!defined('SOURCES')) die("Error");

/* Kiểm tra active product */
if (isset($config['product'])) {
  $arrCheck = array();
  foreach ($config['product'] as $k => $v) $arrCheck[] = $k;
  if (!count($arrCheck) || !in_array($type, $arrCheck)) $func->transfer(trangkhongtontai, "index.php", false);
} else {
  $func->transfer(trangkhongtontai, "index.php", false);
}

/* Cấu hình đường dẫn trả về */
$strUrl = "";
$arrUrl = array('id_list', 'id_cat', 'id_item', 'id_sub', 'id_vari', 'id_brand');
if (isset($_POST['data'])) {
  $dataUrl = isset($_POST['data']) ? $_POST['data'] : null;
  if ($dataUrl) {
    foreach ($arrUrl as $k => $v) {
      if (isset($dataUrl[$arrUrl[$k]])) $strUrl .= "&" . $arrUrl[$k] . "=" . htmlspecialchars($dataUrl[$arrUrl[$k]]);
    }
  }
} else {
  foreach ($arrUrl as $k => $v) {
    if (isset($_REQUEST[$arrUrl[$k]])) $strUrl .= "&" . $arrUrl[$k] . "=" . htmlspecialchars($_REQUEST[$arrUrl[$k]]);
  }

  if (!empty($_REQUEST['comment_status'])) $strUrl .= "&comment_status=" . htmlspecialchars($_REQUEST['comment_status']);
  if (isset($_REQUEST['keyword'])) $strUrl .= "&keyword=" . htmlspecialchars($_REQUEST['keyword']);
}

switch ($act) {
  /* Man */
  case "man":
    viewMans();
    $template = "product/man/mans";
    break;
  case "add":
    $template = "product/man/man_add";
    break;
  case "edit":
  case "copy":
    if ((!isset($config['product'][$type]['copy']) || $config['product'][$type]['copy'] == false) && $act == 'copy') {
      $template = "404";
      return false;
    }
    editMan();
    $template = "product/man/man_add";
    break;
  case "save":
  case "save_copy":
    saveMan();
    break;
  case "delete":
    deleteMan();
    break;

  /* Size */
  case "man_size":
    viewSizes();
    $template = "product/size/sizes";
    break;
  case "add_size":
    $template = "product/size/size_add";
    break;
  case "edit_size":
    editSize();
    $template = "product/size/size_add";
    break;
  case "save_size":
    saveSize();
    break;
  case "delete_size":
    deleteSize();
    break;

  /* Color */
  case "man_color":
    viewColors();
    $template = "product/color/colors";
    break;
  case "add_color":
    $template = "product/color/color_add";
    break;
  case "edit_color":
    editColor();
    $template = "product/color/color_add";
    break;
  case "save_color":
    saveColor();
    break;
  case "delete_color":
    deleteColor();
    break;

  /* Brand */
  case "man_brand":
    viewBrands();
    $template = "product/brand/brand";
    break;
  case "add_brand":
    $template = "product/brand/brand_add";
    break;
  case "edit_brand":
    editBrand();
    $template = "product/brand/brand_add";
    break;
  case "save_brand":
    saveBrand();
    break;
  case "delete_brand":
    deleteBrand();
    break;

  /* List */
  case "man_list":
    viewLists();
    $template = "product/list/lists";
    break;
  case "add_list":
    $template = "product/list/list_add";
    break;
  case "edit_list":
    editList();
    $template = "product/list/list_add";
    break;
  case "save_list":
    saveList();
    break;
  case "delete_list":
    deleteList();
    break;

  /* Cat */
  case "man_cat":
    viewCats();
    $template = "product/cat/cats";
    break;
  case "add_cat":
    $template = "product/cat/cat_add";
    break;
  case "edit_cat":
    editCat();
    $template = "product/cat/cat_add";
    break;
  case "save_cat":
    saveCat();
    break;
  case "delete_cat":
    deleteCat();
    break;

  /* Item */
  case "man_item":
    viewItems();
    $template = "product/item/items";
    break;
  case "add_item":
    $template = "product/item/item_add";
    break;
  case "edit_item":
    editItem();
    $template = "product/item/item_add";
    break;
  case "save_item":
    saveItem();
    break;
  case "delete_item":
    deleteItem();
    break;

  /* Sub */
  case "man_sub":
    viewSubs();
    $template = "product/sub/subs";
    break;
  case "add_sub":
    $template = "product/sub/sub_add";
    break;
  case "edit_sub":
    editSub();
    $template = "product/sub/sub_add";
    break;
  case "save_sub":
    saveSub();
    break;
  case "delete_sub":
    deleteSub();
    break;
  /* Vari */
  case "man_vari":
    viewVaris();
    $template = "product/vari/varis";
    break;
  case "add_vari":
    $template = "product/vari/vari_add";
    break;
  case "edit_vari":
    editVari();
    $template = "product/vari/vari_add";
    break;
  case "save_vari":
    saveVari();
    break;
  case "delete_vari":
    deleteVari();
    break;

  /* Gallery */
  case "man_photo":
  case "add_photo":
  case "edit_photo":
  case "save_photo":
  case "delete_photo":
    include "gallery.php";
    break;

  default:
    $template = "404";
}

/* View man */
function viewMans()
{
  global $d, $langadmin, $func, $comment, $strUrl, $curPage, $items, $paging, $type;

  $where = "";
  $idlist = (isset($_REQUEST['id_list'])) ? htmlspecialchars($_REQUEST['id_list']) : 0;
  $idcat = (isset($_REQUEST['id_cat'])) ? htmlspecialchars($_REQUEST['id_cat']) : 0;
  $iditem = (isset($_REQUEST['id_item'])) ? htmlspecialchars($_REQUEST['id_item']) : 0;
  $idsub = (isset($_REQUEST['id_sub'])) ? htmlspecialchars($_REQUEST['id_sub']) : 0;
  $idvari = (isset($_REQUEST['id_vari'])) ? htmlspecialchars($_REQUEST['id_vari']) : 0;
  $idbrand = (isset($_REQUEST['id_brand'])) ? htmlspecialchars($_REQUEST['id_brand']) : 0;
  $comment_status = (!empty($_REQUEST['comment_status'])) ? htmlspecialchars($_REQUEST['comment_status']) : '';

  if ($idlist) $where .= " and id_list=$idlist";
  if ($idcat) $where .= " and id_cat=$idcat";
  if ($iditem) $where .= " and id_item=$iditem";
  if ($idsub) $where .= " and id_sub=$idsub";
  if ($idvari) $where .= " and id_vari=$idvari";
  if ($idbrand) $where .= " and id_brand=$idbrand";
  if ($comment_status == 'new') {
    $comment = $d->rawQuery("select distinct id_variant from #_comment where type = ? and find_in_set('new-admin',status)", array($type));
    $idcomment = (!empty($comment)) ? $func->joinCols($comment, 'id_variant') : 0;
    $where .= " and id in ($idcomment)";
  }
  if (isset($_REQUEST['keyword'])) {
    $keyword = htmlspecialchars($_REQUEST['keyword']);
    $where .= " and (namevi LIKE '%$keyword%' or nameen LIKE '%$keyword%')";
  }

  $perPage = 10;
  $startpoint = ($curPage * $perPage) - $perPage;
  $limit = " limit " . $startpoint . "," . $perPage;
  $sql = "select * from #_product where type = ? $where order by numb,id desc $limit";
  $items = $d->rawQuery($sql, array($type));
  $sqlNum = "select count(*) as 'num' from #_product where type = ? $where order by numb,id desc";
  $count = $d->rawQueryOne($sqlNum, array($type));
  $total = (!empty($count)) ? $count['num'] : 0;
  $url = "index.php?com=product&act=man" . $strUrl . "&type=" . $type;
  $paging = $func->pagination($total, $perPage, $curPage, $url);
}

/* Edit man */
function editMan()
{
  global $d, $langadmin, $func, $strUrl, $curPage, $item, $gallery, $type, $com, $act;

  if (!empty($_GET['id'])) $id = htmlspecialchars($_GET['id']);
  else if (!empty($_GET['id_copy'])) $id = htmlspecialchars($_GET['id_copy']);
  else $id = 0;

  if (empty($id)) {
    $func->transfer(khongnhanduocdulieu, "index.php?com=product&act=man&type=" . $type . "&p=" . $curPage . $strUrl, false);
  } else {
    $item = $d->rawQueryOne("select * from #_product where id = ? and type = ? limit 0,1", array($id, $type));

    if (empty($item)) {
      $func->transfer(dulieukhongcothuc, "index.php?com=product&act=man&type=" . $type . "&p=" . $curPage . $strUrl, false);
    } else {
      if ($act != 'copy') {
        /* Get gallery */
        $gallery = $d->rawQuery("select * from #_gallery where id_parent = ? and com = ? and type = ? and kind = ? and val = ? order by numb,id desc", array($id, $com, $type, 'man', $type));
      }
    }
  }
}

/* Save man */
function saveMan()
{
  global $d, $strUrl, $func, $flash, $curPage, $config, $com, $act, $type, $configBase, $setting;

  if (empty($_POST)) {
    $func->transfer(khongnhanduocdulieu, "index.php?com=product&act=man&type=$type&p=$curPage$strUrl", false);
  }

  $response = [];
  $savehere = isset($_POST['save-here']);
  $buildSchema = isset($_POST['build-schema']);
  $id = !empty($_POST['id']) ? (int)$_POST['id'] : 0;

  $data = $_POST['data'] ?? [];
  $dataSeo = $_POST['dataSeo'] ?? [];
  $dataTags = $_POST['dataTags'] ?? [];
  $dataColor = $_POST['dataColor'] ?? [];
  $dataSize  = $_POST['dataSize'] ?? [];

  $photoDeleted = !empty($data['photo_deleted']);
  unset($data['photo_deleted']);

  foreach ($data as $col => $val) {
    $data[$col] = htmlspecialchars(
      $func->sanitize(
        $val,
        (strpos($col, 'content') !== false || strpos($col, 'desc') !== false) ? 'iframe' : ''
      )
    );
  }

  foreach ($dataSeo as $k => $v) {
    $dataSeo[$k] = htmlspecialchars($func->sanitize($v));
  }

  if (!empty($config['product'][$type]['slug'])) {
    foreach ($config['website']['lang'] as $k => $v) {
      $data["slug$k"] = !empty($_POST["slug$k"])
        ? $func->changeTitle($_POST["slug$k"])
        : $func->changeTitle($data["name$k"] ?? '');
    }
  }

  if (!empty($_POST['status'])) {
    $data['status'] = rtrim(implode(',', array_filter($_POST['status'])), ',');
  } else {
    $data['status'] = '';
  }

  $data['regular_price'] = !empty($data['regular_price']) ? str_replace(',', '', $data['regular_price']) : 0;
  $data['sale_price']    = !empty($data['sale_price']) ? str_replace(',', '', $data['sale_price']) : 0;
  $data['discount']      = !empty($data['discount']) ? $data['discount'] : 0;
  $data['type']          = $type;

  if ($errs = $func->checkTitle($data)) {
    $response['messages'] = array_values($errs);
  }

  foreach (['regular_price', 'sale_price', 'discount'] as $f) {
    if (!empty($data[$f]) && !$func->isNumber($data[$f])) {
      $response['messages'][] = $f . ' không hợp lệ';
    }
  }

  if (!empty($response)) {
    foreach ($data as $k => $v) if ($v) $flash->set($k, $v);
    foreach ($dataSeo as $k => $v) if ($v) $flash->set($k, $v);

    $flash->set('message', base64_encode(json_encode([
      'status' => 'danger',
      'messages' => $response['messages']
    ])));

    $func->redirect(
      $id
        ? "index.php?com=product&act=edit&type=$type&p=$curPage$strUrl&id=$id"
        : "index.php?com=product&act=add&type=$type&p=$curPage$strUrl"
    );
  }

  $isUpdate = ($id && $act != 'save_copy');

  if ($isUpdate) {
    if ($photoDeleted) {
      $row = $d->rawQueryOne("select photo from #_product where id=? and type=?", [$id, $type]);
      if ($row['photo']) {
        $func->deleteFile(UPLOAD_PRODUCT . $row['photo']);
        $d->where('id', $id)->update('product', ['photo' => '']);
      }
    }
    $data['date_updated'] = time();
    $d->where('id', $id)->where('type', $type)->update('product', $data);
    $idSave = $id;
  } else {
    $data['date_created'] = time();
    $d->insert('product', $data);
    $idSave = $d->getLastInsertId();
  }

  foreach (['file' => 'photo', 'file1' => 'icon'] as $field => $col) {
    if ($func->hasFile($field)) {
      $file_name = $func->uploadName($_FILES[$field]['name']);
      if ($img = $func->uploadImage($field, $config['product'][$type]['img_type'], UPLOAD_PRODUCT, $file_name)) {
        $old = $d->rawQueryOne("select $col from #_product where id=?", [$idSave]);
        if (!empty($old[$col])) $func->deleteFile(UPLOAD_PRODUCT . $old[$col]);
        $d->where('id', $idSave)->update('product', [$col => $img]);
      }
    }
  }

  if (!empty($config['product'][$type]['seo'])) {
    $d->rawQuery("delete from #_seo where id_parent=? and com=? and act='man' and type=?", [$idSave, $com, $type]);
    $dataSeo += [
      'id_parent' => $idSave,
      'com' => $com,
      'act' => 'man',
      'type' => $type
    ];
    $d->insert('seo', $dataSeo);
  }

  if (!empty($config['product'][$type]['tags'])) {
    $d->rawQuery("delete from #_product_tags where id_parent=?", [$idSave]);
    foreach ($dataTags as $v) {
      $d->insert('product_tags', ['id_parent' => $idSave, 'id_tags' => $v]);
    }
  }

  if (!empty($config['product'][$type]['color']) || !empty($config['product'][$type]['size'])) {
    $d->rawQuery("delete from #_product_sale where id_parent=?", [$idSave]);
    foreach ($dataColor ?: [null] as $c) {
      foreach ($dataSize ?: [null] as $s) {
        $row = ['id_parent' => $idSave];
        if ($c) $row['id_color'] = $c;
        if ($s) $row['id_size'] = $s;
        $d->insert('product_sale', $row);
      }
    }
  }

  $func->transfer($isUpdate ? capnhatdulieuthanhcong : luudulieuthanhcong, $savehere ? "index.php?com=product&act=edit&type=$type&p=$curPage$strUrl&id=$idSave" : "index.php?com=product&act=man&type=$type&p=$curPage$strUrl");
}

/* Delete man */
function deleteMan()
{
  global $d, $strUrl, $func, $curPage, $com, $type;

  $id = (!empty($_GET['id'])) ? htmlspecialchars($_GET['id']) : 0;

  if ($id) {
    /* Lấy dữ liệu */
    $row = $d->rawQueryOne("select id, photo, icon from #_product where id = ? and type = ? limit 0,1", array($id, $type));

    if (!empty($row)) {
      /* Xóa chính */
      $func->deleteFile(UPLOAD_PRODUCT . $row['photo']);
      $func->deleteFile(UPLOAD_PRODUCT . $row['icon']);
      $d->rawQuery("delete from #_product where id = ?", array($id));

      /* Xóa SEO */
      $d->rawQuery("delete from #_seo where id_parent = ? and com = ? and act = ? and type = ?", array($id, $com, 'man', $type));

      /* Xóa Tags */
      $d->rawQuery("delete from #_product_tags where id_parent = ?", array($id));

      /* Xóa Sale */
      $d->rawQuery("delete from #_product_sale where id_parent = ?", array($id));

      /* Xóa gallery */
      $rowGallery = $d->rawQuery("select id, photo, file_attach from #_gallery where id_parent = ? and kind = ? and com = ?", array($id, 'man', $com));

      if (count($rowGallery)) {
        foreach ($rowGallery as $v) {
          $func->deleteFile(UPLOAD_PRODUCT . $v['photo']);
          $func->deleteFile(UPLOAD_FILE . $v['file_attach']);
        }

        $d->rawQuery("delete from #_gallery where id_parent = ? and kind = ? and com = ?", array($id, 'man', $com));
      }

      /* Xóa comment */
      $rowComment = $d->rawQuery("select id, id_parent from #_comment where id_variant = ? and type = ?", array($id, $type));

      if (!empty($rowComment)) {
        foreach ($rowComment as $v) {
          if ($v['id_parent'] == 0) {
            /* Xóa comment photo */
            $rowCommentPhoto = $d->rawQuery("select photo from #_comment_photo where id_parent = ?", array($v['id']));

            if (!empty($rowCommentPhoto)) {
              /* Xóa image */
              foreach ($rowCommentPhoto as $v_photo) {
                $func->deleteFile(UPLOAD_PHOTO . $v_photo['photo']);
              }

              /* Xóa photo */
              $d->rawQuery("delete from #_comment_photo where id_parent = ?", array($v['id']));
            }

            /* Xóa comment video */
            $rowCommentVideo = $d->rawQueryOne("select photo, video from #_comment_video where id_parent = ? limit 0,1", array($v['id']));

            if (!empty($rowCommentVideo)) {
              $func->deleteFile(UPLOAD_PHOTO . $rowCommentVideo['photo']);
              $func->deleteFile(UPLOAD_VIDEO . $rowCommentVideo['video']);
              $d->rawQuery("delete from #_comment_video where id_parent = ?", array($v['id']));
            }

            /* Xóa child */
            $d->rawQuery("delete from #_comment where id_parent = ? and type = ?", array($v['id'], $type));
          }

          /* Xóa comment main */
          $d->rawQuery("delete from #_comment where id = ? and type = ?", array($v['id'], $type));
        }
      }

      $func->transfer(xoadulieuthanhcong, "index.php?com=product&act=man&type=" . $type . "&p=" . $curPage . $strUrl);
    } else {
      $func->transfer(xoadulieubiloi, "index.php?com=product&act=man&type=" . $type . "&p=" . $curPage . $strUrl, false);
    }
  } elseif (isset($_GET['listid'])) {
    $listid = explode(",", $_GET['listid']);

    for ($i = 0; $i < count($listid); $i++) {
      $id = htmlspecialchars($listid[$i]);

      /* Lấy dữ liệu */
      $row = $d->rawQueryOne("select id, photo, icon from #_product where id = ? and type = ? limit 0,1", array($id, $type));

      if (!empty($row)) {
        /* Xóa chính */
        $func->deleteFile(UPLOAD_PRODUCT . $row['photo']);
        $func->deleteFile(UPLOAD_PRODUCT . $row['icon']);
        $d->rawQuery("delete from #_product where id = ?", array($id));

        /* Xóa SEO */
        $d->rawQuery("delete from #_seo where id_parent = ? and com = ? and act = ? and type = ?", array($id, $com, 'man', $type));

        /* Xóa Tags */
        $d->rawQuery("delete from #_product_tags where id_parent = ?", array($id));

        /* Xóa Sale */
        $d->rawQuery("delete from #_product_sale where id_parent = ?", array($id));

        /* Xóa gallery */
        $rowGallery = $d->rawQuery("select id, photo, file_attach from #_gallery where id_parent = ? and kind = ? and com = ?", array($id, 'man', $com));

        if (count($rowGallery)) {
          foreach ($rowGallery as $v) {
            $func->deleteFile(UPLOAD_PRODUCT . $v['photo']);
            $func->deleteFile(UPLOAD_FILE . $v['file_attach']);
          }

          $d->rawQuery("delete from #_gallery where id_parent = ? and kind = ? and com = ?", array($id, 'man', $com));
        }

        /* Xóa comment */
        $rowComment = $d->rawQuery("select id, id_parent from #_comment where id_variant = ? and type = ?", array($id, $type));

        if (!empty($rowComment)) {
          foreach ($rowComment as $v) {
            if ($v['id_parent'] == 0) {
              /* Xóa comment photo */
              $rowCommentPhoto = $d->rawQuery("select photo from #_comment_photo where id_parent = ?", array($v['id']));

              if (!empty($rowCommentPhoto)) {
                /* Xóa image */
                foreach ($rowCommentPhoto as $v_photo) {
                  $func->deleteFile(UPLOAD_PHOTO . $v_photo['photo']);
                }

                /* Xóa photo */
                $d->rawQuery("delete from #_comment_photo where id_parent = ?", array($v['id']));
              }

              /* Xóa comment video */
              $rowCommentVideo = $d->rawQueryOne("select photo, video from #_comment_video where id_parent = ? limit 0,1", array($v['id']));

              if (!empty($rowCommentVideo)) {
                $func->deleteFile(UPLOAD_PHOTO . $rowCommentVideo['photo']);
                $func->deleteFile(UPLOAD_VIDEO . $rowCommentVideo['video']);
                $d->rawQuery("delete from #_comment_video where id_parent = ?", array($v['id']));
              }

              /* Xóa child */
              $d->rawQuery("delete from #_comment where id_parent = ? and type = ?", array($v['id'], $type));
            }

            /* Xóa comment main */
            $d->rawQuery("delete from #_comment where id = ? and type = ?", array($v['id'], $type));
          }
        }
      }
    }

    $func->transfer(xoadulieuthanhcong, "index.php?com=product&act=man&type=" . $type . "&p=" . $curPage . $strUrl);
  } else {
    $func->transfer(khongnhanduocdulieu, "index.php?com=product&act=man&type=" . $type . "&p=" . $curPage . $strUrl, false);
  }
}

/* View size */
function viewSizes()
{
  global $d, $langadmin, $func, $curPage, $items, $paging, $type;

  $where = "";

  if (isset($_REQUEST['keyword'])) {
    $keyword = htmlspecialchars($_REQUEST['keyword']);
    $where .= " and (namevi LIKE '%$keyword%' or nameen LIKE '%$keyword%')";
  }

  $perPage = 10;
  $startpoint = ($curPage * $perPage) - $perPage;
  $limit = " limit " . $startpoint . "," . $perPage;
  $sql = "select * from #_size where type = ? $where order by numb,id desc $limit";
  $items = $d->rawQuery($sql, array($type));
  $sqlNum = "select count(*) as 'num' from #_size where type = ? $where order by numb,id desc";
  $count = $d->rawQueryOne($sqlNum, array($type));
  $total = (!empty($count)) ? $count['num'] : 0;
  $url = "index.php?com=product&act=man_size&type=" . $type;
  $paging = $func->pagination($total, $perPage, $curPage, $url);
}

/* Edit size */
function editSize()
{
  global $d, $langadmin, $func, $curPage, $item, $type;

  $id = (!empty($_GET['id'])) ? htmlspecialchars($_GET['id']) : 0;

  if (empty($id)) {
    $func->transfer(khongnhanduocdulieu, "index.php?com=product&act=man_size&type=" . $type . "&p=" . $curPage, false);
  } else {
    $item = $d->rawQueryOne("select * from #_size where id = ? limit 0,1", array($id));

    if (empty($item)) {
      $func->transfer(dulieukhongcothuc, "index.php?com=product&act=man_size&type=" . $type . "&p=" . $curPage, false);
    }
  }
}

/* Save size */
function saveSize()
{
  global $d, $langadmin, $func, $flash, $curPage, $config, $type;

  /* Check post */
  if (empty($_POST)) {
    $func->transfer(khongnhanduocdulieu, "index.php?com=product&act=man_size&type=" . $type . "&p=" . $curPage, false);
  }

  /* Post dữ liệu */
  $message = '';
  $response = array();
  $id = (!empty($_POST['id'])) ? htmlspecialchars($_POST['id']) : 0;
  $data = (!empty($_POST['data'])) ? $_POST['data'] : null;
  if ($data) {
    foreach ($data as $column => $value) {
      $data[$column] = htmlspecialchars($func->sanitize($value));
    }

    if (isset($_POST['status'])) {
      $status = '';
      foreach ($_POST['status'] as $attr_column => $attr_value) if ($attr_value != "") $status .= $attr_value . ',';
      $data['status'] = (!empty($status)) ? rtrim($status, ",") : "";
    } else {
      $data['status'] = "";
    }

    $data['type'] = $type;
  }

  /* Valid data */
  $checkTitle = $func->checkTitle($data);

  if (!empty($checkTitle)) {
    foreach ($checkTitle as $k => $v) {
      $response['messages'][] = $v;
    }
  }

  if (!empty($response)) {
    /* Flash data */
    if (!empty($data)) {
      foreach ($data as $k => $v) {
        if (!empty($v)) {
          $flash->set($k, $v);
        }
      }
    }

    /* Errors */
    $response['status'] = 'danger';
    $message = base64_encode(json_encode($response));
    $flash->set('message', $message);

    if ($id) {
      $func->redirect("index.php?com=product&act=edit_size&type=" . $type . "&p=" . $curPage . "&id=" . $id);
    } else {
      $func->redirect("index.php?com=product&act=add_size&type=" . $type . "&p=" . $curPage);
    }
  }

  /* Save data */
  if ($id) {
    $data['date_updated'] = time();

    $d->where('id', $id);
    $d->where('type', $type);
    if ($d->update('size', $data)) {
      $func->transfer(capnhatdulieuthanhcong, "index.php?com=product&act=man_size&type=" . $type . "&p=" . $curPage);
    } else {
      $func->transfer(capnhatdulieubiloi, "index.php?com=product&act=man_size&type=" . $type . "&p=" . $curPage, false);
    }
  } else {
    $data['date_created'] = time();

    if ($d->insert('size', $data)) {
      $func->transfer(luudulieuthanhcong, "index.php?com=product&act=man_size&type=" . $type . "&p=" . $curPage);
    } else {
      $func->transfer(luudulieubiloi, "index.php?com=product&act=man_size&type=" . $type . "&p=" . $curPage, false);
    }
  }
}

/* Delete size */
function deleteSize()
{
  global $d, $langadmin, $func, $curPage, $type;

  $id = (!empty($_GET['id'])) ? htmlspecialchars($_GET['id']) : 0;

  if ($id) {
    $row = $d->rawQueryOne("select id from #_size where id = ? and type = ? limit 0,1", array($id, $type));

    if (!empty($row)) {
      $d->rawQuery("delete from #_size where id = ? and type = ?", array($id, $type));

      /* Xóa size in Sale */
      $d->rawQuery("delete from #_product_sale where id_size = ?", array($id));

      $func->transfer(xoadulieuthanhcong, "index.php?com=product&act=man_size&type=" . $type . "&p=" . $curPage);
    } else {
      $func->transfer(xoadulieubiloi, "index.php?com=product&act=man_size&type=" . $type . "&p=" . $curPage, false);
    }
  } elseif (isset($_GET['listid'])) {
    $listid = explode(",", $_GET['listid']);

    for ($i = 0; $i < count($listid); $i++) {
      $id = htmlspecialchars($listid[$i]);
      $row = $d->rawQueryOne("select id from #_size where id = ? and type = ? limit 0,1", array($id, $type));

      if (!empty($row)) {
        $d->rawQuery("delete from #_size where id = ? and type = ?", array($id, $type));

        /* Xóa size in Sale */
        $d->rawQuery("delete from #_product_sale where id_size = ?", array($id));
      }
    }

    $func->transfer(xoadulieuthanhcong, "index.php?com=product&act=man_size&type=" . $type . "&p=" . $curPage);
  } else {
    $func->transfer(khongnhanduocdulieu, "index.php?com=product&act=man_size&type=" . $type . "&p=" . $curPage, false);
  }
}

/* View color */
function viewColors()
{
  global $d, $langadmin, $func, $curPage, $items, $paging, $type;

  $where = "";

  if (isset($_REQUEST['keyword'])) {
    $keyword = htmlspecialchars($_REQUEST['keyword']);
    $where .= " and (namevi LIKE '%$keyword%' or nameen LIKE '%$keyword%')";
  }

  $perPage = 10;
  $startpoint = ($curPage * $perPage) - $perPage;
  $limit = " limit " . $startpoint . "," . $perPage;
  $sql = "select * from #_color where type = ? $where order by numb,id desc $limit";
  $items = $d->rawQuery($sql, array($type));
  $sqlNum = "select count(*) as 'num' from #_color where type = ? $where order by numb,id desc";
  $count = $d->rawQueryOne($sqlNum, array($type));
  $total = (!empty($count)) ? $count['num'] : 0;
  $url = "index.php?com=product&act=man_color&type=" . $type;
  $paging = $func->pagination($total, $perPage, $curPage, $url);
}

/* Edit color */
function editColor()
{
  global $d, $langadmin, $func, $curPage, $item, $type;

  $id = (!empty($_GET['id'])) ? htmlspecialchars($_GET['id']) : 0;

  if (empty($id)) {
    $func->transfer(khongnhanduocdulieu, "index.php?com=product&act=man_color&type=" . $type . "&p=" . $curPage, false);
  } else {
    $item = $d->rawQueryOne("select * from #_color where id = ? limit 0,1", array($id));

    if (empty($item)) {
      $func->transfer(dulieukhongcothuc, "index.php?com=product&act=man_color&type=" . $type . "&p=" . $curPage, false);
    }
  }
}

/* Save color */
function saveColor()
{
  global $d, $langadmin, $func, $flash, $curPage, $config, $type;

  /* Check post */
  if (empty($_POST)) {
    $func->transfer(khongnhanduocdulieu, "index.php?com=product&act=man_color&type=" . $type . "&p=" . $curPage, false);
  }

  /* Post dữ liệu */
  $message = '';
  $response = array();
  $id = (!empty($_POST['id'])) ? htmlspecialchars($_POST['id']) : 0;
  $data = (!empty($_POST['data'])) ? $_POST['data'] : null;
  if ($data) {
    foreach ($data as $column => $value) {
      $data[$column] = htmlspecialchars($func->sanitize($value));
    }

    if (isset($_POST['status'])) {
      $status = '';
      foreach ($_POST['status'] as $attr_column => $attr_value) if ($attr_value != "") $status .= $attr_value . ',';
      $data['status'] = (!empty($status)) ? rtrim($status, ",") : "";
    } else {
      $data['status'] = "";
    }

    $data['type'] = $type;
  }

  /* Valid data */
  $checkTitle = $func->checkTitle($data);

  if (!empty($checkTitle)) {
    foreach ($checkTitle as $k => $v) {
      $response['messages'][] = $v;
    }
  }

  if (!empty($response)) {
    /* Flash data */
    if (!empty($data)) {
      foreach ($data as $k => $v) {
        if (!empty($v)) {
          $flash->set($k, $v);
        }
      }
    }

    /* Errors */
    $response['status'] = 'danger';
    $message = base64_encode(json_encode($response));
    $flash->set('message', $message);

    if ($id) {
      $func->redirect("index.php?com=product&act=edit_color&type=" . $type . "&p=" . $curPage . "&id=" . $id);
    } else {
      $func->redirect("index.php?com=product&act=add_color&type=" . $type . "&p=" . $curPage);
    }
  }

  /* Save data */
  if ($id) {
    $data['date_updated'] = time();

    $d->where('id', $id);
    $d->where('type', $type);
    if ($d->update('color', $data)) {
      /* Photo */
      if ($func->hasFile("file")) {
        $photoUpdate = array();
        $file_name = $func->uploadName($_FILES["file"]["name"]);

        if ($photo = $func->uploadImage("file", $config['product'][$type]['img_type_color'], UPLOAD_COLOR, $file_name)) {
          $row = $d->rawQueryOne("select id, photo from #_color where id = ? and type = ? limit 0,1", array($id, $type));

          if (!empty($row)) {
            $func->deleteFile(UPLOAD_COLOR . $row['photo']);
          }

          $photoUpdate['photo'] = $photo;
          $d->where('id', $id);
          $d->update('color', $photoUpdate);
          unset($photoUpdate);
        }
      }

      $func->transfer(capnhatdulieuthanhcong, "index.php?com=product&act=man_color&type=" . $type . "&p=" . $curPage);
    } else {
      $func->transfer(capnhatdulieubiloi, "index.php?com=product&act=man_color&type=" . $type . "&p=" . $curPage, false);
    }
  } else {
    $data['date_created'] = time();

    if ($d->insert('color', $data)) {
      $id_insert = $d->getLastInsertId();

      /* Photo */
      if ($func->hasFile("file")) {
        $photoUpdate = array();
        $file_name = $func->uploadName($_FILES['file']["name"]);

        if ($photo = $func->uploadImage("file", $config['product'][$type]['img_type_color'], UPLOAD_COLOR, $file_name)) {
          $photoUpdate['photo'] = $photo;
          $d->where('id', $id_insert);
          $d->update('color', $photoUpdate);
          unset($photoUpdate);
        }
      }

      $func->transfer(luudulieuthanhcong, "index.php?com=product&act=man_color&type=" . $type . "&p=" . $curPage);
    } else {
      $func->transfer(luudulieubiloi, "index.php?com=product&act=man_color&type=" . $type . "&p=" . $curPage, false);
    }
  }
}

/* Delete color */
function deleteColor()
{
  global $d, $langadmin, $curPage, $func, $type;

  $id = (!empty($_GET['id'])) ? htmlspecialchars($_GET['id']) : 0;

  if ($id) {
    $row = $d->rawQueryOne("select * from #_color where id = ? and type = ? limit 0,1", array($id, $type));

    if (!empty($row)) {
      $func->deleteFile(UPLOAD_COLOR . $row['photo']);
      $d->rawQuery("delete from #_color where id = ?", array($id));

      /* Xóa color in Sale */
      $d->rawQuery("delete from #_product_sale where id_color = ?", array($id));

      $func->transfer(xoadulieuthanhcong, "index.php?com=product&act=man_color&type=" . $type . "&p=" . $curPage);
    } else {
      $func->transfer(xoadulieubiloi, "index.php?com=product&act=man_color&type=" . $type . "&p=" . $curPage, false);
    }
  } elseif (isset($_GET['listid'])) {
    $listid = explode(",", $_GET['listid']);

    for ($i = 0; $i < count($listid); $i++) {
      $id = htmlspecialchars($listid[$i]);
      $row = $d->rawQueryOne("select * from #_color where id = ? and type = ? limit 0,1", array($id, $type));

      if (!empty($row)) {
        $func->deleteFile(UPLOAD_COLOR . $row['photo']);
        $d->rawQuery("delete from #_color where id = ?", array($id));

        /* Xóa color in Sale */
        $d->rawQuery("delete from #_product_sale where id_color = ?", array($id));
      }
    }

    $func->transfer(xoadulieuthanhcong, "index.php?com=product&act=man_color&type=" . $type . "&p=" . $curPage);
  } else {
    $func->transfer(khongnhanduocdulieu, "index.php?com=product&act=man_color&type=" . $type . "&p=" . $curPage, false);
  }
}

/* View list */
function viewLists()
{
  global $d, $func, $curPage, $items, $paging, $type;

  $where = "";

  if (isset($_REQUEST['keyword'])) {
    $keyword = htmlspecialchars($_REQUEST['keyword']);
    $where .= " and (namevi LIKE '%$keyword%' or nameen LIKE '%$keyword%')";
  }

  $perPage = 10;
  $startpoint = ($curPage * $perPage) - $perPage;
  $limit = " limit " . $startpoint . "," . $perPage;
  $sql = "select * from #_product_list where type = ? $where order by numb,id desc $limit";
  $items = $d->rawQuery($sql, array($type));
  $sqlNum = "select count(*) as 'num' from #_product_list where type = ? $where order by numb,id desc";
  $count = $d->rawQueryOne($sqlNum, array($type));
  $total = (!empty($count)) ? $count['num'] : 0;
  $url = "index.php?com=product&act=man_list&type=" . $type;
  $paging = $func->pagination($total, $perPage, $curPage, $url);
}

/* Edit list */
function editList()
{
  global $d, $func, $strUrl, $curPage, $item, $gallery, $type, $com;
  $id = (!empty($_GET['id'])) ? htmlspecialchars($_GET['id']) : 0;
  if (empty($id)) {
    $func->transfer(khongnhanduocdulieu, "index.php?com=product&act=man_list&type=" . $type . "&p=" . $curPage . $strUrl, false);
  } else {
    $item = $d->rawQueryOne("select * from #_product_list where id = ? and type = ? limit 0,1", array($id, $type));
    if (empty($item)) {
      $func->transfer(dulieukhongcothuc, "index.php?com=product&act=man_list&type=" . $type . "&p=" . $curPage . $strUrl, false);
    } else {
      /* Get gallery */
      $gallery = $d->rawQuery("select * from #_gallery where id_parent = ? and com = ? and type = ? and kind = ? and val = ? order by numb,id desc", array($id, $com, $type, 'man_list', $type));
    }
  }
}

/* Save list */
function saveList()
{
  global $d, $func, $flash, $config, $com, $type, $curPage, $strUrl;

  if (empty($_POST)) {
    $func->transfer(khongnhanduocdulieu, "index.php?com=product&act=man_list&type=$type&p=$curPage$strUrl", false);
  }

  $id       = !empty($_POST['id']) ? (int)$_POST['id'] : 0;
  $savehere = isset($_POST['save-here']);
  $data     = $_POST['data'] ?? [];
  $dataSeo  = $_POST['dataSeo'] ?? [];
  $errors   = [];

  /* Xử lý xóa ảnh */
  $photoDeleted = !empty($data['photo_deleted']);
  unset($data['photo_deleted']);

  /* Sanitize data */
  foreach ($data as $k => $v) {
    $data[$k] = htmlspecialchars(
      strpos($k, 'content') !== false || strpos($k, 'desc') !== false
        ? $func->sanitize($v, 'iframe')
        : $func->sanitize($v)
    );
  }

  /* Status */
  $data['status'] = !empty($_POST['status'])
    ? implode(',', array_filter($_POST['status']))
    : '';

  /* Slug */
  if (!empty($config['product'][$type]['slug_list'])) {
    foreach ($config['website']['lang'] as $k => $v) {
      $slugKey = "slug$k";
      $nameKey = "name$k";
      $data[$slugKey] = !empty($_POST[$slugKey])
        ? $func->changeTitle($_POST[$slugKey])
        : (!empty($data[$nameKey]) ? $func->changeTitle($data[$nameKey]) : '');
    }
  }

  $data['type'] = $type;

  /* SEO */
  if (!empty($config['product'][$type]['seo_list'])) {
    foreach ($dataSeo as $k => $v) {
      $dataSeo[$k] = htmlspecialchars($func->sanitize($v));
    }
  }

  /* Validate */
  $errors = array_merge($errors, $func->checkTitle($data) ?? []);

  if (!empty($config['product'][$type]['slug_list'])) {
    foreach ($config['website']['slug'] as $k => $v) {
      if (empty($data["slug$k"])) continue;

      $check = $func->checkSlug([
        'slug' => $data["slug$k"],
        'id'   => $id,
        'copy' => false
      ]);

      if ($check === 'exist') $errors[] = duongdandatontai;
      if ($check === 'empty') $errors[] = duongdankhongduoctrong;
    }
  }

  /* Nếu lỗi */
  if ($errors) {
    foreach (array_merge($data, $dataSeo) as $k => $v) {
      if ($v) $flash->set($k, $v);
    }

    $flash->set('message', base64_encode(json_encode([
      'status'   => 'danger',
      'messages' => $errors
    ])));

    $act = $id ? 'edit_list&id=' . $id : 'add_list';
    $func->redirect("index.php?com=product&act=$act&type=$type&p=$curPage$strUrl");
  }

  /* SAVE */
  $timeField = $id ? 'date_updated' : 'date_created';
  $data[$timeField] = time();

  if ($id) {
    if ($photoDeleted) {
      $row = $d->rawQueryOne("select photo from #_product_list where id=? and type=?", [$id, $type]);
      if (!empty($row['photo'])) {
        $func->deleteFile(UPLOAD_PRODUCT . $row['photo']);
        $d->where('id', $id)->update('product_list', ['photo' => '']);
      }
    }

    $d->where('id', $id)->where('type', $type);
    $success = $d->update('product_list', $data);
  } else {
    $success = $d->insert('product_list', $data);
    $id = $success ? $d->getLastInsertId() : 0;
  }

  if (!$success) {
    $func->transfer(luudulieubiloi, "index.php?com=product&act=man_list&type=$type&p=$curPage$strUrl");
  }

  /* Upload ảnh */
  if ($func->hasFile('file')) {
    $fileName = $func->uploadName($_FILES['file']['name']);
    if ($photo = $func->uploadImage('file', $config['product'][$type]['img_type_list'], UPLOAD_PRODUCT, $fileName)) {
      $row = $d->rawQueryOne("select photo from #_product_list where id=?", [$id]);
      if (!empty($row['photo'])) $func->deleteFile(UPLOAD_PRODUCT . $row['photo']);
      $d->where('id', $id)->update('product_list', ['photo' => $photo]);
    }
  }

  /* SEO */
  if (!empty($config['product'][$type]['seo_list'])) {
    $d->rawQuery("delete from #_seo where id_parent=? and com=? and act='man_list' and type=?", [$id, $com, $type]);
    $dataSeo += ['id_parent' => $id, 'com' => $com, 'act' => 'man_list', 'type' => $type];
    $d->insert('seo', $dataSeo);
  }

  /* Redirect */
  $url = $savehere
    ? "index.php?com=product&act=edit_list&type=$type&id=$id&p=$curPage$strUrl"
    : "index.php?com=product&act=man_list&type=$type&p=$curPage$strUrl";

  $func->transfer($id ? capnhatdulieuthanhcong : luudulieuthanhcong, $url);
}

/* Delete list */
function deleteList()
{
  global $d, $strUrl, $func, $curPage, $com, $type;

  $id = (!empty($_GET['id'])) ? htmlspecialchars($_GET['id']) : 0;

  if ($id) {
    /* Lấy dữ liệu */
    $row = $d->rawQueryOne("select id, photo from #_product_list where id = ? and type = ? limit 0,1", array($id, $type));

    if (!empty($row)) {
      /* Xóa chính */
      $func->deleteFile(UPLOAD_PRODUCT . $row['photo']);
      $d->rawQuery("delete from #_product_list where id = ?", array($id));

      /* Xóa SEO */
      $d->rawQuery("delete from #_seo where id_parent = ? and com = ? and act = ? and type = ?", array($id, $com, 'man_list', $type));

      /* Xóa gallery */
      $row = $d->rawQuery("select id, photo, file_attach from #_gallery where id_parent = ? and kind = ? and com = ?", array($id, 'man_list', $com));

      if (count($row)) {
        foreach ($row as $v) {
          $func->deleteFile(UPLOAD_PRODUCT . $v['photo']);
          $func->deleteFile(UPLOAD_FILE . $v['file_attach']);
        }

        $d->rawQuery("delete from #_gallery where id_parent = ? and kind = ? and com = ?", array($id, 'man_list', $com));
      }

      $func->transfer(xoadulieuthanhcong, "index.php?com=product&act=man_list&type=" . $type . "&p=" . $curPage . $strUrl);
    } else {
      $func->transfer(xoadulieubiloi, "index.php?com=product&act=man_list&type=" . $type . "&p=" . $curPage . $strUrl, false);
    }
  } elseif (isset($_GET['listid'])) {
    $listid = explode(",", $_GET['listid']);

    for ($i = 0; $i < count($listid); $i++) {
      $id = htmlspecialchars($listid[$i]);

      /* Lấy dữ liệu */
      $row = $d->rawQueryOne("select id, photo from #_product_list where id = ? and type = ? limit 0,1", array($id, $type));

      if (!empty($row)) {
        /* Xóa chính */
        $func->deleteFile(UPLOAD_PRODUCT . $row['photo']);
        $d->rawQuery("delete from #_product_list where id = ?", array($id));

        /* Xóa SEO */
        $d->rawQuery("delete from #_seo where id_parent = ? and com = ? and act = ? and type = ?", array($id, $com, 'man_list', $type));

        /* Xóa gallery */
        $row = $d->rawQuery("select id, photo, file_attach from #_gallery where id_parent = ? and kind = ? and com = ?", array($id, 'man_list', $com));

        if (count($row)) {
          foreach ($row as $v) {
            $func->deleteFile(UPLOAD_PRODUCT . $v['photo']);
            $func->deleteFile(UPLOAD_FILE . $v['file_attach']);
          }

          $d->rawQuery("delete from #_gallery where id_parent = ? and kind = ? and com = ?", array($id, 'man_list', $com));
        }
      }
    }

    $func->transfer(xoadulieuthanhcong, "index.php?com=product&act=man_list&type=" . $type . "&p=" . $curPage . $strUrl);
  } else {
    $func->transfer(khongnhanduocdulieu, "index.php?com=product&act=man_list&type=" . $type . "&p=" . $curPage . $strUrl, false);
  }
}

/* Get cat */
function viewCats()
{
  global $d, $func, $strUrl, $curPage, $items, $paging, $type;

  $where = "";
  $idlist = (isset($_REQUEST['id_list'])) ? htmlspecialchars($_REQUEST['id_list']) : 0;

  if ($idlist) $where .= " and id_list=$idlist";
  if (isset($_REQUEST['keyword'])) {
    $keyword = htmlspecialchars($_REQUEST['keyword']);
    $where .= " and (namevi LIKE '%$keyword%' or nameen LIKE '%$keyword%')";
  }

  $perPage = 10;
  $startpoint = ($curPage * $perPage) - $perPage;
  $limit = " limit " . $startpoint . "," . $perPage;
  $sql = "select * from #_product_cat where type = ? $where order by numb,id desc $limit";
  $items = $d->rawQuery($sql, array($type));
  $sqlNum = "select count(*) as 'num' from #_product_cat where type = ? $where order by numb,id desc";
  $count = $d->rawQueryOne($sqlNum, array($type));
  $total = (!empty($count)) ? $count['num'] : 0;
  $url = "index.php?com=product&act=man_cat" . $strUrl . "&type=" . $type;
  $paging = $func->pagination($total, $perPage, $curPage, $url);
}

/* Edit cat */
function editCat()
{
  global $d, $func, $strUrl, $curPage, $item, $type;
  $id = (!empty($_GET['id'])) ? htmlspecialchars($_GET['id']) : 0;
  if (empty($id)) {
    $func->transfer(khongnhanduocdulieu, "index.php?com=product&act=man_cat&type=" . $type . "&p=" . $curPage . $strUrl, false);
  } else {
    $item = $d->rawQueryOne("select * from #_product_cat where id = ? and type = ? limit 0,1", array($id, $type));
    if (empty($item)) {
      $func->transfer(dulieukhongcothuc, "index.php?com=product&act=man_cat&type=" . $type . "&p=" . $curPage . $strUrl, false);
    }
  }
}

/* Save cat */
function saveCat()
{
  global $d, $func, $flash, $config, $com, $type, $curPage, $strUrl;

  if (empty($_POST)) {
    $func->transfer(khongnhanduocdulieu, "index.php?com=product&act=man_cat&type=$type&p=$curPage$strUrl", false);
  }

  $id        = !empty($_POST['id']) ? (int)$_POST['id'] : 0;
  $data      = $_POST['data'] ?? [];
  $dataSeo   = $_POST['dataSeo'] ?? [];
  $savehere  = isset($_POST['save-here']);

  /* Sanitize data */
  foreach ($data as $col => $val) {
    $data[$col] = htmlspecialchars(
      $func->sanitize($val, (strpos($col, 'content') !== false || strpos($col, 'desc') !== false) ? 'iframe' : '')
    );
  }

  /* Status */
  $data['status'] = !empty($_POST['status']) ? implode(',', array_filter($_POST['status'])) : '';

  /* Slug */
  if (!empty($config['product'][$type]['slug_cat'])) {
    foreach ($config['website']['lang'] as $k => $v) {
      $data["slug$k"] = !empty($_POST["slug$k"])
        ? $func->changeTitle($_POST["slug$k"])
        : $func->changeTitle($data["name$k"] ?? '');
    }
  }

  $data['type'] = $type;

  /* SEO sanitize */
  foreach ($dataSeo as $k => $v) {
    $dataSeo[$k] = htmlspecialchars($func->sanitize($v));
  }

  /* Validate title */
  $errors = $func->checkTitle($data);

  /* Validate slug */
  if (!empty($config['product'][$type]['slug_cat'])) {
    foreach ($config['website']['slug'] as $k => $v) {
      if (empty($data["slug$k"])) continue;
      $checkSlug = $func->checkSlug([
        'slug' => $data["slug$k"],
        'id'   => $id,
        'copy' => false
      ]);
      if ($checkSlug === 'exist') $errors[] = duongdandatontai;
      if ($checkSlug === 'empty') $errors[] = duongdankhongduoctrong;
    }
  }

  /* Errors */
  if (!empty($errors)) {
    foreach (array_merge($data, $dataSeo) as $k => $v) {
      if (!empty($v)) $flash->set($k, $v);
    }
    $flash->set('message', base64_encode(json_encode([
      'status' => 'danger',
      'messages' => $errors
    ])));

    $url = $id
      ? "index.php?com=product&act=edit_cat&type=$type&id=$id&p=$curPage$strUrl"
      : "index.php?com=product&act=add_cat&type=$type&p=$curPage$strUrl";
    $func->redirect($url);
  }

  /* Xử lý xóa ảnh */
  $photoDeleted = !empty($data['photo_deleted']);
  unset($data['photo_deleted']);

  /* Save */
  $data[$id ? 'date_updated' : 'date_created'] = time();

  if ($id) {
    $d->where('id', $id);
    $d->where('type', $type);
    $success = $d->update('product_cat', $data);
  } else {
    $success = $d->insert('product_cat', $data);
    $id = $d->getLastInsertId();
  }

  if (!$success) {
    $func->transfer(luudulieubiloi, "index.php?com=product&act=man_cat&type=$type&p=$curPage$strUrl", false);
  }

  /* Delete photo */
  if ($photoDeleted) {
    $row = $d->rawQueryOne("select photo from #_product_cat where id = ?", [$id]);
    if (!empty($row['photo'])) {
      $func->deleteFile(UPLOAD_PRODUCT . $row['photo']);
      $d->where('id', $id)->update('product_cat', ['photo' => '']);
    }
  }

  /* Upload photo */
  if ($func->hasFile('file')) {
    $fileName = $func->uploadName($_FILES['file']['name']);
    if ($photo = $func->uploadImage('file', $config['product'][$type]['img_type_cat'], UPLOAD_PRODUCT, $fileName)) {
      $row = $d->rawQueryOne("select photo from #_product_cat where id = ?", [$id]);
      if (!empty($row['photo'])) $func->deleteFile(UPLOAD_PRODUCT . $row['photo']);
      $d->where('id', $id)->update('product_cat', ['photo' => $photo]);
    }
  }

  /* SEO */
  if (!empty($config['product'][$type]['seo_cat'])) {
    $d->rawQuery(
      "delete from #_seo where id_parent = ? and com = ? and act = 'man_cat' and type = ?",
      [$id, $com, $type]
    );
    $dataSeo += [
      'id_parent' => $id,
      'com' => $com,
      'act' => 'man_cat',
      'type' => $type
    ];
    $d->insert('seo', $dataSeo);
  }

  /* Redirect */
  if ($savehere) {
    $func->transfer(capnhatdulieuthanhcong, "index.php?com=product&act=edit_cat&type=$type&id=$id&p=$curPage$strUrl");
  } else {
    $func->transfer(capnhatdulieuthanhcong, "index.php?com=product&act=man_cat&type=$type&p=$curPage$strUrl");
  }
}

/* Delete cat */
function deleteCat()
{
  global $d, $langadmin, $strUrl, $func, $curPage, $com, $type;

  $id = (!empty($_GET['id'])) ? htmlspecialchars($_GET['id']) : 0;

  if ($id) {
    /* Lấy dữ liệu */
    $row = $d->rawQueryOne("select id, photo from #_product_cat where id = ? and type = ? limit 0,1", array($id, $type));

    if (!empty($row)) {
      /* Xóa chính */
      $func->deleteFile(UPLOAD_PRODUCT . $row['photo']);
      $d->rawQuery("delete from #_product_cat where id = ?", array($id));

      /* Xóa SEO */
      $d->rawQuery("delete from #_seo where id_parent = ? and com = ? and act = ? and type = ?", array($id, $com, 'man_cat', $type));

      $func->transfer(xoadulieuthanhcong, "index.php?com=product&act=man_cat&type=" . $type . "&p=" . $curPage . $strUrl);
    } else {
      $func->transfer(xoadulieubiloi, "index.php?com=product&act=man_cat&type=" . $type . "&p=" . $curPage . $strUrl, false);
    }
  } elseif (isset($_GET['listid'])) {
    $listid = explode(",", $_GET['listid']);

    for ($i = 0; $i < count($listid); $i++) {
      $id = htmlspecialchars($listid[$i]);

      /* Lấy dữ liệu */
      $row = $d->rawQueryOne("select id, photo from #_product_cat where id = ? and type = ? limit 0,1", array($id, $type));

      if (!empty($row)) {
        /* Xóa chính */
        $func->deleteFile(UPLOAD_PRODUCT . $row['photo']);
        $d->rawQuery("delete from #_product_cat where id = ?", array($id));

        /* Xóa SEO */
        $d->rawQuery("delete from #_seo where id_parent = ? and com = ? and act = ? and type = ?", array($id, $com, 'man_cat', $type));
      }
    }

    $func->transfer(xoadulieuthanhcong, "index.php?com=product&act=man_cat&type=" . $type . "&p=" . $curPage . $strUrl);
  } else {
    $func->transfer(khongnhanduocdulieu, "index.php?com=product&act=man_cat&type=" . $type . "&p=" . $curPage . $strUrl, false);
  }
}

/* View item */
function viewItems()
{
  global $d, $langadmin, $func, $strUrl, $curPage, $items, $paging, $type;

  $where = "";
  $idlist = (isset($_REQUEST['id_list'])) ? htmlspecialchars($_REQUEST['id_list']) : 0;
  $idcat = (isset($_REQUEST['id_cat'])) ? htmlspecialchars($_REQUEST['id_cat']) : 0;

  if ($idlist) $where .= " and id_list=$idlist";
  if ($idcat) $where .= " and id_cat=$idcat";
  if (isset($_REQUEST['keyword'])) {
    $keyword = htmlspecialchars($_REQUEST['keyword']);
    $where .= " and (namevi LIKE '%$keyword%' or nameen LIKE '%$keyword%')";
  }

  $perPage = 10;
  $startpoint = ($curPage * $perPage) - $perPage;
  $limit = " limit " . $startpoint . "," . $perPage;
  $sql = "select * from #_product_item where type = ? $where order by numb,id desc $limit";
  $items = $d->rawQuery($sql, array($type));
  $sqlNum = "select count(*) as 'num' from #_product_item where type = ? $where order by numb,id desc";
  $count = $d->rawQueryOne($sqlNum, array($type));
  $total = (!empty($count)) ? $count['num'] : 0;
  $url = "index.php?com=product&act=man_item" . $strUrl . "&type=" . $type;
  $paging = $func->pagination($total, $perPage, $curPage, $url);
}

/* Edit item */
function editItem()
{
  global $d, $langadmin, $func, $strUrl, $curPage, $item, $type;

  $id = (!empty($_GET['id'])) ? htmlspecialchars($_GET['id']) : 0;

  if (empty($id)) {
    $func->transfer(khongnhanduocdulieu, "index.php?com=product&act=man_item&type=" . $type . "&p=" . $curPage . $strUrl, false);
  } else {
    $item = $d->rawQueryOne("select * from #_product_item where id = ? and type = ? limit 0,1", array($id, $type));

    if (empty($item)) {
      $func->transfer(dulieukhongcothuc, "index.php?com=product&act=man_item&type=" . $type . "&p=" . $curPage . $strUrl, false);
    }
  }
}

/* Save item */
function saveItem()
{
  global $d, $func, $flash, $config, $com, $type, $curPage, $strUrl;

  if (empty($_POST)) {
    $func->transfer(
      khongnhanduocdulieu,
      "index.php?com=product&act=man_item&type=$type&p=$curPage$strUrl",
      false
    );
  }

  $id       = !empty($_POST['id']) ? (int)$_POST['id'] : 0;
  $data     = $_POST['data'] ?? [];
  $dataSeo  = $_POST['dataSeo'] ?? [];
  $savehere = isset($_POST['save-here']);

  /* Sanitize data */
  foreach ($data as $col => $val) {
    $data[$col] = htmlspecialchars(
      $func->sanitize(
        $val,
        (strpos($col, 'content') !== false || strpos($col, 'desc') !== false) ? 'iframe' : ''
      )
    );
  }

  /* Status */
  $data['status'] = !empty($_POST['status'])
    ? implode(',', array_filter($_POST['status']))
    : '';

  /* Slug */
  if (!empty($config['product'][$type]['slug_item'])) {
    foreach ($config['website']['lang'] as $k => $v) {
      $data["slug$k"] = !empty($_POST["slug$k"])
        ? $func->changeTitle($_POST["slug$k"])
        : $func->changeTitle($data["name$k"] ?? '');
    }
  }

  $data['type'] = $type;

  /* SEO sanitize */
  foreach ($dataSeo as $k => $v) {
    $dataSeo[$k] = htmlspecialchars($func->sanitize($v));
  }

  /* Validate */
  $errors = $func->checkTitle($data);

  if (!empty($config['product'][$type]['slug_item'])) {
    foreach ($config['website']['slug'] as $k => $v) {
      if (empty($data["slug$k"])) continue;

      $checkSlug = $func->checkSlug([
        'slug' => $data["slug$k"],
        'id'   => $id,
        'copy' => false
      ]);

      if ($checkSlug === 'exist') $errors[] = duongdandatontai;
      if ($checkSlug === 'empty') $errors[] = duongdankhongduoctrong;
    }
  }

  /* Errors */
  if (!empty($errors)) {
    foreach (array_merge($data, $dataSeo) as $k => $v) {
      if (!empty($v)) $flash->set($k, $v);
    }

    $flash->set('message', base64_encode(json_encode([
      'status'   => 'danger',
      'messages' => $errors
    ])));

    $url = $id
      ? "index.php?com=product&act=edit_item&type=$type&id=$id&p=$curPage$strUrl"
      : "index.php?com=product&act=add_item&type=$type&p=$curPage$strUrl";

    $func->redirect($url);
  }

  /* Xử lý xoá ảnh */
  $photoDeleted = !empty($data['photo_deleted']);
  unset($data['photo_deleted']);

  /* Save */
  $data[$id ? 'date_updated' : 'date_created'] = time();

  if ($id) {
    $d->where('id', $id);
    $d->where('type', $type);
    $success = $d->update('product_item', $data);
  } else {
    $success = $d->insert('product_item', $data);
    $id = $d->getLastInsertId();
  }

  if (!$success) {
    $func->transfer(
      luudulieubiloi,
      "index.php?com=product&act=man_item&type=$type&p=$curPage$strUrl",
      false
    );
  }

  /* Delete photo */
  if ($photoDeleted) {
    $row = $d->rawQueryOne("select photo from #_product_item where id = ?", [$id]);
    if (!empty($row['photo'])) {
      $func->deleteFile(UPLOAD_PRODUCT . $row['photo']);
      $d->where('id', $id)->update('product_item', ['photo' => '']);
    }
  }

  /* Upload photo */
  if ($func->hasFile('file')) {
    $fileName = $func->uploadName($_FILES['file']['name']);
    if ($photo = $func->uploadImage(
      'file',
      $config['product'][$type]['img_type_item'],
      UPLOAD_PRODUCT,
      $fileName
    )) {
      $row = $d->rawQueryOne("select photo from #_product_item where id = ?", [$id]);
      if (!empty($row['photo'])) $func->deleteFile(UPLOAD_PRODUCT . $row['photo']);
      $d->where('id', $id)->update('product_item', ['photo' => $photo]);
    }
  }

  /* SEO */
  if (!empty($config['product'][$type]['seo_item'])) {
    $d->rawQuery(
      "delete from #_seo where id_parent = ? and com = ? and act = 'man_item' and type = ?",
      [$id, $com, $type]
    );

    $dataSeo += [
      'id_parent' => $id,
      'com'       => $com,
      'act'       => 'man_item',
      'type'      => $type
    ];
    $d->insert('seo', $dataSeo);
  }

  /* Redirect */
  if ($savehere) {
    $func->transfer(
      luudulieuthanhcong,
      "index.php?com=product&act=edit_item&type=$type&id=$id&p=$curPage$strUrl"
    );
  } else {
    $func->transfer(
      luudulieuthanhcong,
      "index.php?com=product&act=man_item&type=$type&p=$curPage$strUrl"
    );
  }
}

/* Delete item */
function deleteItem()
{
  global $d, $langadmin, $strUrl, $func, $curPage, $com, $type;

  $id = (!empty($_GET['id'])) ? htmlspecialchars($_GET['id']) : 0;

  if ($id) {
    /* Lấy dữ liệu */
    $row = $d->rawQueryOne("select id, photo from #_product_item where id = ? and type = ? limit 0,1", array($id, $type));

    if (!empty($row)) {
      /* Xóa chính */
      $func->deleteFile(UPLOAD_PRODUCT . $row['photo']);
      $d->rawQuery("delete from #_product_item where id = ?", array($id));

      /* Xóa SEO */
      $d->rawQuery("delete from #_seo where id_parent = ? and com = ? and act = ? and type = ?", array($id, $com, 'man_item', $type));

      $func->transfer(xoadulieuthanhcong, "index.php?com=product&act=man_item&type=" . $type . "&p=" . $curPage . $strUrl);
    } else {
      $func->transfer(xoadulieubiloi, "index.php?com=product&act=man_item&type=" . $type . "&p=" . $curPage . $strUrl, false);
    }
  } elseif (isset($_GET['listid'])) {
    $listid = explode(",", $_GET['listid']);

    for ($i = 0; $i < count($listid); $i++) {
      $id = htmlspecialchars($listid[$i]);

      /* Lấy dữ liệu */
      $row = $d->rawQueryOne("select id, photo from #_product_item where id = ? and type = ? limit 0,1", array($id, $type));

      if (!empty($row)) {
        /* Xóa chính */
        $func->deleteFile(UPLOAD_PRODUCT . $row['photo']);
        $d->rawQuery("delete from #_product_item where id = ?", array($id));

        /* Xóa SEO */
        $d->rawQuery("delete from #_seo where id_parent = ? and com = ? and act = ? and type = ?", array($id, $com, 'man_item', $type));
      }
    }

    $func->transfer(xoadulieuthanhcong, "index.php?com=product&act=man_item&type=" . $type . "&p=" . $curPage . $strUrl);
  } else {
    $func->transfer(khongnhanduocdulieu, "index.php?com=product&act=man_item&type=" . $type . "&p=" . $curPage . $strUrl, false);
  }
}

/* View sub */
function viewSubs()
{
  global $d, $langadmin, $func, $strUrl, $curPage, $items, $paging, $type;

  $where = "";

  $idlist = (isset($_REQUEST['id_list'])) ? htmlspecialchars($_REQUEST['id_list']) : 0;
  $idcat = (isset($_REQUEST['id_cat'])) ? htmlspecialchars($_REQUEST['id_cat']) : 0;
  $iditem = (isset($_REQUEST['id_item'])) ? htmlspecialchars($_REQUEST['id_item']) : 0;

  if ($idlist) $where .= " and id_list=$idlist";
  if ($idcat) $where .= " and id_cat=$idcat";
  if ($iditem) $where .= " and id_item=$iditem";
  if (isset($_REQUEST['keyword'])) {
    $keyword = htmlspecialchars($_REQUEST['keyword']);
    $where .= " and (namevi LIKE '%$keyword%' or nameen LIKE '%$keyword%')";
  }

  $perPage = 10;
  $startpoint = ($curPage * $perPage) - $perPage;
  $limit = " limit " . $startpoint . "," . $perPage;
  $sql = "select * from #_product_sub where type = ? $where order by numb,id desc $limit";
  $items = $d->rawQuery($sql, array($type));
  $sqlNum = "select count(*) as 'num' from #_product_sub where type = ? $where order by numb,id desc";
  $count = $d->rawQueryOne($sqlNum, array($type));
  $total = (!empty($count)) ? $count['num'] : 0;
  $url = "index.php?com=product&act=man_sub" . $strUrl . "&type=" . $type;
  $paging = $func->pagination($total, $perPage, $curPage, $url);
}

/* Edit sub */
function editSub()
{
  global $d, $langadmin, $func, $strUrl, $curPage, $item, $type;

  $id = (!empty($_GET['id'])) ? htmlspecialchars($_GET['id']) : 0;

  if (empty($id)) {
    $func->transfer(khongnhanduocdulieu, "index.php?com=product&act=man_sub&type=" . $type . "&p=" . $curPage . $strUrl, false);
  } else {
    $item = $d->rawQueryOne("select * from #_product_sub where id = ? and type = ? limit 0,1", array($id, $type));

    if (empty($item)) {
      $func->transfer(dulieukhongcothuc, "index.php?com=product&act=man_sub&type=" . $type . "&p=" . $curPage . $strUrl, false);
    }
  }
}

/* Save sub */
function saveSub()
{
  global $d, $func, $flash, $config, $com, $type, $curPage, $strUrl;

  if (empty($_POST)) {
    $func->transfer(
      khongnhanduocdulieu,
      "index.php?com=product&act=man_sub&type=$type&p=$curPage$strUrl",
      false
    );
  }

  $id       = !empty($_POST['id']) ? (int)$_POST['id'] : 0;
  $data     = $_POST['data'] ?? [];
  $dataSeo  = $_POST['dataSeo'] ?? [];
  $savehere = isset($_POST['save-here']);

  /* Sanitize data */
  foreach ($data as $col => $val) {
    $data[$col] = htmlspecialchars(
      $func->sanitize(
        $val,
        (strpos($col, 'content') !== false || strpos($col, 'desc') !== false) ? 'iframe' : ''
      )
    );
  }

  /* Status */
  $data['status'] = !empty($_POST['status'])
    ? implode(',', array_filter($_POST['status']))
    : '';

  /* Slug */
  if (!empty($config['product'][$type]['slug_sub'])) {
    foreach ($config['website']['lang'] as $k => $v) {
      $data["slug$k"] = !empty($_POST["slug$k"])
        ? $func->changeTitle($_POST["slug$k"])
        : $func->changeTitle($data["name$k"] ?? '');
    }
  }

  $data['type'] = $type;

  /* SEO sanitize */
  foreach ($dataSeo as $k => $v) {
    $dataSeo[$k] = htmlspecialchars($func->sanitize($v));
  }

  /* Validate */
  $errors = $func->checkTitle($data);

  if (!empty($config['product'][$type]['slug_sub'])) {
    foreach ($config['website']['slug'] as $k => $v) {
      if (empty($data["slug$k"])) continue;

      $checkSlug = $func->checkSlug([
        'slug' => $data["slug$k"],
        'id'   => $id,
        'copy' => false
      ]);

      if ($checkSlug === 'exist') $errors[] = duongdandatontai;
      if ($checkSlug === 'empty') $errors[] = duongdankhongduoctrong;
    }
  }

  /* Errors */
  if (!empty($errors)) {
    foreach (array_merge($data, $dataSeo) as $k => $v) {
      if (!empty($v)) $flash->set($k, $v);
    }

    $flash->set('message', base64_encode(json_encode([
      'status'   => 'danger',
      'messages' => $errors
    ])));

    $url = $id
      ? "index.php?com=product&act=edit_sub&type=$type&id=$id&p=$curPage$strUrl"
      : "index.php?com=product&act=add_sub&type=$type&p=$curPage$strUrl";

    $func->redirect($url);
  }

  /* Xử lý xoá ảnh */
  $photoDeleted = !empty($data['photo_deleted']);
  unset($data['photo_deleted']);

  /* Save */
  $data[$id ? 'date_updated' : 'date_created'] = time();

  if ($id) {
    $d->where('id', $id);
    $d->where('type', $type);
    $success = $d->update('product_sub', $data);
  } else {
    $success = $d->insert('product_sub', $data);
    $id = $d->getLastInsertId();
  }

  if (!$success) {
    $func->transfer(
      luudulieubiloi,
      "index.php?com=product&act=man_sub&type=$type&p=$curPage$strUrl",
      false
    );
  }

  /* Delete photo */
  if ($photoDeleted) {
    $row = $d->rawQueryOne("select photo from #_product_sub where id = ?", [$id]);
    if (!empty($row['photo'])) {
      $func->deleteFile(UPLOAD_PRODUCT . $row['photo']);
      $d->where('id', $id)->update('product_sub', ['photo' => '']);
    }
  }

  /* Upload photo */
  if ($func->hasFile('file')) {
    $fileName = $func->uploadName($_FILES['file']['name']);
    if ($photo = $func->uploadImage(
      'file',
      $config['product'][$type]['img_type_sub'],
      UPLOAD_PRODUCT,
      $fileName
    )) {
      $row = $d->rawQueryOne("select photo from #_product_sub where id = ?", [$id]);
      if (!empty($row['photo'])) $func->deleteFile(UPLOAD_PRODUCT . $row['photo']);
      $d->where('id', $id)->update('product_sub', ['photo' => $photo]);
    }
  }

  /* SEO */
  if (!empty($config['product'][$type]['seo_sub'])) {
    $d->rawQuery(
      "delete from #_seo where id_parent = ? and com = ? and act = 'man_sub' and type = ?",
      [$id, $com, $type]
    );

    $dataSeo += [
      'id_parent' => $id,
      'com'       => $com,
      'act'       => 'man_sub',
      'type'      => $type
    ];
    $d->insert('seo', $dataSeo);
  }

  /* Redirect */
  if ($savehere) {
    $func->transfer(
      luudulieuthanhcong,
      "index.php?com=product&act=edit_sub&type=$type&id=$id&p=$curPage$strUrl"
    );
  } else {
    $func->transfer(
      luudulieuthanhcong,
      "index.php?com=product&act=man_sub&type=$type&p=$curPage$strUrl"
    );
  }
}

/* Delete sub */
function deleteSub()
{
  global $d, $langadmin, $strUrl, $func, $curPage, $com, $type;

  $id = (!empty($_GET['id'])) ? htmlspecialchars($_GET['id']) : 0;

  if ($id) {
    /* Lấy dữ liệu */
    $row = $d->rawQueryOne("select id, photo from #_product_sub where id = ? and type = ? limit 0,1", array($id, $type));

    if (!empty($row)) {
      /* Xóa chính */
      $func->deleteFile(UPLOAD_PRODUCT . $row['photo']);
      $d->rawQuery("delete from #_product_sub where id = ?", array($id));

      /* Xóa SEO */
      $d->rawQuery("delete from #_seo where id_parent = ? and com = ? and act = ? and type = ?", array($id, $com, 'man_sub', $type));

      $func->transfer(xoadulieuthanhcong, "index.php?com=product&act=man_sub&type=" . $type . "&p=" . $curPage . $strUrl);
    } else {
      $func->transfer(xoadulieubiloi, "index.php?com=product&act=man_sub&type=" . $type . "&p=" . $curPage . $strUrl, false);
    }
  } elseif (isset($_GET['listid'])) {
    $listid = explode(",", $_GET['listid']);

    for ($i = 0; $i < count($listid); $i++) {
      $id = htmlspecialchars($listid[$i]);

      /* Lấy dữ liệu */
      $row = $d->rawQueryOne("select id, photo from #_product_sub where id = ? and type = ? limit 0,1", array($id, $type));

      if (!empty($row)) {
        /* Xóa chính */
        $func->deleteFile(UPLOAD_PRODUCT . $row['photo']);
        $d->rawQuery("delete from #_product_sub where id = ?", array($id));

        /* Xóa SEO */
        $d->rawQuery("delete from #_seo where id_parent = ? and com = ? and act = ? and type = ?", array($id, $com, 'man_sub', $type));
      }
    }

    $func->transfer(xoadulieuthanhcong, "index.php?com=product&act=man_sub&type=" . $type . "&p=" . $curPage . $strUrl);
  } else {
    $func->transfer(khongnhanduocdulieu, "index.php?com=product&act=man_sub&type=" . $type . "&p=" . $curPage . $strUrl, false);
  }
}
/* View Vari */
function viewVaris()
{
  global $d, $langadmin, $func, $strUrl, $curPage, $items, $paging, $type;

  $where = "";

  $idlist = (isset($_REQUEST['id_list'])) ? htmlspecialchars($_REQUEST['id_list']) : 0;
  $idcat = (isset($_REQUEST['id_cat'])) ? htmlspecialchars($_REQUEST['id_cat']) : 0;
  $iditem = (isset($_REQUEST['id_item'])) ? htmlspecialchars($_REQUEST['id_item']) : 0;
  $idsub = (isset($_REQUEST['id_sub'])) ? htmlspecialchars($_REQUEST['id_sub']) : 0;

  if ($idlist) $where .= " and id_list=$idlist";
  if ($idcat) $where .= " and id_cat=$idcat";
  if ($iditem) $where .= " and id_item=$iditem";
  if ($idsub) $where .= " and id_sub=$idsub";
  if (isset($_REQUEST['keyword'])) {
    $keyword = htmlspecialchars($_REQUEST['keyword']);
    $where .= " and (namevi LIKE '%$keyword%' or nameen LIKE '%$keyword%')";
  }

  $perPage = 10;
  $startpoint = ($curPage * $perPage) - $perPage;
  $limit = " limit " . $startpoint . "," . $perPage;
  $sql = "select * from #_product_vari where type = ? $where order by numb,id desc $limit";
  $items = $d->rawQuery($sql, array($type));
  $sqlNum = "select count(*) as 'num' from #_product_vari where type = ? $where order by numb,id desc";
  $count = $d->rawQueryOne($sqlNum, array($type));
  $total = (!empty($count)) ? $count['num'] : 0;
  $url = "index.php?com=product&act=man_vari" . $strUrl . "&type=" . $type;
  $paging = $func->pagination($total, $perPage, $curPage, $url);
}

/* Edit vari */
function editVari()
{
  global $d, $langadmin, $func, $strUrl, $curPage, $item, $type;

  $id = (!empty($_GET['id'])) ? htmlspecialchars($_GET['id']) : 0;

  if (empty($id)) {
    $func->transfer(khongnhanduocdulieu, "index.php?com=product&act=man_vari&type=" . $type . "&p=" . $curPage . $strUrl, false);
  } else {
    $item = $d->rawQueryOne("select * from #_product_vari where id = ? and type = ? limit 0,1", array($id, $type));

    if (empty($item)) {
      $func->transfer(dulieukhongcothuc, "index.php?com=product&act=man_vari&type=" . $type . "&p=" . $curPage . $strUrl, false);
    }
  }
}

/* Save vari */
function saveVari()
{
  global $d, $langadmin, $strUrl, $func, $flash, $curPage, $config, $com, $type;

  /* Check post */
  if (empty($_POST)) {
    $func->transfer(khongnhanduocdulieu, "index.php?com=product&act=man_vari&type=" . $type . "&p=" . $curPage . $strUrl, false);
  }

  /* Post dữ liệu */
  $message = '';
  $response = array();
  $id = (!empty($_POST['id'])) ? htmlspecialchars($_POST['id']) : 0;
  $data = (!empty($_POST['data'])) ? $_POST['data'] : null;
  if ($data) {
    foreach ($data as $column => $value) {
      if (strpos($column, 'content') !== false || strpos($column, 'desc') !== false) {
        $data[$column] = htmlspecialchars($func->sanitize($value, 'iframe'));
      } else {
        $data[$column] = htmlspecialchars($func->sanitize($value));
      }
    }

    if (isset($_POST['status'])) {
      $status = '';
      foreach ($_POST['status'] as $attr_column => $attr_value) if ($attr_value != "") $status .= $attr_value . ',';
      $data['status'] = (!empty($status)) ? rtrim($status, ",") : "";
    } else {
      $data['status'] = "";
    }

    if (!empty($config['product'][$type]['slug_vari'])) {
      if (!empty($_POST['slugvi'])) $data['slugvi'] = $func->changeTitle(htmlspecialchars($_POST['slugvi']));
      else $data['slugvi'] = (!empty($data['namevi'])) ? $func->changeTitle($data['namevi']) : '';
      if (!empty($_POST['slugen'])) $data['slugen'] = $func->changeTitle(htmlspecialchars($_POST['slugen']));
      else $data['slugen'] = (!empty($data['nameen'])) ? $func->changeTitle($data['nameen']) : '';
    }

    $data['type'] = $type;
  }

  /* Post seo */
  if (isset($config['product'][$type]['seo_vari']) && $config['product'][$type]['seo_vari'] == true) {
    $dataSeo = (isset($_POST['dataSeo'])) ? $_POST['dataSeo'] : null;
    if ($dataSeo) {
      foreach ($dataSeo as $column => $value) {
        $dataSeo[$column] = htmlspecialchars($func->sanitize($value));
      }
    }
  }

  /* Valid data */
  $checkTitle = $func->checkTitle($data);

  if (!empty($checkTitle)) {
    foreach ($checkTitle as $k => $v) {
      $response['messages'][] = $v;
    }
  }

  if (!empty($config['product'][$type]['slug_vari'])) {
    foreach ($config['website']['slug'] as $k => $v) {
      $dataSlug = array();
      $dataSlug['slug'] = $data['slug' . $k];
      $dataSlug['id'] = $id;
      $dataSlug['copy'] = false;
      $checkSlug = $func->checkSlug($dataSlug);

      if ($checkSlug == 'exist') {
        $response['messages'][] = duongdandatontai;
      } else if ($checkSlug == 'empty') {
        $response['messages'][] = duongdankhongduoctrong;
      }
    }
  }

  if (!empty($response)) {
    /* Flash data */
    if (!empty($data)) {
      foreach ($data as $k => $v) {
        if (!empty($v)) {
          $flash->set($k, $v);
        }
      }
    }

    if (!empty($dataSeo)) {
      foreach ($dataSeo as $k => $v) {
        if (!empty($v)) {
          $flash->set($k, $v);
        }
      }
    }

    /* Errors */
    $response['status'] = 'danger';
    $message = base64_encode(json_encode($response));
    $flash->set('message', $message);

    if ($id) {
      $func->redirect("index.php?com=product&act=edit_vari&type=" . $type . "&p=" . $curPage . $strUrl . "&id=" . $id);
    } else {
      $func->redirect("index.php?com=product&act=add_vari&type=" . $type . "&p=" . $curPage . $strUrl);
    }
  }

  /* save data */
  if ($id) {
    $data['date_updated'] = time();

    $d->where('id', $id);
    $d->where('type', $type);
    if ($d->update('product_vari', $data)) {
      /* Photo */
      if ($func->hasFile("file")) {
        $photoUpdate = array();
        $file_name = $func->uploadName($_FILES["file"]["name"]);

        if ($photo = $func->uploadImage("file", $config['product'][$type]['img_type_vari'], UPLOAD_PRODUCT, $file_name)) {
          $row = $d->rawQueryOne("select id, photo from #_product_vari where id = ? and type = ? limit 0,1", array($id, $type));

          if (!empty($row)) {
            $func->deleteFile(UPLOAD_PRODUCT . $row['photo']);
          }

          $photoUpdate['photo'] = $photo;
          $d->where('id', $id);
          $d->update('product_vari', $photoUpdate);
          unset($photoUpdate);
        }
      }

      /* SEO */
      if (isset($config['product'][$type]['seo_vari']) && $config['product'][$type]['seo_vari'] == true) {
        $d->rawQuery("delete from #_seo where id_parent = ? and com = ? and act = ? and type = ?", array($id, $com, 'man_sub', $type));

        $dataSeo['id_parent'] = $id;
        $dataSeo['com'] = $com;
        $dataSeo['act'] = 'man_vari';
        $dataSeo['type'] = $type;
        $d->insert('seo', $dataSeo);
      }

      $func->transfer(capnhatdulieuthanhcong, "index.php?com=product&act=man_vari&type=" . $type . "&p=" . $curPage . $strUrl);
    } else {
      $func->transfer(capnhatdulieubiloi, "index.php?com=product&act=man_vari&type=" . $type . "&p=" . $curPage . $strUrl, false);
    }
  } else {
    $data['date_created'] = time();

    if ($d->insert('product_vari', $data)) {
      $id_insert = $d->getLastInsertId();

      /* Photo */
      if ($func->hasFile("file")) {
        $photoUpdate = array();
        $file_name = $func->uploadName($_FILES['file']["name"]);

        if ($photo = $func->uploadImage("file", $config['product'][$type]['img_type_vari'], UPLOAD_PRODUCT, $file_name)) {
          $photoUpdate['photo'] = $photo;
          $d->where('id', $id_insert);
          $d->update('product_vari', $photoUpdate);
          unset($photoUpdate);
        }
      }

      /* SEO */
      if (isset($config['product'][$type]['seo_vari']) && $config['product'][$type]['seo_vari'] == true) {
        $dataSeo['id_parent'] = $id_insert;
        $dataSeo['com'] = $com;
        $dataSeo['act'] = 'man_vari';
        $dataSeo['type'] = $type;
        $d->insert('seo', $dataSeo);
      }

      $func->transfer(luudulieuthanhcong, "index.php?com=product&act=man_vari&type=" . $type . "&p=" . $curPage . $strUrl);
    } else {
      $func->transfer(luudulieubiloi, "index.php?com=product&act=man_vari&type=" . $type . "&p=" . $curPage . $strUrl, false);
    }
  }
}

/* Delete vari */
function deleteVari()
{
  global $d, $langadmin, $strUrl, $func, $curPage, $com, $type;

  $id = (!empty($_GET['id'])) ? htmlspecialchars($_GET['id']) : 0;

  if ($id) {
    /* Lấy dữ liệu */
    $row = $d->rawQueryOne("select id, photo from #_product_vari where id = ? and type = ? limit 0,1", array($id, $type));

    if (!empty($row)) {
      /* Xóa chính */
      $func->deleteFile(UPLOAD_PRODUCT . $row['photo']);
      $d->rawQuery("delete from #_product_vari where id = ?", array($id));

      /* Xóa SEO */
      $d->rawQuery("delete from #_seo where id_parent = ? and com = ? and act = ? and type = ?", array($id, $com, 'man_vari', $type));

      $func->transfer(xoadulieuthanhcong, "index.php?com=product&act=man_vari&type=" . $type . "&p=" . $curPage . $strUrl);
    } else {
      $func->transfer(xoadulieubiloi, "index.php?com=product&act=man_vari&type=" . $type . "&p=" . $curPage . $strUrl, false);
    }
  } elseif (isset($_GET['listid'])) {
    $listid = explode(",", $_GET['listid']);

    for ($i = 0; $i < count($listid); $i++) {
      $id = htmlspecialchars($listid[$i]);

      /* Lấy dữ liệu */
      $row = $d->rawQueryOne("select id, photo from #_product_vari where id = ? and type = ? limit 0,1", array($id, $type));

      if (!empty($row)) {
        /* Xóa chính */
        $func->deleteFile(UPLOAD_PRODUCT . $row['photo']);
        $d->rawQuery("delete from #_product_vari where id = ?", array($id));

        /* Xóa SEO */
        $d->rawQuery("delete from #_seo where id_parent = ? and com = ? and act = ? and type = ?", array($id, $com, 'man_vari', $type));
      }
    }

    $func->transfer(xoadulieuthanhcong, "index.php?com=product&act=man_vari&type=" . $type . "&p=" . $curPage . $strUrl);
  } else {
    $func->transfer(khongnhanduocdulieu, "index.php?com=product&act=man_vari&type=" . $type . "&p=" . $curPage . $strUrl, false);
  }
}

/* View brand */
function viewBrands()
{
  global $d, $langadmin, $func, $strUrl, $curPage, $items, $paging, $type;

  $where = "";

  if (isset($_REQUEST['keyword'])) {
    $keyword = htmlspecialchars($_REQUEST['keyword']);
    $where .= " and (namevi LIKE '%$keyword%' or nameen LIKE '%$keyword%')";
  }

  $perPage = 10;
  $startpoint = ($curPage * $perPage) - $perPage;
  $limit = " limit " . $startpoint . "," . $perPage;
  $sql = "select * from #_product_brand where type = ? $where order by numb,id desc $limit";
  $items = $d->rawQuery($sql, array($type));
  $sqlNum = "select count(*) as 'num' from #_product_brand where type = ? $where order by numb,id desc";
  $count = $d->rawQueryOne($sqlNum, array($type));
  $total = (!empty($count)) ? $count['num'] : 0;
  $url = "index.php?com=product&act=man_brand&type=" . $type;
  $paging = $func->pagination($total, $perPage, $curPage, $url);
}

/* Edit brand */
function editBrand()
{
  global $d, $langadmin, $func, $strUrl, $curPage, $item, $gallery, $type, $com;

  $id = (!empty($_GET['id'])) ? htmlspecialchars($_GET['id']) : 0;

  if (empty($id)) {
    $func->transfer(khongnhanduocdulieu, "index.php?com=product&act=man_brand&type=" . $type . "&p=" . $curPage . $strUrl, false);
  } else {
    $item = $d->rawQueryOne("select * from #_product_brand where id = ? and type = ? limit 0,1", array($id, $type));

    if (empty($item)) {
      $func->transfer(dulieukhongcothuc, "index.php?com=product&act=man_brand&type=" . $type . "&p=" . $curPage . $strUrl, false);
    }
  }
}

/* Save brand */
function saveBrand()
{
  global $d, $func, $flash, $config, $com, $type, $curPage, $strUrl;

  if (empty($_POST)) {
    $func->transfer(
      khongnhanduocdulieu,
      "index.php?com=product&act=man_brand&type=$type&p=$curPage",
      false
    );
  }

  $id       = !empty($_POST['id']) ? (int)$_POST['id'] : 0;
  $data     = $_POST['data'] ?? [];
  $dataSeo  = $_POST['dataSeo'] ?? [];
  $savehere = isset($_POST['save-here']);

  /* Sanitize data */
  foreach ($data as $col => $val) {
    $data[$col] = htmlspecialchars(
      $func->sanitize(
        $val,
        (strpos($col, 'content') !== false || strpos($col, 'desc') !== false) ? 'iframe' : ''
      )
    );
  }

  /* Status */
  $data['status'] = !empty($_POST['status'])
    ? implode(',', array_filter($_POST['status']))
    : '';

  /* Slug */
  if (!empty($config['product'][$type]['slug_brand'])) {
    foreach ($config['website']['lang'] as $k => $v) {
      $data["slug$k"] = !empty($_POST["slug$k"])
        ? $func->changeTitle($_POST["slug$k"])
        : $func->changeTitle($data["name$k"] ?? '');
    }
  }

  $data['type'] = $type;

  /* SEO sanitize */
  foreach ($dataSeo as $k => $v) {
    $dataSeo[$k] = htmlspecialchars($func->sanitize($v));
  }

  /* Validate */
  $errors = $func->checkTitle($data);

  if (!empty($config['product'][$type]['slug_brand'])) {
    foreach ($config['website']['slug'] as $k => $v) {
      if (empty($data["slug$k"])) continue;

      $checkSlug = $func->checkSlug([
        'slug' => $data["slug$k"],
        'id'   => $id,
        'copy' => false
      ]);

      if ($checkSlug === 'exist') $errors[] = duongdandatontai;
      if ($checkSlug === 'empty') $errors[] = duongdankhongduoctrong;
    }
  }

  /* Errors */
  if (!empty($errors)) {
    foreach (array_merge($data, $dataSeo) as $k => $v) {
      if (!empty($v)) $flash->set($k, $v);
    }

    $flash->set('message', base64_encode(json_encode([
      'status'   => 'danger',
      'messages' => $errors
    ])));

    $url = $id
      ? "index.php?com=product&act=edit_brand&type=$type&id=$id&p=$curPage$strUrl"
      : "index.php?com=product&act=add_brand&type=$type&p=$curPage$strUrl";

    $func->redirect($url);
  }

  /* Xử lý xoá ảnh */
  $photoDeleted = !empty($data['photo_deleted']);
  unset($data['photo_deleted']);

  /* Save */
  $data[$id ? 'date_updated' : 'date_created'] = time();

  if ($id) {
    $d->where('id', $id);
    $d->where('type', $type);
    $success = $d->update('product_brand', $data);
  } else {
    $success = $d->insert('product_brand', $data);
    $id = $d->getLastInsertId();
  }

  if (!$success) {
    $func->transfer(
      luudulieubiloi,
      "index.php?com=product&act=man_brand&type=$type&p=$curPage",
      false
    );
  }

  /* Delete photo */
  if ($photoDeleted) {
    $row = $d->rawQueryOne("select photo from #_product_brand where id = ?", [$id]);
    if (!empty($row['photo'])) {
      $func->deleteFile(UPLOAD_PRODUCT . $row['photo']);
      $d->where('id', $id)->update('product_brand', ['photo' => '']);
    }
  }

  /* Upload photo */
  if ($func->hasFile('file')) {
    $fileName = $func->uploadName($_FILES['file']['name']);
    if ($photo = $func->uploadImage(
      'file',
      $config['product'][$type]['img_type_brand'],
      UPLOAD_PRODUCT,
      $fileName
    )) {
      $row = $d->rawQueryOne("select photo from #_product_brand where id = ?", [$id]);
      if (!empty($row['photo'])) $func->deleteFile(UPLOAD_PRODUCT . $row['photo']);
      $d->where('id', $id)->update('product_brand', ['photo' => $photo]);
    }
  }

  /* SEO */
  if (!empty($config['product'][$type]['seo_brand'])) {
    $d->rawQuery(
      "delete from #_seo where id_parent = ? and com = ? and act = 'man_brand' and type = ?",
      [$id, $com, $type]
    );

    $dataSeo += [
      'id_parent' => $id,
      'com'       => $com,
      'act'       => 'man_brand',
      'type'      => $type
    ];
    $d->insert('seo', $dataSeo);
  }

  /* Redirect */
  if ($savehere) {
    $func->transfer(
      luudulieuthanhcong,
      "index.php?com=product&act=edit_brand&type=$type&id=$id&p=$curPage$strUrl"
    );
  } else {
    $func->transfer(
      luudulieuthanhcong,
      "index.php?com=product&act=man_brand&type=$type&p=$curPage"
    );
  }
}

/* Delete brand */
function deleteBrand()
{
  global $d, $langadmin, $strUrl, $func, $curPage, $com, $type;

  $id = (!empty($_GET['id'])) ? htmlspecialchars($_GET['id']) : 0;

  if ($id) {
    /* Lấy dữ liệu */
    $row = $d->rawQueryOne("select id, photo from #_product_brand where id = ? and type = ? limit 0,1", array($id, $type));

    if (!empty($row)) {
      /* Xóa chính */
      $func->deleteFile(UPLOAD_PRODUCT . $row['photo']);
      $d->rawQuery("delete from #_product_brand where id = ?", array($id));

      /* Xóa SEO */
      $d->rawQuery("delete from #_seo where id_parent = ? and com = ? and act = ? and type = ?", array($id, $com, 'man_brand', $type));

      $func->transfer(xoadulieuthanhcong, "index.php?com=product&act=man_brand&type=" . $type . "&p=" . $curPage . $strUrl);
    } else {
      $func->transfer(xoadulieubiloi, "index.php?com=product&act=man_brand&type=" . $type . "&p=" . $curPage . $strUrl, false);
    }
  } elseif (isset($_GET['listid'])) {
    $listid = explode(",", $_GET['listid']);

    for ($i = 0; $i < count($listid); $i++) {
      $id = htmlspecialchars($listid[$i]);

      /* Lấy dữ liệu */
      $row = $d->rawQueryOne("select id, photo from #_product_brand where id = ? and type = ? limit 0,1", array($id, $type));

      if (!empty($row)) {
        /* Xóa chính */
        $func->deleteFile(UPLOAD_PRODUCT . $row['photo']);
        $d->rawQuery("delete from #_product_brand where id = ?", array($id));

        /* Xóa SEO */
        $d->rawQuery("delete from #_seo where id_parent = ? and com = ? and act = ? and type = ?", array($id, $com, 'man_brand', $type));
      }
    }

    $func->transfer(xoadulieuthanhcong, "index.php?com=product&act=man_brand&type=" . $type . "&p=" . $curPage . $strUrl);
  } else {
    $func->transfer(khongnhanduocdulieu, "index.php?com=product&act=man_brand&type=" . $type . "&p=" . $curPage . $strUrl, false);
  }
}
