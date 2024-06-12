<?php
session_start();
require_once "../functions.php";

$complain = new Complain();
$results = $complain->getAllComplain();

// $adminQuery = "SELECT namaAdmin FROM admin LIMIT 1";
// $adminResult = query($adminQuery);
// $adminName = $adminResult[0]['namaAdmin'];

// Fetch all complained orders where the adminId is 2020
// $syntax = "SELECT `order`.`idOrder`, `complain`.`complainId`, `complain`.`complainMessage`, `complain`.`complainPicture`
//            FROM `order` 
//            JOIN `complain` ON `order`.`idOrder` = `complain`.`idOrder` 
//            WHERE `complain`.`adminId` = 2020";

// $datas = query($syntax);

// // Check if the Approve button is clicked
// if (isset($_POST['approve'])) {
//     $complainId = $_POST['complainId']; // Get the complain ID from the form data

//     // Get the idOrder for the given complainId
//     $orderQuery = "SELECT `idOrder` FROM `complain` WHERE `complainId` = ?";
//     $stmt = mysqli_prepare($conn, $orderQuery);
//     mysqli_stmt_bind_param($stmt, 'i', $complainId);
//     mysqli_stmt_execute($stmt);
//     $orderResult = mysqli_stmt_get_result($stmt);
//     if (mysqli_num_rows($orderResult) > 0) {
//         $idOrder = mysqli_fetch_assoc($orderResult)['idOrder'];
//     } else {
//         die("Error: No order found for complain with complainId of {$complainId}.");
//     }

//     // Change the statusOrder of the order to 4
//     $updateQuery = "UPDATE `order` SET `statusOrder` = 4 WHERE `idOrder` = ?";
//     $stmt = mysqli_prepare($conn, $updateQuery);
//     mysqli_stmt_bind_param($stmt, 'i', $idOrder);
//     mysqli_stmt_execute($stmt);

//     // Delete the complaint from the complain table
//     $deleteQuery = "DELETE FROM complain WHERE complainId = ?";
//     $stmt = mysqli_prepare($conn, $deleteQuery);
//     mysqli_stmt_bind_param($stmt, 'i', $complainId);
//     mysqli_stmt_execute($stmt);

//     $_SESSION['message'] = 'Complaint approved successfully.';
//     header('Location: ' . $_SERVER['PHP_SELF']);
//     exit;
// }

// // Check if the Reject button is clicked
// if (isset($_POST['reject'])) {
//     $complainId = $_POST['complainId']; // Get the complain ID from the form data

//     // Get the idOrder for the given complainId
//     $orderQuery = "SELECT `idOrder` FROM `complain` WHERE `complainId` = ?";
//     $stmt = mysqli_prepare($conn, $orderQuery);
//     mysqli_stmt_bind_param($stmt, 'i', $complainId);
//     mysqli_stmt_execute($stmt);
//     $orderResult = mysqli_stmt_get_result($stmt);
//     if (mysqli_num_rows($orderResult) > 0) {
//         $idOrder = mysqli_fetch_assoc($orderResult)['idOrder'];
//     } else {
//         die("Error: No order found for complain with complainId of {$complainId}.");
//     }

//     // Change the statusOrder of the order to 5
//     $updateQuery = "UPDATE `order` SET `statusOrder` = 5 WHERE `idOrder` = ?";
//     $stmt = mysqli_prepare($conn, $updateQuery);
//     mysqli_stmt_bind_param($stmt, 'i', $idOrder);
//     mysqli_stmt_execute($stmt);

//     // Delete the complaint from the complain table
//     $deleteQuery = "DELETE FROM complain WHERE complainId = ?";
//     $stmt = mysqli_prepare($conn, $deleteQuery);
//     mysqli_stmt_bind_param($stmt, 'i', $complainId);
//     mysqli_stmt_execute($stmt);

//     $_SESSION['message'] = 'Complaint rejected successfully.';
//     header('Location: ' . $_SERVER['PHP_SELF']);
//     exit;
// }

// $message = $_SESSION['message'] ?? '';
// unset($_SESSION['message']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>TutorTap - Admin</title>
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
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link href="../styles/styles.css" rel="stylesheet" />
</head>

<body class="montserratRegular">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    </head>

    <body>
        <div class="navbar w-100 bg-info ">
            <div class="container-fluid d-flex justify-content-between">
                <div class="input-group w-75">
                    <input type="text" class="form-control" placeholder="Search Classes" aria-label="Recipient's username" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="button">Search</button>
                    </div>
                </div>

                <div class="container w-25 row mt-1">
                    <h5>Selamat datang, Admin</h5>
                </div>
            </div>
        </div>

        <div class="container-fluid text-center mt-5 ">
            <h1>Kelas dalam Aduan</h1>
        </div>

        <div class="container-fluid">
            <div class="row">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">idOrder</th>
                            <th scope="col">ComplainId</th>
                            <th scope="col">complainMessage</th>
                            <th scope="col">Complain Picture</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($results as $result) : ?>
                            <tr>
                                <td><?php echo $result['idOrder']; ?></td>
                                <td><?php echo $result['complainId']; ?></td>
                                <td><?php echo $result['complainMessage']; ?></td>
                                <td>
                                    <!-- Display the complain picture -->
                                    <img style="width: 100px; height: 100px" src="../complain_picture/<?php echo $result['complainPicture']; ?>" alt="">
                                </td>
                                <td>
                                    <form method="post" action="">
                                        <input type="hidden" name="complainId" value="<?php echo $result['complainId']; ?>">
                                        <a class="btn btn-success" href="../throw/admin.php?complainId=<?= $result['complainId']; ?>&status=0" role="button" name="approve">Approve</a>
                                        <a class="btn btn-danger" href="../throw/admin.php?complainId=<?= $result['complainId']; ?>&status=1" role="button" name="reject">Reject</a>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>

                </table>
            </div>
        </div>





    </body>

</html>