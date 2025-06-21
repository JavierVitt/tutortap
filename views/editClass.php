<?php
require_once "../functions.php";

// Check if user is logged in
session_start();
if (!isset($_GET['id'])) {
	// Redirect to login page if not logged in
	echo "<script>document.location.href = 'login.php'</script>";
	exit;
}

$userId = $_GET['id'];
$isEditing = isset($_GET['classId']);
$classData = [];
$id = $userId;

// If we're editing, get the class data
if ($isEditing) {
	$classId = $_GET['classId'];
	$kelas = new Kelas();
	$classData = $kelas->getKelasById($classId);

	// Check if class exists and belongs to this user
	if (empty($classData) || $classData[0]['userId'] != $userId) {
		echo "<script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'You do not have permission to edit this class!',
                    footer: '<a href=\"homeTutor.php?id=$userId\">Return to home</a>'
                }).then((result) => {
                    window.location.href = 'homeTutor.php?id=$userId';
                });
            });
        </script>";
		exit;
	}

	// Extract the data for easier access
	$classData = $classData[0];
}

// Function to upload class image
function uploadClassImage()
{
	$targetDir = "../images/";
	$fileName = basename($_FILES["classImage"]["name"]);
	$imageFileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

	// Generate unique filename
	$newFileName = uniqid() . '.' . $imageFileType;
	$targetFilePath = $targetDir . $newFileName;

	// Check if file is an actual image
	$check = getimagesize($_FILES["classImage"]["tmp_name"]);
	if ($check === false) {
		return false;
	}
	// Check file size (limit to 5MB)
	if ($_FILES["classImage"]["size"] > 5000000) {
		return false;
	}

	// Allow certain file formats
	if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" && $imageFileType != "webp") {
		return false;
	}

	// Upload file
	if (move_uploaded_file($_FILES["classImage"]["tmp_name"], $targetFilePath)) {
		return $newFileName;
	} else {
		return false;
	}
}

// Process form submission
$successMessage = "";
$errorMessage = "";

