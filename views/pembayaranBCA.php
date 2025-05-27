<?php
require_once "../functions.php";

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$idKelas = $_GET['classId'];
$idUser = $_GET['id'];
$harga = $_GET['harga'];
$idOrder = $_GET['idOrder'];
$syn = "SELECT * FROM USER WHERE userId = $idUser";
$users = query($syn);
$syn = "SELECT * FROM KELAS WHERE idKelas = $idKelas"; 
$kelas = query($syn);



if(count($users) == 0 || count($kelas) == 0){
    // echo "<script>document.location.href = 'homeLearner.php?id=$idUser'</script>";
    echo "<script>document.location.href = 'error.php?status=queryError'</script>";
}

// Fetch order details
$orderQuery = "SELECT * FROM `order` WHERE idOrder = $idOrder";
$orderData = query($orderQuery);
if(empty($orderData)) {
    echo "<script>document.location.href = 'error.php?status=orderNotFound'</script>";
    exit;
}

// Redirect to orderListLearner if order has already been processed
if($orderData[0]['statusOrder'] > 0) {
    echo "<script>document.location.href = 'orderListLearner.php?id=$idUser'</script>";
    exit;
}

$orderDuration = $orderData[0]['jumlahDurasi'];

function generateCode() {
        // Generate a 12-digit numeric virtual account number for BCA
        $code = '';
        $length = 12;

        for ($i = 0; $i < $length; $i++) {
            $code .= rand(0, 9); // Only use digits 0-9
        }

        return $code;
}

function requestVAToBank($harga){
    global $idOrder, $conn;
    
    // Check if a VA already exists for this order
    $syn = "SELECT vaOrder FROM `order` WHERE idOrder = $idOrder";
    $result = query($syn);
    
    if (!empty($result) && $result[0]['vaOrder'] > 0) {
        // VA code already exists, no need to generate a new one
        // Generate a display format from the numeric VA
        return str_pad($result[0]['vaOrder'] % 1000000000000, 12, '0', STR_PAD_LEFT);
    }
    
    // If no VA exists, generate a new one
    $vaOrder = generateCode();
    Order::setVA($idOrder, $vaOrder);
    return $vaOrder; // Return the generated VA code
}

function pembayaranSelesai(){
    // Implementation for when payment is completed
}

// Generate VA and store its numeric representation in database - only once
$vaSessionKey = "va_code_{$idOrder}";

if (!isset($_SESSION[$vaSessionKey])) {
    // Only generate a new VA code if one doesn't exist in the session
    $displayVA = requestVAToBank($harga);
    $_SESSION[$vaSessionKey] = $displayVA;
} else {
    // Use the existing VA code from the session
    $displayVA = $_SESSION[$vaSessionKey];
}

// Get the numeric VA from database for verification purposes
$numericVA = Order::getVA($idOrder);


?>

<script>
    // Function to start the countdown timer
    function startCountdown(duration, display) {
        var timer = duration, minutes, seconds;
        timer = duration;
        setInterval(function () {
            minutes = parseInt(timer / 60, 10);
            seconds = parseInt(timer % 60, 10);

            minutes = minutes < 10 ? "0" + minutes : minutes;
            seconds = seconds < 10 ? "0" + seconds : seconds;

            display.textContent = minutes + ":" + seconds;

            if (--timer < 0) {
                timer = 0;
                console.log(0);

                // Create an AJAX request to a PHP file
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "delete_order.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        // Handle response received from the server
                        console.log(this.responseText);
                    }
                };
                // Send the request with the idOrder parameter
                xhr.send("idOrder=" + encodeURIComponent(<?php echo $idOrder; ?>));
            }

            // if(timer <= 590){
            //     console.log("Pembayaran sudah dilakukan");
            //     window.location.href = "orderListLearner.php?id=<?php echo $idUser; ?>";
            // }

        }, 1000);
    }

    window.onload = function () {
        var tenMinutes = 10 * 60, // Change 10 to whatever minutes you need
            display = document.getElementById('countdown');
        startCountdown(tenMinutes, display);
    };
