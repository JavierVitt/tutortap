<?php
    $status = $_GET['status'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>TutorTap - Error</title>
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

<body class="text-center d-flex align-items-center montserratSemiBold" style="height:700px; font-size: 30px; background-color: rgb(200,200,200)">
    <div class="container w-50">
    <?php 
        if($status == 'queryError'): 
            echo "<h1 style=\"font-size: 30px;\">Kelas tidak ditemukan. Silahkan klik back.</h1>";
        elseif($status == 'suspiciousUser'):
            echo "<h1 style=\"font-size: 30px;\">Tidak dapat memesan kelas karena terdeteksi aktivitas ilegal pada akun anda. Silahkan klik back.</h1>";
        endif;
        ?>
    </div>
</body>
</html>