// Handle activation/deactivation requests
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['toggleStatus']) && $isEditing) {
	global $conn;
	$newStatus = ($classData['statusKelas'] == 1) ? 0 : 1; // Toggle the status

	$query = "UPDATE kelas SET statusKelas = ? WHERE idKelas = ?";
	$stmt = mysqli_prepare($conn, $query);
	mysqli_stmt_bind_param($stmt, "ii", $newStatus, $classId);

	if (mysqli_stmt_execute($stmt)) {
		$statusAction = ($newStatus == 1) ? "activated" : "deactivated";
		$successMessage = "Class has been $statusAction successfully!";

		// Update the class data to reflect the new status
		$classData['statusKelas'] = $newStatus;

		// Redirect to homeTutor.php after 3 seconds
		echo "<script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Your class has been $statusAction successfully.',
                    footer: '<a href=\"homeTutor.php?id=$userId\">Go to your home page</a>'
                });
            });
        </script>";
		header("refresh:3;url=homeTutor.php?id=$userId");
	} else {
		$errorMessage = "Error updating class status: " . mysqli_error($conn);
	}
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['saveClass'])) {
	// Validate input
	$classTitle = htmlspecialchars($_POST['classTitle']);
	$classPrice = (int)$_POST['classPrice'];
	$classDuration = $_POST['classDuration'];
	$classDescription = htmlspecialchars($_POST['classDescription']);
	$lokasiKelas = htmlspecialchars($_POST['lokasiKelas']);

	// Validate required fields
	if (empty($classTitle) || empty($classPrice) || empty($classDescription) || empty($lokasiKelas)) {
		$errorMessage = "All fields are required!";
	} else {
		global $conn;
		$classImage = null;

		// Process image upload if a new image was provided
		if (isset($_FILES["classImage"]) && $_FILES["classImage"]["error"] == 0) {
			$classImage = uploadClassImage();
			if (!$classImage) {
				$errorMessage = "Failed to upload image. Please ensure it's a valid image file (JPG, PNG, JPEG, GIF, WEBP) and under 5MB.";
			}
		}
		// If we're editing and no image was uploaded, keep the existing image
		if ($isEditing && (empty($classImage) || $_FILES["classImage"]["error"] == 4)) {
			$classImage = $classData['fotoKelas'];
		}

		// Status 1 means the class is active/approved
		$statusKelas = $isEditing ? $classData['statusKelas'] : 1;

		if (empty($errorMessage)) {
			if ($isEditing) {
				// Update existing class
				$query = "UPDATE kelas SET 
                          namaKelas = ?, 
                          hargaKelas = ?, 
                          durasiKelas = ?, 
                          statusKelas = ?, 
                          deskripsiKelas = ?, 
                          fotoKelas = ? 
                          WHERE idKelas = ?";

				$stmt = mysqli_prepare($conn, $query);
				mysqli_stmt_bind_param($stmt, "sisissi", $classTitle, $classPrice, $classDuration, $statusKelas, $classDescription, $classImage, $classId);
			} else {
				// Insert new class
				$query = "INSERT INTO kelas (userId, namaKelas, hargaKelas, durasiKelas, statusKelas, deskripsiKelas, fotoKelas) 
                          VALUES (?, ?, ?, ?, ?, ?, ?)";

				$stmt = mysqli_prepare($conn, $query);
				mysqli_stmt_bind_param($stmt, "isisiss", $userId, $classTitle, $classPrice, $classDuration, $statusKelas, $classDescription, $classImage);
			}

			if (mysqli_stmt_execute($stmt)) {
				$successMessage = $isEditing ? "Class updated successfully!" : "Class added successfully!";
				// Redirect to tutor home page after successful class creation/update
				echo "<script>
                    document.addEventListener('DOMContentLoaded', function () {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: '" . ($isEditing ? 'Your class has been updated successfully.' : 'Your class has been added successfully.') . "',
                            footer: '<a href=\"homeTutor.php?id=$userId\">Go to your home page</a>'
                        }).then((result) => {
                            window.location.href = 'homeTutor.php?id=$userId';
                        });
                    });
                </script>";
			} else {
				$errorMessage = ($isEditing ? "Error updating class: " : "Error adding class: ") . mysqli_error($conn);
			}
		}
	}
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<!-- The above meta tags *must* come first in the head; any other head content must come *after* these tags -->

	<title><?php echo $isEditing ? 'Edit Class: ' . htmlspecialchars($classData['namaKelas']) : 'Add Class'; ?> - TutorTap</title>

	<!-- Favicon-->
	<link rel="icon" type="image/x-icon" href="assets/favicon.ico" />

	<!-- Google font -->
	<link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet">

	<!-- Bootstrap -->
	<!-- <link type="text/css" rel="stylesheet" href="css/bootstrap.min.css" /> -->

	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

	<!-- Bootstrap icons-->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

	<!-- Custom stlylesheet -->
	<link type="text/css" rel="stylesheet" href="css/style.css" />

	<!-- Core theme CSS (includes Bootstrap)-->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
	<link href="../styles/styles.css" rel="stylesheet" />
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

	<!-- SweetAlert2 for nice alerts -->
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
		  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->

	<style>
		.section {
			position: relative;
			min-height: 100vh;
			height: auto;
			padding: 50px 0;
		}

		.navbar h1 {
			margin: 0;
			color: #fff;
			font-weight: bold;
		}

		.btn-icon {
			display: flex;
			justify-content: center;
			align-items: center;
		}

		.btn-icon i {
			transition: transform 0.2s;
		}

		.btn-icon:hover i {
			transform: scale(1.2);
		}

		.section .section-center {
			position: relative;
			width: 100%;
			margin: 20px auto;
		}

		#booking {
			font-family: 'Montserrat', sans-serif;
			background-color: #ffffff;
			background-size: cover;
			background-position: center;
			overflow-x: hidden;
			min-height: 100vh;
			padding: 1rem 0 4rem 0;
		}

		#booking::before {
			content: '';
			position: fixed;
			left: 0;
			right: 0;
			bottom: 0;
			top: 0;
			background: rgba(255, 255, 255, 0.6);
			z-index: -1;
		}

		.booking-form {
			width: 100%;
			margin: 0;
			background-color: #101113;
			padding: 30px 20px;
			border-radius: 8px;
			margin-bottom: 20px;
		}

		.booking-form .form-header {
			text-align: center;
			margin-bottom: 25px;
		}

		.booking-form .form-header h1 {
			font-size: 42px;
			text-transform: uppercase;
			font-weight: 700;
			color: #ffc001;
			margin: 0px;
		}

		.booking-form>form {
			background-color: #101113;
			padding: 30px 20px;
			border-radius: 8px;
			margin-bottom: 20px;
		}

		.booking-form .form-group {
			position: relative;
			margin-bottom: 15px;
		}

		.booking-form .form-control {
			background-color: #ffffff;
			border: none;
			height: auto;
			border-radius: 3px;
			-webkit-box-shadow: none;
			box-shadow: none;
			font-weight: 400;
			color: #101113;
		}

		.booking-form .form-control::-webkit-input-placeholder {
			color: rgba(16, 17, 19, 0.3);
		}

		.booking-form .form-control:-ms-input-placeholder {
			color: rgba(16, 17, 19, 0.3);
		}

		.booking-form .form-control::placeholder {
			color: rgba(16, 17, 19, 0.3);
		}

		.booking-form input[type="date"].form-control:invalid {
			color: rgba(16, 17, 19, 0.3);
		}

		.booking-form select.form-control {
			-webkit-appearance: none;
			-moz-appearance: none;
			appearance: none;
		}

		.booking-form select.form-control+.select-arrow {
			position: absolute;
			right: 0px;
			bottom: 6px;
			width: 32px;
			line-height: 32px;
			height: 32px;
			text-align: center;
			pointer-events: none;
			color: #101113;
			font-size: 14px;
		}

		.booking-form select.form-control+.select-arrow:after {
			content: '\279C';
			display: block;
			-webkit-transform: rotate(90deg);
			transform: rotate(90deg);
		}

		.booking-form .form-label {
			color: #fff;
			font-size: 12px;
			font-weight: 400;
			margin-bottom: 5px;
			display: block;
			text-transform: uppercase;
		}

		.booking-form .submit-btn {
			color: #101113;
			background-color: #ffc001;
			font-weight: 700;
			height: 50px;
			border: none;
			width: 100%;
			display: block;
			border-radius: 3px;
			text-transform: uppercase;
		}

		.bg-ouryellow {
			background-color: #FFCC01;
		}

		.class-image-preview {
			position: relative;
			margin-bottom: 20px;
			border-radius: 10px;
			overflow: hidden;
			box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
		}

		.class-image-preview img {
			width: 100%;
			height: auto;
			object-fit: cover;
			display: block;
		}

		.form-control:focus {
			border-color: #FFCC01;
			box-shadow: 0 0 0 0.25rem rgba(255, 204, 1, 0.25);
		}

		.badge {
			font-family: 'Montserrat', sans-serif;
			font-weight: 600;
			letter-spacing: 0.5px;
		}

		.form-header h1 {
			text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
		}

		body {
			overflow-x: hidden;
		}

		#booking {
			overflow-y: auto;
			padding-bottom: 50px;
			margin-bottom: 30px;
		}

		.preview-container {
			background-color: #212529;
			border-radius: 8px;
			padding: 20px;
			height: 100%;
			display: flex;
			flex-direction: column;
			justify-content: center;
		}

		.card-container {
			position: relative;
			transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
			cursor: pointer;
			transform-origin: center;
		}

		.card-container:hover .card {
			opacity: 1;
			transform: scale(1.05);
			box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1) !important;
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
		}

		.card {
			transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
		}

		.card-preview {
			max-width: 320px;
			margin: 0 auto;
			transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
			transform: scale(0.95);
		}

		/* FIXED SIDE-BY-SIDE LAYOUT */
		@media (min-width: 992px) {
			.container-fluid {
				max-width: 1400px;
				margin: 0 auto;
			}

			.side-by-side {
				display: flex !important;
				flex-wrap: nowrap !important;
				gap: 20px;
			}

			.form-column {
				flex: 0 0 58% !important;
				max-width: 58% !important;
			}

			.preview-column {
				flex: 0 0 38% !important;
				max-width: 38% !important;
			}

			.preview-sticky {
				position: sticky;
				top: 20px;
				max-height: calc(100vh - 40px);
				overflow-y: auto;
			}

			.booking-form {
				height: auto;
			}
		}

		/* Mobile responsiveness */
		@media (max-width: 991px) {
			.booking-form {
				max-width: 100%;
				width: 100%;
				margin: 0 auto 20px;
			}

			.booking-form .form-header h1 {
				font-size: 32px;
			}

			#booking {
				padding-top: 20px;
			}

			.section {
				padding: 20px 0;
			}

			.preview-container {
				margin-top: 30px !important;
				margin-bottom: 30px;
				padding: 15px;
			}

			.card-preview {
				max-width: 280px;
			}

			.side-by-side {
				display: block !important;
			}

			.form-column,
			.preview-column {
				width: 100% !important;
				max-width: 100% !important;
				flex: 0 0 100% !important;
				padding: 0 10px;
			}
		}

		@media (max-width: 767px) {
			.booking-form {
				padding: 0 8px;
			}

			.booking-form .form-header h1 {
				font-size: 28px;
			}

			.booking-form>form {
				padding: 20px 15px;
			}

			#booking {
				padding: 10px 0 30px;
			}

			.section {
				padding: 10px 0;
			}
		}

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
	</style>
