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
