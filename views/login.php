<?php
    require_once '../functions.php';
    
    // Start session
    session_start();
    
    // Check if user is already logged in, redirect to home
    if(isset($_SESSION['user_id'])) {
        echo "<script>document.location.href = 'homeLearner.php?id=".$_SESSION['user_id']."'</script>";
        exit;
    }
    
    if(isset($_POST['signIn'])){
        $username = $_POST['email'];
        $password = $_POST['password'];

        $syntax = "SELECT * FROM USER WHERE email = '$username' AND password = '$password'";
        $results = query($syntax);

        $count = count($results);

        if($count>0){
            $id = $results[0]['userId'];
            
            // Set session variables
            $_SESSION['user_id'] = $id;
            $_SESSION['user_name'] = $results[0]['nama'];
            $_SESSION['user_email'] = $results[0]['email'];
            
            echo "<script>document.location.href = 'homeLearner.php?id=$id'</script>";
        } else {
            echo '<script>
                document.addEventListener("DOMContentLoaded", function () {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Email or password is incorrect!",
                        footer: \'<a href="#"></a>\'
                    });
                });
            </script>';
        }
    }
?>

<!DOCTYPE html>
<html lang="en">    

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account</title>
    <link rel="stylesheet" type="text/css" href="../styles/styles.css">
    <link rel="stylesheet" type="text/css" href="opening.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">    <style>
        body {
            background-color: #f8f9fa;
            overflow-x: hidden;
        }

        .black-background {
            background-color: black;
        }

        h2 {
            color: black;
            transition: color 2s ease;
        }

        .white-text {
            color: white;
        }
          .hero-image {
            height: 100vh;
            background-image: url('../images/background 1.png');
            background-size: cover;
            background-position: center;
            position: relative;
        }
        
        .hero-image::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0);
            z-index: 1;
        }
        
        .hero-content {
            position: relative;
            z-index: 2;
            color: white;
            padding: 2rem;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .hero-title {
            font-size: 3rem;
            font-weight: bold;
            margin-bottom: 1rem;
            color: rgb(238, 181, 0);
        }
        
        .hero-text {
            font-size: 1.25rem;
            max-width: 80%;
        }          
        .form-section {
            height: 100vh;
            overflow-y: auto;
            padding: 2rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background-color: #f8f9fa;
            box-shadow: -5px 0 15px rgba(0, 0, 0, 0.1);
        }
          .form-container {
            max-width: 400px;
            margin: 0 auto;
            background-color: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .form-section .logo-container {
            margin-bottom: 2rem;
        }
        
        .form-section form {
            margin-top: 1rem;
        }
        
        .form-section .btn-outline-warning {
            transition: all 0.3s ease;
            color: #333;
            border-color: rgb(238, 181, 0);
        }
        
        .form-section .btn-outline-warning:hover {
            background-color: rgb(238, 181, 0);
            color: black;
        }
        
        .form-section input.form-control {
            border: 1px solid #ced4da;
            color: #333;
        }
        
        .form-section input.form-control::placeholder {
            color: #6c757d;
        }
        
        @media (max-width: 768px) {
            .hero-image {
                display: none;
            }
            
            .form-section {
                width: 100% !important;
            }
        }
    </style>
</head>

<body class="min-vh-100">
    <div class="row g-0 min-vh-100">        <!-- Hero Image Section (Left Column) -->
        <div class="col-md-8 p-0 hero-image">
            <div class="hero-content">
                <!-- <h1 class="hero-title">Learn Anything, Anywhere</h1>
                <p class="hero-text">Connect with expert tutors to help you master any subject. Get personalized lessons tailored to your learning style and schedule.</p> -->
                <div class="d-flex flex-column align-items-center justify-content-center">
                    <a class="navbar-brand montserratBold mb-0" href="#mainSection">
                        <img src="../images/skilltap logo+brand - hero.png" style="width:100%; max-width: 600px;" alt="SkillTap Logo">
                    </a>
                    <!-- <h1 class="montserratBold text-black mt-0">
                        <img src="../images/get_some_help.png" style="width:100%; max-width: 150px;" alt="SkillTap Logo">
                    </h1> -->
                </div>
            </div>
        </div>
          <!-- Login Form Section (Right Column) -->
        <div class="col-md-4 form-section">
            <div class="form-container">
                <!-- <div class="logo-container text-center">
                    <a class="navbar-brand montserratBold" href="#mainSection">
                        <img src="../images/skilltap logo+brand.png" style="width:100%; max-width: 300px;" alt="SkillTap Logo">
                    </a>
                </div> -->                <!-- <h2 class="text-center montserratBold mb-4" style="font-size: 32px;">Get Some Help!</h2> -->
                <h3 class="text-center montserratBold mb-4">Login</h3>
                <form action="" method="post">
                    <!-- Email input -->
                    <div class="input-group mb-3">
                        <button class="btn btn-outline-warning change" type="button" id="button-addon1">Email</button>
                        <input type="text" class="form-control" placeholder="Enter your email" name="email" required>
                    </div>

                    <!-- Password input -->
                    <div class="input-group mb-4">
                        <button class="btn btn-outline-warning change" type="button" id="button-addon1">Password</button>
                        <input type="password" class="form-control" placeholder="Enter your password" name="password" required>
                    </div>
                      <div class="d-grid gap-2">
                        <!-- Submit button -->
                        <button type="submit" name="signIn" class="btn btn-outline-warning fontMonsseratSemiBold shadow-sm">Log In</button>
                        <a href="/" class="btn btn-outline-warning font-weight-semibold shadow-sm">Back</a>
                    </div>
                    <div class="text-center mt-3 text-muted">
                        <small>Login to access your account and get tutoring help</small>
                    </div>
                </form>
            </div>
        </div>
    <!-- <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Add black-background class after some time
            setTimeout(function () {
                document.querySelector(".form-section").classList.add("black-background");
                document.querySelector("h2").classList.add("white-text");
            }, 1500); // Set delay time before color change occurs (in milliseconds)

            // Add event listener on "change" buttons
            var changeButtons = document.querySelectorAll(".change");
            changeButtons.forEach(function (button) {
                button.addEventListener("click", function () {
                    // Toggle black-background class on form section
                    document.querySelector(".form-section").classList.toggle("black-background");

                    // Toggle white-text class on h2
                    document.querySelector("h2").classList.toggle("white-text");
                });
            });
        });
    </script> -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>