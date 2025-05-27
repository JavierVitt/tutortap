<?php
$conn = mysqli_connect("localhost", "root", "", "tutortap");


function query($syntax)
{
    global $conn;

    $result = mysqli_query($conn, $syntax);

    $rows = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }    return $rows;
}

// Function to execute query without returning result
function executeQuery($syntax)
{
    global $conn;
    $result = mysqli_query($conn, $syntax);
    return $result;
}

class Kelas
{
    static function getAllKelas()
    {
        $syntax = "SELECT * FROM KELAS WHERE statusKelas = 1";
        $datas = query($syntax);
        return $datas;
    }      static function searchKelas($searchTerm)
    {
        global $conn;
        
        // If search term is empty or only whitespace, return all classes
        if (trim($searchTerm) === '') {
            return self::getAllKelas();
        }
        
        // Break the search term into individual words
        $searchWords = explode(' ', trim($searchTerm));
          // Base query with class and user joins to include tutor information
        $baseQuery = "SELECT k.*, ";
        
        // Prepare parameterized conditions for each word in the search term
        $conditions = [];
        $params = [];
        $types = "";
        
        // Add exact match parameter for title (highest priority)
        $exactMatchParam = "%$searchTerm%";
        $params[] = $exactMatchParam;
        $types .= "s";
        
        // Start building the CASE statement for relevance scoring
        $scoreQuery = "CASE WHEN k.namaKelas LIKE ? THEN 3 ";  // Exact match in title: highest priority
        
        // For each word, create weighted conditions
        foreach ($searchWords as $word) {
            if (strlen($word) > 2) { // Only consider words with more than 2 characters
                // Title matches (highest weight)
                $scoreQuery .= "WHEN k.namaKelas LIKE ? THEN 2 ";
                $params[] = "%$word%";
                $types .= "s";
                
                // Description matches (medium weight)
                $scoreQuery .= "WHEN k.deskripsiKelas LIKE ? THEN 1 ";
                $params[] = "%$word%";
                $types .= "s";
            }
        }
        
        // Complete the CASE statement
        $scoreQuery .= "ELSE 0 END AS relevance_score";
        
        // Complete the query with FROM, JOIN, WHERE, and ORDER BY clauses
        $query = $baseQuery . $scoreQuery . " 
                 FROM kelas k
                 WHERE k.statusKelas = 1
                 HAVING relevance_score > 0
                 ORDER BY relevance_score DESC, k.namaKelas ASC";
        
        // Prepare and execute the statement
        $stmt = mysqli_prepare($conn, $query);
        
        if ($stmt) {
            // Bind all parameters
            mysqli_stmt_bind_param($stmt, $types, ...$params);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            
            $datas = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $datas[] = $row;
            }
            return $datas;
        }
        
        // Fallback to basic search if the advanced query fails
        $searchParam = "%$searchTerm%";
        $fallbackQuery = "SELECT * FROM kelas WHERE statusKelas = 1 AND 
                         (namaKelas LIKE ? OR deskripsiKelas LIKE ?)";
        $stmt = mysqli_prepare($conn, $fallbackQuery);
        mysqli_stmt_bind_param($stmt, "ss", $searchParam, $searchParam);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        $datas = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $datas[] = $row;
        }
        return $datas;
    }
    
    static function getTutorKelas($idUser)
    {
        $syntax = "SELECT * FROM KELAS WHERE userId = $idUser";
        $kelass = query($syntax);
        return $kelass;
    }
    static function getKelasById($idClass){
        $syntax = "SELECT * FROM KELAS WHERE idKelas = $idClass";
        $result  = query($syntax);
        return $result;
    }
}
class Complain{
    static function upload($data){
        $namaGambar = $data['complainPicture']['name'];
        $tmpName = $data['complainPicture']['tmp_name'];

        $namaFileBaru = uniqid() . '.jpg';

        move_uploaded_file($tmpName, '../complain_picture/' . $namaFileBaru);

        return $namaFileBaru;
    }
    static function complainOrder($idOrder, $data, $files){

        global $conn;

        $complainMessage = $data['complainMessage'];

        $gambar = self::upload($files);
        if( !$gambar ){
            return false;
        }

        $syntax = "INSERT INTO COMPLAIN VALUES ('', $idOrder, '$complainMessage', '$gambar',2020)";
        mysqli_query($conn, $syntax);
    }
    static function getAllComplain(){
        $syntax = "SELECT * FROM COMPLAIN";
        $result = query($syntax);

        return $result;
    }
}
class Admin{
    static function process($complainId, $status){
        global $conn;
        if($status == 0){
            //Ini approve
            $syn = "DELETE FROM COMPLAIN WHERE complainId = $complainId";
            mysqli_query($conn, $syn);
        }elseif($status == 1){
            //Ini delete
            $syn = "DELETE FROM COMPLAIN WHERE complainId = $complainId";
            mysqli_query($conn, $syn);
        }
        echo "<script>document.location.href = '../views/AdminApproval.php'</script>";
    }
}
class User{
    static function getUserById($userId) {
        $syntax = "SELECT * FROM USER WHERE userId = $userId";
        $result = query($syntax);
        return !empty($result) ? $result[0] : null;
    }
    
