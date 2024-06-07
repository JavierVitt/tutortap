<?php
$conn = mysqli_connect("localhost", "root", "", "tutortap");

var_dump($conn);

function query($syntax){
    global $conn;

    $result = mysqli_query($conn, $syntax);

    $rows = [];

    while($row = mysqli_fetch_assoc($result)){
        $rows[] = $row;
    }

    return $rows;
}
