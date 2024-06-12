<?php
require_once '../functions.php';

// $id = $_GET['id'];

$idUser = $_GET['id'];

$kelas = new Kelas();
$kelass = $kelas->getTutorKelas($idUser);

$user = new User();
$saldo = $user->getSaldo($idUser);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>TutorTap - Home</title>
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
</head>

<body class="">
    <!-- <div class="navbar w-100 " style="background-color: rgb(0,0,0);">
        <div class="container-fluid d-flex justify-content-center">
            <img src="../images/skilltap logo+brand.png" style="width:300px; " alt="">
        </div>
    </div> -->
    <div class="navbar w-100 bg-ouryellow">
        <div class="container-fluid d-flex justify-content-between">
            <div class="container w-25 d-flex justify-content-center">
                <a href="">
                    <img src="../images/skilltap logo+brand.png" class="rounded-pill" style="width:200px; background-color:black" alt="">
                </a>
            </div>
            <div class="input-group w-50">
                <input type="text" class="form-control montserratRegular" placeholder="Search Classes" aria-label="Recipient's username" aria-describedby="basic-addon2">
                <div class="input-group-append">
                    <button class="btn btn-outline-dark montserratSemiBold" type="button">Search</button>
                </div>
            </div>

            <div class="container w-25 px-5 row" style="color:black;">
                <div class="container col-3">
                    <a href="">
                    <i class="bi bi-envelope-fill text-dark" style="font-size: 30px; "></i></a></th>
                    </a>
                </div>
                <div class="container col-3">
                    <a href="">
                    <i class="bi bi-filter text-dark" style="font-size: 30px;"></i></a></th>
                    </a>
                </div>
                <div class="container col-3">
                    <a href="">
                    <i class="bi bi-cart-fill text-dark" style="font-size: 30px;"></i></a></th>
                    </a>
                </div>
                <div class="container col-3">
                    <a href="">
                    <i class="bi bi-list text-dark" style="font-size: 30px;"></i></a></th>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="containter-fluid d-flex justify-content-center align-items-center my-4">
        <div class="container mx-5 rounded-4 montserratBold" style="height:10%; width:50%; background-color: rgb(200,200,200)">
            <div class="container-fluid row">
                <div class="col-2 text-center text-primary">
                    <i class="bi bi-wallet2" style="font-size:40px"></i>
                </div>

                <!-- akses tutor balance disini -->
                <div class="col-7 text-center text-primary d-flex justify-content-center align-items-center">
                    <h1 class="montserratSemiBold"><?php echo $saldo; ?></h1>
                </div>
                <div class="col-3 text-center d-flex justify-content-center align-items-center">
                    <button class="btn btn-outline-primary montserratBold" style="font-size:20px;" onclick="window.location.href='WithdrawBalance.php?id=<?=$idUser;?>'">Withdraw</button>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid text-center montserratBold">
        <h1>Your Classes</h1>
    </div>

    <div class="container-fluid p-5">
        <div class="row">
            <?php foreach ($kelass as $kelas) : ?>
                <div class="card mb-3 col-6 p-4 shadow-lg p-3 mb-5 bg-white rounded">
                    <img class="card-img-top" src="../images/<?php echo $kelas['fotoKelas']; ?>" alt="Card image cap" style="height: 200px; width: 100%; object-fit: cover;">
                    <div class="card-body">
                        <!-- CLASS.namaKelas -->
                        <h5 class="card-title"><?php echo $kelas['namaKelas']; ?></h5>
                        <!-- CLASS.hargaKelas, CLASS.durasiKelas -->
                        <h5 class="card-text">Rp.<?= $kelas['hargaKelas']; ?>/<?= $kelas['durasiKelas']; ?></h5>
                        <!-- CLASS.deskripsiKelas -->
                        <p class="card-text h-50"><?= $kelas['deskripsiKelas']; ?></p>
                        <!-- <div class="card-text" style="display: flex; align-items: center;">
                            <i class="bi bi-star-fill" style="font-size: 30px; color: #FFCC01;"></i> -->
                            <?php
                            // $syn = "SELECT * FROM CLASS_RATING_RESULT WHERE classId = '" . $kelas['idKelas'] . "'";
                            // $hasils = query($syn);
                            // $rataRataRating = $hasils['totalRatingSum'] / $hasils['totalRatingCount'];
                            ?>
                            <!-- <span style="margin-left: 10px;"><?php echo $rataRataRating; ?> - <?php echo $hasils['totalRatingCount'] ?> reviews</span>
                        </div> -->

                        <div class="container-fluid d-flex justify-content-center">
                            <div class="btn-group w-50">
                                <button type="button" class="btn btn-outline-dark " style="font-size: 25px;">
                                    <a href=""><i class="bi bi-pencil-fill" style="color: gray;"></i></a>
                                </button>
                                <button type="button" class="btn btn-outline-danger" style="font-size: 25px;">
                                    <a href=""><i class="bi bi-trash-fill" style="color:red;"></i></a>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="container-fluid bg-ouryellow d-flex justify-content-center py-3">
        <div class="btn-group w-50 py-5">
            <button type="button" class="btn btn-outline-dark " style="font-size: 25px;" onclick="window.location.href='hoeTutor.php?id=<?php echo $id; ?>'">
                <h1>Tutor</h1>
            </button>
            <button type="button" class="btn btn-outline-dark" style="font-size: 25px;" onclick="window.location.href='homeLearner.php?id=<?php echo $idUser; ?>'">
                 <h1>Learner</h1>
            </button>
        </div>
    </div>




</body>
<!-- Footer -->
<div class="container">
    <footer class="d-flex flex-wrap justify-content-between align-items-center border-top">
        <p class="col-md-4 mb-0 text-muted">&copy; 2024 Company, Inc</p>

        <a href="/" class="col-md-4 d-flex align-items-center justify-content-center mb-3 mb-md-0 me-md-auto link-dark text-decoration-none">

            <img src="../images/skilltap logo+brand.png" alt="" style="width: 30%; height:30%;">
        </a>

        <ul class="nav col-md-4 justify-content-end">
            <li class="nav-item"><a href="#" class="nav-link px-2 text-muted">Home</a></li>
            <li class="nav-item"><a href="#" class="nav-link px-2 text-muted">Features</a></li>
            <li class="nav-item"><a href="#" class="nav-link px-2 text-muted">Pricing</a></li>
            <li class="nav-item"><a href="#" class="nav-link px-2 text-muted">FAQs</a></li>
            <li class="nav-item"><a href="#" class="nav-link px-2 text-muted">About</a></li>
        </ul>
    </footer>
</div>

</html>