<?php
// filepath: c:\laragon\www\tutortap\views\addToWishlist.php
require_once "../functions.php";

// Start session
session_start();

// Check if user is logged in
if(!isset($_SESSION['user_id']) && !isset($_POST['userId'])) {
    // Return error response
    // echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

// Get the user ID and class ID from POST request
$userId = isset($_POST['userId']) ? $_POST['userId'] : $_SESSION['user_id'];
$classId = isset($_POST['classId']) ? $_POST['classId'] : null;

// Validate input
if(empty($classId)) {
    // echo json_encode(['success' => false, 'message' => 'Class ID is required']);
    exit;
}

// Check if already in wishlist
$checkQuery = "SELECT * FROM wishlist WHERE userId = '$userId' AND classId = '$classId'";
$existing = query($checkQuery);

if(!empty($existing)) {
    // Already in wishlist - remove it
    $deleteQuery = "DELETE FROM wishlist WHERE userId = '$userId' AND classId = '$classId'";
    $result = executeQuery($deleteQuery);
    
    if($result) {
        echo json_encode(['success' => true, 'action' => 'removed', 'message' => 'Class removed from wishlist']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to remove from wishlist']);
    }
} else {
    // Not in wishlist - add it
    $insertQuery = "INSERT INTO wishlist (userId, classId) VALUES ('$userId', '$classId')";
    $result = executeQuery($insertQuery);
    
    if($result) {
        echo json_encode(['success' => true, 'action' => 'added', 'message' => 'Class added to wishlist']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to add to wishlist: ' . mysqli_error($GLOBALS['conn'])]);
    }
}