    static function checkSaldo($idUser, $reqSaldo){
        global $conn;

        $syn = "SELECT * FROM USER WHERE userId = $idUser";
        $saldo = query($syn);
        $saldo = $saldo[0]['saldo'];

        if((int)$reqSaldo>(int)$saldo){
            //Gk valid
            echo '<script>
                document.addEventListener("DOMContentLoaded", function () {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Your balance isn\'t enough!",
                        footer: \'<a href="#"></a>\'
                    });
                });
            </script>';
        }else{
            //Valid
            $saldoAkhir = (int)$saldo-(int)$reqSaldo;
            
            $syn = "UPDATE USER SET saldo = $saldoAkhir WHERE userId = $idUser";
            mysqli_query($conn, $syn);
            
            echo '<script>
                document.addEventListener("DOMContentLoaded", function () {
                    Swal.fire({
                        icon: "success",
                        title: "Oops...",
                        text: "Withdraw Success!",
                        footer: \'<a href="../throw/withdraw.php?id=$idUser"></a>\'
                    });
                });
            </script>';
            echo "<script>document.location.href = ''</script>";
        }
    }
    static function getSaldo($idUser){
        $syn = "SELECT * FROM USER WHERE userId = $idUser";
        $saldo = query($syn);

        return $saldo[0]['saldo'];
    }
}
class Order{
    private $conn;
    private $idOrder;

    // public function __construct($conn, $idOrder) {
    //     $this->conn = $conn;
    //     $this->idOrder = $idOrder;
    // }

