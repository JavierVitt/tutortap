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

// Function to upload class image
function uploadClassImage() {
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
        // Process image upload
        if (isset($_FILES["classImage"]) && $_FILES["classImage"]["error"] == 0) {
            $classImage = uploadClassImage();
            if ($classImage) {
                // Insert class into database
                global $conn;
                
                // Status 1 means the class is active/approved
                $statusKelas = 1;
                  $query = "INSERT INTO kelas (userId, namaKelas, hargaKelas, durasiKelas, statusKelas, deskripsiKelas, fotoKelas, lokasiKelas) 
                          VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                          
                $stmt = mysqli_prepare($conn, $query);
                mysqli_stmt_bind_param($stmt, "isisisss", $userId, $classTitle, $classPrice, $classDuration, $statusKelas, $classDescription, $classImage, $lokasiKelas);
                
                if (mysqli_stmt_execute($stmt)) {
                    $successMessage = "Class added successfully!";
                    // Redirect to tutor home page after successful class creation
                    echo "<script>
                        document.addEventListener('DOMContentLoaded', function () {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: 'Your class has been added successfully.',
                                footer: '<a href=\"homeTutor.php?id=$userId\">Go to your home page</a>'
                            }).then((result) => {
                                window.location.href = 'homeTutor.php?id=$userId';
                            });
                        });
                    </script>";
                } else {
                    $errorMessage = "Error adding class: " . mysqli_error($conn);
                }
            } else {
                $errorMessage = "Failed to upload image. Please ensure it's a valid image file (JPG, PNG, JPEG, GIF, WEBP) and under 5MB.";
            }
        } else {
            $errorMessage = "Please select an image for your class.";
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

	<title>Add Class - TutorTap</title>
	
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

		.booking-form > form {
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

		.booking-form select.form-control + .select-arrow {
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

		.booking-form select.form-control + .select-arrow:after {
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
			box-shadow: 0 5px 15px rgba(0,0,0,0.2);
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
			text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
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

		.card-preview {
			max-width: 320px;
			margin: 0 auto;
			transition: all 0.3s ease;
			transform: scale(0.95);
			box-shadow: 0 10px 20px rgba(0,0,0,0.3);
		}

		.card-preview:hover {
			transform: scale(1);
		}

		.card-preview .card-body {
			transition: all 0.3s ease;
		}

		.card-preview .card-title {
			font-weight: bold;
			margin-bottom: 10px;
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
			
			.booking-form > form {
				padding: 20px 15px;
			}
			
			#booking {
				padding: 10px 0 30px;
			}
			
			.section {
				padding: 10px 0;
			}
		}
	</style>
</head>

<body>
	<div class="navbar navbar-expand-lg navbar-light bg-ouryellow">
		<div class="container-fluid">
			<!-- Logo -->
			<a class="navbar-brand" href="homeTutor.php?id=<?php echo $userId; ?>">
				<img src="../images/skilltap logo+brand.png" class="rounded-pill" style="width:150px; background-color:black" alt="">
			</a>
			<!-- Navigation Icons -->
			<div class="navbar-nav ms-auto">
				<a class="btn-icon me-3" href="#"><i class="bi bi-envelope-fill text-dark" style="font-size: 25px;"></i></a>
				<a class="btn-icon me-3" href="homeTutor.php?id=<?php echo $userId; ?>"><i class="bi bi-house-fill text-dark" style="font-size: 25px;"></i></a>
				<a class="btn-icon me-3" href="#"><i class="bi bi-cart-fill text-dark" style="font-size: 25px;"></i></a>
				<a class="btn-icon me-3" href="logout.php" title="Logout"><i class="bi bi-box-arrow-right text-dark" style="font-size: 25px;"></i></a>
			</div>
		</div>
	</div>

	<div id="booking" class="section">
		<div class="section-center">
			<div class="container-fluid px-2 px-md-4">
				<div class="row justify-content-center">
					<div class="col-12">
						<div class="form-header text-center mb-4">
							<h1>Add Class</h1>
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
								<div class="form-group">
									<span class="form-label">Title</span>
									<input class="form-control" type="text" name="classTitle" id="classTitle" placeholder="Add your Class Title Here" required>
								</div>								<div class="form-group">
									<span class="form-label">Image</span>
									<input class="form-control" type="file" name="classImage" id="classImage" accept="image/jpeg,image/png,image/gif,image/webp" required>
									<small class="form-text text-white">Supported formats: JPG, PNG, JPEG, GIF, WEBP (max 5MB)</small>
								</div>
								<div class="row">
									<div class="col-sm-6">
										<div class="form-group">
											<span class="form-label">Price</span>
											<input class="form-control" type="number" name="classPrice" id="classPrice" placeholder="Input Price here (Rp)" min="0" required>
										</div>
									</div>
									<div class="col-sm-6">
										<div class="form-group">
											<span class="form-label">Hour</span>
											<select class="form-control" name="classDuration" id="classDuration" required>
												<option value="hour">hour</option>
												<option value="session">session</option>
											</select>
											<span class="select-arrow"></span>
										</div>
									</div>
								</div>
								<div class="form-group">
									<span class="form-label">Description</span>
									<textarea class="form-control" name="classDescription" id="classDescription" placeholder="Describe your class!" rows="4" required></textarea>
								</div>
								<div class="form-group">
									<span class="form-label">Location</span>
									<input class="form-control" type="text" name="lokasiKelas" placeholder="Set Location" required>
									<!-- <small class="form-text text-white">Note: This location will be displayed on the form but is not currently stored in the database.</small> -->
								</div>
								<div class="form-btn">
									<div class="row gx-2">
										<div class="col-12 mb-3">
											<button type="submit" name="saveClass" class="submit-btn">Create Class</button>
										</div>
										<div class="col-12 mb-3">
											<a href="homeTutor.php?id=<?php echo $userId; ?>" class="btn btn-outline-light w-100">
												<i class="bi bi-x-circle"></i> Cancel
											</a>
										</div>
									</div>
								</div>
							</form>
						</div>
					</div>
					
					<!-- Preview Column (Right Side) -->
					<div class="preview-column">
						<div class="preview-container preview-sticky" style="margin-top:0 !important; height: auto;">
							<h5 class="text-center mb-3 montserratBold text-white">Class Card Preview</h5>
							<div class="card shadow-lg mx-auto bg-white text-dark card-preview">
								<img class="card-img-top" id="previewImage" src="../images/preview-placeholder.jpg" onerror="this.src='https://via.placeholder.com/400x200?text=Select+an+image'" alt="Class image" style="height: 180px; object-fit: cover;">
								<div class="card-body">
									<h5 class="card-title montserratBold" id="previewTitle">Class Title</h5>
									<h5 class="card-text" id="previewPrice">Rp.0/hour</h5>
									<p class="card-text" id="previewDescription" style="max-height: 80px; overflow-y: auto;">Class description will appear here</p>
									<div class="d-flex justify-content-end">
										<span class="badge bg-success">
											Active
										</span>
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
		// Get form elements
		const titleInput = document.getElementById('classTitle');
		const priceInput = document.getElementById('classPrice');
		const durationSelect = document.getElementById('classDuration');
		const descriptionTextarea = document.getElementById('classDescription');
		const imageInput = document.getElementById('classImage');
		
		// Get preview elements
		const previewTitle = document.getElementById('previewTitle');
		const previewPrice = document.getElementById('previewPrice');
		const previewDescription = document.getElementById('previewDescription');
		const previewImage = document.getElementById('previewImage');
		
		// Update title
		if (titleInput && previewTitle) {
			titleInput.addEventListener('input', function() {
				previewTitle.textContent = this.value || 'Class Title';
			});
		}
		
		// Update price and duration
		if (priceInput && durationSelect && previewPrice) {
			const updatePrice = function() {
				const price = priceInput.value || '0';
				const duration = durationSelect.value || '/hour';
				previewPrice.textContent = 'Rp.' + price + "/" + duration;
			};
			
			priceInput.addEventListener('input', updatePrice);
			durationSelect.addEventListener('change', updatePrice);
		}
		
		// Update description
		if (descriptionTextarea && previewDescription) {
			descriptionTextarea.addEventListener('input', function() {
				previewDescription.textContent = this.value || 'Class description will appear here';
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
	});
	</script>
</body>

</html>