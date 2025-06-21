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

// Helper function to remove a parameter from the current URL
function removeParameterFromCurrentUrl($paramToRemove) {
    $params = $_GET;
    unset($params[$paramToRemove]);
    
    return '?' . http_build_query($params);
}

// Helper function to remove multiple parameters from the current URL
function removeParametersFromCurrentUrl($paramsToRemove) {
    $params = $_GET;
    
    foreach($paramsToRemove as $param) {
        unset($params[$param]);
    }
    
    return '?' . http_build_query($params);
}

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

// Apply location filter if set
if(isset($_GET['location']) && !empty($_GET['location'])) {
    $locationFilter = $_GET['location'];
    $locationFiltered = [];
    
    foreach($datas as $data) {
        // For "Online" filter
        if($locationFilter === "Online" && (empty($data['lokasiKelas']) || $data['lokasiKelas'] === "Online")) {
            $locationFiltered[] = $data;
        }
        // For specific locations
        else if($locationFilter !== "Online" && !empty($data['lokasiKelas']) && $data['lokasiKelas'] === $locationFilter) {
            $locationFiltered[] = $data;
        }
    }
    $datas = $locationFiltered;
}

// Apply price filter if set
if((isset($_GET['minPrice']) && $_GET['minPrice'] !== '') || (isset($_GET['maxPrice']) && $_GET['maxPrice'] !== '')) {
    $minPrice = isset($_GET['minPrice']) && $_GET['minPrice'] !== '' ? (int)$_GET['minPrice'] : 0;
    $maxPrice = isset($_GET['maxPrice']) && $_GET['maxPrice'] !== '' ? (int)$_GET['maxPrice'] : PHP_INT_MAX;
    
    $priceFiltered = [];
    foreach($datas as $data) {
        $price = (int)$data['hargaKelas'];
        if($price >= $minPrice && $price <= $maxPrice) {
            $priceFiltered[] = $data;
        }
    }
    $datas = $priceFiltered;
}

// Apply rating filter if set
if(isset($_GET['rating']) && $_GET['rating'] !== '') {
    $minRating = (float)$_GET['rating'];
    $ratingFiltered = [];
    
    foreach($datas as $data) {
        $syn = "SELECT * FROM class_rating_result WHERE classId = '" . $data['idKelas'] . "'";
        $hasils = query($syn);
        
        if(!empty($hasils)) {
            $hasil = $hasils[0];
            $avgRating = $hasil['totalRatingCount'] > 0 ? 
                $hasil['totalRatingSum'] / $hasil['totalRatingCount'] : 0;
                
            if($avgRating >= $minRating) {
                $ratingFiltered[] = $data;
            }
        } else if($minRating === 0) {
            // Include classes with no ratings if minimum rating is 0
            $ratingFiltered[] = $data;
        }
    }
    $datas = $ratingFiltered;
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
    <!-- Core theme CSS (includes Bootstrap)-->    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link href="../styles/styles.css" rel="stylesheet" />    <style>        
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
        
        /* Wishlist button styles */
        .wishlist-btn {
            opacity: 0.9;
            transition: all 0.2s ease-in-out;
        }
        
        .wishlist-btn:hover {
            opacity: 1;
            transform: scale(1.1);
        }
        
        .wishlist-btn .btn {
            transition: all 0.2s ease;
            background-color: white !important;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .wishlist-btn:hover .btn {
            background-color: white !important;
            box-shadow: 0 4px 8px rgba(0,0,0,0.15) !important;
        }
        
        /* Filter button styles */
        #filterButton {
            position: relative;
            transition: all 0.2s ease;
        }
        
        #filterButton.active::after {
            content: '';
            position: absolute;
            bottom: 3px;
            left: 50%;
            transform: translateX(-50%);
            width: 8px;
            height: 8px;
            background-color: #FFCC01;
            border-radius: 50%;
        }
        
        /* Filter modal custom styles */
        .bg-ouryellow {
            background-color: #FFCC01 !important;
        }
    </style>
</head>

