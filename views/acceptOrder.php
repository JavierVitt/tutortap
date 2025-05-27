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

// Update order status to 3 (Approved/Awaiting tutoring time)
$updateQuery = "UPDATE `order` SET `statusOrder` = 3 WHERE `idOrder` = ?";
$stmt = mysqli_prepare($conn, $updateQuery);
mysqli_stmt_bind_param($stmt, 'i', $orderId);
$success = mysqli_stmt_execute($stmt);

if ($success) {
    // Redirect with success message
    header("Location: orderListTutor.php?id=" . $tutorId . "&success=" . urlencode("Order has been accepted successfully!"));
    exit;
} else {
    // Redirect with error message
    header("Location: orderListTutor.php?id=" . $tutorId . "&error=" . urlencode("Failed to accept order. Please try again."));
    exit;
}

if ($success) {
    // Redirect back to order list with success message
    echo "<script>
        alert('Order has been accepted successfully!');
        document.location.href = 'orderListTutor.php?id=$tutorId';
    </script>";
} else {
    // Redirect to error page if update fails
    echo "<script>document.location.href = 'error.php?status=updateFailed'</script>";
}
?>
