<?php
require_once '../functions.php';
$idUser = $_GET['id'];

$order = new Order();
$results = $order->showAllKelas($idUser);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order List</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

    <style>
        /* Custom Styles */
        body {
            background: #f8f9fa;
            /* Ubah warna latar belakang */
        }

        .navbar {
            background-color: #FFCC01;
            /* Ubah warna navbar */
            box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
            /* Tambahkan bayangan */
        }

        .navbar h1 {
            margin: 0;
            color: #fff;
            /* Ubah warna teks navbar */
        }

        .back-button {
            background-color: transparent;
            border: none;
            color: #fff;
            font-size: 20px;
            cursor: pointer;
        }

        .back-button:hover {
            text-decoration: underline;
        }

        .panel-order {
            margin-top: 20px;
            /* Tambahkan jarak atas */
            border: 1px solid #383b3d;
            /* Tambahkan garis tepi */
            border-radius: 10px;
            /* Tambahkan sudut bulat */
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            /* Tambahkan bayangan */
            position: relative;
            /* Menambahkan posisi relatif untuk panel */
        }

        .panel-order .panel-heading {
            background-color: #FFCC01;
            /* Ubah warna latar belakang */
            color: #494949;
            /* Ubah warna teks */
            border-radius: 10px 10px 0 0;
            /* Sudut bulat hanya di bagian atas */
            padding: 10px 20px;
            /* Tambahkan ruang di dalam */
        }

        .panel-order .panel-body {
            padding: 20px;
            /* Tambahkan ruang di dalam */
        }

        .panel-order .panel-footer {
            background-color: #3d3d3d;
            /* Ubah warna latar belakang */
            border-top: 1px solid #ced4da;
            /* Tambahkan garis tepi */
            border-radius: 0 0 10px 10px;
            /* Sudut bulat hanya di bagian bawah */
            padding: 10px 20px;
            /* Tambahkan ruang di dalam */
        }

        .label {
            padding: 5px 10px;
            /* Tambahkan padding */
            border-radius: 5px;
            /* Tambahkan sudut bulat */
            font-size: 12px;
            /* Ukuran font */
            font-weight: bold;
            /* Tebalkan teks */
        }

        .label-danger {
            background-color: #5c5c5c;
            /* Warna background merah untuk rejected */
            color: white;
            /* Warna teks putih */
            position: absolute;
            top: 10px;
            /* Jarak dari atas */
            right: 10px;
            /* Jarak dari kanan */
        }

        .label-wait {
            background-color: #eb7e23;
            /* Warna background merah untuk rejected */
            color: white;
            /* Warna teks putih */
            position: absolute;
            top: 10px;
            /* Jarak dari atas */
            right: 10px;
            /* Jarak dari kanan */
        }

        .label-proses {
            background-color: #005b8f;
            /* Warna background merah untuk rejected */
            color: white;
            /* Warna teks putih */
            position: absolute;
            top: 10px;
            /* Jarak dari atas */
            right: 10px;
            /* Jarak dari kanan */
        }

        .label-done {
            background-color: #0dc700;
            /* Warna background merah untuk rejected */
            color: white;
            /* Warna teks putih */
            position: absolute;
            top: 10px;
            /* Jarak dari atas */
            right: 10px;
            /* Jarak dari kanan */
        }

        .order-item {
            margin-bottom: 20px;
            /* Tambahkan jarak antar item */
            border: 1px solid #353535;
            /* Tambahkan garis tepi */
            border-radius: 10px;
            /* Tambahkan sudut bulat */
            padding: 15px;
            /* Tambahkan ruang di dalam */
            background-color: #3d3d3d;
            /* Ubah warna latar belakang */
        }

        .order-item:last-child {
            margin-bottom: 5;
        }

        .btn-complain {
            display: block;
            width: 100%;
            /* Adjusted width */
            margin-top: 10px;
            text-align: center;
            text-decoration: none;
            background-color: #dc3545;
            color: #fff;
            border: none;
            padding: 8px 0;
            /* Adjusted padding */
            border-radius: 5px;
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
        }

        .btn-complain:hover {
            background-color: #c82333;
            box-shadow: 0 4px 8px rgba(220, 53, 69, 0.4);
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <div class="navbar w-100">
        <div class="container-fluid d-flex justify-content-between">
            <a href="homeLearner.php?id=<?=$idUser?>" class="back-button"><i class="bi bi-chevron-left" style="font-weight:bolder;"></i></a>
            <div class="d-flex">
                <div class="container col-3">
                    <i class="bi bi-share-fill text-white mx-2" style="font-size: 30px;"></i>
                </div>
                <div class="container col-3">
                    <i class="bi bi-list text-white" style="font-size: 30px;"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container bootdey">
        <?php foreach ($results as $result) : ?>
            <?php
            $idKelas = $result['idClass'];
            $kelas = new Kelas();
            $hasil = $kelas->getKelasById($idKelas);
            ?>
            <div class="panel panel-default panel-order">
                <div class="panel-body">
                    <?php if ($hasil[0]['statusKelas'] == 0) : ?>
                        <div class="label label-danger">Belum Bayar</div>
                    <?php endif; ?>
                    <?php if ($hasil[0]['statusKelas'] == 1) : ?>
                        <div class="label label-done">Sudah Dibayar</div>
                    <?php endif; ?>
                    <?php if ($hasil[0]['statusKelas'] == 2) : ?>
                        <div class="label label-wait">Menunggu Konfirmasi Tutor</div>
                    <?php endif; ?>
                    <?php if ($hasil[0]['statusKelas'] == 3) : ?>
                        <div class="label label-wait">Pesanan Disetujui/Menunggu Waktu Tutoring</div>
                    <?php endif; ?>
                    <?php if ($hasil[0]['statusKelas'] == 4) : ?>
                        <div class="label label-proses">Dalam Proses Tutoring</div>
                    <?php endif; ?>
                    <?php if ($hasil[0]['statusKelas'] == 5) : ?>
                        <div class="label label-done">Pesanan Selesai</div>
                    <?php endif; ?>
                    <?php if ($hasil[0]['statusKelas'] == 6) : ?>
                        <div class="label label-danger">Pesanan Dikomplain</div>
                    <?php endif; ?>
                    <?php if ($hasil[0]['statusKelas'] == 7) : ?>
                        <div class="label label-done">Komplain Terselesaikan/Resolved</div>
                    <?php endif; ?>
                    <div class="row">
                        <div class="col-md-11">
                            <div class="row">
                                <div class="col-md-12">
                                    <span><strong><?php echo $hasil[0]['namaKelas']; ?></strong></span>
                                    <br /><?php echo $hasil[0]['durasiKelas']; ?> hour
                                    <br /><?php echo $hasil[0]['hargaKelas']; ?>
                                    <br />
                                    <i class="bi bi-geo-alt-fill"></i>
                                    <?php echo $hasil[0]['deskripsiKelas']; ?>
                                    <br />
                                </div>
                            </div>
                        </div>
                        <?php if($hasil[0]['statusKelas'] == 2 || $hasil[0]['statusKelas'] == 3): ?>
                            <a href="#" class="btn-complain">Complain</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <div style="margin-bottom: 30px;"></div>
</body>

</html>