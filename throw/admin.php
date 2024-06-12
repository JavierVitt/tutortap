<?php
require_once "../functions.php";


$status = $_GET['status'];
$complainId = $_GET['complainId'];

$admin = new Admin();
$admin->process($complainId, $status);