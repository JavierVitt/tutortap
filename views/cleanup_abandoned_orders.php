<?php
require_once "../functions.php";

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// This script cleans up abandoned orders that have a status of 0 (pending)
// and are more than 24 hours old

// Set timezone to Asia/Jakarta (adjust if needed)
date_default_timezone_set('Asia/Jakarta');

// Get orders that are in status 0 and more than 24 hours old
$cutoffTime = date('Y-m-d H:i:s', strtotime('-24 hours'));
$query = "SELECT * FROM `order` WHERE statusOrder = 0 AND tanggalOrder < '$cutoffTime'";
$abandonedOrders = query($query);

$totalDeleted = 0;

foreach ($abandonedOrders as $order) {
    $idOrder = $order['idOrder'];
    $idUser = $order['idUser'];
    $idClass = $order['idClass'];
    
    // Delete the order
    Order::deleteOrder($idOrder);
    
    // Clear any session variables related to this order
    $orderSessionKey = "order_{$idUser}_{$idClass}";
    $vaSessionKey = "va_code_{$idOrder}";
    
    if (isset($_SESSION[$orderSessionKey])) {
        unset($_SESSION[$orderSessionKey]);
    }
    
    if (isset($_SESSION[$vaSessionKey])) {
        unset($_SESSION[$vaSessionKey]);
    }
    
    $totalDeleted++;
}

// Output the result
if (isset($_GET['manual']) && $_GET['manual'] == 1) {
    // Manual cleanup request (e.g., from admin page)
    echo "<p>Cleaned up $totalDeleted abandoned orders.</p>";
    echo "<p><a href='../views/homeLearner.php?id={$_GET['id']}'>Return to home</a></p>";
} else {
    // Automated/cron job request
    echo "Cleaned up $totalDeleted abandoned orders.";
}
?>
