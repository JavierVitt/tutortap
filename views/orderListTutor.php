<?php
require_once '../functions.php';
$idUser = $_GET['id'];

$order = new Order();
// Use the tutor-specific function instead
$results = $order->showAllKelasByTutor($idUser);
// Remove debug var_dump for production
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
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
    <?php
    // Check for success message in URL parameters
    if (isset($_GET['success']) && !empty($_GET['success'])) {
        $successMessage = htmlspecialchars($_GET['success']);
        echo "
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: '{$successMessage}',
                    confirmButtonColor: '#28a745',
                    timer: 3000,
                    timerProgressBar: true
                });
            });
        </script>
        ";
    }
    // Check for error message in URL parameters
    if (isset($_GET['error']) && !empty($_GET['error'])) {
        $errorMessage = htmlspecialchars($_GET['error']);
        echo "
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: '{$errorMessage}',
                    confirmButtonColor: '#dc3545'
                });
            });
        </script>
        ";
    }
    ?>    <!-- Navbar -->
    <div class="navbar w-100">
        <div class="container-fluid d-flex justify-content-between">
            <a href="homeTutor.php?id=<?=$idUser?>" class="back-button"><i class="bi bi-chevron-left" style="font-weight:bolder;"></i></a>
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
            <h3 class="fw-bold">Order Requests for Your Classes</h3>
            <p class="text-muted">Manage tutoring requests from students here</p>
        </div>
        
        <?php foreach ($results as $result) : ?>
            <?php
            $idKelas = $result['idClass'];
            $kelas = new Kelas();
            $hasilKelas = $kelas->getKelasById($idKelas);
            
            // Get student info
            $idStudent = $result['idUser'];
            $student = User::getUserById($idStudent);
            // var_dump($hasilKelas[0]);
            ?>            <div class="panel panel-default panel-order">
                <div class="panel-body">
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
                        <div class="col-md-3">
                            <h5 class="fw-bold"><?php echo $hasilKelas[0]['namaKelas']; ?></h5>
                            
                            <!-- Student Info - New section for tutor view -->
                            <div class="d-flex align-items-center mt-2">
                                <i class="bi bi-person-fill me-2 text-secondary"></i>
                                <span class="fw-bold">Student: <?php echo $student['nama']; ?></span>
                            </div>
                              <div class="d-flex align-items-center mt-2">
                                <i class="bi bi-clock-fill me-2 text-secondary"></i>
                                <span><?php echo $result["jumlahDurasi"]; ?> hour</span>
                            </div>
                            <div class="d-flex align-items-center mt-2">
                                <i class="bi bi-cash-stack me-2 text-secondary"></i>
                                <span class="fw-bold">Rp <?php echo number_format($result["subtotalOrder"], 0, ',', '.'); ?></span>
                            </div>                            <!-- Schedule/jadwalKelas display with standout styling -->
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

                        
                        </div>                        <div class="col-md-3 d-flex flex-column justify-content-center align-items-center h-100">
                            <!-- Tutor Action Buttons based on order status -->
                            <div class="d-flex flex-column justify-content-center align-items-center w-100 p-2">                                <?php if($result["statusOrder"] == 1): ?>
                                    <!-- Accept/Reject buttons for pending orders -->
                                    <div class="d-flex gap-2 mb-3 w-100">
                                        <a href="javascript:void(0);" 
                                           onclick="confirmAcceptOrder(<?= $result['idOrder']; ?>, <?= $idUser; ?>)"
                                           class="btn btn-success w-50 py-2">
                                            <i class="bi bi-check-circle-fill me-1"></i> Accept
                                        </a>
                                        <a href="javascript:void(0);" 
                                           onclick="confirmRejectOrder(<?= $result['idOrder']; ?>, <?= $idUser; ?>)"
                                           class="btn btn-danger w-50 py-2">
                                            <i class="bi bi-x-circle-fill me-1"></i> Reject
                                        </a>
                                    </div>
                                <?php elseif($result["statusOrder"] == 3): ?>
                                    <!-- Start tutoring button -->
                                    <a href="javascript:void(0);" 
                                       onclick="confirmStartTutoring(<?= $result['idOrder']; ?>, <?= $idUser; ?>)"
                                       class="btn btn-primary w-100 py-2 mb-3">
                                        <i class="bi bi-play-circle-fill me-1"></i> Start Tutoring
                                    </a>
                                <?php elseif($result["statusOrder"] == 4): ?>
                                    <!-- Complete tutoring button -->
                                    <a href="javascript:void(0);" 
                                       onclick="confirmCompleteTutoring(<?= $result['idOrder']; ?>, <?= $idUser; ?>)"
                                       class="btn btn-primary w-100 py-2 mb-3">
                                        <i class="bi bi-check-circle-fill me-1"></i> Complete Tutoring
                                    </a>
                                <?php elseif($result["statusOrder"] == 6): ?>
                                    <!-- Complaint info -->
                                    <div class="alert alert-danger p-3 w-100 mb-3 text-center">
                                        <i class="bi bi-exclamation-triangle-fill me-1"></i>
                                        This order has been complained
                                    </div>
                                <?php endif; ?>
                                
                                <!-- Chat with student button available for all orders except unpaid ones -->
                                <?php if($result["statusOrder"] > 0): ?>
                                    <button type="button" 
                                       class="btn btn-outline-secondary w-100 py-2 disabled" 
                                       disabled aria-disabled="true">
                                        <i class="bi bi-chat-dots-fill me-1"></i> Chat with Student
                                        <small class="d-block mt-1 text-muted">(Not available)</small>
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                        <!-- Order ID and Date (if available) -->
                        <div class="col-md-3 text-end">
                            <div class="order-details">                                <div class="text-muted order-id">Order ID: #<?php echo $result['idOrder']; ?></div>
                                
                                <?php if(isset($result['tanggalOrder'])): ?>
                                    <div class="text-muted mt-2">
                                        <i class="bi bi-calendar3 me-1"></i>
                                        <small>Ordered: <?php echo date('d M Y H:i', strtotime($result['tanggalOrder'])); ?></small>
                                    </div>
                                <?php endif; ?>
                                
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
                                
                                <?php if(isset($result['createdAt'])): ?>
                                    <div class="text-muted mt-2">
                                        <small><?php echo date('d M Y', strtotime($result['createdAt'])); ?></small>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
          <?php if(empty($results)): ?>
            <div class="text-center py-5">
                <i class="bi bi-bag-x" style="font-size: 48px; color: #ccc;"></i>
                <h4 class="mt-3">No order requests found</h4>
                <p class="text-muted">You haven't received any order requests for your classes yet</p>
            </div>
        <?php endif; ?>
    </div>    <div style="margin-bottom: 30px;"></div>

    <!-- JavaScript for SweetAlert confirmations -->
    <script>
        // Accept Order Confirmation
        function confirmAcceptOrder(orderId, tutorId) {
            Swal.fire({
                title: 'Accept Order?',
                text: 'Are you sure you want to accept this order?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, accept it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading state
                    Swal.fire({
                        title: 'Processing...',
                        html: 'Please wait while we process your request.',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    // Redirect to accept order page
                    window.location.href = `acceptOrder.php?orderId=${orderId}&tutorId=${tutorId}`;
                }
            });
        }
        
        // Reject Order Confirmation
        function confirmRejectOrder(orderId, tutorId) {
            Swal.fire({
                title: 'Reject Order?',
                text: 'Are you sure you want to reject this order?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, reject it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading state
                    Swal.fire({
                        title: 'Processing...',
                        html: 'Please wait while we process your request.',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    // Redirect to reject order page
                    window.location.href = `rejectOrder.php?orderId=${orderId}&tutorId=${tutorId}`;
                }
            });
        }
        
        // Start Tutoring Confirmation
        function confirmStartTutoring(orderId, tutorId) {
            Swal.fire({
                title: 'Start Tutoring?',
                text: 'Are you ready to start the tutoring session?',
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#0d6efd',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, start now!',
                cancelButtonText: 'Not yet'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading state
                    Swal.fire({
                        title: 'Processing...',
                        html: 'Please wait while we process your request.',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    // Redirect to start tutoring page
                    window.location.href = `startTutoring.php?orderId=${orderId}&tutorId=${tutorId}`;
                }
            });
        }
        
        // Complete Tutoring Confirmation
        function confirmCompleteTutoring(orderId, tutorId) {
            Swal.fire({
                title: 'Complete Tutoring?',
                text: 'Are you sure you want to mark this tutoring session as complete?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#198754',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, it\'s complete!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading state
                    Swal.fire({
                        title: 'Processing...',
                        html: 'Please wait while we process your request.',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    // Redirect to complete tutoring page
                    window.location.href = `completeTutoring.php?orderId=${orderId}&tutorId=${tutorId}`;
                }
            });
        }
    </script>
</body>

</html>