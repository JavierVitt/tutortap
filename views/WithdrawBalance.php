<?php
require_once "../functions.php";

// Fetch all classes
$syntax = "SELECT * FROM KELAS";
$datas = query($syntax);

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the withdrawal amount from the form data
    $withdrawalAmount = $_POST['withdrawalAmount'];

    // Get the user's balance
    // This is just an example, replace it with your actual query to get user's balance
    $balanceQuery = "SELECT saldo FROM user WHERE userId = YOUR_USER_ID";
    $balanceResult = query($balanceQuery);
    $balance = $balanceResult[0]['saldo'];

    // Check if the withdrawal amount is less than or equal to the user's balance
    if ($withdrawalAmount <= $balance) {
        // Subtract the withdrawal amount from the user's balance
        $newBalance = $balance - $withdrawalAmount;
        $updateBalanceQuery = "UPDATE user SET saldo = ? WHERE userId = YOUR_USER_ID";
        $stmt = mysqli_prepare($conn, $updateBalanceQuery);
        mysqli_stmt_bind_param($stmt, 'i', $newBalance);
        mysqli_stmt_execute($stmt);

        echo "Withdrawal successful. Your new balance is " . $newBalance;
    } else {
        echo "Insufficient balance.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>TutorTap - Home</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <!-- Custom Google font-->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@100;200;300;400;500;600;700;800;900&amp;display=swap" rel="stylesheet" />
    <!-- Bootstrap icons-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Core theme CSS (includes Bootstrap)-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="../styles/styles.css" rel="stylesheet" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>

<body>
    <div class="navbar w-100 bg-ouryellow ">
        <div class="container-fluid d-flex justify-content-between">
            <div class="input-group w-75">
                <input type="text" class="form-control" placeholder="Search Classes" aria-label="Recipient's username" aria-describedby="basic-addon2">
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="button">Search</button>
                </div>
            </div>

            <div class="container w-25 row">
                <div class="container col-3">
                    <i class="bi bi-envelope-fill text-white" style="font-size: 30px;"></i></a></th>
                </div>
                <div class="container col-3">
                    <i class="bi bi-filter text-white" style="font-size: 30px;"></i></a></th>
                </div>
                <div class="container col-3">
                    <i class="bi bi-cart-fill text-white" style="font-size: 30px;"></i></a></th>
                </div>
                <div class="container col-3">
                    <i class="bi bi-list text-white" style="font-size: 30px;"></i></a></th>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid text-center mt-5">
        <h1>Balance Withdrawal</h1>
    </div>

    <div class="container-fluid">
        <div class="row">
        <h5 class="card-title">TEST ANGKA = 50000</h5>
            <?php foreach ($datas as $data) : ?> //change to check saldo utk ditarik
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $data['namaKelas']; ?></h5>
                        <p class="card-text"><?php echo $data['deskripsiKelas']; ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="row">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Jumlah saldo yang kamu miliki:</h5>
                    <form method="post" action="">
                        <input type="number" class="form-control" name="withdrawalAmount" placeholder="Saldo yang ingin ditarik" aria-label="withdrawalAmount" aria-describedby="basic-addon2">
                        <p class="card-text"></p>
                        <button class="btn btn-warning position-relative end-0" type="submit">Tarik</button>
                    </form>
                </div>
            </div>
        </div>
</body>

</html>