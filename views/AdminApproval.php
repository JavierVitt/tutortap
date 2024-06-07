<?php
require_once "../functions.php";

// Fetch all complained orders where the adminId is 2020
$syntax = "SELECT `order`.`idOrder`, `complain`.`complainId`, `complain`.`complainMessage` 
           FROM `order` 
           JOIN `complain` ON `order`.`idOrder` = `complain`.`idOrder` 
           WHERE `complain`.`adminId` = 2020";
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

                <div class="container w-25 row">
                    Selamat Datang, Budi WASD
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
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($datas as $data) : ?>
                            <tr>
                                <td><?php echo $data['idOrder']; ?></td>
                                <td><?php echo $data['complainId']; ?></td>
                                <td><?php echo $data['complainMessage']; ?></td>
                                <td>
                                    <button class="btn btn-success">Approve</button>
                                    <button class="btn btn-danger">Reject</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>




    </body>

</html>