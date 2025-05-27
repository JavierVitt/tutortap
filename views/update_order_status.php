<?php
require_once "../functions.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['idOrder'])) {
    $idOrder = $_POST['idOrder'];
    
    // Update the order status to 2 (Menunggu Konfirmasi Tutor)
    $status = 1; // Status 2 = Menunggu Konfirmasi Tutor
    
    if (Order::updateOrderStatus($idOrder, $status)) {
        echo "success";
    } else {
        global $conn;
        echo "error: " . mysqli_error($conn);
    }
} else {
    echo "invalid request";
}
?>
