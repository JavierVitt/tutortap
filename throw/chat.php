<?php

$senderId = $_GET['senderId'];
$receiverId = $_GET['receiverId'];

echo "<script>document.location.href='../views/chat.php?id=$senderId&receiverId=$receiverId'</script>";