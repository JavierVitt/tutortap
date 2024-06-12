<?php
require_once "../functions.php";
$idKelas = $_GET['classId'];
$idUser = $_GET['id'];

$syn = "SELECT * FROM USER WHERE userId = $idUser";
$users = query($syn);
$syn = "SELECT * FROM KELAS WHERE idKelas = $idKelas"; 
$kelas = query($syn);

if(count($users) == 0 || count($kelas) == 0){
    // echo "<script>document.location.href = 'homeLearner.php?id=$idUser'</script>";
    echo "<script>document.location.href = 'error.php?status=queryError'</script>";
}

// if($users[0]['status'] == 2){
//     echo "<script>document.location.href = 'error.php?status=suspiciousUser'</script>";
// }

$syntax = "SELECT * FROM KELAS where idKelas = $idKelas";
$kelas = query($syntax);

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
            <h1 class="montserratBold pb-3">Class Detail</h1>
        </div>

        <div class="container-fluid">
            <div class="row">
                <div class="card col-12" >
                    <div class="container-fluid d-flex justify-content-center">
                    <img class="card-img-top py-3" src="../images/<?php echo $kelas[0]['fotoKelas']; ?> " style="height: 500px; width: 75%; object-fit: cover;"
                        alt="Card image cap">
                    </div>
                    <div class="card-body d-flex justify-content-center">
                        <div class="container" style="width:75%;">

                            <!-- hapus ini nanti -->
                            <h5 class="card-title montserratBold py-3" style="font-size:35px">
                                <?php echo $kelas[0]['namaKelas']; ?>
                            </h5>


                            <p class="card-text">
                                <?php echo $kelas[0]['deskripsiKelas']; ?>
                            </p>
                            <p class="card-text montserratSemiBold px-3" style="font-size: 30px;">Rp.
                                <?php echo $kelas[0]['hargaKelas']; ?>/
                                <?php echo $kelas[0]['durasiKelas']; ?>
                            </p>


                            <!-- REVIEW BELUM! -->
                            <div class="card-text" style="display: flex; align-items: center;">
                                <i class="bi bi-star-fill" style="font-size: 30px; color: #FFCC01;"></i>
                                <span style="margin-left: 10px;">5.0 - 35 reviews</span>
                            </div>

                            <!-- tutor section -->
                            <div class="d-flex align-items-center mb-4">
                                <div class="flex-shrink-0">
                                    <img src="../images/javier.png"
                                    alt="Generic placeholder image" class="img-fluid rounded-circle border border-dark border-3"
                                    style="width: 70px;">
                                </div>
                                <div class="flex-grow-1 ms-3 py-5">
                                    <div class="d-flex flex-row align-items-center mb-2">
                                    
                                    <p class="mb-0 me-2 montserratBold">Javier Vittorio</p>
                                    <ul class="mb-0 list-unstyled d-flex flex-row" style="color: #1B7B2C;">
                                        <li>
                                        <i class="bi bi-star-fill" style="color: #FFCC01;"></i>
                                        </li>
                                    </ul>
                                    </div>
                                    <div>
                                    <button  type="button" data-mdb-button-init data-mdb-ripple-init class="btn btn-outline-dark btn-rounded btn-sm"
                                        data-mdb-ripple-color="dark">+ Follow</button>
                                    <button  type="button" data-mdb-button-init data-mdb-ripple-init class="btn btn-outline-dark btn-rounded btn-sm"
                                        data-mdb-ripple-color="dark">See profile</button>
                                    <button  type="button" data-mdb-button-init data-mdb-ripple-init class="btn btn-outline-dark btn-floating btn-sm"
                                        data-mdb-ripple-color="dark"><i class="bi bi-chat-left-dots-fill"></i></button>
                                    </div>
                                </div>
                            </div>


                        </div>
                        
                    </div>

                    <div class="container-fluid d-flex justify-content-center" style="width:75%;">
                         <div class="input-group w-25">
                            <input type="text" class="form-control montserratRegular text-center" placeholder="Input" aria-label="Recipient's username" aria-describedby="basic-addon2" style="border-width: 3px; border-color:black;font-size:30px; color:black;">
                            
                            <div class="input-group-append mt-2">
                                <h1 style="font-size:40px;" class=""><?php echo $kelas[0]['durasiKelas']; ?></h1>
                            </div>
                        </div>
                    </div>  

                    <div class="container-fluid d-flex justify-content-center" style="width:75%;">
                        <button type="button" class="btn btn-outline-success my-3 w-100" style="font-size: 25px;" onclick="redirectToPembayaran()">
                            <h1>Order Class</h1>
                        </button>
                        <script>
                                function redirectToPembayaran(){
                                    var inputValue = document.querySelector('.form-control.montserratRegular.text-center').value;
                                    console.log(inputValue);
                                    window.location.href='pembayaranKelas.php?id=<?php echo $idUser;?>&classId=<?php echo $idKelas; ?>&durasi='+inputValue;
                                }
                                var inputValue = document.querySelector('.form-control.montserratRegular.text-center').value;
                                console.log(inputValue);
                        </script>
                    </div>  

                   
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