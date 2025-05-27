<?php
require_once '../functions.php';
$idUser = $_GET['id'];

$order = new Order();
$results = $order->showAllKelas($idUser);
// var_dump($results[0]);
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
        }        .panel-order {
            margin-top: 20px;
            border: 1px solid #e0e0e0;
            border-radius: 12px;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.05);
            position: relative;
            background-color: #ffffff;
            transition: transform 0.2s, box-shadow 0.2s;
            overflow: hidden;
        }
        
        .panel-order:hover {
            transform: translateY(-3px);
            box-shadow: 0px 6px 16px rgba(0, 0, 0, 0.1);
        }

        .panel-order .panel-heading {
            background-color: #FFCC01;
            color: #494949;
            border-radius: 12px 12px 0 0;
            padding: 15px 20px;
        }        .panel-order .panel-body {
            padding: 20px;
            position: relative;
        }

        .panel-order .panel-footer {
            background-color: #f8f9fa;
            border-top: 1px solid #eeeeee;
            border-radius: 0 0 12px 12px;
            padding: 15px 20px;
        }        .label {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            position: absolute;
            top: 15px;
            right: 15px;
            z-index: 1;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
          /* Status badge styling */
        .status-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            text-align: center;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 100%;
        }
        
        .label-danger, .status-badge.label-danger {
            background-color: #dc3545;
            color: white;
        }

        .label-wait, .status-badge.label-wait {
            background-color: #fd7e14;
            color: white;
        }
        
        .label-reject, .status-badge.label-reject {
            background-color: #6c757d;
            color: white;
        }

        .label-proses, .status-badge.label-proses {
            background-color: #0d6efd;
            color: white;
        }
        
        .label-pending, .status-badge.label-pending {
            background-color: #ffc107;
            color: #212529;
        }

        .label-done, .status-badge.label-done {
            background-color: #198754;
            color: white;
        }
          /* Order details styling */
        .order-details {
            position: relative;
            z-index: 0;
        }
        
        .order-id {
            font-size: 0.9rem;
            margin-bottom: 2px;
        }.order-item {
            margin-bottom: 20px;
            border-radius: 12px;
            padding: 0;
            transition: all 0.3s ease;
        }

        .order-item:last-child {
            margin-bottom: 15px;
        }
        
        /* Additional Order Styling */
        .fw-bold {
            font-weight: 600 !important;
        }
        
        .text-secondary {
            color: #6c757d !important;
        }
        
        /* Empty state styling */
        .bi-bag-x {
            opacity: 0.5;
        }
          /* Responsive adjustments */
        @media (max-width: 768px) {
            .col-md-4.text-end {
                text-align: left !important;
                margin-top: 15px;
            }
        }
        
        /* Class image styling */
        .class-image-container {
            width: 100%;
            height: 100px;
            overflow: hidden;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .class-thumbnail {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
          .class-image-container:hover .class-thumbnail {
            transform: scale(1.05);
        }
        
        @media (max-width: 768px) {
            .class-image-container {
                height: 140px;
                margin-bottom: 15px;
            }
        }
          /* Order note styling */
        .order-note {
            font-style: italic;
            font-size: 0.9rem;
            background-color: #f8f9fa;
            padding: 8px 12px;
            border-radius: 8px;
            border-left: 3px solid #FFCC01;
            margin-top: 8px;
        }
        
        /* Schedule display styling */
        .schedule-display {
            background-color: #fff8e1;
            border-left: 5px solid #ffc107;
            border-radius: 6px;
            padding: 8px 12px;
            margin-top: 10px;
            font-weight: bold;
            transition: all 0.3s;
        }
        
        .schedule-display:hover {
            background-color: #fff3cd;
            box-shadow: 0 3px 6px rgba(0,0,0,0.1);
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
        <div class="text-center mb-4 mt-4">
            <h3 class="fw-bold">Your Order History</h3>
            <p class="text-muted">Track and manage your tutoring orders</p>
        </div>
        
        <?php foreach ($results as $result) : ?>
            <?php
            $idKelas = $result['idClass'];
            $kelas = new Kelas();
            $hasilKelas = $kelas->getKelasById($idKelas);
            // var_dump($hasilKelas[0]);
            ?>
            <div class="panel panel-default panel-order">                <div class="panel-body">
                    <!-- Order Content -->
                    <div class="row mt-3">
                        <!-- Class Image -->
                        <div class="col-md-3 mb-3 mb-md-0">
                            <div class="class-image-container">
                                <img src="../images/<?php echo $hasilKelas[0]['fotoKelas']; ?>" 
                                     alt="<?php echo $hasilKelas[0]['namaKelas']; ?>" 
                                     class="img-fluid rounded class-thumbnail">
                            </div>
                        </div>
                        
                        <!-- Class Info -->
                        <div class="col-md-5">
                            <h5 class="fw-bold"><?php echo $hasilKelas[0]['namaKelas']; ?></h5>                            <div class="d-flex align-items-center mt-2">
                                <i class="bi bi-clock-fill me-2 text-secondary"></i>
                                <span><?php echo $result["jumlahDurasi"]; ?> hour</span>
                            </div>
                            <div class="d-flex align-items-center mt-2">
                                <i class="bi bi-cash-stack me-2 text-secondary"></i>
                                <span class="fw-bold">Rp <?php echo number_format($result["subtotalOrder"], 0, ',', '.'); ?></span>
                            </div>                            <!-- Schedule/jadwalKelas display -->
                            <?php if(isset($result['jadwalKelas']) && !empty($result['jadwalKelas'])): ?>
                            <div class="schedule-display d-flex align-items-center">
                                <i class="bi bi-calendar-event-fill me-2 text-primary"></i>
                                <div>
                                    <small class="text-muted d-block">SCHEDULED TIME:</small>
                                    <span class="fs-6 fw-bold">
                                        <?php echo date('D, d M Y - H:i', strtotime($result['jadwalKelas'])); ?>
                                    </span>
                                </div>
                            </div>
                            <?php endif; ?>
                            <div class="d-flex align-items-center mt-2">
                                <i class="bi bi-geo-alt-fill me-2 text-secondary"></i>
                                <span><?php echo $hasilKelas[0]['lokasiKelas']; ?></span>
                            </div><?php if(!empty($result['catatanOrder'])): ?>
                            <div class="order-note mt-2">
                                <i class="bi bi-quote me-2"></i>
                                <?php echo $result['catatanOrder']; ?>
                            </div>
                            <?php endif; ?>
                        </div><!-- Order ID and Date (if available) -->
                        <div class="col-md-4 text-end">
                            <div class="order-details">
                                <div class="text-muted order-id">Order ID: #<?php echo $result['idOrder']; ?></div>
                                
                                <!-- Status Label -->
                                <?php                                $statusLabels = [
                                    0 => ['text' => 'Belum Bayar', 'class' => 'label-danger'],
                                    1 => ['text' => 'Menunggu Konfirmasi Tutor', 'class' => 'label-pending'],
                                    2 => ['text' => 'Tutor Menolak Pesanan', 'class' => 'label-reject'],
                                    3 => ['text' => 'Pesanan Disetujui/Menunggu Waktu Tutoring', 'class' => 'label-wait'],
                                    4 => ['text' => 'Dalam Proses Tutoring', 'class' => 'label-proses'],
                                    5 => ['text' => 'Pesanan Selesai', 'class' => 'label-done'],
                                    6 => ['text' => 'Pesanan Dikomplain', 'class' => 'label-danger'],
                                    7 => ['text' => 'Komplain Terselesaikan/Resolved', 'class' => 'label-done']
                                ];

                                $status = $result["statusOrder"];
                                if (isset($statusLabels[$status])):
                                ?>
                                    <div class="status-badge mt-2 <?php echo $statusLabels[$status]['class']; ?>">
                                        <?php echo $statusLabels[$status]['text']; ?>
                                    </div>
                                <?php endif; ?>                                
                                <?php if(isset($result['tanggalOrder'])): ?>
                                    <div class="text-muted mt-2">
                                        <i class="bi bi-calendar3 me-1"></i>
                                        <small>Ordered: <?php echo date('d M Y H:i', strtotime($result['tanggalOrder'])); ?></small>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if(isset($result['createdAt'])): ?>
                                    <div class="text-muted mt-2">
                                        <small><?php echo date('d M Y', strtotime($result['createdAt'])); ?></small>
                                    </div>
                                <?php endif; ?>
                                
                                <!-- Complain Button -->
                                <!-- <?php if($result["statusOrder"] >= 3 && $result["statusOrder"] < 6): ?>
                                    <a href="complainForm.php?orderId=<?= $result['idOrder'];?>" 
                                       class="btn btn-danger btn-sm mt-3">
                                        <i class="bi bi-exclamation-triangle-fill me-1"></i> Complain
                                    </a>
                                <?php endif; ?> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        
        <?php if(empty($results)): ?>
            <div class="text-center py-5">
                <i class="bi bi-bag-x" style="font-size: 48px; color: #ccc;"></i>
                <h4 class="mt-3">No orders found</h4>
                <p class="text-muted">You haven't placed any orders yet</p>
            </div>
        <?php endif; ?>
    </div>
    <div style="margin-bottom: 30px;"></div>
</body>

</html>