<?php
require_once '../functions.php';

// Start session
session_start();

// Check if user is logged in
if(!isset($_GET['id']) && !isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    echo "<script>document.location.href = 'login.php'</script>";
    exit;
}

// Get user ID from session if not in URL
$idUser = isset($_GET['id']) ? $_GET['id'] : $_SESSION['user_id'];

$kelas = new Kelas();
$kelass = $kelas->getTutorKelas($idUser);

$user = new User();
$saldo = $user->getSaldo($idUser);
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
    <link href="../styles/styles.css" rel="stylesheet" />    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <style>        
        .card-container {
            position: relative;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            cursor: pointer;
            transform-origin: center;
        }
        .card-container:hover .card {
            opacity: 0.7;
            transform: scale(1.05);
            box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important;
        }
        .card-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;     
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            opacity: 0;
            transition: all 0.3s ease-in-out;
            z-index: 10;
            pointer-events: none;
        }        .card-container:hover .card-overlay {
            opacity: 1;
            pointer-events: all;
        }
        .edit-button {
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 5px;
            padding: 10px 20px;
            margin-bottom: 10px;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        .edit-button:hover {
            transform: scale(1.05);
        }
        .card {
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
    </style>
</head>

<body class="montserratRegular">
    <!-- <div class="navbar w-100 " style="background-color: rgb(0,0,0);">
        <div class="container-fluid d-flex justify-content-center">
            <img src="../images/skilltap logo+brand.png" style="width:300px; " alt="">
        </div>
    </div> -->
    
    <div class="navbar w-100">
        <div class="container-fluid row px-5">
            <div class="container col-2 d-flex justify-content-center">
                <a href="">
                    <img src="../images/skilltap brand.png" class="rounded-pill" style="width:200px;" alt="">
                </a>                
            </div>                
            
            <div class="container col-9 d-flex align-items-center justify-content-between rounded-4 p-3 gap-2">
                <!-- <form method="GET" action="homeLearner.php" class="input-group me-2 d-flex justify-content-between gap-2">
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <input type="text" class="form-control montserratRegular rounded-2 bg-light text-dark border border-secondary" name="search" value="<?php echo $searchTerm; ?>" placeholder="Search Classes" aria-label="Search classes" style="box-shadow: inset 0 1px 3px rgba(0,0,0,0.2);">
                    <div class="input-group-append">
                        <button class="btn btn-outline-dark montserratSemiBold" type="submit">Search</button>
                    </div>
                </form>

                <a href="#" id="filterButton" data-bs-toggle="modal" data-bs-target="#filterModal">
                    <i class="bi bi-filter text-dark" style="font-size: 30px;"></i>
                </a> -->
            </div>

            <div class="container col-1 row" style="color:black;">
                <!-- <div class="container col-3">
                    <a href="">
                        <i class="bi bi-envelope-fill text-dark" style="font-size: 30px; "></i></a></th>
                    </a>
                </div> -->                    
                <!-- <div class="container col-3">
                    <a href="#" id="filterButton" data-bs-toggle="modal" data-bs-target="#filterModal">
                        <i class="bi bi-filter text-dark" id="filterIcon" style="font-size: 30px;"></i>
                    </a>                
                </div> -->
                <div class="container col-3">
                    <a href="orderListTutor.php?id=<?php echo $idUser; ?>">
                        <i class="bi bi-cart-fill text-dark" style="font-size: 30px;"></i>
                    </a>
                </div>
                <div class="container col-3">
                    <a href="logout.php" title="Logout">
                        <i class="bi bi-box-arrow-right text-dark" style="font-size: 30px;"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="containter-fluid d-flex justify-content-center align-items-center my-4">
        <div class="container mx-5 rounded-4 montserratBold" style="height:10%; width:50%; background-color: rgb(200,200,200)">
            <div class="container-fluid row">
                <div class="col-2 text-center text-primary">
                    <i class="bi bi-wallet2" style="font-size:40px"></i>
                </div>

                <!-- akses tutor balance disini -->
                <div class="col-7 text-center text-primary d-flex justify-content-center align-items-center">
                    <h1 class="montserratSemiBold">Rp. <?php echo $saldo; ?></h1>
                </div>
                <!-- <div class="col-3 text-center d-flex justify-content-center align-items-center">
                    <button class="btn btn-outline-primary montserratBold" style="font-size:20px;" onclick="window.location.href='WithdrawBalance.php?id=<?=$idUser;?>'">Withdraw</button>
                </div> -->
                <div class="col-3 text-center d-flex justify-content-center align-items-center">
                 <button class="btn btn-outline-secondary montserratBold text-muted" style="font-size:20px; pointer-events: none; opacity: 0.65;" disabled>Withdraw</button>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid px-5 mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="montserratBold">Your Classes</h1>
            <button class="btn btn-warning montserratBold" style="font-size:20px;" onclick="window.location.href='addClass.php?id=<?php echo $idUser; ?>'"><i class="bi bi-plus"></i> Add New Class</button>
        </div>
    </div>    <div class="container-fluid px-5 pb-5">        <div class="row p-3">            <?php foreach ($kelass as $kelas) : ?>
                <div class="col-md-4 mb-4 card-container" onclick="window.location.href='editClass.php?id=<?php echo $idUser; ?>&classId=<?php echo $kelas['idKelas']; ?>'">
                    <div class="card h-100 shadow-lg bg-white rounded">
                        <img class="card-img-top" src="../images/<?php echo $kelas['fotoKelas']; ?>" alt="Card image cap" style="height: 200px; object-fit: cover;">
                        <div class="card-body d-flex flex-column">                            
                            <h5 class="card-title montserratBold"><?php echo $kelas['namaKelas']; ?></h5>
                            
                            <!-- Price, Rating and Tutor Info in a row -->
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <!-- Left side: Price and Rating -->
                                <div class="flex-grow-1">
                                    <h5 class="card-text">Rp.<?= $kelas['hargaKelas']; ?><?php if(isset($kelas['durasiKelas'])): ?>/<?= $kelas['durasiKelas']; ?><?php endif; ?></h5>
                                    
                                    <!-- Class rating -->
                                    <div class="card-text" style="display: flex; align-items: center;">
                                        <i class="bi bi-star-fill" style="font-size: 20px; color: #FFCC01;"></i>
                                        <?php
                                        $syn = "SELECT * FROM class_rating_result WHERE classId = '" . $kelas['idKelas'] . "'";
                                        $hasils = query($syn);
                                        if (!empty($hasils)) {
                                            $hasil = $hasils[0];
                                            $rataRataRating = $hasil['totalRatingCount'] > 0 ? 
                                                number_format($hasil['totalRatingSum'] / $hasil['totalRatingCount'], 1) : 0;
                                            echo '<span style="margin-left: 10px;">' . $rataRataRating . ' - ' . $hasil['totalRatingCount'] . ' reviews</span>';
                                        } else {
                                            echo '<span style="margin-left: 10px;">No ratings yet</span>';
                                        }
                                        ?>
                                    </div>
                                </div>
                                
                                <!-- Right side: Tutor information -->
                                <div class="d-flex align-items-center ms-2">                                <?php 
                                    // Get tutor information - using class creator ID since we're in tutor view
                                    // In this case, idUser is the same as the tutor's ID since we're showing tutor's own classes
                                    $tutor = User::getUserById($kelas['userId']); 
                                    $tutorProfilePic = !empty($tutor['profilePicture']) ? $tutor['profilePicture'] : "javier.png";
                                    ?>
                                    <img src="../images/<?php echo $tutorProfilePic; ?>" 
                                         alt="Tutor profile image" 
                                         class="img-fluid rounded-circle border border-dark border-0" 
                                         style="width: 40px; height: 40px; object-fit: cover;">
                                    <div class="ms-2">
                                        <p class="mb-0 montserratBold" style="font-size: 0.9rem;"><?php echo $tutor['nama']; ?></p>
                                    </div>
                                </div>                            </div>
                            
                            <p class="card-text flex-grow-1">
                                <?php 
                                // Get description from kelas array
                                $description = $kelas['deskripsiKelas'];
                                
                                // Check if there's a search term in the URL
                                $searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
                                
                                if (!empty($searchTerm)) {
                                    // Highlight search term in description
                                    $searchWords = explode(' ', trim($searchTerm));
                                    
                                    foreach ($searchWords as $word) {
                                        if (strlen($word) > 2) { // Only highlight words with more than 2 characters
                                            $description = preg_replace('/(' . preg_quote($word, '/') . ')/i', '<mark class="bg-light">$1</mark>', $description);
                                        }
                                    }
                                }
                                
                                // Truncate description if it's too long
                                if (strlen($description) > 150) {
                                    echo substr($description, 0, 150) . '...';
                                } else {
                                    echo $description;                                }
                                ?>
                            </p>
                            <div class="d-flex justify-content-end">
                                <span class="badge bg-<?php echo $kelas['statusKelas'] == 1 ? 'success' : 'warning'; ?>">
                                    <?php echo $kelas['statusKelas'] == 1 ? 'Active' : 'Inactive'; ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="card-overlay">
                        <h1 class="montserratBold text-muted"><i class="bi bi-pencil-fill"></i></h1>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    
    <div class="container-fluid d-flex justify-content-center py-3">
        <div class="btn-group w-50 py-5">
            <button type="button" class="btn btn-dark" style="font-size: 25px;" onclick="window.location.href='homeTutor.php?id=<?php echo $idUser; ?>'">
                <h1 class="text-white">Tutor</h1>
            </button>
            <button type="button" class="btn btn-outline-dark" style="font-size: 25px;" onclick="window.location.href='homeLearner.php?id=<?php echo $idUser; ?>'">
                 <h1>Learner</h1>
            </button>
        </div>
    </div>


</div>

</body>

<div class="container">
    <footer class="d-flex flex-wrap justify-content-between align-items-center border-top">
        <p class="col-md-4 mb-0 text-muted">&copy; 2024 Company, Inc</p>

        <a href="/" class="col-md-4 d-flex align-items-center justify-content-center mb-3 mb-md-0 me-md-auto link-dark text-decoration-none">
            <img src="../images/skilltap logo+brand.png" alt="" style="width: 30%; height:30%;">
        </a>

        <ul class="nav col-md-4 justify-content-end">
            <li class="nav-item"><a href="#" class="nav-link px-2 text-muted">Home</a></li>
            <li class="nav-item"><a href="#" class="nav-link px-2 text-muted">Features</a></li>
            <li class="nav-item"><a href="#" class="nav-link px-2 text-muted">Pricing</a></li>
            <li class="nav-item"><a href="#" class="nav-link px-2 text-muted">FAQs</a></li>
            <li class="nav-item"><a href="#" class="nav-link px-2 text-muted">About</a></li>
        </ul>
    </footer>
</div>

</html>