<body class="montserratRegular">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    
    <div class="navbar w-100">
        <div class="container-fluid row px-5">
            <div class="container col-2 d-flex justify-content-center">
                <a href="homeLearner.php?id=<?=$id?>">
                    <img src="../images/skilltap brand.png" class="rounded-pill" style="width:200px;" alt="">
                </a>                
            </div>                
            
            <div class="container col-8 d-flex align-items-center justify-content-between rounded-4 py-3 gap-2">
                <form method="GET" action="homeLearner.php" class="input-group me-2 d-flex justify-content-between gap-2 w-100">
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <div class="input-group position-relative">
                        <span class="input-group-text bg-light border border-secondary border-end-0" style="border-radius: 0.375rem 0 0 0.375rem;">
                            <i class="bi bi-search text-secondary"></i>
                        </span>

                        <input type="text" class="form-control montserratRegular rounded-0 bg-light text-dark border border-secondary border-start-0 border-end-0" name="search" value="<?php echo $searchTerm; ?>" placeholder="Search Classes" aria-label="Search classes" style="box-shadow: inset 0 1px 3px rgba(0,0,0,0.2);">

                        <span class="input-group-text bg-dark border border-secondary border-start-0 py-1 px-2" style="border-radius: 0 0.375rem 0.375rem 0; cursor: pointer;" id="filterButton" data-bs-toggle="modal" data-bs-target="#filterModal">
                            <i class="bi bi-filter text-white" style="font-size: 1.5rem;"></i>
                        </span>
                    </div>
                </form>
            </div>            
            
            <div class="col-2 row justify-content-center align-items-center" style="color:black;">

                <!-- Wishlist Button -->
                <div class="col-auto">
                    <a href="wishlist.php?id=<?php echo $id; ?>">
                        <i class="bi bi-heart-fill text-dark" style="font-size: 30px;"></i>
                    </a>
                </div>

                <div class="col-auto">
                    <a href="orderListLearner.php?id=<?php echo $id; ?>">
                        <i class="bi bi-cart-fill text-dark" style="font-size: 30px;"></i>
                    </a>
                </div>

                <div class="col-auto">
                    <a href="logout.php" title="Logout">
                        <i class="bi bi-box-arrow-right text-dark" style="font-size: 30px;"></i>
                    </a>
                </div>
            </div>
            
        </div>
    </div>

        <!-- Breadcrumb Navigation -->
        <nav aria-label="breadcrumb" class="container-fluid px-5 py-3">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="homeLearner.php?id=<?=$id?>" class="text-decoration-none">Home</a></li>
                <?php if(!empty($searchTerm)): ?>
                    <li class="breadcrumb-item active" aria-current="page">Search: "<?php echo $searchTerm; ?>"</li>
                <?php else: ?>
                    <li class="breadcrumb-item active" aria-current="page">Browse Classes</li>
                <?php endif; ?>
                
                <?php if(isset($_GET['location']) && !empty($_GET['location'])): ?>
                    <li class="breadcrumb-item active" aria-current="page">Location: <?php echo htmlspecialchars($_GET['location']); ?></li>
                <?php endif; ?>
                
                <?php if((isset($_GET['minPrice']) && $_GET['minPrice'] !== '') || (isset($_GET['maxPrice']) && $_GET['maxPrice'] !== '')): ?>
                    <li class="breadcrumb-item active" aria-current="page">
                        Price: 
                        <?php if(isset($_GET['minPrice']) && $_GET['minPrice'] !== ''): ?>
                            Rp<?php echo number_format($_GET['minPrice']); ?>
                        <?php endif; ?>
                        <?php if(isset($_GET['minPrice']) && $_GET['minPrice'] !== '' && isset($_GET['maxPrice']) && $_GET['maxPrice'] !== ''): ?>
                            - 
                        <?php endif; ?>
                        <?php if(isset($_GET['maxPrice']) && $_GET['maxPrice'] !== ''): ?>
                            Rp<?php echo number_format($_GET['maxPrice']); ?>
                        <?php endif; ?>
                    </li>
                <?php endif; ?>
                
                <?php if(isset($_GET['rating']) && $_GET['rating'] !== ''): ?>
                    <li class="breadcrumb-item active" aria-current="page">Rating: <?php echo $_GET['rating']; ?>+ Stars</li>
                <?php endif; ?>
            </ol>
        </nav>
        
        <div class="container-fluid text-center montserratBold ">
            <h1 class="montserratBold">
                <?php if(!empty($searchTerm)): ?>
                    Search Results for: "<?php echo $searchTerm; ?>"
                <?php else: ?>
                    <!-- Discover Classes -->
                <?php endif; ?>
            </h1>
        </div>        
        
        <div class="container-fluid px-5">            
            <?php if(empty($datas) && !empty($searchTerm)): ?>
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
              <?php 
            // Display active filters if any are applied
            $hasFilters = isset($_GET['location']) || 
                         (isset($_GET['minPrice']) && $_GET['minPrice'] !== '') || 
                         (isset($_GET['maxPrice']) && $_GET['maxPrice'] !== '') || 
                         (isset($_GET['rating']) && $_GET['rating'] !== '');
                         
            if($hasFilters): 
            ?>
            <div class="bg-light p-3 rounded mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 montserratBold">Active Filters:</h5>
                    <a href="homeLearner.php?id=<?php echo $id; ?><?php echo !empty($searchTerm) ? '&search=' . urlencode($searchTerm) : ''; ?>" class="btn btn-sm btn-outline-secondary">
                        Clear All Filters
                    </a>
                </div>
                <div class="d-flex flex-wrap gap-2 mt-2">
                    <?php if(isset($_GET['location']) && !empty($_GET['location'])): ?>
                    <span class="badge bg-ouryellow text-dark p-2 d-flex align-items-center">
                        <i class="bi bi-geo-alt-fill me-1"></i>
                        Location: <?php echo htmlspecialchars($_GET['location']); ?>
                        <a href="<?php echo removeParameterFromCurrentUrl('location'); ?>" class="ms-2 text-dark text-decoration-none">
                            <i class="bi bi-x-circle-fill"></i>
                        </a>
                    </span>
                    <?php endif; ?>
                    
                    <?php if(isset($_GET['minPrice']) && $_GET['minPrice'] !== '' && isset($_GET['maxPrice']) && $_GET['maxPrice'] !== ''): ?>
                    <span class="badge bg-ouryellow text-dark p-2 d-flex align-items-center">
                        <i class="bi bi-cash-coin me-1"></i>
                        Price: Rp<?php echo number_format($_GET['minPrice']); ?> - Rp<?php echo number_format($_GET['maxPrice']); ?>
                        <a href="<?php echo removeParametersFromCurrentUrl(['minPrice', 'maxPrice']); ?>" class="ms-2 text-dark text-decoration-none">
                            <i class="bi bi-x-circle-fill"></i>
                        </a>
                    </span>
                    <?php elseif(isset($_GET['minPrice']) && $_GET['minPrice'] !== ''): ?>
                    <span class="badge bg-ouryellow text-dark p-2 d-flex align-items-center">
                        <i class="bi bi-cash-coin me-1"></i>
                        Min Price: Rp<?php echo number_format($_GET['minPrice']); ?>
                        <a href="<?php echo removeParameterFromCurrentUrl('minPrice'); ?>" class="ms-2 text-dark text-decoration-none">
                            <i class="bi bi-x-circle-fill"></i>
                        </a>
                    </span>
                    <?php elseif(isset($_GET['maxPrice']) && $_GET['maxPrice'] !== ''): ?>
                    <span class="badge bg-ouryellow text-dark p-2 d-flex align-items-center">
                        <i class="bi bi-cash-coin me-1"></i>
                        Max Price: Rp<?php echo number_format($_GET['maxPrice']); ?>
                        <a href="<?php echo removeParameterFromCurrentUrl('maxPrice'); ?>" class="ms-2 text-dark text-decoration-none">
                            <i class="bi bi-x-circle-fill"></i>
                        </a>
                    </span>
                    <?php endif; ?>
                    
                    <?php if(isset($_GET['rating']) && $_GET['rating'] !== ''): ?>
                    <span class="badge bg-ouryellow text-dark p-2 d-flex align-items-center">
                        <i class="bi bi-star-fill me-1"></i>
                        Min Rating: <?php echo $_GET['rating']; ?>+ Stars
                        <a href="<?php echo removeParameterFromCurrentUrl('rating'); ?>" class="ms-2 text-dark text-decoration-none">
                            <i class="bi bi-x-circle-fill"></i>
                        </a>
                    </span>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>            
            <div class="row p-3">                  
                <?php foreach ($datas as $key => $data) : ?>                    
                    <div class="col-md-4 mb-4 card-container search-result-item" style="animation-delay: <?php echo $key * 0.1; ?>s;">
                        <div class="card h-100 shadow-lg bg-white rounded">                              <!-- Wishlist button -->
                            <div class="wishlist-btn" style="position: absolute; top: 10px; right: 10px; z-index: 100;">
                                <a href="#" class="btn btn-white rounded-circle shadow-sm" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; background-color: white;" onclick="event.stopPropagation(); toggleWishlist(<?php echo $data['idKelas']; ?>, this);">
                                    <?php 
                                    // Check if this class is already in user's wishlist
                                    $checkWishlistQuery = "SELECT * FROM wishlist WHERE userId = '$id' AND classId = '" . $data['idKelas'] . "'";
                                    $isWishlisted = !empty(query($checkWishlistQuery));
                                    
                                    if($isWishlisted): 
                                    ?>
                                    <i class="bi bi-heart-fill" style="font-size: 20px; color: #FFCC01;"></i>
                                    <?php else: ?>
                                    <i class="bi bi-heart" style="font-size: 20px; color: #FFCC01;"></i>
                                    <?php endif; ?>
                                </a>
                            </div>
                            <img class="card-img-top" src="../images/<?php echo $data['fotoKelas']; ?>" alt="Card image cap" style="height: 200px; object-fit: cover;" onclick="window.location.href='classDetail.php?id=<?php echo $id; ?>&classId=<?php echo $data['idKelas']; ?>'">
                            <div class="card-body d-flex flex-column" onclick="window.location.href='classDetail.php?id=<?php echo $id; ?>&classId=<?php echo $data['idKelas']; ?>'">                            
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
                                <h5 class="card-title montserratBold d-flex align-items-center">
                                    <i class="bi bi-geo-alt-fill me-2" style="font-size: 1rem; color: #0275d8;"></i>
                                    <span class="montserratSemiBold" style="font-size: 0.8rem;">
                                        <?php echo !empty($data['lokasiKelas']) ? $data['lokasiKelas'] : 'Online'; ?>
                                    </span>
                                </h5>
                                <!-- Price, Rating and Tutor Info in a row -->
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <!-- Left side: Price and Rating -->
                                    <div class="flex-grow-1">
                                        <h5 class="card-text"><span class="montserratBold">Rp. <?php echo number_format($data['hargaKelas'], 0, ',', '.'); ?></span><?php if(isset($data['durasiKelas'])): ?>/<?= $data['durasiKelas']; ?><?php endif; ?></h5>
                                        
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
        <div class="container-fluid d-flex justify-content-center py-3">
            <div class="btn-group w-50 py-5">
                <button type="button" class="btn btn-outline-dark" style="font-size: 25px;" onclick="window.location.href='homeTutor.php?id=<?php echo $id; ?>'">
                    <h1>Tutor</h1>
                </button>
                <button type="button" class="btn btn-dark" style="font-size: 25px;" onclick="window.location.href='homeLearner.php?id=<?php echo $id; ?>'">
                    <h1>Learner</h1>
                </button>
            </div>
        </div>    </div>

    <!-- Filter Modal -->
    <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-ouryellow">
                    <h5 class="modal-title montserratBold" id="filterModalLabel">Filter Classes</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>                <div class="modal-body">
                    <form id="filterForm" method="GET" action="">
                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                        <!-- Preserve search term if exists -->
                        <?php if(!empty($searchTerm)): ?>
                        <input type="hidden" name="search" value="<?php echo $searchTerm; ?>">
                        <?php endif; ?>
                        
                        <!-- Location Filter -->
                        <div class="mb-4">
                            <h5 class="montserratBold">Location</h5>
                            <select class="form-select mb-3" name="location" id="locationSelect">
                                <option value="" selected>All Locations</option>
                                <option value="Online">Online Only</option>
                                <?php
                                // Get all unique locations from the classes
                                $uniqueLocations = [];
                                $allKelas = new Kelas();
                                $allClasses = $allKelas->getAllKelas();
                                
                                foreach ($allClasses as $class) {
                                    if (!empty($class['lokasiKelas']) && $class['lokasiKelas'] !== 'Online' && !in_array($class['lokasiKelas'], $uniqueLocations)) {
                                        $uniqueLocations[] = $class['lokasiKelas'];
                                    }
                                }
                                
                                // Sort locations alphabetically
                                sort($uniqueLocations);
                                
                                // Output options for each unique location
                                foreach ($uniqueLocations as $location) {
                                    $selected = (isset($_GET['location']) && $_GET['location'] === $location) ? 'selected' : '';
                                    echo '<option value="' . htmlspecialchars($location) . '" ' . $selected . '>' . htmlspecialchars($location) . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        
                        <!-- Price Range Filter -->
                        <div class="mb-4">
                            <h5 class="montserratBold">Price Range</h5>
                            <div class="d-flex align-items-center gap-2">
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" class="form-control" name="minPrice" id="minPrice" placeholder="Min" min="0">
                                </div>
                                <span class="mx-2">-</span>
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" class="form-control" name="maxPrice" id="maxPrice" placeholder="Max" min="0">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Rating Filter -->
                        <div class="mb-4">
                            <h5 class="montserratBold">Minimum Rating</h5>
                            <select class="form-select" name="rating" id="rating">
                                <option value="" selected>Any Rating</option>
                                <option value="4">4+ Stars</option>
                                <option value="3">3+ Stars</option>
                                <option value="2">2+ Stars</option>
                                <option value="1">1+ Stars</option>
                            </select>
                        </div>
                    </form>
                </div>                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-outline-dark me-2" id="clearFiltersBtn">Clear Filters</button>
                    <button type="button" class="btn btn-primary bg-ouryellow border-dark" onclick="document.getElementById('filterForm').submit();">Apply Filters</button>
                </div>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script>

// Update the clear filters button event listener in the modal
document.addEventListener('DOMContentLoaded', function() {
    const clearFiltersBtn = document.getElementById('clearFiltersBtn');
    if (clearFiltersBtn) {
        clearFiltersBtn.addEventListener('click', function() {
            // Reset all filter fields in the modal for visual feedback
            document.getElementById('locationSelect').value = '';
            document.getElementById('minPrice').value = '';
            document.getElementById('maxPrice').value = '';
            document.getElementById('rating').value = '';
            
            // Get the current URL and preserve only the id parameter and search term
            const currentUrl = new URL(window.location.href);
            const id = currentUrl.searchParams.get('id');
            const search = currentUrl.searchParams.get('search');
            
            // Create a new URL with only the id parameter
            let newUrl = `${currentUrl.pathname}?id=${id}`;
            
            // Add search parameter if it exists
            if (search) {
                newUrl += `&search=${encodeURIComponent(search)}`;
            }
            
            // Redirect to the new URL
            window.location.href = newUrl;
        });
    }
});
// Add event listener for Enter key in the filter modal
document.addEventListener('DOMContentLoaded', function() {
    // Get reference to the filter form
    const filterForm = document.getElementById('filterForm');
    
    if (filterForm) {
        // Add keypress event listener to the form
        filterForm.addEventListener('keypress', function(event) {
            // Check if Enter key was pressed (key code 13)
            if (event.key === 'Enter') {
                // Prevent default form submission
                event.preventDefault();
                
                // Manually submit the form (same as clicking Apply Filters button)
                document.getElementById('filterForm').submit();
            }
        });
    }
});

    // Wait for the document to be fully loaded
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
        
        // Set filter values from URL parameters if they exist
        const urlParams = new URLSearchParams(window.location.search);
        
        // Location filter
        if(urlParams.has('location')) {
            const locationValue = urlParams.get('location');
            document.getElementById('locationSelect').value = locationValue;
        }
        
        // Price range filters
        if(urlParams.has('minPrice')) {
            document.getElementById('minPrice').value = urlParams.get('minPrice');
        }
        
        if(urlParams.has('maxPrice')) {
            document.getElementById('maxPrice').value = urlParams.get('maxPrice');
        }
        
        // Rating filter
        if(urlParams.has('rating')) {
            document.getElementById('rating').value = urlParams.get('rating');
        }
        
        // Initialize filter button with active state if filters are applied
        const filterButton = document.getElementById('filterButton');
        const filterIcon = document.getElementById('filterIcon');
        if(urlParams.has('location') || urlParams.has('minPrice') || urlParams.has('maxPrice') || urlParams.has('rating')) {
            filterButton.classList.add('active');
            filterIcon.classList.remove('bi-filter');
            filterIcon.classList.add('bi-filter-fill');
        }
        
        // Add event listener to clear filters button
        const clearFiltersBtn = document.getElementById('clearFiltersBtn');
        if (clearFiltersBtn) {
            clearFiltersBtn.addEventListener('click', function() {
                // Reset all filter fields
                document.getElementById('locationSelect').value = '';
                document.getElementById('minPrice').value = '';
                document.getElementById('maxPrice').value = '';
                document.getElementById('rating').value = '';
                
                // Get the current URL and preserve only the id parameter
                const currentUrl = new URL(window.location.href);
                const id = currentUrl.searchParams.get('id');
                const search = currentUrl.searchParams.get('search');
                
                // Create a new URL with only the id parameter
                let newUrl = `${currentUrl.pathname}?id=${id}`;
                
                // Add search parameter if it exists
                if (search) {
                    newUrl += `&search=${search}`;
                }
                
                // Redirect to the new URL
                window.location.href = newUrl;
            });
        }

        // Handle the Apply Filters button click with multiple filter support
        const applyFiltersBtn = document.querySelector('button[onclick="document.getElementById(\'filterForm\').submit();"]');
        if (applyFiltersBtn) {
            applyFiltersBtn.onclick = function(event) {
                event.preventDefault();
                
                // Create an object to store filter values
                const filters = {};
                
                // Always include id parameter
                filters.id = urlParams.get('id');
                
                // Include search parameter if it exists
                if(urlParams.has('search')) {
                    filters.search = urlParams.get('search');
                }
                
                // Get location filter
                const location = document.getElementById('locationSelect').value;
                if(location) {
                    filters.location = location;
                }
                
                // Get price range filters
                const minPrice = document.getElementById('minPrice').value;
                const maxPrice = document.getElementById('maxPrice').value;
                if(minPrice) {
                    filters.minPrice = minPrice;
                }
                if(maxPrice) {
                    filters.maxPrice = maxPrice;
                }
                
                // Get rating filter
                const rating = document.getElementById('rating').value;
                if(rating) {
                    filters.rating = rating;
                }
                
                // Build the query string
                const queryString = new URLSearchParams(filters).toString();
                  // Redirect to the filtered URL
                window.location.href = `homeLearner.php?${queryString}`;
            };
        }    });