</script>




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
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@100;200;300;400;500;600;700;800;900&amp;display=swap"
        rel="stylesheet" />
    <!-- Bootstrap icons-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Core theme CSS (includes Bootstrap)-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <link href="../styles/styles.css" rel="stylesheet" />
    
    <!-- SweetAlert2 for nice alerts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="montserratRegular">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    </head>

    <body>
        <div class="navbar w-100 bg-ouryellow ">
        <div class="container-fluid d-flex justify-content-between">
            <div class="container w-25 d-flex justify-content-center">
                <a href="homeLearner.php?id=<?php echo $idUser; ?>">
                    <img src="../images/skilltap logo+brand.png" class="rounded-pill" style="width:200px; background-color:black" alt="">
                </a>
            </div>
            <div class="input-group w-50">
                <input type="text" class="form-control montserratRegular" placeholder="Search Classes" aria-label="Recipient's username" aria-describedby="basic-addon2">
                <div class="input-group-append">
                    <button class="btn btn-outline-dark montserratSemiBold" type="button">Search</button>
                </div>
            </div>

            <div class="container w-25 px-5 row" style="color:black;">
                <div class="container col-3">
                    <a href="">
                    <i class="bi bi-envelope-fill text-dark" style="font-size: 30px; "></i></a></th>
                    </a>
                </div>
                <div class="container col-3">
                    <a href="">
                    <i class="bi bi-filter text-dark" style="font-size: 30px;"></i></a></th>
                    </a>
                </div>
                <div class="container col-3">
                    <a href="">
                    <i class="bi bi-cart-fill text-dark" style="font-size: 30px;"></i></a></th>
                    </a>
                </div>
                <div class="container col-3">
                    <a href="">
                    <i class="bi bi-list text-dark" style="font-size: 30px;"></i></a></th>
                    </a>
                </div>
            </div>
        </div>
    </div>
        <div class="container-fluid text-center mt-5 montserratBold ">
            <h1 class="montserratBold pb-3">Pembayaran BCA</h1>
        </div>

        <div class="container-fluid text-center mt-5">
            <h1>Virtual Account</h1>
        </div>        <div class="container-fluid text-center">
            <h1 class="montserratExtraBold" style="font-size: 100px;"><?php echo $displayVA; ?> <i class="bi bi-copy"></i></h1>
        </div>

        <div class="container-fluid text-center mb-5">
            <div class="card mx-auto" style="max-width: 500px;">
            <div class="card-body">
                <h3 class="card-title montserratBold">Payment Details</h3>
                <div class="row py-2">
                <div class="col-6 text-start montserratSemiBold">Class:</div>
                <div class="col-6 text-end"><?php echo $kelas[0]['namaKelas']; ?></div>
                </div>
                <div class="row py-2">
                <div class="col-6 text-start montserratSemiBold">Price per <?php echo $kelas[0]['durasiKelas']; ?>:</div>
                <div class="col-6 text-end">Rp. <?php echo number_format($kelas[0]['hargaKelas'], 0, ',', '.'); ?></div>
                </div>                    
                <div class="row py-2">
                <div class="col-6 text-start montserratSemiBold">Duration ordered:</div>
                <div class="col-6 text-end">
                    <?php echo $orderDuration; ?> <?php echo $kelas[0]['durasiKelas']; ?>
                </div>
                </div>
                <div class="row py-2">
                <div class="col-6 text-start montserratSemiBold">Scheduled Time:</div>
                <div class="col-6 text-end"><?php echo isset($orderData[0]['jadwalKelas']) ? date('l, d F Y - H:i', strtotime($orderData[0]['jadwalKelas'])) : 'Not scheduled yet'; ?></div>
                </div>
                <hr>
                <div class="row py-2">
                <div class="col-6 text-start montserratBold">Total Payment:</div>
                <div class="col-6 text-end montserratBold">Rp. <?php echo number_format($harga, 0, ',', '.'); ?></div>
                </div>
            </div>
            </div>
        </div>
        
        <!-- countdown -->        
         <div class="container-fluid text-center">
            <h1 id="countdown" class="text-danger"></h1>
        </div>

        <!-- Payment instructions -->
        <!-- <div class="container text-center mb-3">
            <p class="text-muted">After completing your payment through BCA, click the button below to notify the tutor.</p>
        </div> -->

        <!-- Payment Done Button -->
        <div class="container-fluid text-center mt-4 mb-5">
            <button id="paymentDoneBtn" class="btn btn-success btn-lg montserratBold px-5 py-3" onclick="paymentDone()">
                <i class="bi bi-check-circle-fill me-2"></i> Payment Done
            </button>        </div>        <script>
            function paymentDone() {
                // Disable the button to prevent multiple submissions
                const paymentButton = document.getElementById('paymentDoneBtn');
                paymentButton.disabled = true;
                paymentButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...';
                
                // Create an AJAX request to update the order status
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "update_order_status.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function() {
                    if (this.readyState == 4) {
                        if (this.status == 200 && this.responseText.includes("success")) {
                            console.log("Order status updated successfully");
                            // Show SweetAlert success message before redirecting
                            Swal.fire({
                                icon: 'success',
                                title: 'Payment Confirmed!',
                                text: 'The tutor will be notified of your order.',
                                confirmButtonColor: '#28a745',
                                timer: 3000,  // Auto close after 3 seconds
                                timerProgressBar: true
                            }).then((result) => {
                                // Redirect to order list page after user clicks OK or timer expires
                                if (result.dismiss === Swal.DismissReason.timer || result.isConfirmed) {
                                    window.location.href = "orderListLearner.php?id=<?php echo $idUser; ?>";
                                }
                            });
                        } else {                            console.error("Error updating order status: " + this.responseText);
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'There was an error processing your payment. Please try again.',
                                confirmButtonColor: '#dc3545'
                            });
                            
                            // Re-enable the button if there's an error
                            paymentButton.disabled = false;
                            paymentButton.innerHTML = '<i class="bi bi-check-circle-fill me-2"></i> Payment Done';
                        }
                    }
                };
                // Send the request with the idOrder parameter
                xhr.send("idOrder=" + encodeURIComponent(<?php echo $idOrder; ?>));
            }
        </script>

              

    </body>

    <div class="container">
        <footer class="d-flex flex-wrap justify-content-between align-items-center border-top">
            <p class="col-md-4 mb-0 text-muted">&copy; 2024 Company, Inc</p>

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

</html>
