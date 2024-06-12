<?php
require_once "../functions.php";

$idOrder = (int)$_GET['idOrder'];

if(isset($_POST['submit'])){
    $complain = new Complain();
    $complain->complainOrder($idOrder, $_POST, $_FILES);
    echo '<script>
                document.addEventListener("DOMContentLoaded", function () {
                    Swal.fire({
                        icon: "success",
                        title: "Oops...",
                        text: "Complain Accepted",
                        footer: \'<a href="#"></a>\'
                    });
                });
            </script>';
}


$syntax = "SELECT * FROM KELAS";
$datas = query($syntax);
$syntaxComplain = "SELECT * FROM KELAS";
$complaindatum = query($syntax);

// Check if form is submitted
// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//     // Get the complaint message from the form data
//     $complainMessage = $_POST['complainMessage'];

//     // Get the uploaded picture
//     $complainPicture = $_FILES['complainPicture'];

//     //TESTING IDADMIN = 2020 ATAU YANG PERTAMA MUNCUL. UBAH JADI ID ADMIN [0] SORT BY NUMBER OF COMPLAINTS ON HAND
//     $adminQuery = "SELECT adminId FROM admin LIMIT 1";
//     $adminResult = query($adminQuery);
//     $adminId = $adminResult[0]['adminId'];

//     //TESTING IDCLASS = 1
//     $orderQuery = "SELECT `idOrder` FROM `order` WHERE `idClass` = 1 LIMIT 1";
//     $orderResult = query($orderQuery);
//     if (count($orderResult) > 0) {
//         $idOrder = $orderResult[0]['idOrder'];
//     } else {
//         die("Error: No orders found for class with idClass of 1.");
//     }

//     $idOrder = $orderResult[0]['idOrder'];

//     // Insert the new complaint into the "complain" table
//     $query = "INSERT INTO complain (complainMessage, adminId, idOrder) VALUES (?, ?, ?)";
//     $stmt = mysqli_prepare($conn, $query);
//     mysqli_stmt_bind_param($stmt, 'sii', $complainMessage, $adminId, $idOrder);
//     mysqli_stmt_execute($stmt);

//     // Update the statusOrder of the order to '3'
//     $updateQuery = "UPDATE `order` SET `statusOrder` = 3 WHERE `idOrder` = ?";
//     $stmt = mysqli_prepare($conn, $updateQuery);
//     mysqli_stmt_bind_param($stmt, 'i', $idOrder);
//     mysqli_stmt_execute($stmt);

//     echo "Complaint submitted successfully.";
// }


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>TutorTap - Complain</title>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .bg-ouryellow {
            background-color: #FFCC01;
        }

        .card-body {
            position: relative;
            height: 245px;
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
            margin-top: 2rem;
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

        .btn-primary {
            background-color: #FFCC01;
            border: none;
            color: #343a40;
            font-weight: bold;
            border-radius: 0.5rem;
            padding: 0.75rem 1.5rem;
            transition: background-color 0.2s;
        }

        .btn-primary:hover {
            background-color: #e6b800;
        }

        .container-fluid {
            padding-left: 15px;
            padding-right: 15px;
        }

        input::placeholder {
            color: #d1d1d1;
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

    <div class="container-fluid text-center mt-5">
        <h1>Complain Form</h1>
    </div>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Form Pengajuan</h5>
                        <form method="post" action="" enctype="">
                            <input type="text" class="form-control" name="complainMessage" placeholder="Tuliskan masalah yang kamu alami" aria-label="MessageKomplain" aria-describedby="basic-addon2" autocomplete="off" autofocus>
                            <input type="file" name="complainPicture">
                            <p class="card-text"></p>
                            <button class="btn btn-primary position-relative end-0 mt-0" type="submit" name="submit">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>