</script>

<script>
    // Function to toggle wishlist status
    function toggleWishlist(classId, buttonElement) {
        // Get the current user ID from the URL parameter
        const urlParams = new URLSearchParams(window.location.search);
        const userId = urlParams.get('id');
        
        // Get the heart icon
        const heartIcon = buttonElement.querySelector('i.bi');
        
        // Create form data for the request
        const formData = new FormData();
        formData.append('userId', userId);
        formData.append('classId', classId);
        
        // Send AJAX request to add/remove from wishlist
        fetch('addToWishlist.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {            if (data.success) {
                // Update the heart icon based on the action
                if (data.action === 'added') {
                    heartIcon.classList.replace('bi-heart', 'bi-heart-fill');
                    
                    // Show success toast
                    showToast('Class added to wishlist!', 'success');
                } else if (data.action === 'removed') {
                    heartIcon.classList.replace('bi-heart-fill', 'bi-heart');
                    
                    // Show info toast
                    showToast('Class removed from wishlist', 'info');
                    
                    // Show info toast
                    // showToast('Class removed from wishlist', 'info');
                }
            } else {
                // Show error toast
                showToast('Error: ' + data.message, 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('An error occurred. Please try again.', 'danger');
        });
    }
    
    // Function to show toast notification
    function showToast(message, type = 'info') {
        // Create toast container if it doesn't exist
        let toastContainer = document.getElementById('toast-container');
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.id = 'toast-container';
            toastContainer.className = 'position-fixed bottom-0 end-0 p-3';
            toastContainer.style.zIndex = '11';
            document.body.appendChild(toastContainer);
        }
        
        // Create a unique ID for this toast
        const toastId = 'toast-' + Date.now();
        
        // Create toast element
        const toast = document.createElement('div');
        toast.className = `toast align-items-center text-white bg-${type} border-0`;
        toast.id = toastId;
        toast.setAttribute('role', 'alert');
        toast.setAttribute('aria-live', 'assertive');
        toast.setAttribute('aria-atomic', 'true');
        
        // Create toast content
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        `;
        
        // Add the toast to the container
        toastContainer.appendChild(toast);
        
        // Initialize the toast
        const bsToast = new bootstrap.Toast(toast, {
            animation: true,
            autohide: true,
            delay: 3000
        });
        
        // Show the toast
        bsToast.show();
        
        // Remove the toast after it's hidden
        toast.addEventListener('hidden.bs.toast', function() {
            toast.remove();
        });
    }
</script>
</html>