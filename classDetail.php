<?php

$idKelas = $_POST["idKelas"];

$syntax = "SELECT * FROM KELAS WHERE idKelas == $idKelas";
$result = query($syntax);