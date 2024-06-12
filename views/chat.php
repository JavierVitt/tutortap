<?php
require_once '../functions.php';
$senderId = $_GET['id'];
$receiverId = $_GET['receiverId'];

$syn = "SELECT * FROM CHAT WHERE (senderId = $senderId AND receiverId = $receiverId) OR (receiverId = $senderId AND senderId = $receiverId)";
$results = query($syn);

if (isset($_POST['submit'])) {
  $chat = $_POST['textBox'];
  $chats = new Chat();
  $chats->addChat($senderId, $receiverId, $chat);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <title>Chat</title>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <meta name="description" content="" />
  <meta name="author" content="" />
  <!-- Favicon-->
  <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
  <!-- Custom Google font-->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@100;200;300;400;500;600;700;800;900&amp;display=swap" rel="stylesheet" />
  <!-- Bootstrap icons-->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <!-- Core theme CSS (includes Bootstrap)-->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <link href="../styles/styles.css" rel="stylesheet" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
  <style>
    .bg-ouryellow {
            background-color: #FFCC01;
        }

        .card-body {
            position: relative;
            height: 400px;
            overflow-y: auto;
            padding: 2rem;
        }

        .navbar h1 {
            margin: 0;
            color: #fff;
            font-weight: bold;
        }

        .btn-icon {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .btn-icon i {
            transition: transform 0.2s;
        }

        .btn-icon:hover i {
            transform: scale(1.2);
        }

        .container-fluid.text-center.mt-5 h1 {
            margin-top: 2rem;
            margin-bottom: 2rem;
            font-weight: bold;
            color: #343a40;
        }

        .card {
            margin-top: 0;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            border: none;
            border-radius: 1rem;
        }

        .card-title {
            font-weight: bold;
            color: #343a40;
        }

        .form-control {
            border-radius: 0.5rem;
            border: 2px solid #FFCC01;
            margin-bottom: 1rem;
        }

        .btn-warning {
            background-color: #FFCC01;
            border: none;
            color: #343a40;
            font-weight: bold;
            border-radius: 0.5rem;
            padding: 0.75rem 1.5rem;
            transition: background-color 0.2s;
        }

        .btn-warning:hover {
            background-color: #e6b800;
        }
    </style>
</head>

<body>
  <div class="navbar navbar-expand-lg navbar-light bg-ouryellow">
        <div class="container-fluid">
            <!-- Logo -->
            <a class="navbar-brand" href="#">
                <img src="../images/skilltap logo+brand.png" class="rounded-pill" style="width:100px; background-color:black" alt="">
            </a>
    
            <!-- Navigation Icons -->
            <div class="navbar-nav ms-auto">
                <a class="btn-icon me-5" href="#"><i class="bi bi-person-circle text-white" style="font-size: 30px;"></i></a>
                <a class="btn-icon me-5" href="#"><i class="bi bi-filter text-white" style="font-size: 30px;"></i></a>
                <a class="btn-icon me-5" href="#"><i class="bi bi-cart-fill text-white" style="font-size: 30px;"></i></a>
                <a class="btn-icon" href="#"><i class="bi bi-list text-white" style="font-size: 30px;"></i></a>
            </div>
        </div>
    </div>

  <section style="background-color: #ffffff;">
    <div class="container py-5">

      <div class="row d-flex justify-content-center">
        <div class="col-md-8 col-lg-6 col-xl-4">

          <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center p-3" style="border-top: 4px solid #ffa900;">
              <h5 class="mb-0">Chat messages</h5>
              <div class="d-flex flex-row align-items-center">
                <span class="badge bg-warning me-3">20</span>
                <i class="fas fa-minus me-3 text-muted fa-xs"></i>
                <i class="fas fa-comments me-3 text-muted fa-xs"></i>
                <i class="fas fa-times text-muted fa-xs"></i>
              </div>
            </div>
            <div class="card-body" data-mdb-perfect-scrollbar-init style="position: relative; height: 400px">
              <!-- sad -->
              <?php foreach ($results as $result) : ?>

                <?php if ($result['receiverId'] == $senderId) : ?>
                  <?php
                  $idRand = $result['receiverId'];
                  $syntax = "SELECT * FROM USER WHERE userId = $idRand";
                  $nama =  query($syntax);
                  $hasilNama = $nama[0]['nama'];
                  $hasilWaktu = $result['waktu'];
                  $hasilPesan = $result['pesan'];
                  ?>
                  <div class="d-flex justify-content-between">
                    <p class="small mb-1"><?= $hasilNama; ?></p>
                    <p class="small mb-1 text-muted"><?= $hasilWaktu; ?></p>
                  </div>
                  <div class="d-flex flex-row justify-content-start">
                    <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-chat/ava5-bg.webp" alt="avatar 1" style="width: 45px; height: 100%;">
                    <div>
                      <p class="small p-2 ms-3 mb-3 rounded-3" style="background-color: #f5f6f7;"><?= $result['pesan']; ?></p>
                    </div>
                  </div>
                <?php endif; ?>
                <?php if ($result['senderId'] == $senderId) : ?>
                  <?php
                  $idRand = $result['senderId'];
                  $syntax = "SELECT * FROM USER WHERE userId = $idRand";
                  $nama =  query($syntax);
                  $hasilNama = $nama[0]['nama'];
                  $hasilWaktu = $result['waktu'];
                  $hasilPesan = $result['pesan'];
                  ?>
                  <div class="d-flex justify-content-between">
                    <p class="small mb-1 text-muted"><?= $hasilWaktu; ?></p>
                    <p class="small mb-1"><?= $hasilNama; ?></p>
                  </div>
                  <div class="d-flex flex-row justify-content-end mb-4 pt-1">
                    <div>
                      <p class="small p-2 me-3 mb-3 text-white rounded-3 bg-warning"><?= $hasilPesan; ?></p>
                    </div>
                    <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-chat/ava6-bg.webp" alt="avatar 1" style="width: 45px; height: 100%;">
                  </div>
                <?php endif; ?>
              <?php endforeach; ?>
              <!-- sdnan -->

            </div>
            <form action="" method="post">
              <div class="card-footer text-muted d-flex justify-content-start align-items-center p-3">
                <div class="input-group mb-0">
                  <input type="text" class="form-control" placeholder="Type message" aria-label="Recipient's username" name="textBox" aria-describedby="button-addon2" />
                  <button data-mdb-button-init data-mdb-ripple-init class="btn btn-warning" type="submit" name="submit" id="button-addon2" style="padding-top: .55rem; height: 2.47rem;">
                    Send
                  </button>
                </div>
              </div>
            </form>
          </div>

        </div>
      </div>

    </div>
  </section>



</body>

</html>