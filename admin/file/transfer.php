<HTML>
<?php
include '../lib/database.php';
if (isset($_GET['url']) && $_GET['url'] != NULL) {
  $url = $_GET['url'];
  if (isset($_GET['id']) && $_GET['id'] != NULL) {
    $id = $_GET['id'];
  }
  if ($url === 'gallery') {
    $fullUrl = $config['baseAdmin'] . $url . '.php?id=' . $id;
  } else {
    $fullUrl = $config['baseAdmin'] . $url . '.php';
  }
}
if (isset($_GET['stt']) && $_GET['stt'] != NULL) {
  $stt = $_GET['stt'];
}
?>

<HEAD>
  <TITLE>:: Thông Báo ::</TITLE>
  <base href="<?php echo $config['base'] ?>" />
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <meta http-equiv="REFRESH" content="1.5; url=<?= $fullUrl ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
  <meta name="robots" content="noodp,noindex,nofollow" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="assets/bootstrap/bootstrap.css">

  <style type="text/css">
    body {
      background: #eee
    }

    #alert {
      background: #fff;
      padding: 20px;
      margin: 30px auto;
      border-radius: 3px;
      -webkit-box-shadow: 0px 0px 3px 0px rgba(50, 50, 50, 0.3);
      -moz-box-shadow: 0px 0px 3px 0px rgba(50, 50, 50, 0.3);
      box-shadow: 0px 0px 3px 0px rgba(50, 50, 50, 0.3);
      margin-top: 100px;
      text-align: center;
      width: 100%;
      max-width: 400px;
    }

    #alert .fas,
    .fa-sharp {
      font-size: 60px;
    }

    #alert .rlink {
      margin: 10px 0px;
    }

    #alert .title {
      text-transform: uppercase;
      font-weight: bold;
      margin: 10px;
    }

    .fasuccess {
      color: #5cb85c;
    }

    .fadanger {
      color: #D9534F;
    }

    #process-bar {
      width: 0%;
      -webkit-transition: all 0.3s !important;
      transition: all 0.3s !important;
    }
  </style>
</HEAD>

<BODY>
  <div id="alert">
    <i
      class="fa-sharp fa-solid <?= ($stt === "success") ? 'fa-check-circle fasuccess' : 'fa-exclamation-triangle fadanger' ?>"></i>
    <div class="title">Thông báo</div>
    <div class="message alert <?= ($stt === "success") ? 'alert-success' : 'alert-danger' ?>">
      <?= ($stt === "success") ? 'Cập nhật dữ liệu thành công' : 'Lỗi thao tác' ?>
    </div>
    <div class="rlink">(<a href="<?php echo $config['baseAdmin'] . '' . $url . '.php' ?>">Click vào đây nếu không muốn
        đợi
        lâu</a>)</div>
    <div class="progress">
      <div id="process-bar"
        class="progress-bar progress-bar-striped progress-bar-<?= ($stt === "success") ? 'success' : 'danger' ?> active"
        role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
    </div>
  </div>
  <script type="text/javascript">
    var elem = document.getElementById("process-bar");
    var pos = 0;
    setInterval(function() {
      pos += 1;
      elem.style.width = pos + '%';
    }, 5);
  </script>
</BODY>

</HTML>
