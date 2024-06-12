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
        $syntax = "SELECT * FROM KELAS WHERE status === 1";
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
    static function checkSaldo($idUser){
        $syn = "SELECT saldo FROM USER WHERE userId = $idUser";
    }
}