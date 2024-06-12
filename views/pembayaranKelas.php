<?php
require_once "../functions.php";
$idKelas = $_GET['classId'];
$idUser = $_GET['id'];
$durasi = $_GET['durasi'];
$syn = "SELECT * FROM USER WHERE userId = $idUser";
$users = query($syn);
$syn = "SELECT * FROM KELAS WHERE idKelas = $idKelas"; 
$kelas = query($syn);

if(count($users) == 0 || count($kelas) == 0){
    // echo "<script>document.location.href = 'homeLearner.php?id=$idUser'</script>";
    echo "<script>document.location.href = 'error.php?status=queryError'</script>";
}

if($users[0]['status'] == 2){
    echo "<script>document.location.href = 'error.php?status=suspiciousUser'</script>";
}
else{
    validasiPesanan();
}



function validasiPesanan(){
    global $idUser, $idKelas, $durasi, $totalHarga, $idOrder;
    $syntax = "SELECT * FROM KELAS WHERE idKelas = $idKelas";
    $kelas = query($syntax);
    $totalHarga = $durasi * $kelas[0]['hargaKelas'];

    $order = new Order();
    $idOrder = $order->addOrder($idUser, $idKelas, $durasi, $totalHarga);
}

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
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@100;200;300;400;500;600;700;800;900&amp;display=swap"
        rel="stylesheet" />
    <!-- Bootstrap icons-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Core theme CSS (includes Bootstrap)-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <link href="../styles/styles.css" rel="stylesheet" />
</head>

<body class="montserratRegular">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    </head>

    <body>
        <div class="navbar w-100 bg-ouryellow ">
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
        <div class="container-fluid text-center mt-5 montserratBold ">
            <h1 class="montserratBold pb-3">Pilih Metode Pembayaran</h1>
        </div>
        <div class="container-fluid d-flex justify-content-center my-5">
            <div class="container row">
            <div class="container col-4 text-center " style="text-decoration:none; color:inherit;">
                <a href="" style="text-decoration:none; color:inherit;">
                    <img src="https://i0.wp.com/swanz.id/wp-content/uploads/2020/10/OVO-Logo.jpg?ssl=1" alt="" style="height: 100px; width: 45%; object-fit: contain;">
                </a>
                <h3 class="montserratBold">OVO</h3>
            </div>
            <div class="container col-4 text-center" style="text-decoration:none; color:inherit;">
                <a href="">
                <img src="https://i.pinimg.com/originals/65/bb/9c/65bb9cee7877f98145f05d73fbf7ebbf.png" alt="" style="height: 100px; width: 45%; object-fit: contain;">
                </a>
                <h3 class="montserratBold">Gopay</h3>
            </div>
            <div class="container col-4 text-center" style="text-decoration:none; color:inherit;">
                <a href="pembayaranBCA.php?id=<?php echo $idUser;?>&classId=<?php echo $idKelas; ?>&harga=<?php echo $kelas[0]['hargaKelas'] ?>&idOrder=<?php echo $idOrder; ?>" style="text-decoration:none; color:inherit;">
                    <img src="https://logos-download.com/wp-content/uploads/2017/03/BCA_logo_Bank_Central_Asia.png" alt="" style="height: 100px; width: 45%; object-fit: contain;">
                    <h3 class="montserratBold">BCA</h3>
                </a>
            </div>
        </div>
        </div>
        

            

        


        <div class="container-fluid bg-ouryellow d-flex justify-content-center pt-3">
            <div class="btn-group w-50 py-5">
                <button type="button" class="btn btn-outline-dark " style="font-size: 25px;">
                    <h1>Tutor</h1>
                </button>
                <button type="button" class="btn btn-outline-dark" style="font-size: 25px;">
                    <h1>Learner</h1>
                </button>
            </div>
        </div>

    </body>

    <div class="container">
        <footer class="d-flex flex-wrap justify-content-between align-items-center border-top">
            <p class="col-md-4 mb-0 text-muted">&copy; 2024 Company, Inc</p>

            <a href="/"
                class="col-md-4 d-flex align-items-center justify-content-center mb-3 mb-md-0 me-md-auto link-dark text-decoration-none">

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