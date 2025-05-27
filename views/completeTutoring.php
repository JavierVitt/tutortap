<?php
require_once "../functions.php";

// Validate and get parameters
if (!isset($_GET['orderId']) || !isset($_GET['tutorId'])) {
    echo "<script>document.location.href = 'error.php?status=missingParameters'</script>";
    exit;
}

$orderId = $_GET['orderId'];
$tutorId = $_GET['tutorId'];

// Connect to database
global $conn;

// Update order status to 5 (Completed)
$updateQuery = "UPDATE `order` SET `statusOrder` = 5 WHERE `idOrder` = ?";
$stmt = mysqli_prepare($conn, $updateQuery);
mysqli_stmt_bind_param($stmt, 'i', $orderId);
$success = mysqli_stmt_execute($stmt);

if ($success) {
    // Redirect with success message
    header("Location: orderListTutor.php?id=" . $tutorId . "&success=" . urlencode("Tutoring session has been completed successfully!"));
    exit;
} else {
    // Redirect with error message
    header("Location: orderListTutor.php?id=" . $tutorId . "&error=" . urlencode("Failed to complete tutoring session. Please try again."));
    exit;
}

if ($success) {
    // Get order details to calculate tutor earnings
    $orderQuery = "SELECT o.*, k.userId AS tutorId, k.hargaKelas 
                   FROM `order` o 
                   JOIN kelas k ON o.idClass = k.idKelas 
                   WHERE o.idOrder = ?";
    $stmt = mysqli_prepare($conn, $orderQuery);
    mysqli_stmt_bind_param($stmt, 'i', $orderId);
    mysqli_stmt_execute($stmt);
    $orderResult = mysqli_stmt_get_result($stmt);
    
    if ($orderData = mysqli_fetch_assoc($orderResult)) {
        // Calculate tutor earnings (you can define your own commission rate)
        $totalAmount = $orderData['subtotalOrder'];
        $commissionRate = 0.20; // 20% platform fee
        $tutorEarnings = $totalAmount * (1 - $commissionRate);
        
        // Update tutor's balance
        $updateTutorQuery = "UPDATE `user` SET `saldo` = `saldo` + ? WHERE `userId` = ?";
        $stmt = mysqli_prepare($conn, $updateTutorQuery);
        mysqli_stmt_bind_param($stmt, 'di', $tutorEarnings, $tutorId);
        mysqli_stmt_execute($stmt);
        
        // Redirect back to order list with success message
        echo "<script>
            alert('Tutoring has been completed! Rp " . number_format($tutorEarnings, 0, ',', '.') . " has been added to your balance.');
            document.location.href = 'orderListTutor.php?id=$tutorId';
        </script>";
    } else {
        // Redirect back to order list with simple success message if order details can't be fetched
        echo "<script>
            alert('Tutoring has been completed!');
            document.location.href = 'orderListTutor.php?id=$tutorId';
        </script>";
    }
} else {
    // Redirect to error page if update fails
    echo "<script>document.location.href = 'error.php?status=updateFailed'</script>";
}
?>
