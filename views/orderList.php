<?php
    $idUser = $_GET['id'];

    
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Order List</title>

        <!-- Bootstrap CSS -->
        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"
            rel="stylesheet">

        <!-- Bootstrap Icons -->
        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css"
            rel="stylesheet">

        <!-- Google Fonts -->
        <link
            href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
            rel="stylesheet">

        <style>
        /* Custom Styles */
        body {
            background: #f8f9fa; /* Ubah warna latar belakang */
        }

        .navbar {
            background-color: #FFCC01; /* Ubah warna navbar */
            box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1); /* Tambahkan bayangan */
        }

        .navbar h1 {
            margin: 0;
            color: #fff; /* Ubah warna teks navbar */
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
            margin-top: 20px; /* Tambahkan jarak atas */
            border: 1px solid #383b3d; /* Tambahkan garis tepi */
            border-radius: 10px; /* Tambahkan sudut bulat */
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1); /* Tambahkan bayangan */
            position: relative; /* Menambahkan posisi relatif untuk panel */
        }

        .panel-order .panel-heading {
            background-color: #FFCC01; /* Ubah warna latar belakang */
            color: #494949; /* Ubah warna teks */
            border-radius: 10px 10px 0 0; /* Sudut bulat hanya di bagian atas */
            padding: 10px 20px; /* Tambahkan ruang di dalam */
        }

        .panel-order .panel-body {
            padding: 20px; /* Tambahkan ruang di dalam */
        }

        .panel-order .panel-footer {
            background-color: #3d3d3d; /* Ubah warna latar belakang */
            border-top: 1px solid #ced4da; /* Tambahkan garis tepi */
            border-radius: 0 0 10px 10px; /* Sudut bulat hanya di bagian bawah */
            padding: 10px 20px; /* Tambahkan ruang di dalam */
        }

        .label {
            padding: 5px 10px; /* Tambahkan padding */
            border-radius: 5px; /* Tambahkan sudut bulat */
            font-size: 12px; /* Ukuran font */
            font-weight: bold; /* Tebalkan teks */
        }

        .label-danger {
            background-color: #5c5c5c; /* Warna background merah untuk rejected */
            color: white; /* Warna teks putih */
            position: absolute;
            top: 10px; /* Jarak dari atas */
            right: 10px; /* Jarak dari kanan */
        }

        .label-done {
            background-color: #0dc700; /* Warna background merah untuk rejected */
            color: white; /* Warna teks putih */
            position: absolute;
            top: 10px; /* Jarak dari atas */
            right: 10px; /* Jarak dari kanan */
        }

        .order-item {
            margin-bottom: 20px; /* Tambahkan jarak antar item */
            border: 1px solid #353535; /* Tambahkan garis tepi */
            border-radius: 10px; /* Tambahkan sudut bulat */
            padding: 15px; /* Tambahkan ruang di dalam */
            background-color: #3d3d3d; /* Ubah warna latar belakang */
        }

        .order-item:last-child {
            margin-bottom: 5;
        }

        .btn-complain {
            display: block; 
            width: 100%; /* Adjusted width */
            margin-top: 10px; 
            text-align: center; 
            text-decoration: none; 
            background-color: #dc3545; 
            color: #fff; 
            border: none; 
            padding: 8px 0; /* Adjusted padding */
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
                <a href="#" class="back-button"><i class="bi bi-chevron-left"
                        style="font-weight:bolder;"></i></a>
                <div class="d-flex">
                    <div class="container col-3">
                        <i class="bi bi-share-fill text-white mx-2"
                            style="font-size: 30px;"></i>
                    </div>
                    <div class="container col-3">
                        <i class="bi bi-list text-white"
                            style="font-size: 30px;"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="container bootdey">
            <div class="panel panel-default panel-order">
                <div class="panel-body">
                    <div class="label label-danger">Ongoing</div>
                    <div class="row">
                        <div class="col-md-11">
                            <div class="row">
                                <div class="col-md-12">
                                    <span><strong>Tutoring Kalkulus
                                            I</strong></span>
                                    <br />1 hour
                                    <br />Rp51.500
                                    <br />
                                    <i class="bi bi-geo-alt-fill"></i>
                                    Siwalankerto, Surabaya
                                    <br />
                                </div>
                            </div>
                        </div>
                        <a href="#" class="btn-complain">Complain</a>
                    </div>
                </div>
            </div>

            <div class="panel panel-default panel-order">
                <div class="panel-body">
                    <div class="label label-done">Done</div>
                    <div class="row">
                        <div class="col-md-11">
                            <div class="row">
                                <div class="col-md-12">
                                    <span><strong>Knitting Class</strong></span>
                                    <br />2 hour
                                    <br />Rp82.000
                                    <br />
                                    <i class="bi bi-geo-alt-fill"></i>
                                    Siwalankerto, Surabaya
                                    <br />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel panel-default panel-order">
                <div class="panel-body">
                    <div class="label label-done">Done</div>
                    <div class="row">
                        <div class="col-md-11">
                            <div class="row">
                                <div class="col-md-12">
                                    <span><strong>Public Speaking
                                            Basics</strong></span>
                                    <br />1 hour
                                    <br />Rp53.000
                                    <br />
                                    <i class="bi bi-geo-alt-fill"></i>
                                    Siwalankerto, Surabaya
                                    <br />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel panel-default panel-order">
                <div class="panel-body">
                    <div class="label label-done">Done</div>
                    <div class="row">
                        <div class="col-md-11">
                            <div class="row">
                                <div class="col-md-12">
                                    <span><strong>Python Data
                                            Science</strong></span>
                                    <br />3 hour
                                    <br />Rp120.000
                                    <br />
                                    <i class="bi bi-geo-alt-fill"></i>
                                    Siwalankerto, Surabaya
                                    <br />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div style="margin-bottom: 30px;"></div>
    </body>
</html>
