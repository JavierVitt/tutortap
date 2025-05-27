<?php
require_once "../functions.php";

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idOrder = isset($_POST['idOrder']) ? $_POST['idOrder'] : '';

    if (!empty($idOrder)) {
        // Get order details before deleting to clear session variables
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
        
        // Delete the order
        Order::deleteOrder($idOrder);
        
        // Echo a response back to the client
        echo "Order deleted successfully";
    } else {
        echo "No order ID provided";
    }
}
?>