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
        $syntax = "SELECT * FROM KELAS WHERE statusKelas = 1";
        $datas = query($syntax);
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
    }
    static function addOrder($idUser, $idKelas, $durasi, $totalHarga){
        global $conn;

        $syn = "INSERT INTO `order`(`idOrder`, `idUser`, `idClass`, `tanggalOrder`, `jumlahDurasi`, `catatanOrder`, `statusOrder`, `subtotalOrder`, `vaOrder`) VALUES ('', " . $idUser . ", " . $idKelas . ", '', " . $durasi . ", '', 0, '" . $totalHarga . "', '')";
        mysqli_query($conn, $syn);

        $result = mysqli_query($conn, "SELECT LAST_INSERT_ID() as id");
        $row = mysqli_fetch_assoc($result);
        $idOrder = $row['id'];
        return $idOrder;
        
    }

    static function setVA($idOrder, $va){
        global $conn;

        $syn = "UPDATE `order` SET vaOrder = '$va' WHERE idOrder = $idOrder";
        mysqli_query($conn, $syn);
    }

    static function getVA($idOrder){
        global $conn;

        $syn = "SELECT * FROM `order` WHERE idOrder = $idOrder";
        $result = query($syn);

        return $result[0]['vaOrder'];
    }
    static function showAllKelas($idUser){
        $syn = "SELECT * FROM `order` WHERE idUser = $idUser";
        
        $result = query($syn);
        return $result;
    }
    static function deleteOrder($idOrder){
        global $conn;

        $syn = "DELETE FROM order WHERE idOrder = $idOrder";
        mysqli_query($conn, $syn);
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