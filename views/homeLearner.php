<?php
require_once "../functions.php";

// Start session
session_start();

// Check if user is logged in
if(!isset($_GET['id']) && !isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    echo "<script>document.location.href = 'login.php'</script>";
    exit;
}

// Get user ID from session if not in URL
$id = isset($_GET['id']) ? $_GET['id'] : $_SESSION['user_id'];

$searchTerm = '';

// Process search query if form was submitted
if(isset($_GET['search'])) {
    $searchTerm = htmlspecialchars($_GET['search']);
      // If search term is empty, get all classes
    if(trim($searchTerm) === '') {
        $kelas = new Kelas();
        $datas = $kelas->getAllKelas();
    } else {
        // Get filtered classes based on search term
        $kelas = new Kelas();
        $datas = $kelas->searchKelas($searchTerm);
    }
} else {
    // Get all classes if no search was performed
    $kelas = new Kelas();
    $datas = $kelas->getAllKelas();
}

// Filter out classes created by the current user
// We only want to show classes from other users in learner view
$filteredDatas = [];
foreach ($datas as $data) {
    if ($data['userId'] != $id) {
        $filteredDatas[] = $data;
    }
}
$datas = $filteredDatas;
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
    <!-- Core theme CSS (includes Bootstrap)-->    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link href="../styles/styles.css" rel="stylesheet" />
    <style>        
        .card-container {
            position: relative;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            cursor: pointer;
            transform-origin: center;
        }
        .card-container:hover .card {
            opacity: 1;
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
        }
        .card-container:hover .card-overlay {
            opacity: 1;
            pointer-events: all;
        }        .card {
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        
        /* Search highlighting styles */
        mark.bg-warning {
            padding: 0.1rem 0.2rem;
            border-radius: 3px;
        }
        
        mark.bg-light {
            padding: 0.05rem;
            border-radius: 2px;
            background-color: rgba(255, 204, 1, 0.2) !important;
            color: inherit;
        }
        
        /* Search results animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .search-result-item {
            animation: fadeIn 0.3s ease-in-out forwards;
        }
    </style>
</head>

<body class="montserratRegular">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    
    <div class="navbar w-100 bg-ouryellow ">
            <div class="container-fluid d-flex justify-content-between">
                <div class="container w-25 d-flex justify-content-center">
                    <a href="">
                        <img src="../images/skilltap logo+brand.png" class="rounded-pill" style="width:200px; background-color:black" alt="">
                    </a>                </div>                <form method="GET" action="" class="input-group w-50">
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <input type="text" class="form-control montserratRegular" name="search" placeholder="Search Classes by name, description or topic" 
                           value="<?php echo $searchTerm; ?>" aria-label="Search classes">
                    <div class="input-group-append">
                        <button class="btn btn-outline-dark montserratSemiBold" type="submit">
                            <i class="bi bi-search me-1"></i> Search
                        </button>
                        
                    </div>
                </form>

                <div class="container w-25 px-5 row" style="color:black;">
                    <!-- <div class="container col-3">
                        <a href="">
                            <i class="bi bi-envelope-fill text-dark" style="font-size: 30px; "></i></a></th>
                        </a>
                    </div> -->
                    <div class="container col-3">
                        <i class="bi bi-filter text-dark" style="font-size: 30px;"></i></th>                
                    </div>                    
                    <div class="container col-3">
                        <a href="orderListLearner.php?id=<?php echo $id; ?>">
                            <i class="bi bi-cart-fill text-dark" style="font-size: 30px;"></i>
                        </a>
                    </div>
                    <div class="container col-3">
                        <a href="logout.php" title="Logout">
                            <i class="bi bi-box-arrow-right text-dark" style="font-size: 30px;"></i></a></th>
                        </a>
                    </div>
                </div>
            </div>
        </div>        <div class="container-fluid text-center mt-5 montserratBold ">
            <h1 class="montserratBold">
                <?php if(!empty($searchTerm)): ?>
                    Search Results for: "<?php echo $searchTerm; ?>"
                <?php else: ?>
                    Discover Classes
                <?php endif; ?>
            </h1>
        </div>

        <div class="container-fluid p-5">            <?php if(empty($datas) && !empty($searchTerm)): ?>
                <div class="alert alert-warning text-center">
                    <h3>No classes found matching "<?php echo $searchTerm; ?>"</h3>
                    <p>Try another search term, check your spelling, or <a href="homeLearner.php?id=<?php echo $id; ?>">browse all classes</a>.</p>
                    
                    <div class="mt-3">
                        <h5>Search Tips:</h5>
                        <ul class="list-unstyled">
                            <li>Use specific terms related to what you want to learn</li>
                            <li>Try different words with similar meanings</li>
                            <li>Search by skill name, subject, or topic area</li>
                        </ul>
                    </div>
                </div>
            <?php elseif(!empty($searchTerm)): ?>
                <div class="alert alert-success text-center">
                    <p>Found <?php echo count($datas); ?> classes matching your search for "<?php echo $searchTerm; ?>"</p>
                </div>
            <?php endif; ?>
            <div class="row p-3">                
                <?php foreach ($datas as $key => $data) : ?>
                    <div class="col-md-4 mb-4 card-container search-result-item" style="animation-delay: <?php echo $key * 0.1; ?>s;" onclick="window.location.href='classDetail.php?id=<?php echo $id; ?>&classId=<?php echo $data['idKelas']; ?>'">
                        <div class="card h-100 shadow-lg bg-white rounded">
                            <img class="card-img-top" src="../images/<?php echo $data['fotoKelas']; ?>" alt="Card image cap" style="height: 200px; object-fit: cover;">
                            <div class="card-body d-flex flex-column">                            
                                <h5 class="card-title montserratBold">
                                    <?php 
                                    if (!empty($searchTerm)) {
                                        // Highlight search term in class name
                                        $highlighted = preg_replace('/(' . preg_quote($searchTerm, '/') . ')/i', '<mark class="bg-warning">$1</mark>', $data['namaKelas']);
                                        echo $highlighted;
                                    } else {
                                        echo $data['namaKelas'];
                                    }
                                    ?>
                                </h5>
                                <!-- Price, Rating and Tutor Info in a row -->
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <!-- Left side: Price and Rating -->
                                    <div class="flex-grow-1">
                                        <h5 class="card-text">Rp.<?= $data['hargaKelas']; ?><?php if(isset($data['durasiKelas'])): ?>/<?= $data['durasiKelas']; ?><?php endif; ?></h5>
                                        
                                        <!-- Class rating -->
                                        <div class="card-text" style="display: flex; align-items: center;">
                                            <i class="bi bi-star-fill" style="font-size: 20px; color: #FFCC01;"></i>
                                            <?php
                                            $syn = "SELECT * FROM class_rating_result WHERE classId = '" . $data['idKelas'] . "'";
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
                                    <div class="d-flex align-items-center ms-2">
                                        <?php 
                                        // Get tutor information
                                        $tutor = User::getUserById($data['userId']); 
                                        $tutorProfilePic = !empty($tutor['profilePicture']) ? $tutor['profilePicture'] : "javier.png";
                                        ?>
                                        <img src="../images/<?php echo $tutorProfilePic; ?>" 
                                            alt="Tutor profile image" 
                                            class="img-fluid rounded-circle border border-dark border-0" 
                                            style="width: 40px; height: 40px; object-fit: cover;">
                                        <div class="ms-2">
                                            <p class="mb-0 montserratBold" style="font-size: 0.9rem;"><?php echo $tutor['nama']; ?></p>
                                            <!-- <i class="bi bi-star-fill" style="color: #FFCC01; font-size: 0.8rem;"></i> -->
                                        </div>
                                    </div>
                                </div>
                                
                                <p class="card-text flex-grow-1">
                                    <?php 
                                    if (!empty($searchTerm)) {
                                        // Highlight search term in description
                                        $searchWords = explode(' ', trim($searchTerm));
                                        $description = $data['deskripsiKelas'];
                                        
                                        foreach ($searchWords as $word) {
                                            if (strlen($word) > 2) { // Only highlight words with more than 2 characters
                                                $description = preg_replace('/(' . preg_quote($word, '/') . ')/i', '<mark class="bg-light">$1</mark>', $description);
                                            }
                                        }
                                        
                                        // Truncate description if it's too long
                                        if (strlen($description) > 150) {
                                            echo substr($description, 0, 150) . '...';
                                        } else {
                                            echo $description;
                                        }
                                    } else {
                                        // Truncate description if it's too long
                                        if (strlen($data['deskripsiKelas']) > 150) {
                                            echo substr($data['deskripsiKelas'], 0, 150) . '...';
                                        } else {
                                            echo $data['deskripsiKelas'];
                                        }
                                    }
                                    ?>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- <div class="card mb-3">
            <img class="card-img-top" src="../images/20221003_133232.jpg" alt="Card image cap">
            <div class="card-body">
                <h5 class="card-title">Card title</h5>
                <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content.
                    This content is a little bit longer.</p>
                <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>
            </div>
        </div> -->


        <div class="container-fluid bg-ouryellow d-flex justify-content-center py-3">
            <div class="btn-group w-50 py-5">
                <button type="button" class="btn btn-outline-dark " style="font-size: 25px;" onclick="window.location.href='homeTutor.php?id=<?php echo $id; ?>'">
                    <h1>Tutor</h1>
                </button>
                <button type="button" class="btn btn-dark" style="font-size: 25px;" onclick="window.location.href='homeLearner.php?id=<?php echo $id; ?>'">
                    <h1>Learner</h1>
                </button>
            </div>
        </div>    </div>
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

<script>        // Wait for the document to be fully loaded
        document.addEventListener('DOMContentLoaded', function() {
            // Get references to the search form and button
            const searchForm = document.querySelector('form.input-group');
            const searchButton = searchForm.querySelector('button[type="submit"]');
            const searchInput = searchForm.querySelector('input[name="search"]');
            
            // Add event listener to the form submission
            searchForm.addEventListener('submit', function(event) {
                // Change the button text to indicate search is in progress
                searchButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Searching...';
                searchButton.disabled = true;
            });
            
            // No need to disable the search button for empty input
            // This allows submitting empty searches which will show all classes
        });
    </script>
</html>