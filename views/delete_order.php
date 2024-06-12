<?php
require_once "../functions.php";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Assuming you have a session started and the idOrder is stored there
    session_start();
    $idOrder = isset($_POST['idOrder']) ? $_POST['idOrder'] : '';

    // Your Order class and deleteOrder method logic here
    $order = new Order();
    $order->deleteOrder($idOrder);

    // Echo a response back to the client
    echo "Order deleted successfully";
}
?>