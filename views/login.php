<?php
    require_once '../functions.php';
    if(isset($_POST['signIn'])){
        $username = $_POST['email'];
        $password = $_POST['password'];

        $syntax = "SELECT * FROM USER WHERE email = '$username' AND password = '$password'";
        $results = query($syntax);

        $count = count($results);

        if($count>0){
            $id = $results[0]['userId'];
            echo "<script>document.location.href = 'homeLearner.php?id=$id'</script>";
        }else {

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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            background-color: black;
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
    </style>
</head>

<body class="d-flex justify-content-center align-items-center" style="min-height: 100vh;"></body>
    <div class="form-container w-50">
        <div class="container d-flex justify-content-center align-items-center w-75">
            <a class="navbar-brand montserratBold text-center" style="font-size: 50px; color: rgb(238, 181, 0);"
                href="#mainSection">
                <img src="../images/skilltap logo+brand.png" style="width:100%; height:100%;" alt="">
            </a>
                
        </div>

        <!-- Flash messages would go here, but are omitted for pure HTML -->

        <h2 class="p-3 text-center montserratBold pb-5" style="font-size: 40px;">Get Some Help!</h2>
        <form action="" method="post">
            <!-- Email input -->
            <div class="input-group mb-3">
                <button class="btn btn-outline-warning change" type="button" id="button-addon1">Email</button>
                <input type="text" class="form-control" placeholder="Email" name="email" required>
            </div>

            <!-- Password input -->
            <div class="input-group my-3">
                <button class="btn btn-outline-warning change" type="button" id="button-addon1">Password</button>
                <input type="password" class="form-control" placeholder="Password" name="password" required>
            </div>
            <!-- Submit button -->
            <button type="submit" name="signIn" class="btn btn-outline-warning fontMonsseratSemiBold my-3" type="submit">Log In</button>
            <a href="/" class="btn btn-outline-warning font-weight-semibold my-3">Back</a>
        </form>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Add black-background class after some time
            setTimeout(function () {
                document.body.classList.add("black-background");
                document.querySelector("h2").classList.add("white-text");
            }, 1500); // Set delay time before color change occurs (in milliseconds)

            // Add event listener on "change" buttons
            var changeButtons = document.querySelectorAll(".change");
            changeButtons.forEach(function (button) {
                button.addEventListener("click", function () {
                    // Toggle black-background class on body
                    document.body.classList.toggle("black-background");

                    // Toggle white-text class on h2
                    document.querySelector("h2").classList.toggle("white-text");
                });
            });
        });
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>