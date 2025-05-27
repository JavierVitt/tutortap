<?php
require_once '../functions.php';

// Check if user is logged in and class ID is provided
if (!isset($_GET['id']) || !isset($_GET['classId'])) {
    // Redirect to login page if not logged in
    echo "<script>document.location.href = 'login.php'</script>";
    exit;
}

$userId = $_GET['id'];
$classId = $_GET['classId'];

// Validate that this class belongs to the user
$kelas = Kelas::getKelasById($classId);
if (empty($kelas) || $kelas[0]['userId'] != $userId) {
    // Redirect to tutor home if class doesn't exist or doesn't belong to user
    echo "<script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'You do not have permission to delete this class!',
                footer: '<a href=\"homeTutor.php?id=$userId\">Return to home</a>'
            }).then((result) => {
                window.location.href = 'homeTutor.php?id=$userId';
            });
        });
    </script>";
    exit;
}

// Delete the class (update status to inactive)
global $conn;
$query = "UPDATE kelas SET statusKelas = 0 WHERE idKelas = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $classId);

if (mysqli_stmt_execute($stmt)) {
    // Redirect to tutor home page with success message
    echo "<script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: 'Your class has been deleted successfully.',
                footer: '<a href=\"homeTutor.php?id=$userId\">Return to home</a>'
            }).then((result) => {
                window.location.href = 'homeTutor.php?id=$userId';
            });
        });
    </script>";
} else {
    // Show error message
    echo "<script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to delete class. Please try again.',
                footer: '<a href=\"homeTutor.php?id=$userId\">Return to home</a>'
            }).then((result) => {
                window.location.href = 'homeTutor.php?id=$userId';
            });
        });
    </script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Class - TutorTap</title>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <!-- Page content will be replaced by SweetAlert dialog -->
</body>
</html>
