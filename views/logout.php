<?php
// Start the session to access session variables
session_start();

// Clear any session variables
$_SESSION = array();

// If a session cookie is used, destroy it
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 3600, '/');
}

// Destroy the session
session_destroy();

// Redirect to login page
echo "<script>document.location.href = 'login.php'</script>";
exit;
?>
