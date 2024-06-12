<?php
$conn = mysqli_connect("localhost", "root", "", "tutortap");


function query($syntax)
{
    global $conn;

    $result = mysqli_query($conn, $syntax);

    $rows = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }

    return $rows;
}
class Kelas
{
    static function getAllKelas()
    {
        $syntax = "SELECT * FROM KELAS";
        $datas = query($syntax);
        return $datas;
    }
    static function getTutorKelas($idUser)
    {
        $syntax = "SELECT * FROM KELAS WHERE userId = $idUser";
        $kelass = query($syntax);
        return $kelass;
    }
}
class Order {
    private $conn;
    private $idOrder;

    public function __construct($conn, $idOrder) {
        $this->conn = $conn;
        $this->idOrder = $idOrder;
    }

    public function createComplain($complainMessage) {
        // Get the first adminId
        $adminQuery = "SELECT adminId FROM admin LIMIT 1";
        $adminResult = query($adminQuery);
        $adminId = $adminResult[0]['adminId'];

        // Insert the new complaint into the "complain" table
        $query = "INSERT INTO complain (complainMessage, adminId, idOrder) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, 'sii', $complainMessage, $adminId, $this->idOrder);
        mysqli_stmt_execute($stmt);

        echo "Complaint submitted successfully.";
    }

    public function changeOrderStatus() {
        // Update the statusOrder of the order to '3'
        $updateQuery = "UPDATE `order` SET `statusOrder` = 3 WHERE `idOrder` = ?";
        $stmt = mysqli_prepare($this->conn, $updateQuery);
        mysqli_stmt_bind_param($stmt, 'i', $this->idOrder);
        mysqli_stmt_execute($stmt);
    }
}