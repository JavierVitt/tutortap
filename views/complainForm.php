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

    <div class="container-fluid text-center mt-5">
        <h1>Complain Form</h1>
    </div>

    <div class="container-fluid">
        <!-- <div class="row">
            <?php foreach ($datas as $data) : ?>
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $data['namaKelas']; ?></h5>
                        <p class="card-text"><?php echo $data['deskripsiKelas']; ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div> -->
        <div class="row">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Form Pengajuan</h5>
                    <form method="post" action="" enctype="multipart/form-data">
                        <input type="text" class="form-control" name="complainMessage" placeholder="Tuliskan masalah yang kamu alami" aria-label="MessageKomplain" aria-describedby="basic-addon2">
                        <input type="file" name="complainPicture">
                        <p class="card-text"></p>
                        <button class="btn btn-primary position-relative end-0" type="submit" name="submit">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>