    public function __construct($conn = null, $idOrder = null) {
        $this->conn = $conn;
        $this->idOrder = $idOrder;
    }
    public function changeOrderStatus() {
        // Update the statusOrder of the order to '3'
        $updateQuery = "UPDATE `order` SET `statusOrder` = 3 WHERE `idOrder` = ?";
        $stmt = mysqli_prepare($this->conn, $updateQuery);
        mysqli_stmt_bind_param($stmt, 'i', $this->idOrder);
        mysqli_stmt_execute($stmt);
    }    static function addOrder($idUser, $idKelas, $durasi, $totalHarga){
        global $conn;

        // Use NULL instead of empty string for auto-increment primary key
        // Use proper date format for tanggalOrder
        // Use 0 for vaOrder since it's an integer column
        $syn = "INSERT INTO `order`(`idOrder`, `idUser`, `idClass`, `tanggalOrder`, `jumlahDurasi`, `catatanOrder`, `statusOrder`, `subtotalOrder`, `vaOrder`) 
                VALUES (NULL, " . $idUser . ", " . $idKelas . ", NOW(), " . $durasi . ", '', 0, '" . $totalHarga . "', 0)";
        
        // Execute query with error handling
        if (!mysqli_query($conn, $syn)) {
            // Log the error for debugging
            error_log("SQL Error in Order::addOrder: " . mysqli_error($conn));
            error_log("SQL Query: " . $syn);
            return 0; // Return 0 on failure
        }

        $result = mysqli_query($conn, "SELECT LAST_INSERT_ID() as id");
        $row = mysqli_fetch_assoc($result);
        $idOrder = $row['id'];
        return $idOrder;
    }    static function addOrderWithSchedule($idUser, $idKelas, $durasi, $totalHarga, $jadwalKelas, $catatanOrder = ''){
        global $conn;

        // Use NULL instead of empty string for auto-increment primary key
        // Use proper date format for tanggalOrder
        // Use 0 for vaOrder since it's an integer column
        $syn = "INSERT INTO `order`(`idOrder`, `idUser`, `idClass`, `tanggalOrder`, `jumlahDurasi`, `catatanOrder`, `statusOrder`, `subtotalOrder`, `vaOrder`, `jadwalKelas`) 
                VALUES (NULL, " . $idUser . ", " . $idKelas . ", NOW(), " . $durasi . ", '" . mysqli_real_escape_string($conn, $catatanOrder) . "', 0, '" . $totalHarga . "', 0, '" . mysqli_real_escape_string($conn, $jadwalKelas) . "')";
        
        // Execute query with error handling
        if (!mysqli_query($conn, $syn)) {
            // Log the error for debugging
            error_log("SQL Error in Order::addOrderWithSchedule: " . mysqli_error($conn));
            error_log("SQL Query: " . $syn);
            return 0; // Return 0 on failure
        }

        $result = mysqli_query($conn, "SELECT LAST_INSERT_ID() as id");
        $row = mysqli_fetch_assoc($result);
        $idOrder = $row['id'];
        return $idOrder;
    }static function setVA($idOrder, $va){
        global $conn;

        // Convert the VA code to a numeric value by using only the numeric portion
        // or by generating a hash of the code
        $numericVA = crc32($va) & 0x7FFFFFFF; // Generate a positive integer from the string
        
        $syn = "UPDATE `order` SET vaOrder = $numericVA WHERE idOrder = $idOrder";
        mysqli_query($conn, $syn);
    }

    static function getVA($idOrder){
        global $conn;

        $syn = "SELECT * FROM `order` WHERE idOrder = $idOrder";
        $result = query($syn);

        return $result[0]['vaOrder'];
    }    static function showAllKelas($idUser){
        $syn = "SELECT * FROM `order` WHERE idUser = $idUser ORDER BY tanggalOrder DESC";
        
        $result = query($syn);
        return $result;
    }
    
    static function showAllKelasByTutor($idUser){
        // First find all classes taught by this tutor
        $syn = "SELECT o.* FROM `order` o 
                INNER JOIN `kelas` k ON o.idClass = k.idKelas 
                WHERE k.userId = $idUser 
                ORDER BY o.tanggalOrder DESC";
        
        $result = query($syn);
        return $result;
    }    static function deleteOrder($idOrder){
        global $conn;

        $syn = "DELETE FROM `order` WHERE idOrder = $idOrder";
        mysqli_query($conn, $syn);
    }
    
    static function updateOrderStatus($idOrder, $status){
        global $conn;

        $syn = "UPDATE `order` SET statusOrder = $status WHERE idOrder = $idOrder";
        return mysqli_query($conn, $syn);
    }
    static function getOrderStatus($idOrder){
        global $conn;

        $syn = "SELECT * FROM order WHERE idOrder = $idOrder";
        $result = query($syn);

        return $result[0]['statusOrder'];
    }
    public function createComplain($complainMessage) {
        
        //TEMPDATA = ADMIN[0]
        $adminQuery = "SELECT adminId FROM admin LIMIT 1";
        $adminResult = query($adminQuery);
        $adminId = $adminResult[0]['adminId'];

        //INSERT COMPLAIN TEMPDATA = ID 1
        $query = "INSERT INTO complain (complainMessage, adminId, idOrder) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, 'sii', $complainMessage, $adminId, $this->idOrder);
        mysqli_stmt_execute($stmt);

        echo "Complaint submitted successfully.";
    }
}
class Chat{
    static function addChat($senderId, $receiverId, $chat){
        global $conn;
        $syn = "INSERT INTO CHAT (senderId, receiverId, pesan, waktu) VALUES ('$senderId', '$receiverId', '$chat', CURRENT_TIMESTAMP)";
        mysqli_query($conn, $syn);

        echo "<script>document.location.href='../throw/chat.php?senderId=$senderId&receiverId=$receiverId'</script>";
    }
}