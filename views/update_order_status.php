<?php
require_once "../functions.php";

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['idOrder'])) {
    $idOrder = $_POST['idOrder'];
    
    // Update the order status to 1 (Payment Confirmed, Waiting for Tutor Confirmation)
    $status = 1; 
    
    if (Order::updateOrderStatus($idOrder, $status)) {
        // Get order details to clear the relevant session variables
        $orderQuery = "SELECT * FROM `order` WHERE idOrder = $idOrder";
        $orderData = query($orderQuery);
        
        if (!empty($orderData)) {
            $idUser = $orderData[0]['idUser'];
            $idClass = $orderData[0]['idClass'];
            
            // Clear session variables related to this order
            $orderSessionKey = "order_{$idUser}_{$idClass}";
            $vaSessionKey = "va_code_{$idOrder}";
            
            if (isset($_SESSION[$orderSessionKey])) {
                unset($_SESSION[$orderSessionKey]);
            }
            
            if (isset($_SESSION[$vaSessionKey])) {
                unset($_SESSION[$vaSessionKey]);
            }
        }
        
        echo "success";
    } else {
        global $conn;
        echo "error: " . mysqli_error($conn);
    }
} else {
    echo "invalid request";
}
?>
