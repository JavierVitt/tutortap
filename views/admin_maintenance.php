<?php
require_once "../functions.php";

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in and is admin (you may need to adjust this check)
if(!isset($_GET['id'])) {
    echo "<script>document.location.href = 'login.php'</script>";
    exit;
}

$idUser = $_GET['id'];

$syn = "SELECT * FROM USER WHERE userId = $idUser";
$users = query($syn);

if(count($users) == 0) {
    echo "<script>document.location.href = 'error.php?status=queryError'</script>";
    exit;
}

// Simple admin check - you may need to adjust this based on your user roles
$isAdmin = ($users[0]['role'] == 'admin' || $users[0]['role'] == 'super_admin');

if(!$isAdmin) {
    echo "<script>document.location.href = 'error.php?status=unauthorizedAccess'</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>TutorTap - Admin Maintenance</title>
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
    <!-- SweetAlert2 for nice alerts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="montserratRegular">
    <div class="navbar w-100 bg-ouryellow">
        <div class="container-fluid d-flex justify-content-between">
            <div class="container w-25 d-flex justify-content-center">
                <a href="homeLearner.php?id=<?php echo $idUser; ?>">
                    <img src="../images/skilltap logo+brand.png" class="rounded-pill" style="width:200px; background-color:black" alt="">
                </a>
            </div>
            <div class="container w-25 px-5 row" style="color:black;">
                <div class="container col-3">
                    <a href="logout.php" title="Logout">
                        <i class="bi bi-box-arrow-right text-dark" style="font-size: 30px;"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid text-center mt-5 montserratBold">
        <h1 class="montserratBold pb-3">Admin Maintenance</h1>
    </div>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="card mb-4">
                    <div class="card-header bg-ouryellow">
                        <h2 class="montserratBold text-center mb-0">System Maintenance</h2>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-3">
                            <a href="cleanup_abandoned_orders.php?manual=1&id=<?php echo $idUser; ?>" class="btn btn-primary btn-lg montserratSemiBold">
                                <i class="bi bi-trash3"></i> Clean Up Abandoned Orders
                            </a>
                            <button type="button" id="cleanupSessionBtn" class="btn btn-warning btn-lg montserratSemiBold">
                                <i class="bi bi-arrow-clockwise"></i> Clean Session Data
                            </button>
                        </div>
                        
                        <hr class="my-4">
                        
                        <h5 class="montserratSemiBold mb-3">Pending Orders (Status 0)</h5>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>User</th>
                                        <th>Class</th>
                                        <th>Date</th>
                                        <th>Schedule</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Get all pending orders
                                    $query = "SELECT o.*, u.nama as userName, k.namaKelas 
                                              FROM `order` o 
                                              JOIN user u ON o.idUser = u.userId 
                                              JOIN kelas k ON o.idClass = k.idKelas 
                                              WHERE o.statusOrder = 0 
                                              ORDER BY o.tanggalOrder DESC";
                                    $pendingOrders = query($query);
                                    
                                    if (empty($pendingOrders)) {
                                        echo '<tr><td colspan="6" class="text-center">No pending orders found</td></tr>';
                                    } else {
                                        foreach ($pendingOrders as $order) {
                                            echo '<tr>';
                                            echo '<td>' . $order['idOrder'] . '</td>';
                                            echo '<td>' . $order['userName'] . '</td>';
                                            echo '<td>' . $order['namaKelas'] . '</td>';
                                            echo '<td>' . date('Y-m-d H:i', strtotime($order['tanggalOrder'])) . '</td>';
                                            echo '<td>' . (isset($order['jadwalKelas']) ? date('Y-m-d H:i', strtotime($order['jadwalKelas'])) : 'Not scheduled') . '</td>';
                                            echo '<td>';
                                            echo '<button type="button" class="btn btn-sm btn-danger" onclick="deleteOrder(' . $order['idOrder'] . ')">Delete</button>';
                                            echo '</td>';
                                            echo '</tr>';
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Clean session data button
        document.getElementById('cleanupSessionBtn').addEventListener('click', function() {
            fetch('cleanup_session.php')
                .then(response => response.text())
                .then(data => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Session Cleaned',
                        text: 'Session data has been reset successfully.'
                    }).then(() => {
                        window.location.reload();
                    });
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to clean session: ' + error
                    });
                });
        });

        // Delete order function
        function deleteOrder(orderId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You are about to delete order #" + orderId,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Send delete request
                    const formData = new FormData();
                    formData.append('idOrder', orderId);
                    
                    fetch('delete_order.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.text())
                    .then(data => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: 'Order has been deleted.'
                        }).then(() => {
                            window.location.reload();
                        });
                    })
                    .catch(error => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to delete order: ' + error
                        });
                    });
                }
            });
        }
    </script>

    <div class="container">
        <footer class="d-flex flex-wrap justify-content-between align-items-center border-top mt-5">
            <p class="col-md-4 mb-0 text-muted">&copy; 2024 TutorTap, Inc</p>

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
</body>
</html>
