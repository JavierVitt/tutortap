<?php
require_once "../functions.php";

$syntax = "SELECT * FROM KELAS";
$datas = query($syntax);
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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <link href="../styles/styles.css" rel="stylesheet" />
</head>

<body class="montserratRegular">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>

<body>
    <div class="navbar w-100 bg-ouryellow ">
        <div class="container-fluid d-flex justify-content-between">
            <div class="input-group w-75">
                <input type="text" class="form-control" placeholder="Search Classes" aria-label="Recipient's username" aria-describedby="basic-addon2">
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="button">Search</button>
                </div>
            </div>

            <div class="container w-25 row">
                <div class="container col-3">
                    <i class="bi bi-envelope-fill text-white" style="font-size: 30px;"></i></a></th>
                </div>
                <div class="container col-3">
                    <i class="bi bi-filter text-white" style="font-size: 30px;"></i></a></th>
                </div>
                <div class="container col-3">
                    <i class="bi bi-cart-fill text-white" style="font-size: 30px;"></i></a></th>
                </div>
                <div class="container col-3">
                    <i class="bi bi-list text-white" style="font-size: 30px;"></i></a></th>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid text-center mt-5 montserratBold ">
        <h1 class="montserratBold pb-3">Discover Classes</h1>
    </div>

    <div class="container-fluid">
        <div class="row">
            <?php foreach ($datas as $data) : ?>
                <div class="card col-6" style="background-color: #E9D7AF">
                    <img class="card-img-top py-3" src="../images/<?php echo $data['fotoKelas']; ?>" alt="Card image cap">
                    <div class="card-body">
                        <h5 class="card-title montserratBold py-3" style="font-size:35px"><?php echo $data['namaKelas']; ?></h5>
                        <p class="card-text"><?php echo $data['deskripsiKelas']; ?></p>
                        <p class="card-text montserratSemiBold px-3" style="font-size: 30px;">Rp. <?php echo $data['hargaKelas']; ?></p>

                        <!-- REVIEW BELUM! -->
                        <div class="card-text" style="display: flex; align-items: center;">
                            <i class="bi bi-star-fill" style="font-size: 30px; color: #FFCC01;"></i>
                            <span style="margin-left: 10px;">5.0 - 35 reviews</span>
                        </div>

                        
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

        <!-- <div class="card mb-3">
            <img class="card-img-top" src="../images/20221003_133232.jpg" alt="Card image cap">
            <div class="card-body">
                <h5 class="card-title">Card title</h5>
                <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content.
                    This content is a little bit longer.</p>
                <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>
            </div>
        </div> -->




</body>

</html>