</head>

<body>

	<body class="montserratRegular">
		<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

		<div class="navbar w-100">
			<div class="container-fluid row pe-4">
				<div class="col-auto float-start ps-0">
					<a href="homeTutor.php?id=<?= $id ?>">
						<img src="../images/skilltap brand.png" class="rounded-pill" style="width:200px;" alt="">
					</a>
				</div>

				<div class="col-2 row justify-content-center align-items-center" style="color:black;">

					<!-- Wishlist Button -->
					<div class="col-auto">
						<a href="wishlist.php?id=<?php echo $id; ?>">
							<i class="bi bi-heart-fill text-dark" style="font-size: 30px;"></i>
						</a>
					</div>

					<div class="col-auto">
						<a href="orderListTutor.php?id=<?php echo $id; ?>">
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
	</body>

	

	<div id="booking" class="section">
		<nav aria-label="breadcrumb" class="container-fluid px-5 mt-3">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="">Home</a></li>
				<li class="breadcrumb-item" aria-current="page"><a href="#">Tutor Dashboard</a></li>
				<li class="breadcrumb-item active" aria-current="page">Edit Class</li>
			</ol>
		</nav>
		<div class="section-center">
			<div class="container-fluid px-2 px-md-4">
				
				<div class="row justify-content-center">
					<div class="col-12">
						<div class="form-header text-center mb-4">
							<h1><?php echo $isEditing ? 'Edit Class' : 'Add Class'; ?></h1>
							<?php if ($isEditing): ?>
								<h4 class="text-white mt-3"><?php echo htmlspecialchars($classData['namaKelas']); ?></h4>
							<?php endif; ?>
						</div>

						<?php if (!empty($errorMessage)): ?>
							<div class="alert alert-danger" role="alert">
								<?php echo $errorMessage; ?>
							</div>
						<?php endif; ?>

						<?php if (!empty($successMessage)): ?>
							<div class="alert alert-success" role="alert">
								<?php echo $successMessage; ?>
							</div>
						<?php endif; ?>
					</div>
				</div>

				<div class="row side-by-side mx-0 mt-0">
					<!-- Form Column (Left Side) -->
					<div class="form-column">
						<div class="booking-form" style="margin-top:0;">
							<form method="POST" action="" enctype="multipart/form-data">
								<!-- <?php if ($isEditing): ?>
								<div class="text-center mb-4">
									<div style="max-height: 200px; overflow: hidden; border-radius: 8px; margin: 0 auto; max-width: 80%;">
										<img src="../images/<?php echo $classData['fotoKelas']; ?>" alt="Current Class Image" 
											 style="width: 100%; object-fit: cover;" class="img-fluid">
									</div>
								</div>
								<?php endif; ?> -->

								<div class="form-group">
									<span class="form-label">Title</span>
									<input class="form-control" type="text" name="classTitle" placeholder="Add your Class Title Here"
										value="<?php echo $isEditing ? htmlspecialchars($classData['namaKelas']) : ''; ?>" required>
								</div>
								<div class="form-group">
									<span class="form-label"><?php echo $isEditing ? 'Change Image (Optional)' : 'Image'; ?></span>
									<input class="form-control" type="file" name="classImage" accept="image/jpeg,image/png,image/gif,image/webp" <?php echo $isEditing ? '' : 'required'; ?>>
									<?php if ($isEditing): ?>
										<!-- <small class="form-text text-white">Leave empty to keep the current image</small> -->
									<?php endif; ?>
									<small class="form-text text-white">Supported formats: JPG, PNG, JPEG, GIF, WEBP (max 5MB)</small>
								</div>
								<div class="row">
									<div class="col-sm-6">
										<div class="form-group">
											<span class="form-label">Price</span>
											<input class="form-control" type="number" name="classPrice" placeholder="Input Price here (Rp)" min="0"
												value="<?php echo $isEditing ? $classData['hargaKelas'] : ''; ?>" required>
										</div>
									</div>
									<div class="col-sm-6">
										<div class="form-group">
											<span class="form-label">Hour</span>
											<select class="form-control" name="classDuration" required>
												<?php
												$durations = ['hour', 'session'];
												foreach ($durations as $duration) {
													$selected = $isEditing && $classData['durasiKelas'] == $duration ? 'selected' : '';
													echo "<option value=\"$duration\" $selected>$duration</option>";
												}
												?>
											</select>
											<span class="select-arrow"></span>
										</div>
									</div>
								</div>
								<div class="form-group">
									<span class="form-label">Description</span>
									<textarea class="form-control" name="classDescription" placeholder="Describe your class!" rows="4" required><?php echo $isEditing ? htmlspecialchars($classData['deskripsiKelas']) : ''; ?></textarea>
								</div>
								<div class="form-group">
									<span class="form-label">Location</span>
									<input class="form-control" type="text" name="lokasiKelas" placeholder="Set Location"
										value="<?php echo $isEditing ? htmlspecialchars($classData['lokasiKelas']) : (isset($_POST['lokasiKelas']) ? htmlspecialchars($_POST['lokasiKelas']) : ''); ?>" required>
									<!-- <small class="form-text text-white">Note: This location will be displayed on the form but is not currently stored in the database.</small> -->
								</div>
								<?php if ($isEditing): ?>
									<div class="form-group">
										<span class="form-label">Class Status</span>
										<!-- <div class="p-2 text-center">
										<span class="badge bg-<?php echo $classData['statusKelas'] == 1 ? 'success' : 'warning'; ?> p-2" style="font-size: 14px;">
											<?php echo $classData['statusKelas'] == 1 ? 'Active' : 'Inactive'; ?>
										</span>
									</div> -->
										<div class="mt-2">
											<form method="POST" action="">
												<button type="submit" name="toggleStatus" class="btn btn-<?php echo $classData['statusKelas'] == 1 ? 'warning' : 'success'; ?> w-100">
													<i class="bi bi-<?php echo $classData['statusKelas'] == 1 ? 'pause-circle' : 'play-circle'; ?>"></i>
													<?php echo $classData['statusKelas'] == 1 ? 'Deactivate Class' : 'Activate Class'; ?>
												</button>
											</form>
											<small class="form-text text-white mt-2">
												<?php if ($classData['statusKelas'] == 1): ?>
													Deactivating will temporarily hide this class from learners.
												<?php else: ?>
													Activating will make this class visible to learners again.
												<?php endif; ?>
											</small>
										</div>
									</div>
								<?php endif; ?>
								<div class="form-btn">
									<div class="row gx-2">
										<div class="col-12 mb-3">
											<button type="submit" name="saveClass" class="submit-btn"><?php echo $isEditing ? 'Update Class' : 'Create Class'; ?></button>
										</div>
										<?php if ($isEditing): ?>
											<div class="col-md-6 col-12 mb-3">
												<a href="deleteClass.php?id=<?php echo $userId; ?>&classId=<?php echo $classId; ?>"
													class="btn btn-danger w-100"
													onclick="return confirm('Are you sure you want to delete this class? This action cannot be undone.');">
													<i class="bi bi-trash-fill"></i> Delete Class
												</a>
											</div>
											<div class="col-md-6 col-12 mb-3">
												<a href="homeTutor.php?id=<?php echo $userId; ?>" class="btn btn-outline-light w-100">
													<i class="bi bi-x-circle"></i> Cancel
												</a>
											</div>
										<?php endif; ?>
									</div>
								</div>
							</form>
						</div>
					</div> <!-- Preview Column (Right Side) -->
					<div class="preview-column">
						<div class="preview-container preview-sticky" style="margin-top:0 !important; height: auto;">
							<h5 class="text-center mb-3 montserratBold text-white">Class Card Preview</h5>
							<div class="card-container">
								<div class="card h-100 shadow-lg bg-white rounded card-preview">
									<img class="card-img-top" src="../images/<?php echo $isEditing ? $classData['fotoKelas'] : 'class-placeholder.jpg'; ?>" alt="Class image" style="height: 200px; object-fit: cover;">
									<div class="card-body d-flex flex-column">
										<h5 class="card-title montserratBold"><?php echo $isEditing ? htmlspecialchars($classData['namaKelas']) : 'Your Class Title'; ?></h5>

										<!-- Price, Rating and Tutor Info in a row -->
										<div class="d-flex justify-content-between align-items-center mb-3">
											<!-- Left side: Price and Rating -->
											<div class="flex-grow-1">
												<h5 class="card-text">Rp.<?php echo $isEditing ? $classData['hargaKelas'] : '0'; ?>/<?php echo $isEditing ? $classData['durasiKelas'] : 'hour'; ?></h5>

												<!-- Class rating -->
												<div class="card-text" style="display: flex; align-items: center;">
													<i class="bi bi-star-fill" style="font-size: 20px; color: #FFCC01;"></i>
													<?php if ($isEditing): ?>
														<?php
														// Check if class has ratings
														$syn = "SELECT * FROM class_rating_result WHERE classId = '" . $classData['idKelas'] . "'";
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
													<?php else: ?>
														<span style="margin-left: 10px;">No ratings yet</span>
													<?php endif; ?>
												</div>
											</div>

											<!-- Right side: Tutor information -->
											<?php
											// Get tutor information
											$tutor = User::getUserById($userId);
											$tutorProfilePic = !empty($tutor['profilePicture']) ? $tutor['profilePicture'] : "javier.png";
											?>
											<div class="d-flex align-items-center ms-2">
												<img src="../images/<?php echo $tutorProfilePic; ?>"
													alt="Tutor profile image"
													class="img-fluid rounded-circle border border-dark border-0"
													style="width: 40px; height: 40px; object-fit: cover;">
												<div class="ms-2">
													<p class="mb-0 montserratBold" style="font-size: 0.9rem;"><?php echo $tutor['nama']; ?></p>
												</div>
											</div>
										</div>

										<p class="card-text flex-grow-1">
											<?php
											if ($isEditing) {
												// Truncate description if it's too long - just like in homeLearner.php
												$description = htmlspecialchars($classData['deskripsiKelas']);
												if (strlen($description) > 150) {
													echo substr($description, 0, 150) . '...';
												} else {
													echo $description;
												}
											} else {
												echo 'Your class description will appear here. If it exceeds 150 characters, it will be trimmed with an ellipsis...';
											}
											?>
										</p>

										<?php if ($isEditing): ?>
											<!-- <div class="d-flex justify-content-end">
											<span class="badge bg-<?php echo $classData['statusKelas'] == 1 ? 'success' : 'warning'; ?>">
												<?php echo $classData['statusKelas'] == 1 ? 'Active' : 'Inactive'; ?>
											</span>
										</div> -->
										<?php else: ?>
											<div class="d-flex justify-content-end">
												<span class="badge bg-success">Preview</span>
											</div>
										<?php endif; ?>
									</div>
								</div>
							</div>
							<div class="text-center mt-3">
								<small class="text-white-50">This is how your class will appear on your profile</small>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script>
		// Real-time preview update functionality
		document.addEventListener('DOMContentLoaded', function() {
			// Only run this if we're in edit mode and the preview exists
			if (document.querySelector('.card-preview')) {
				const titleInput = document.querySelector('input[name="classTitle"]');
				const priceInput = document.querySelector('input[name="classPrice"]');
				const durationSelect = document.querySelector('select[name="classDuration"]');
				const descriptionTextarea = document.querySelector('textarea[name="classDescription"]');
				const imageInput = document.querySelector('input[name="classImage"]');

				const previewTitle = document.querySelector('.card-preview .card-title');
				const previewPrice = document.querySelector('.card-preview h5.card-text');
				const previewDescription = document.querySelector('.card-preview p.card-text.flex-grow-1');
				const previewImage = document.querySelector('.card-preview .card-img-top');

				// Update title
				if (titleInput && previewTitle) {
					titleInput.addEventListener('input', function() {
						previewTitle.textContent = this.value;
					});
				}

				// Update price and duration
				if (priceInput && durationSelect && previewPrice) {
					const updatePrice = function() {
						previewPrice.textContent = 'Rp.' + priceInput.value + '/' + durationSelect.value;
					};

					priceInput.addEventListener('input', updatePrice);
					durationSelect.addEventListener('change', updatePrice);
				}

				// Update description
				if (descriptionTextarea && previewDescription) {
					descriptionTextarea.addEventListener('input', function() {
						// Truncate description if it's too long - just like in homeLearner.php
						const description = this.value;
						if (description.length > 150) {
							previewDescription.textContent = description.substring(0, 150) + '...';
						} else {
							previewDescription.textContent = description;
						}
					});
				}

				// Update image (preview)
				if (imageInput && previewImage) {
					imageInput.addEventListener('change', function() {
						if (this.files && this.files[0]) {
							const reader = new FileReader();
							reader.onload = function(e) {
								previewImage.src = e.target.result;
							};
							reader.readAsDataURL(this.files[0]);
						}
					});
				}
			}
		});
	</script>